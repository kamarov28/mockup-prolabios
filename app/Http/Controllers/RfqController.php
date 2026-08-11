<?php

namespace App\Http\Controllers;

use App\Jobs\SendRfqApprovedEmailJob;
use App\Jobs\SendRfqCustomerReceiptEmailJob;
use App\Jobs\SendRfqSubmittedEmailJob;
use App\Mail\RfqSubmittedMail;
use App\Models\Rfq;
use App\Models\RfqItem;
use App\Services\DataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RfqController extends Controller
{
    protected DataService $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $total = 0;
        foreach ($cart as $key => &$item) {
            $product = null;
            if (!empty($item['id'])) {
                $product = $this->dataService->getProductById((int)$item['id']);
            }
            if (!$product && !empty($item['title'])) {
                $product = $this->dataService->getProductByTitle($item['title']);
            }

            if ($product) {
                $item['price'] = (float)($product['price'] ?? 0);
            }
            $total += ((float)$item['price'] * (int)$item['quantity']);
        }
        session()->put('cart', $cart);

        return view('rfq-checkout', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $validated = $request->validate([
            'company_name'   => 'required|string|max:255',
            'company_tax_id' => 'nullable|string|max:100',
            'pic_name'       => 'required|string|max:255',
            'pic_position'   => 'nullable|string|max:255',
            'email'          => 'required|email|max:255',
            'phone_wa'       => 'required|string|max:50',
            'address'        => 'required|string',
            'notes'          => 'nullable|string',
        ]);

        $rfqNumber = 'RFQ-' . date('Ym') . '-' . strtoupper(Str::random(6));

        $rfq = Rfq::create([
            'rfq_number'     => $rfqNumber,
            'access_token'   => Str::random(48),
            'company_name'   => $validated['company_name'],
            'company_tax_id' => $validated['company_tax_id'] ?? null,
            'pic_name'       => $validated['pic_name'],
            'pic_position'   => $validated['pic_position'] ?? 'Procurement Staff',
            'email'          => $validated['email'],
            'phone_wa'       => $validated['phone_wa'],
            'address'        => $validated['address'],
            'notes'          => $validated['notes'] ?? null,
            'status'         => 'pending_review',
        ]);

        $totalEstimated = 0;
        foreach ($cart as $item) {
            $product = null;
            if (!empty($item['id'])) {
                $product = $this->dataService->getProductById((int)$item['id']);
            }
            if (!$product && !empty($item['title'])) {
                $product = $this->dataService->getProductByTitle($item['title']);
            }

            // Fetch live price from DB to prevent session price manipulation
            $origPrice = $product ? (float)($product['price'] ?? 0) : (float)($item['price'] ?? 0);
            $qty = max(1, (int)($item['quantity'] ?? 1));
            $subtotal = $origPrice * $qty;
            $totalEstimated += $subtotal;

            RfqItem::create([
                'rfq_id'         => $rfq->id,
                'product_id'     => $product['id'] ?? ($item['id'] ?? null),
                'product_title'  => $product['title'] ?? $item['title'],
                'catalog_no'     => $product['catalog'] ?? ($item['catalog'] ?? null),
                'original_price' => $origPrice,
                'offered_price'  => $origPrice, // Initial price estimate
                'quantity'       => $qty,
                'subtotal'       => $subtotal,
            ]);
        }

        $rfq->update(['total_offered_amount' => $totalEstimated]);

        // Clear Cart
        session()->forget('cart');
        session()->put('last_rfq_number', $rfq->rfq_number);
        session()->put('last_rfq_token', $rfq->access_token);

        // Dispatch Notification Email Jobs asynchronously without blocking user response
        try {
            // Dispatch receipt email job to Customer PIC
            SendRfqCustomerReceiptEmailJob::dispatch($rfq->id);

            // Dispatch notification email job to Sales Admin
            SendRfqSubmittedEmailJob::dispatch($rfq->id);
        } catch (\Throwable $e) {
            \Log::warning('Failed to dispatch RFQ queue jobs: ' . $e->getMessage());
        }

        return redirect()->route('rfq.success', [
            'number' => $rfq->rfq_number, 
            'token'  => $rfq->access_token
        ]);
    }

    public function success(Request $request, string $number)
    {
        $rfq = Rfq::with('items')->where('rfq_number', $number)->firstOrFail();

        // Optional token check for enhanced security - fallback gracefully if missing
        $token = $request->query('token');
        if ($token && !hash_equals((string)$rfq->access_token, (string)$token)) {
            // Log security warning but allow previewing official RFQ tracking page
            \Log::warning("RFQ token mismatch for number {$number}");
        }

        return view('rfq-success', compact('rfq'));
    }

    public function track(Request $request, string $number)
    {
        $rfq = Rfq::with('items')->where('rfq_number', $number)->firstOrFail();

        // Security check: validate token or allow if session token matches
        $token = $request->query('token');
        $sessionToken = session('last_rfq_token');
        
        if ($token && hash_equals((string)$rfq->access_token, (string)$token)) {
            // Valid token
        } elseif ($sessionToken && hash_equals((string)$rfq->access_token, (string)$sessionToken)) {
            // Valid session from recent submission
        } else {
            return redirect()->route('home')->with('error', 'Akses ditolak: Link pelacak RFQ tidak valid. Gunakan link resmi dari email Anda.');
        }

        return view('rfq-track', compact('rfq'));
    }

    public function approve(Request $request, string $number)
    {
        $rfq = Rfq::with('items')->where('rfq_number', $number)->first();
        if (!$rfq) {
            return redirect('/')->with('error', 'Penawaran RFQ tidak ditemukan.');
        }

        // Validate authorization via signed route OR access_token OR active session
        $token = $request->query('token');
        $sessionToken = session('last_rfq_token');
        $isValidAuth = $request->hasValidSignature() 
                       || ($token && hash_equals((string)$rfq->access_token, (string)$token))
                       || ($sessionToken && hash_equals((string)$rfq->access_token, (string)$sessionToken));

        if (!$isValidAuth) {
            return redirect()->route('rfq.track', ['number' => $number, 'token' => $rfq->access_token])
                             ->with('error', 'Sesi persetujuan telah diperbarui. Silakan klik persetujuan dari halaman resmi ini.');
        }

        return \Illuminate\Support\Facades\DB::transaction(function () use ($number) {
            $rfq = Rfq::with('items')->where('rfq_number', $number)->lockForUpdate()->firstOrFail();

            if ($rfq->status === 'approved') {
                return redirect()->route('rfq.track', ['number' => $number, 'token' => $rfq->access_token])
                                 ->with('info', 'Penawaran ini sudah Anda setujui sebelumnya.');
            }

            // Check stock availability with row-level locking to prevent race conditions
            foreach ($rfq->items as $item) {
                $product = null;
                if ($item->product_id) {
                    $product = \Illuminate\Support\Facades\DB::table('products')->where('id', $item->product_id)->lockForUpdate()->first();
                } else if ($item->product_title) {
                    $product = \Illuminate\Support\Facades\DB::table('products')->where('title', $item->product_title)->lockForUpdate()->first();
                }

                if ($product && isset($product->stock) && $product->stock < $item->quantity) {
                    return redirect()->route('rfq.track', ['number' => $number, 'token' => $rfq->access_token])
                                     ->with('error', 'Stok tidak mencukupi untuk produk: ' . $product->title . ' (tersedia: ' . $product->stock . ', dibutuhkan: ' . $item->quantity . ').');
                }
            }

            $rfq->update(['status' => 'approved']);

            // Decrement product stock dynamically
            foreach ($rfq->items as $item) {
                if ($item->product_id) {
                    $this->dataService->decrementStock($item->product_id, $item->quantity);
                } else {
                    $this->dataService->decrementStock($item->product_title, $item->quantity);
                }
            }

            // Notify Sales Admin that the buyer has approved the quotation
            try {
                SendRfqApprovedEmailJob::dispatch($rfq->id);
            } catch (\Throwable $e) {
                \Log::warning('Failed to dispatch RFQ approved email job: ' . $e->getMessage());
            }

            return redirect()->route('rfq.track', ['number' => $number, 'token' => $rfq->access_token])
                             ->with('success', 'Selamat! Penawaran resmi berhasil disetujui. Tim Prolabios akan segera menghubungi Anda untuk koordinasi pengiriman.');
        });
    }

    public function pdf(Request $request, string $number)
    {
        $rfq = Rfq::with('items')->where('rfq_number', $number)->firstOrFail();

        // Security check: validate access token to prevent unauthorized access (IDOR Protection)
        $token = $request->query('token');
        $sessionToken = session('last_rfq_token');

        if ($token && hash_equals((string)$rfq->access_token, (string)$token)) {
            // Authorized
        } elseif ($sessionToken && hash_equals((string)$rfq->access_token, (string)$sessionToken)) {
            // Authorized via session
        } else {
            return redirect()->route('home')->with('error', 'Akses ditolak: Dokumen penawaran ini dilindungi. Silakan buka melalui link resmi di email Anda.');
        }

        return view('quotation-pdf', compact('rfq'));
    }
}

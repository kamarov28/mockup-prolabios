<?php

namespace App\Http\Controllers;

use App\Jobs\SendRfqCustomerReceiptEmailJob;
use App\Jobs\SendRfqSubmittedEmailJob;
use App\Models\Rfq;
use App\Models\RfqItem;
use App\Services\AuditLogger;
use App\Services\CaptchaService;
use App\Services\DataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $total = 0;
        foreach ($cart as $key => $item) {
            $product = null;
            if (! empty($item['id'])) {
                $product = $this->dataService->getProductById((int) $item['id']);
            }
            if (! $product && ! empty($item['title'])) {
                $product = $this->dataService->getProductByTitle($item['title']);
            }

            $price = $product ? (float) ($product['price'] ?? 0) : (float) ($item['price'] ?? 0);
            $cart[$key]['price'] = $price;
            $total += ($price * (int) $item['quantity']);
        }
        session()->put('cart', $cart);

        return view('rfq-checkout', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        // Anti-Bot Honeypot Guard: if invisible field is populated, silently drop spam
        if ($request->filled('_hp_website')) {
            \Illuminate\Support\Facades\Log::warning('RFQ submission bot honeypot triggered.', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            session()->forget('cart');

            return redirect()->route('home')->with('success', 'Pengajuan penawaran Anda telah kami terima.');
        }

        // Production CAPTCHA verification (reCAPTCHA v3 / Cloudflare Turnstile)
        if (! CaptchaService::verify($request)) {
            return back()->withInput()->withErrors([
                'captcha' => 'Verifikasi keamanan bot gagal. Silakan muat ulang halaman dan coba lagi.',
            ]);
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company_name' => 'required|string|max:255',
            'phone_wa' => ['required', 'string', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'notes' => 'nullable|string|max:3000',
        ], [
            'phone_wa.regex' => 'Nomor WhatsApp hanya boleh berisi angka, spasi, serta karakter + atau - (minimal 8 digit).',
        ]);

        $rfqNumber = 'RFQ-'.date('Ym').'-'.strtoupper(Str::random(6));

        $rfq = DB::transaction(function () use ($rfqNumber, $validated, $cart) {
            $rfq = Rfq::create([
                'rfq_number' => $rfqNumber,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'company_name' => $validated['company_name'],
                'phone_wa' => $validated['phone_wa'],
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($cart as $item) {
                $product = null;
                if (! empty($item['id'])) {
                    $product = $this->dataService->getProductById((int) $item['id']);
                }
                if (! $product && ! empty($item['title'])) {
                    $product = $this->dataService->getProductByTitle($item['title']);
                }

                $origPrice = $product ? (float) ($product['price'] ?? 0) : (float) ($item['price'] ?? 0);
                $qty = max(1, (int) ($item['quantity'] ?? 1));

                RfqItem::create([
                    'rfq_id' => $rfq->id,
                    'product_id' => $product['id'] ?? ($item['id'] ?? null),
                    'product_title' => $product['title'] ?? $item['title'],
                    'catalog_no' => $product['catalog'] ?? ($item['catalog'] ?? null),
                    'original_price' => $origPrice,
                    'quantity' => $qty,
                ]);
            }

            return $rfq;
        });

        // Clear Cart & Store Session Token for Success Page Protection
        session()->forget('cart');
        session()->put('submitted_rfq_number', $rfq->rfq_number);

        AuditLogger::log('rfq.submit', 'Rfq', $rfq->id, [
            'rfq_number' => $rfq->rfq_number,
            'company' => $rfq->company_name,
            'email' => $rfq->email,
            'items_count' => count($cart),
        ]);

        // Dispatch notification emails asynchronously
        try {
            SendRfqSubmittedEmailJob::dispatch($rfq->id);
            SendRfqCustomerReceiptEmailJob::dispatch($rfq->id);
        } catch (\Throwable $e) {
            \Log::warning('Failed to dispatch RFQ email jobs: '.$e->getMessage());
        }

        return redirect()->route('rfq.success', ['number' => $rfq->rfq_number]);
    }

    public function success(Request $request, string $number)
    {
        // Protection: Ensure only recent session submitter can view the success summary
        if (session('submitted_rfq_number') !== $number) {
            return redirect()->route('home')->with('info', 'Halaman konfirmasi pengajuan telah kedaluwarsa.');
        }

        $rfq = Rfq::with('items')->where('rfq_number', $number)->firstOrFail();

        return view('rfq-success', compact('rfq'));
    }
}

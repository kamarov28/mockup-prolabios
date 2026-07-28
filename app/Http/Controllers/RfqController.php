<?php

namespace App\Http\Controllers;

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
        foreach ($cart as $item) {
            $total += ($item['price'] * $item['quantity']);
        }

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
            $origPrice = (float)($item['price'] ?? 0);
            $qty = (int)($item['quantity'] ?? 1);
            $subtotal = $origPrice * $qty;
            $totalEstimated += $subtotal;

            RfqItem::create([
                'rfq_id'         => $rfq->id,
                'product_id'     => $item['id'] ?? null,
                'product_title'  => $item['title'],
                'catalog_no'     => $item['catalog'] ?? null,
                'original_price' => $origPrice,
                'offered_price'  => $origPrice, // Initial price estimate
                'quantity'       => $qty,
                'subtotal'       => $subtotal,
            ]);
        }

        $rfq->update(['total_offered_amount' => $totalEstimated]);

        // Send Notification Email to Sales Admin
        try {
            $adminEmail = config('mail.from.address', 'sales@prolabios.com');
            Mail::to($adminEmail)->send(new RfqSubmittedMail($rfq));
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim email RFQ ke admin: ' . $e->getMessage());
        }

        // Clear Cart
        session()->forget('cart');

        return redirect()->route('rfq.success', ['number' => $rfq->rfq_number]);
    }

    public function success(string $number)
    {
        $rfq = Rfq::with('items')->where('rfq_number', $number)->firstOrFail();
        return view('rfq-success', compact('rfq'));
    }

    public function track(string $number)
    {
        $rfq = Rfq::with('items')->where('rfq_number', $number)->firstOrFail();
        return view('rfq-track', compact('rfq'));
    }

    public function approve(Request $request, string $number)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Link persetujuan penawaran tidak sah atau telah kadaluarsa.');
        }

        return \Illuminate\Support\Facades\DB::transaction(function () use ($number) {
            $rfq = Rfq::with('items')->where('rfq_number', $number)->lockForUpdate()->firstOrFail();

            if ($rfq->status === 'approved') {
                return redirect()->route('rfq.track', ['number' => $number])->with('info', 'Penawaran ini sudah Anda setujui sebelumnya.');
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

            return redirect()->route('rfq.track', ['number' => $number])->with('success', 'Selamat! Penawaran resmi berhasil disetujui. Tim Prolabios akan segera menghubungi Anda untuk koordinasi pengiriman.');
        });
    }

    public function pdf(string $number)
    {
        $rfq = Rfq::with('items')->where('rfq_number', $number)->firstOrFail();
        return view('quotation-pdf', compact('rfq'));
    }
}

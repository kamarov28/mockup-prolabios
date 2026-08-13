<?php

namespace App\Http\Controllers;

use App\Jobs\SendRfqCustomerReceiptEmailJob;
use App\Jobs\SendRfqSubmittedEmailJob;
use App\Models\Rfq;
use App\Models\RfqItem;
use App\Services\DataService;
use Illuminate\Http\Request;
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
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'company_name' => 'required|string|max:255',
            'phone_wa'     => 'required|string|max:50',
            'notes'        => 'nullable|string',
        ]);

        $rfqNumber = 'RFQ-' . date('Ym') . '-' . strtoupper(Str::random(6));

        $rfq = Rfq::create([
            'rfq_number'   => $rfqNumber,
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'company_name' => $validated['company_name'],
            'phone_wa'     => $validated['phone_wa'],
            'notes'        => $validated['notes'] ?? null,
        ]);

        foreach ($cart as $item) {
            $product = null;
            if (!empty($item['id'])) {
                $product = $this->dataService->getProductById((int)$item['id']);
            }
            if (!$product && !empty($item['title'])) {
                $product = $this->dataService->getProductByTitle($item['title']);
            }

            $origPrice = $product ? (float)($product['price'] ?? 0) : (float)($item['price'] ?? 0);
            $qty = max(1, (int)($item['quantity'] ?? 1));

            RfqItem::create([
                'rfq_id'        => $rfq->id,
                'product_id'    => $product['id'] ?? ($item['id'] ?? null),
                'product_title' => $product['title'] ?? $item['title'],
                'catalog_no'    => $product['catalog'] ?? ($item['catalog'] ?? null),
                'original_price' => $origPrice,
                'quantity'      => $qty,
            ]);
        }

        // Clear Cart
        session()->forget('cart');

        // Dispatch notification emails asynchronously
        try {
            SendRfqSubmittedEmailJob::dispatch($rfq->id);
            SendRfqCustomerReceiptEmailJob::dispatch($rfq->id);
        } catch (\Throwable $e) {
            \Log::warning('Failed to dispatch RFQ email jobs: ' . $e->getMessage());
        }

        return redirect()->route('rfq.success', ['number' => $rfq->rfq_number]);
    }

    public function success(string $number)
    {
        $rfq = Rfq::with('items')->where('rfq_number', $number)->firstOrFail();
        return view('rfq-success', compact('rfq'));
    }
}

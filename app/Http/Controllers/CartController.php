<?php

namespace App\Http\Controllers;

use App\Services\DataService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected DataService $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $title => &$item) {
            $product = $this->dataService->getProductByTitle($title);
            if ($product) {
                if ((float)($product['price'] ?? 0) > 0) {
                    $item['price'] = (float)$product['price'];
                }
                $item['stock'] = (int)($product['stock'] ?? 0);
            } else {
                $item['stock'] = (int)($item['stock'] ?? 0);
            }
            $total += ($item['price'] * $item['quantity']);
        }
        session()->put('cart', $cart);

        return view('cart', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $title = $request->input('title');
        $qty = max(1, (int) $request->input('quantity', 1));

        $product = $this->dataService->getProductByTitle($title);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.'], 404);
        }

        $stock = (int)($product['stock'] ?? 0);
        $cart = session()->get('cart', []);

        $currentQty = $cart[$title]['quantity'] ?? 0;
        $newQty = $currentQty + $qty;

        if (isset($cart[$title])) {
            $cart[$title]['quantity'] = $newQty;
            $cart[$title]['stock'] = $stock;
        } else {
            $cart[$title] = [
                'id'       => $product['id'] ?? null,
                'title'    => $product['title'],
                'catalog'  => $product['catalog'] ?? '',
                'image'    => $product['image'] ?? '',
                'price'    => (float)($product['price'] ?? 0),
                'stock'    => $stock,
                'quantity' => $newQty
            ];
        }

        session()->put('cart', $cart);

        $cartCount = array_sum(array_column($cart, 'quantity'));

        if ($request->wantsJson()) {
            return response()->json([
                'success'   => true,
                'message'   => 'Produk berhasil ditambahkan ke keranjang RFQ!',
                'cartCount' => $cartCount,
                'isIndent'  => $newQty > $stock,
                'stock'     => $stock
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang RFQ!');
    }

    public function update(Request $request)
    {
        $title = $request->input('title');
        $qty = max(1, (int) $request->input('quantity', 1));

        $cart = session()->get('cart', []);
        if (isset($cart[$title])) {
            // Enforce max quantity based on cached stock in cart
            $stock = (int)($cart[$title]['stock'] ?? 999);
            if ($stock > 0 && $qty > $stock) {
                $qty = $stock;
            }
            $cart[$title]['quantity'] = $qty;
            session()->put('cart', $cart);
        }

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            $total = 0;
            foreach ($cart as $item) {
                $total += ($item['price'] * $item['quantity']);
            }
            $itemSubtotal = isset($cart[$title]) ? ($cart[$title]['price'] * $cart[$title]['quantity']) : 0;

            return response()->json([
                'success' => true,
                'cart' => $cart,
                'total' => $total,
                'totalFormatted' => $total > 0 ? 'Rp ' . number_format($total, 0, ',', '.') : 'Rp 0',
                'itemSubtotal' => $itemSubtotal > 0 ? 'Rp ' . number_format($itemSubtotal, 0, ',', '.') : 'Est. Penawaran',
                'cartCount' => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function remove(Request $request)
    {
        $title = $request->input('title');
        $cart = session()->get('cart', []);

        if (isset($cart[$title])) {
            unset($cart[$title]);
            session()->put('cart', $cart);
        }

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            $total = 0;
            foreach ($cart as $item) {
                $total += ($item['price'] * $item['quantity']);
            }
            return response()->json([
                'success' => true,
                'cart' => $cart,
                'total' => $total,
                'totalFormatted' => $total > 0 ? 'Rp ' . number_format($total, 0, ',', '.') : 'Rp 0',
                'cartCount' => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Keranjang belanja telah dikosongkan.');
    }
}

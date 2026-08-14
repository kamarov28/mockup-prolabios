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
        $migratedCart = [];

        foreach ($cart as $key => $item) {
            $product = null;
            if (! empty($item['id'])) {
                $product = $this->dataService->getProductById((int) $item['id']);
            }
            if (! $product && ! empty($item['title'])) {
                $product = $this->dataService->getProductByTitle($item['title']);
            }

            if ($product) {
                $productId = is_object($product) ? $product->id : ($product['id'] ?? $key);
                $stock = (int) (is_object($product) ? $product->stock : ($product['stock'] ?? 0));
                $price = (float) (is_object($product) ? $product->price : ($product['price'] ?? 0));
                $price = $price > 0 ? $price : (float) ($item['price'] ?? 0);
                $title = is_object($product) ? $product->title : ($product['title'] ?? ($item['title'] ?? ''));
                $catalog = is_object($product) ? $product->catalog : ($product['catalog'] ?? ($item['catalog'] ?? ''));
                $image = is_object($product) ? $product->image : ($product['image'] ?? ($item['image'] ?? ''));
            } else {
                $productId = $item['id'] ?? $key;
                $stock = (int) ($item['stock'] ?? 0);
                $price = (float) ($item['price'] ?? 0);
                $title = $item['title'] ?? $key;
                $catalog = $item['catalog'] ?? '';
                $image = $item['image'] ?? '';
            }

            $cartKey = (string) $productId;
            $migratedCart[$cartKey] = [
                'id' => $productId,
                'title' => $title,
                'catalog' => $catalog,
                'image' => $image,
                'price' => $price,
                'stock' => $stock,
                'quantity' => (int) ($item['quantity'] ?? 1),
            ];

            $total += $price * (int) ($item['quantity'] ?? 1);
        }

        session()->put('cart', $migratedCart);

        return view('cart', ['cart' => $migratedCart, 'total' => $total]);
    }

    public function add(Request $request)
    {
        $id = $request->input('id');
        $title = $request->input('title');
        $qty = max(1, (int) $request->input('quantity', 1));

        $product = null;
        if (! empty($id)) {
            $product = $this->dataService->getProductById((int) $id);
        }
        if (! $product && ! empty($title)) {
            $product = $this->dataService->getProductByTitle($title);
        }

        if (! $product) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.'], 404);
            }

            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        $productId = is_object($product) ? $product->id : ($product['id'] ?? null);
        $productTitle = is_object($product) ? $product->title : ($product['title'] ?? $title);
        $productCatalog = is_object($product) ? $product->catalog : ($product['catalog'] ?? '');
        $productImage = is_object($product) ? $product->image : ($product['image'] ?? '');
        $productPrice = is_object($product) ? (float) $product->price : (float) ($product['price'] ?? 0);
        $stock = (int) (is_object($product) ? $product->stock : ($product['stock'] ?? 0));

        $cart = session()->get('cart', []);
        $cartKey = (string) ($productId ?: $productTitle);

        // Fallback check if old cart was keyed by title
        if (! isset($cart[$cartKey]) && isset($cart[$productTitle])) {
            $cart[$cartKey] = $cart[$productTitle];
            unset($cart[$productTitle]);
        }

        $currentQty = isset($cart[$cartKey]) ? (int) $cart[$cartKey]['quantity'] : 0;
        $newQty = $currentQty + $qty;

        $isIndent = ($stock > 0 && $newQty > $stock) || ($stock === 0);

        $cart[$cartKey] = [
            'id' => $productId,
            'title' => $productTitle,
            'catalog' => $productCatalog,
            'image' => $productImage,
            'price' => $productPrice,
            'stock' => $stock,
            'quantity' => $newQty,
        ];

        session()->put('cart', $cart);

        $cartCount = array_sum(array_column($cart, 'quantity'));

        $successMessage = $isIndent
            ? 'Produk berhasil ditambahkan ke keranjang (Status: Indent / Pre-Order)!'
            : 'Produk berhasil ditambahkan ke keranjang!';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'cartCount' => $cartCount,
                'isIndent' => $isIndent,
                'stock' => $stock,
                'quantity' => $newQty,
            ]);
        }

        return redirect()->back()->with('success', $successMessage);
    }

    public function update(Request $request)
    {
        $id = $request->input('id');
        $title = $request->input('title');
        $qty = max(1, (int) $request->input('quantity', 1));

        $cart = session()->get('cart', []);
        $cartKey = null;

        if (! empty($id) && isset($cart[(string) $id])) {
            $cartKey = (string) $id;
        } elseif (! empty($title) && isset($cart[$title])) {
            $cartKey = $title;
        } else {
            // Search in items
            foreach ($cart as $k => $item) {
                if ((! empty($id) && isset($item['id']) && (int) $item['id'] === (int) $id) ||
                    (! empty($title) && isset($item['title']) && $item['title'] === $title)) {
                    $cartKey = $k;
                    break;
                }
            }
        }

        if ($cartKey !== null && isset($cart[$cartKey])) {
            // Fetch fresh stock from DB to ensure accurate limit
            $product = null;
            if (! empty($cart[$cartKey]['id'])) {
                $product = $this->dataService->getProductById((int) $cart[$cartKey]['id']);
            }
            if (! $product && ! empty($cart[$cartKey]['title'])) {
                $product = $this->dataService->getProductByTitle($cart[$cartKey]['title']);
            }

            $stock = $product
                ? (int) (is_object($product) ? $product->stock : ($product['stock'] ?? 0))
                : (int) ($cart[$cartKey]['stock'] ?? 0);

            $cart[$cartKey]['stock'] = $stock;
            $cart[$cartKey]['quantity'] = $qty;
            session()->put('cart', $cart);
        }

        if ($request->ajax() || $request->wantsJson()) {
            $total = 0;
            foreach ($cart as $item) {
                $total += ($item['price'] * $item['quantity']);
            }
            $itemSubtotal = ($cartKey !== null && isset($cart[$cartKey])) ? ($cart[$cartKey]['price'] * $cart[$cartKey]['quantity']) : 0;

            return response()->json([
                'success' => true,
                'cart' => $cart,
                'total' => $total,
                'totalFormatted' => $total > 0 ? 'Rp '.number_format($total, 0, ',', '.') : 'Rp 0',
                'itemSubtotal' => $itemSubtotal > 0 ? 'Rp '.number_format($itemSubtotal, 0, ',', '.') : 'Est. Penawaran',
                'cartCount' => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function remove(Request $request)
    {
        $id = $request->input('id');
        $title = $request->input('title');
        $cart = session()->get('cart', []);

        $targetKey = null;
        if (! empty($id) && isset($cart[(string) $id])) {
            $targetKey = (string) $id;
        } elseif (! empty($title) && isset($cart[$title])) {
            $targetKey = $title;
        } else {
            foreach ($cart as $k => $item) {
                if ((! empty($id) && isset($item['id']) && (int) $item['id'] === (int) $id) ||
                    (! empty($title) && isset($item['title']) && $item['title'] === $title)) {
                    $targetKey = $k;
                    break;
                }
            }
        }

        if ($targetKey !== null && isset($cart[$targetKey])) {
            unset($cart[$targetKey]);
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
                'totalFormatted' => $total > 0 ? 'Rp '.number_format($total, 0, ',', '.') : 'Rp 0',
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

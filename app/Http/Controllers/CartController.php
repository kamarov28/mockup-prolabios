<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\DataService;
use App\Traits\ResolvesProducts;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ResolvesProducts;

    protected DataService $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    private function findCartKey(array $cart, string $id = '', string $title = ''): ?string
    {
        if (! empty($id) && isset($cart[(string) $id])) {
            return (string) $id;
        }

        if (! empty($title) && isset($cart[$title])) {
            return $title;
        }

        foreach ($cart as $k => $item) {
            $idMatch = ! empty($id) && isset($item['id']) && (int) $item['id'] === (int) $id;
            $titleMatch = ! empty($title) && isset($item['title']) && $item['title'] === $title;

            if ($idMatch || $titleMatch) {
                return $k;
            }
        }

        return null;
    }

    private function productToCartRow(Product $product, int $quantity, float $priceOverride = 0.0): array
    {
        return [
            'id' => $product->id,
            'slug' => $product->slug ?? '',
            'title' => $product->title,
            'catalog' => $product->catalog ?? '',
            'image' => $product->image ?? '',
            'price' => (float) $product->price > 0 ? (float) $product->price : $priceOverride,
            'stock' => (int) ($product->stock ?? 0),
            'quantity' => $quantity,
        ];
    }

    private function cartTotal(array $cart): float
    {
        return (float) array_sum(array_map(
            fn ($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 0),
            $cart
        ));
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        $migratedCart = [];

        foreach ($cart as $key => $item) {
            $product = $this->resolveProduct($item['id'] ?? null, $item['title'] ?? null);

            if ($product) {
                $cartKey = (string) $product->id;
                $row = $this->productToCartRow($product, (int) ($item['quantity'] ?? 1), (float) ($item['price'] ?? 0));
                $migratedCart[$cartKey] = $row;
                $total += $row['price'] * $row['quantity'];
            } else {
                $cartKey = (string) ($item['id'] ?? $key);
                $row = [
                    'id' => $item['id'] ?? $key,
                    'slug' => $item['slug'] ?? '',
                    'title' => $item['title'] ?? $key,
                    'catalog' => $item['catalog'] ?? '',
                    'image' => $item['image'] ?? '',
                    'price' => (float) ($item['price'] ?? 0),
                    'stock' => (int) ($item['stock'] ?? 0),
                    'quantity' => (int) ($item['quantity'] ?? 1),
                ];
                $migratedCart[$cartKey] = $row;
                $total += $row['price'] * $row['quantity'];
            }
        }

        session()->put('cart', $migratedCart);

        return view('cart', ['cart' => $migratedCart, 'total' => $total]);
    }

    public function add(Request $request)
    {
        $id = $request->input('id');
        $title = $request->input('title');
        $qty = max(1, (int) $request->input('quantity', 1));

        $product = $this->resolveProduct($id, $title);

        if (! $product) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.'], 404);
            }

            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        $cart = session()->get('cart', []);
        $cartKey = (string) $product->id;

        if (! isset($cart[$cartKey]) && isset($cart[$product->title])) {
            $cart[$cartKey] = $cart[$product->title];
            unset($cart[$product->title]);
        }

        $currentQty = (int) ($cart[$cartKey]['quantity'] ?? 0);
        $newQty = $currentQty + $qty;
        $stock = (int) ($product->stock ?? 0);
        $isIndent = ($stock > 0 && $newQty > $stock) || ($stock === 0);

        $cart[$cartKey] = $this->productToCartRow($product, $newQty);

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
        $id = (string) $request->input('id', '');
        $title = (string) $request->input('title', '');
        $qty = max(1, (int) $request->input('quantity', 1));
        $cart = session()->get('cart', []);
        $cartKey = $this->findCartKey($cart, $id, $title);

        if ($cartKey !== null && isset($cart[$cartKey])) {
            $product = $this->resolveProduct(
                (string) ($cart[$cartKey]['id'] ?? ''),
                (string) ($cart[$cartKey]['title'] ?? '')
            );

            $cart[$cartKey]['stock'] = $product
                ? (int) ($product->stock ?? 0)
                : (int) ($cart[$cartKey]['stock'] ?? 0);
            $cart[$cartKey]['quantity'] = $qty;
            if ($product && ! empty($product->slug)) {
                $cart[$cartKey]['slug'] = $product->slug;
            }
            session()->put('cart', $cart);
        }

        if ($request->ajax() || $request->wantsJson()) {
            $total = $this->cartTotal($cart);
            $itemSubtotal = ($cartKey !== null && isset($cart[$cartKey]))
                ? (($cart[$cartKey]['price'] ?? 0) * ($cart[$cartKey]['quantity'] ?? 0))
                : 0;

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
        $id = (string) $request->input('id', '');
        $title = (string) $request->input('title', '');
        $cart = session()->get('cart', []);
        $targetKey = $this->findCartKey($cart, $id, $title);

        if ($targetKey !== null && isset($cart[$targetKey])) {
            unset($cart[$targetKey]);
            session()->put('cart', $cart);
        }

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            $total = $this->cartTotal($cart);

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

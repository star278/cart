<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('shop.index', compact('products'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        $cart = $this->getOrCreateCart();

        $item = $cart->items()->where('product_id', $productId)->first();

        if ($item) {
            $item->quantity += $quantity;
            $item->save();
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Товар добавлен в корзину!');
    }

    public function cart()
    {
        $cart = $this->getOrCreateCart();
        $items = $cart->items()->with('product')->get();

        return view('shop.cart', compact('items'));
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'quantities' => 'required|array'
        ]);

        $cart = $this->getOrCreateCart();

        foreach ($request->quantities as $itemId => $qty) {
            $item = $cart->items()->where('id', $itemId)->first();
            if ($item) {
                $item->update(['quantity' => (int) $qty]);
            }
        }

        return redirect()->route('cart.index')->with('success', 'Корзина обновлена!');
    }

    public function removeFromCart(Request $request)
    {
        $itemId = $request->input('item_id');

        $cart = $this->getOrCreateCart();
        $item = $cart->items()->where('id', $itemId)->first();

        if ($item) {
            $item->delete();
        }

        return redirect()->route('cart.index')->with('success', 'Товар удален!');
    }

    private function getOrCreateCart(): Cart
    {
        $sessionId = session()->get('guest_cart_id');

        if (!$sessionId) {
            $sessionId = (string) Str::uuid();
            session(['guest_cart_id' => $sessionId]);
        }

        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }
}

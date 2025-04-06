<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->get(); // Charger les utilisateurs associés
        return view('admin.orders.index', compact('orders'));
    }

    // Afficher le formulaire de création
    public function create()
    {
        $users = User::all();
        $products = Product::all();
        return view('admin.orders.create', compact('users', 'products'));
    }

    // Enregistrer une nouvelle commande
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'order_date' => 'required|date',
            'address' => 'required|string',
            'total_amount' => 'required|numeric',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
        ]);

        $order = Order::create([
            'user_id' => $request->user_id,
            'order_date' => $request->order_date,
            'address' => $request->address,
            'total_amount' => $request->total_amount,
        ]);

        foreach ($request->products as $productId) {
            $product = Product::find($productId);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => 1, // Vous pouvez ajuster la quantité si nécessaire
                'price' => $product->price,
            ]);
        }

        return redirect()->route('admin.orders.index')->with('success', 'Commande créée avec succès.');
    }

    // Afficher les détails d'une commande
    public function show($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // Afficher le formulaire d'édition
    public function edit($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        $users = User::all();
        $products = Product::all();
        return view('admin.orders.edit', compact('order', 'users', 'products'));
    }

    // Mettre à jour une commande
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'order_date' => 'required|date',
            'address' => 'required|string',
            'total_amount' => 'required|numeric',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
        ]);

        // Mettre à jour la commande
        $order = Order::findOrFail($id);
        $order->update([
            'user_id' => $request->user_id,
            'order_date' => $request->order_date,
            'address' => $request->address,
            'total_amount' => $request->total_amount,
        ]);

        // Synchroniser les produits associés à la commande
        $order->orderItems()->delete(); // Supprimer les anciens éléments
        foreach ($request->products as $productId) {
            $product = Product::find($productId);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => 1, // Vous pouvez ajuster la quantité si nécessaire
                'price' => $product->price,
            ]);
        }

        return redirect()->route('admin.orders.index')->with('success', 'Commande mise à jour avec succès.');
    }

    // Supprimer une commande
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Commande supprimée avec succès.');
    }

    /**
     * Afficher la page de paiement.
     *
     * @return \Illuminate\View\View
     */
    public function checkout()
    {
        return view('cart.checkout');
    }
}

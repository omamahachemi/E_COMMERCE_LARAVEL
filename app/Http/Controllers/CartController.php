<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Afficher le panier.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupérer le panier depuis la session
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    /**
     * Ajouter un produit au panier.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        // Valider la requête
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        // Récupérer le produit
        $product = Product::findOrFail($request->product_id);

        // Récupérer le panier depuis la session
        $cart = session()->get('cart', []);

        // Vérifier si le produit est déjà dans le panier
        if (isset($cart[$product->id])) {
            // Si oui, augmenter la quantité
            $cart[$product->id]['quantity'] += $request->quantity ?? 1;
        } else {
            // Sinon, ajouter le produit au panier
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $request->quantity ?? 1,
                'image' => $product->image, // Optionnel : ajouter une image du produit
            ];
        }

        // Mettre à jour la session avec le nouveau panier
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Produit ajouté au panier.');
    }

    /**
     * Supprimer un produit du panier.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Request $request)
    {
        // Valider la requête
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        // Récupérer le panier depuis la session
        $cart = session()->get('cart', []);

        // Supprimer le produit du panier
        if (isset($cart[$request->product_id])) {
            unset($cart[$request->product_id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Produit supprimé du panier.');
    }

    /**
     * Mettre à jour la quantité d'un produit dans le panier.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Valider la requête
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Récupérer le panier depuis la session
        $cart = session()->get('cart', []);

        // Mettre à jour la quantité du produit
        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Quantité mise à jour.');
    }
}

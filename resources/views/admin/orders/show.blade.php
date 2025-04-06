<!-- resources/views/admin/orders/show.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="container" style="color: white;">
    <h1>Détails de la commande</h1>
    <p><strong>ID :</strong> {{ $order->id }}</p>
    <p><strong>Date :</strong> {{ $order->order_date }}</p>
    <p><strong>Utilisateur :</strong> {{ $order->user->name }}</p>
    <p><strong>Adresse :</strong> {{ $order->address }}</p>
    <p><strong>Montant total :</strong> {{ $order->total_amount }} €</p>

    <h2>Produits</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->price }} €</td>
                    <td>{{ $item->quantity * $item->price }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Retour</a>
</div>
@endsection

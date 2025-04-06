@extends('layouts.admin')

@section('content')
    <h1>Liste des commandes</h1>
    <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">Créer une commande</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Utilisateur</th>
                <th>Adresse</th>
                <th>Montant total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->order_date }}</td>
                    <td>{{ $order->user->name }}</td> <!-- Utilisez la relation `user` -->
                    <td>{{ $order->address }}</td>
                    <td>{{ $order->total_amount }} €</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info">Voir</a>
                        <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-warning">Éditer</a>
                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

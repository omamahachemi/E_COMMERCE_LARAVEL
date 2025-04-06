@extends('layouts.admin')

@section('content')
    <h1>Créer une nouvelle commande</h1>
    <form action="{{ route('admin.orders.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="user_id">Utilisateur</label>
            <select name="user_id" id="user_id" class="form-control" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="order_date">Date de la commande</label>
            <input type="date" name="order_date" id="order_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="address">Adresse de livraison</label>
            <input type="text" name="address" id="address" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="total_amount">Montant total</label>
            <input type="number" name="total_amount" id="total_amount" class="form-control" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="products">Produits</label>
            <select name="products[]" id="products" class="form-control" multiple required>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} - {{ $product->price }} €</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Créer</button>
    </form>
@endsection

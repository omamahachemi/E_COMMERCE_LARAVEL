@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Détails du produit</h1>
    <div class="card" style="color: white;">
        <div class="card-body">
            <h5 class="card-title">{{ $product->name }}</h5>
            <p class="card-text">{{ $product->description }}</p>
            <p class="card-text"><strong>Prix :</strong> {{ $product->price }} €</p>
            <p class="card-text"><strong>Quantité :</strong> {{ $product->quantity }}</p>
            <p class="card-text"><strong>Catégorie :</strong> {{ $product->category->name }}</p>

            <!-- Afficher l'image si elle existe -->
            <div class="mt-4">
                <strong>Image :</strong>
                @if($product->image_url)
                <img src="{{ asset('images/' . $product->image_url ) }}" style="width:400px;height:400px;padding:10px;" alt="Image du produit" class="img-fluid">
                @else
                    <p>Aucune image disponible</p>
                @endif
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Retour</a>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Détails de la catégorie</h1>
    <div class="card" style="color: white;">
        <div class="card-body">
            <h5 class="card-title">{{ $category->name }}</h5>
            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning">Modifier</a>
            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </div>
    </div>
</div>
@endsection

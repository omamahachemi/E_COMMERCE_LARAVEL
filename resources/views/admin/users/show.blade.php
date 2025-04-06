@extends('layouts.admin')

@section('content')
    <h1>Détails de l'utilisateur</h1>
    <p><strong>ID :</strong> {{ $user->id }}</p>
    <p><strong>Nom :</strong> {{ $user->name }}</p>
    <p><strong>Email :</strong> {{ $user->email }}</p>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Retour</a>
@endsection

@extends('layouts.admin')

@section('content')
    <h1>Créer un nouvel utilisateur</h1>
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="is_admin">Administrateur</label>
            <select name="is_admin" id="is_admin" class="form-control">
                <option value="0">Non</option>
                <option value="1">Oui</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Créer</button>
    </form>
@endsection

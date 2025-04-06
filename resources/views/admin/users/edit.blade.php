@extends('layouts.admin')

@section('content')
    <h1>Éditer l'utilisateur</h1>
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
        </div>
        <div class="form-group">
            <label for="password">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>
        <div class="form-group">
            <label for="is_admin">Administrateur</label>
            <select name="is_admin" id="is_admin" class="form-control">
                <option value="0" {{ $user->is_admin ? '' : 'selected' }}>Non</option>
                <option value="1" {{ $user->is_admin ? 'selected' : '' }}>Oui</option>
            </select>
        </div>
        <button type="submit" class="btn btn-warning">Mettre à jour</button>
    </form>
@endsection

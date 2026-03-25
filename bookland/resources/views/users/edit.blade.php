@extends('layouts.app')

@section('content')
<h1>Modifier l'utilisateur : {{ $user->prenom }} {{ $user->nom }}</h1>
<form method="POST" action="{{ route('users.update', $user) }}">
    @csrf @method('PUT')
    @include('users._form')
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="{{ route('users.roles') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection
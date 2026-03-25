@extends('layouts.app')

@section('content')
<h1>Créer un utilisateur</h1>
<form method="POST" action="{{ route('users.store') }}">
    @csrf
    @include('users._form')
    <button type="submit" class="btn btn-primary">Créer</button>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection
@extends('layouts.app')

@section('content')
<h1>Créer une ville</h1>
<form method="POST" action="{{ route('villes.store') }}">
    @csrf
    @include('villes._form')
    <button type="submit" class="btn btn-primary">Créer</button>
    <a href="{{ route('villes.index') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection
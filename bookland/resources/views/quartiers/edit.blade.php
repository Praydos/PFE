@extends('layouts.app')

@section('content')
<h1>Modifier le quartier : {{ $quartier->nom }}</h1>
<form method="POST" action="{{ route('quartiers.update', $quartier) }}">
    @csrf @method('PUT')
    @include('quartiers._form')
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="{{ route('quartiers.index') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection
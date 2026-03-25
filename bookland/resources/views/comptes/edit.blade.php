@extends('layouts.app')

@section('content')
<h1>Modifier le compte : {{ $compte->etablissement }}</h1>
<form method="POST" action="{{ route('comptes.update', $compte) }}">
    @csrf @method('PUT')
    @include('comptes._form')
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="{{ route('comptes.index') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection
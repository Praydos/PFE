@extends('layouts.app')

@section('content')
<h1>Créer un quartier</h1>
<form method="POST" action="{{ route('quartiers.store') }}">
    @csrf
    @include('quartiers._form')
    <button type="submit" class="btn btn-primary">Créer</button>
    <a href="{{ route('quartiers.index') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection
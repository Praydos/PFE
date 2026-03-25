@extends('layouts.app')

@section('content')
<h1>Créer une zone</h1>
<form method="POST" action="{{ route('zones.store') }}">
    @csrf
    @include('zones._form', ['selectedVilleId' => $selectedVilleId ?? null])
    <button type="submit" class="btn btn-primary">Créer</button>
    <a href="{{ route('zones.index') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection
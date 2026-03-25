@extends('layouts.app')

@section('content')
<h1>Modifier la zone : {{ $zone->name }}</h1>
<form method="POST" action="{{ route('zones.update', $zone) }}">
    @csrf @method('PUT')
    @include('zones._form')
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="{{ route('zones.index') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection
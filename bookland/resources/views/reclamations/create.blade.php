@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Nouvelle réclamation</h1>
    <form method="POST" action="{{ route('reclamations.store') }}">
        @csrf
        @include('reclamations._form')
        <button type="submit" class="btn-primary">Créer</button>
        <a href="{{ route('reclamations.index') }}" class="btn-secondary">Annuler</a>
    </form>
</div>
@endsection
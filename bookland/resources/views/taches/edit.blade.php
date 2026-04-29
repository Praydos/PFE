@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Modifier la tâche</h1>
    <form method="POST" action="{{ route('taches.update', $tache) }}">
        @csrf @method('PUT')
        @include('taches._form')
        <button type="submit" class="btn-primary">Mettre à jour</button>
        <a href="{{ route('taches.index') }}" class="btn-secondary">Annuler</a>
    </form>
</div>
@endsection
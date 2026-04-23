@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Modifier l'événement</h1>
    <form method="POST" action="{{ route('events.update', $event) }}">
        @csrf @method('PUT')
        @include('events._form')
        <div class="mt-3">
            <button type="submit" class="btn-primary">Mettre à jour</button>
            <a href="{{ route('events.show', $event) }}" class="btn-ghost">Annuler</a>
        </div>
    </form>
</div>
@endsection
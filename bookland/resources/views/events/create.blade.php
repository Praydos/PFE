@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Nouvel événement</h1>
    <form method="POST" action="{{ route('events.store') }}">
        @csrf
        @include('events._form')
        <div class="mt-3">
            <button type="submit" class="btn-primary">Créer</button>
            <a href="{{ route('events.index') }}" class="btn-ghost">Annuler</a>
        </div>
    </form>
</div>
@endsection
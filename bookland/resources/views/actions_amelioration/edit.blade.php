@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Modifier l'action d'amélioration</h1>
    <form method="POST" action="{{ route('actions-amelioration.update', $actions_amelioration) }}">
        @csrf @method('PUT')
        @include('actions_amelioration._form')
        <button type="submit" class="btn-primary">Mettre à jour</button>
        <a href="{{ route('actions-amelioration.index') }}" class="btn-secondary">Annuler</a>
    </form>
</div>
@endsection
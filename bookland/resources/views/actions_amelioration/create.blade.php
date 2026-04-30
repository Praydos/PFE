@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Nouvelle action d'amélioration</h1>
    <form method="POST" action="{{ route('actions-amelioration.store') }}">
        @csrf
        @include('actions_amelioration._form_stage1')
        <button type="submit" class="btn-primary">Créer</button>
        <a href="{{ route('actions-amelioration.index') }}" class="btn-secondary">Annuler</a>
    </form>
</div>
@endsection
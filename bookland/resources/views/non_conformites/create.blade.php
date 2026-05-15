@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Nouvelle non‑conformité</h1>
    <form method="POST" action="{{ route('non-conformites.store') }}">
        @csrf
        @include('non_conformites._form_stage1')
        <button type="submit" class="btn-primary">Créer</button>
        <a href="{{ route('non-conformites.index') }}" class="btn-secondary">Annuler</a>
    </form>
</div>
@endsection
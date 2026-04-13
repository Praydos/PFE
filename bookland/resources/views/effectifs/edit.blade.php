@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Modifier l'effectif</h1>
    <form method="POST" action="{{ route('effectifs.update', $effectif) }}">
        @csrf @method('PUT')
        @include('effectifs._form')
        <button type="submit" class="btn-zn btn-zn-primary">Mettre à jour</button>
        <a href="{{ route('effectifs.index') }}" class="btn-zn btn-zn-ghost">Annuler</a>
    </form>
</div>
@endsection
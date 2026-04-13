@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Nouvel effectif</h1>
    <form method="POST" action="{{ route('effectifs.store') }}">
        @csrf
        @include('effectifs._form')
        <button type="submit" class="btn-zn btn-zn-primary">Enregistrer</button>
        <a href="{{ route('effectifs.index') }}" class="btn-zn btn-zn-ghost">Annuler</a>
    </form>
</div>
@endsection
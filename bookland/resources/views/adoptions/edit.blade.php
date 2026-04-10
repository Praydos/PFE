@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Modifier l'adoption</h1>
    <form method="PUT" action="{{ route('adoptions.update', $adoption->id) }}">
        @csrf
        @include('adoptions._form', ['adoption' => $adoption])
        <button type="submit" class="btn-dr btn-dr-primary">Enregistrer</button>
        <a href="{{ route('adoptions.index') }}" class="btn-dr btn-dr-ghost">Annuler</a>
    </form>
</div>
@endsection
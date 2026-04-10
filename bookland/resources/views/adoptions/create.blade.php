@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Nouvelle adoption manuelle</h1>
    <form method="POST" action="{{ route('adoptions.store') }}">
        @csrf
        @include('adoptions._form', ['adoption' => null])
        <button type="submit" class="btn-dr btn-dr-primary">Enregistrer</button>
        <a href="{{ route('adoptions.index') }}" class="btn-dr btn-dr-ghost">Annuler</a>
    </form>
</div>
@endsection
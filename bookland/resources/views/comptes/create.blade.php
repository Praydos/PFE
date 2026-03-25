@extends('layouts.app')

@section('content')
<h1>Créer un compte client</h1>
<form method="POST" action="{{ route('comptes.store') }}">
    @csrf
    @include('comptes._form')
    <button type="submit" class="btn btn-primary">Créer</button>
    <a href="{{ route('comptes.index') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection
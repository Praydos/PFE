@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Créer un produit</h1>
    <form method="POST" action="{{ route('products.store') }}">
        @csrf
        @include('products._form')
        <button type="submit" class="btn-dr btn-dr-primary">Créer</button>
        <a href="{{ route('products.index') }}" class="btn-dr btn-dr-ghost">Annuler</a>
    </form>
</div>
@endsection
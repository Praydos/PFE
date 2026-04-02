@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Modifier le produit : {{ $product->titre }}</h1>
    <form method="POST" action="{{ route('products.update', $product) }}">
        @csrf @method('PUT')
        @include('products._form')
        <button type="submit" class="btn-dr btn-dr-primary">Mettre à jour</button>
        <a href="{{ route('products.index') }}" class="btn-dr btn-dr-ghost">Annuler</a>
    </form>
</div>
@endsection
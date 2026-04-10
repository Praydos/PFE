@extends('layouts.app')

@section('content')
<div class="dr-page">
    <div class="dr-header">
        <h1>Convertir en adoption</h1>
        <p>BSS : {{ $bssLigne->bss->numero }} – Produit : {{ $bssLigne->product->titre }}</p>
    </div>

    <div class="dr-card">
        <div class="dr-card-header">
            <div class="dr-card-title">Informations de l'adoption</div>
        </div>
        <div class="dr-card-body">
            <form method="POST" action="{{ route('adoptions.store-convert', $bssLigne) }}">
                @csrf
                @include('adoptions._form', [
                    'adoption' => null,
                    'defaultCompteId' => $defaultCompteId,
                    'defaultProductId' => $defaultProductId,
                    'defaultQuantity' => $defaultQuantity,
                    'defaultDate' => $defaultDate,
                    'defaultNiveau' => $defaultNiveau
                ])
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn-dr btn-dr-primary">Convertir</button>
                    <a href="{{ route('bss.show', $bssLigne->bss) }}" class="btn-dr btn-dr-ghost">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
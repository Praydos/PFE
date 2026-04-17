@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
<style>
    .pr-page { padding: 2rem 2.5rem 3rem; max-width: 1000px; margin: 0 auto; }
    .pr-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: #9ba8c5; margin-bottom: 1.4rem; }
    .pr-bc a { color: #9ba8c5; text-decoration: none; }
    .pr-bc a:hover { color: #5b8dee; }
    .pr-header { margin-bottom: 2rem; }
    .pr-header h1 { font-size: 1.65rem; font-weight: 700; color: #1a1f36; }
    .pr-header p { font-size: .83rem; color: #9ba8c5; margin-top: .3rem; }
    .pr-card { background: white; border-radius: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow: hidden; }
    .pr-card-header { padding: 1.1rem 1.6rem; border-bottom: 1px solid #e4e7f0; background: #fafbff; display: flex; align-items: center; gap: .55rem; }
    .pr-card-pip { width: 7px; height: 7px; border-radius: 50%; background: #e8a020; box-shadow: 0 0 0 3px rgba(232,160,32,.2); }
    .pr-card-title { font-size: .88rem; font-weight: 700; color: #1a1f36; }
    .pr-card-body { padding: 1.75rem 1.6rem; }
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .info-item { font-size: 0.84rem; color: #525f7f; border-bottom: 1px solid #e4e7f0; padding-bottom: 0.5rem; }
    .info-label { font-weight: 600; color: #1a1f36; margin-right: 0.5rem; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.03em; }
    .info-value { color: #525f7f; }
    .pr-badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.7rem; font-weight: 600; }
    .bd-blue { background: #e3f2fd; color: #1976d2; }
    .bd-teal { background: #e0f2f1; color: #00897b; }
    .card-footer { padding: 1.1rem 1.6rem; border-top: 1px solid #e4e7f0; background: #f5f6fa; display: flex; justify-content: flex-end; gap: 0.6rem; }
    .btn-pr { display: inline-flex; align-items: center; gap: .4rem; padding: .56rem 1.1rem; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; }
    .btn-pr-primary { background: #5b8dee; color: white; border: none; }
    .btn-pr-ghost { background: white; color: #525f7f; border: 1px solid #e4e7f0; }
    @media (max-width: 768px) { .pr-page { padding: 1rem; } .info-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="pr-page">
    {{-- Breadcrumb --}}
    <div class="pr-bc">
        <a href="{{ route('products.index') }}">Produits</a>
        <span>›</span>
        <span>{{ $product->titre }}</span>
    </div>

    <div class="pr-header">
        <h1>{{ $product->titre }}</h1>
        <p>{{ $product->sous_titre ?? 'Fiche produit' }}</p>
    </div>

    <div class="pr-card">
        <div class="pr-card-header">
            <span class="pr-card-pip"></span>
            <span class="pr-card-title">Informations générales</span>
        </div>
        <div class="pr-card-body">
            <div class="info-grid">
                <div class="info-item"><span class="info-label">Source</span> <span class="pr-badge {{ $product->source == 'bookland' ? 'bd-blue' : 'bd-teal' }}">{{ ucfirst(str_replace('_', ' ', $product->source)) }}</span></div>
                <div class="info-item"><span class="info-label">Titre</span> {{ $product->titre }}</div>
                <div class="info-item"><span class="info-label">Sous-titre</span> {{ $product->sous_titre ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Niveau</span> {{ $product->niveau ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Type</span> {{ $product->type }}</div>
                <div class="info-item"><span class="info-label">Édition</span> {{ $product->edition ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Auteur(s)</span> {{ $product->auteur ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Langue</span> {{ $product->langue ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Rayon</span> {{ $product->rayon ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Sous‑rayon</span> {{ $product->sous_rayon ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Catégorie</span> {{ $product->categorie ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Sous‑catégorie</span> {{ $product->sous_categorie ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Éditeur</span> {{ $product->editeur ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Collection</span> {{ $product->collection ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Support</span> {{ $product->support ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Nombre de pages</span> {{ $product->nbr_pages ?: '-' }}</div>
                <div class="info-item"><span class="info-label">Prix (€)</span> {{ $product->prix ? number_format($product->prix, 2) : '-' }}</div>
                <div class="info-item"><span class="info-label">Date de parution</span> {{ $product->date_parution ? \Carbon\Carbon::parse($product->date_parution)->format('d/m/Y') : '-' }}</div>
                <div class="info-item"><span class="info-label">ISBN‑13</span> {{ $product->isbn_13 ?? '-' }}</div>
                <div class="info-item"><span class="info-label">ISBN‑10</span> {{ $product->isbn_10 ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Référence interne</span> {{ $product->reference_interne ?? '-' }}</div>
            </div>
            @if($product->description)
            <div class="info-item" style="grid-column: 1/-1;">
                <span class="info-label">Description</span>
                <div class="info-value" style="margin-top: 0.5rem;">{{ $product->description }}</div>
            </div>
            @endif
            @if($product->image)
            <div class="info-item" style="grid-column: 1/-1;">
                <span class="info-label">Image</span>
                <div><img src="{{ $product->image }}" alt="{{ $product->titre }}" style="max-width: 200px; margin-top: 0.5rem;"></div>
            </div>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('products.index') }}" class="btn-pr btn-pr-ghost">Retour</a>
            @can('update', $product) {{-- optional, if you have policies --}}
            <a href="{{ route('products.edit', $product) }}" class="btn-pr btn-pr-primary">Modifier</a>
            @endcan
        </div>
    </div>
</div>
@endsection

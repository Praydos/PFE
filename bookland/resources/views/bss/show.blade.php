@extends('layouts.app')

@section('content')
<div class="dr-page">
    <div class="dr-header"><h1>BSS {{ $bss->numero }}</h1></div>
    <div class="dr-card">
        <div class="row">
            <div class="col-md-6"><strong>Compte :</strong> {{ $bss->compte->etablissement }}</div>
            <div class="col-md-6"><strong>Contact :</strong> {{ $bss->contact->prenom }} {{ $bss->contact->nom }}</div>
            <div class="col-md-6"><strong>Délégué :</strong> {{ $bss->delegate->prenom }} {{ $bss->delegate->nom }}</div>
            <div class="col-md-6"><strong>Date création :</strong> {{ $bss->date_bss->format('d/m/Y') }}</div>
            <div class="col-md-6"><strong>Date livraison prévue :</strong> {{ $bss->date_livraison_prevue ? $bss->date_livraison_prevue->format('d/m/Y') : '-' }}</div>
            <div class="col-md-6"><strong>Moyen contact :</strong> {{ $bss->moyen_contact ?? '-' }}</div>
            <div class="col-md-6"><strong>Récupéré par :</strong> {{ $bss->recupere_par_nom }} ({{ $bss->recupere_par_type }})</div>
            <div class="col-md-6"><strong>Statut :</strong> {{ $bss->statut }}</div>
            @if($bss->motif_refus)<div class="col-12"><strong>Motif refus :</strong> {{ $bss->motif_refus }}</div>@endif
            @if($bss->feedback)<div class="col-12"><strong>Feedback :</strong> {{ $bss->feedback }}</div>@endif
            @if($bss->controle_document)<div class="col-12"><strong>Contrôle document :</strong> {{ $bss->controle_document }}</div>@endif
        </div>
        <hr>
        <h3>Produits</h3>
        <table class="dr-table">
            <thead><tr><th>Produit</th><th>Quantité</th><th>Source</th><th>Statut ligne</th></tr></thead>
            <tbody>
                @foreach($bss->lignes as $ligne)
                <tr>
                    <td>{{ $ligne->product->titre }}</td>
                    <td>{{ $ligne->quantity }}</td>
                    <td>{{ $ligne->source }}</td>
                    <td>{{ $ligne->statut_ligne }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">
            <a href="{{ route('bss.index') }}" class="btn-dr btn-dr-ghost">Retour</a>
            @if(auth()->user()->role === 'delegue' && $bss->delegate_id === auth()->id() && $bss->statut === 'valide' && !$bss->feedback)
                <a href="{{ route('bss.edit', $bss) }}" class="btn-dr btn-dr-primary">Ajouter feedback</a>
            @endif
        </div>
        @if($bss->controle_document)
            <div class="col-12"><strong>Contrôle document :</strong> {{ $bss->controle_document }}</div>
        @endif
    </div>
</div>
@endsection
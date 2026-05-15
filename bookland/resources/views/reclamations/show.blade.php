@extends('layouts.app')

@section('content')
<div class="dr-page">
    <div class="dr-header">
        <h1>Réclamation {{ $reclamation->reference }}</h1>
        <p>{{ $reclamation->categorie }} – {{ $reclamation->statut }}</p>
    </div>

    <div class="dr-card">
        <div class="info-grid">
            <div class="info-item"><span class="info-label">Compte</span> {{ $reclamation->compte->etablissement }}</div>
            <div class="info-item"><span class="info-label">Contact</span> {{ $reclamation->contact->prenom }} {{ $reclamation->contact->nom }}</div>
            <div class="info-item"><span class="info-label">Date réclamation</span> {{ $reclamation->date_reclamation->format('d/m/Y') }}</div>
            <div class="info-item"><span class="info-label">Type</span> {{ str_replace('_', ' ', $reclamation->type ?? '-') }}</div>
            <div class="info-item"><span class="info-label">Module lié</span> {{ ucfirst($reclamation->module_lie ?? '-') }} {{ $reclamation->module_id ? ' (ID '.$reclamation->module_id.')' : '' }}</div>
            <div class="info-item"><span class="info-label">Catégorie</span> {{ $reclamation->categorie }} @if($reclamation->sous_categorie) / {{ $reclamation->sous_categorie }} @endif</div>
            <div class="info-item"><span class="info-label">Priorité</span> {{ ucfirst($reclamation->priorite) }}</div>
            <div class="info-item"><span class="info-label">Statut</span> {{ ucfirst($reclamation->statut) }}</div>
            @if($reclamation->date_echeance)
                <div class="info-item"><span class="info-label">Date échéance</span> {{ $reclamation->date_echeance->format('d/m/Y') }}</div>
            @endif
            @if($reclamation->responsable)
                <div class="info-item"><span class="info-label">Responsable</span> {{ $reclamation->responsable->prenom }} {{ $reclamation->responsable->nom }}</div>
            @endif
            @if($reclamation->date_cloture)
                <div class="info-item"><span class="info-label">Date clôture</span> {{ $reclamation->date_cloture->format('d/m/Y') }}</div>
            @endif
            <div class="info-item full"><span class="info-label">Description</span> {{ $reclamation->description }}</div>
            @if($reclamation->analyse)
                <div class="info-item full"><span class="info-label">Analyse</span> {{ $reclamation->analyse }}</div>
            @endif
            @if($reclamation->reponse)
                <div class="info-item full"><span class="info-label">Réponse</span> {{ $reclamation->reponse }}</div>
            @endif
        </div>

        <div class="mt-4">
            <a href="{{ route('reclamations.index') }}" class="btn-secondary">Retour</a>
            @if($reclamation->statut === 'brouillon' && auth()->user()->role === 'delegue' && $reclamation->delegue_id === auth()->id())
                <a href="{{ route('reclamations.edit', $reclamation) }}" class="btn-primary">Modifier</a>
            @endif
            @if(in_array(auth()->user()->role, ['admin','rbo']))
                <a href="{{ route('reclamations.edit', $reclamation) }}" class="btn-primary">Traiter</a>
            @endif
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="dr-page">
    <div class="dr-header">
        <h1>Réclamation {{ $reclamation->reference }}</h1>
        <p>{{ $reclamation->categorie }} – {{ ucfirst($reclamation->statut) }}</p>
    </div>

    <div class="dr-card">
        <div class="info-grid">
            <div class="info-item"><span class="info-label">Compte</span> {{ $reclamation->compte->etablissement }}</div>
            <div class="info-item"><span class="info-label">Contact</span> {{ $reclamation->contact->prenom }} {{ $reclamation->contact->nom }}</div>
            <div class="info-item"><span class="info-label">Date réclamation</span> {{ $reclamation->date_reclamation->format('d/m/Y') }}</div>
            <div class="info-item"><span class="info-label">Type</span> {{ str_replace('_', ' ', $reclamation->type ?? '-') }}</div>
            <div class="info-item"><span class="info-label">Priorité</span> {{ ucfirst($reclamation->priorite) }}</div>
            <div class="info-item"><span class="info-label">Catégorie</span> {{ $reclamation->categorie }} @if($reclamation->sous_categorie) / {{ $reclamation->sous_categorie }} @endif</div>
            @if($reclamation->produit_id)
                <div class="info-item"><span class="info-label">Produit lié</span> <a href="{{ route('products.show', $reclamation->produit_id) }}">{{ optional($reclamation->produit)->titre }}</a></div>
            @endif
            @if($reclamation->specimen_id)
                <div class="info-item"><span class="info-label">Spécimen lié</span> <a href="{{ route('bss.show', $reclamation->specimen_id) }}">{{ optional($reclamation->specimen)->numero }}</a></div>
            @endif
            @if($reclamation->mp_id)
                <div class="info-item"><span class="info-label">MP lié</span> <a href="{{ route('mp-products.show', $reclamation->mp_id) }}">{{ optional($reclamation->mp)->nom }}</a></div>
            @endif
            <div class="info-item full"><span class="info-label">Description</span> {{ $reclamation->description }}</div>
            @if($reclamation->analyse)
                <div class="info-item full"><span class="info-label">Analyse</span> {{ $reclamation->analyse }}</div>
            @endif
            @if($reclamation->reponse)
                <div class="info-item full"><span class="info-label">Réponse</span> {{ $reclamation->reponse }}</div>
            @endif
            @if($reclamation->date_reponse)
                <div class="info-item"><span class="info-label">Date réponse</span> {{ $reclamation->date_reponse->format('d/m/Y') }}</div>
            @endif
            @if($reclamation->responsable)
                <div class="info-item"><span class="info-label">Responsable</span> {{ $reclamation->responsable->prenom }} {{ $reclamation->responsable->nom }}</div>
            @endif
            <div class="info-item"><span class="info-label">Statut</span> {{ ucfirst($reclamation->statut) }}</div>
            @if($reclamation->date_cloture)
                <div class="info-item"><span class="info-label">Date clôture</span> {{ $reclamation->date_cloture->format('d/m/Y') }}</div>
            @endif
            <div class="info-item"><span class="info-label">Est une non‑conformité ?</span> {{ $reclamation->est_non_conformite ? 'Oui' : 'Non' }}</div>
            <div class="info-item"><span class="info-label">Besoin d'action d'amélioration ?</span> {{ $reclamation->besoin_action_amelioration ? 'Oui' : 'Non' }}</div>
        </div>

        <div class="mt-4">
            <a href="{{ route('reclamations.index') }}" class="btn-secondary">Retour</a>
            @if($reclamation->statut === 'brouillon' && auth()->user()->role === 'delegue' && $reclamation->delegue_id === auth()->id())
                <a href="{{ route('reclamations.edit', $reclamation) }}" class="btn-primary">Modifier</a>
            @endif
            @if(in_array(auth()->user()->role, ['admin','rbo']))
                <a href="{{ route('reclamations.edit', $reclamation) }}" class="btn-primary">Traiter</a>
            @endif
            @if(auth()->user()->role === 'admin')
                <form method="POST" action="{{ route('reclamations.destroy', $reclamation) }}" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger" onclick="return confirm('Supprimer définitivement ?')">Supprimer</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
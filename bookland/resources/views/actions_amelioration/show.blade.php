@extends('layouts.app')

@section('content')
<div class="dr-page">
    <div class="dr-header">
        <h1>Action d'amélioration #{{ $actions_amelioration->numero }}</h1>
    </div>
    <div class="dr-card">
        <div class="info-grid">
            <div class="info-item"><span class="info-label">Compte</span> {{ $actions_amelioration->compte->etablissement }}</div>
            <div class="info-item"><span class="info-label">Émetteur</span> {{ $actions_amelioration->emetteur->prenom }} {{ $actions_amelioration->emetteur->nom }}</div>
            <div class="info-item"><span class="info-label">Date AA</span> {{ $actions_amelioration->dateAA->format('d/m/Y') }}</div>
            <div class="info-item"><span class="info-label">Type</span> {{ $actions_amelioration->type }}</div>
            <div class="info-item"><span class="info-label">Origine</span> {{ $actions_amelioration->origine }}</div>
            @if($actions_amelioration->analyse_causes) <div class="info-item"><span class="info-label">Analyse causes</span> {{ $actions_amelioration->analyse_causes }}</div> @endif
            @if($actions_amelioration->sanctions) <div class="info-item"><span class="info-label">Sanctions</span> {{ $actions_amelioration->sanctions }}</div> @endif
            @if($actions_amelioration->resultats_attendus) <div class="info-item"><span class="info-label">Résultats attendus</span> {{ $actions_amelioration->resultats_attendus }}</div> @endif
        </div>

        <!-- Suivi section -->
        @if($actions_amelioration->responsable_suivi_id)
        <hr><h3>Suivi</h3>
        <div class="info-grid">
            <div class="info-item"><span class="info-label">Vérification</span> {{ $actions_amelioration->verification_mise_en_oeuvre ?? '-' }}</div>
            <div class="info-item"><span class="info-label">Responsable suivi</span> {{ $actions_amelioration->responsableSuivi->prenom }} {{ $actions_amelioration->responsableSuivi->nom }}</div>
            <div class="info-item"><span class="info-label">Date suivi</span> {{ $actions_amelioration->date_suivi ? $actions_amelioration->date_suivi->format('d/m/Y') : '-' }}</div>
        </div>
        @endif

        <!-- Efficacité section -->
        @if($actions_amelioration->responsable_efficacite_id)
        <hr><h3>Évaluation de l'efficacité</h3>
        <div class="info-grid">
            <div class="info-item"><span class="info-label">Date efficacité</span> {{ $actions_amelioration->date_efficacite ? $actions_amelioration->date_efficacite->format('d/m/Y') : '-' }}</div>
            <div class="info-item"><span class="info-label">Responsable efficacité</span> {{ $actions_amelioration->responsableEfficacite->prenom }} {{ $actions_amelioration->responsableEfficacite->nom }}</div>
            <div class="info-item"><span class="info-label">Mode contrôle</span> {{ $actions_amelioration->mode_controle ?? '-' }}</div>
            <div class="info-item"><span class="info-label">Description résultat</span> {{ $actions_amelioration->description_resultat ?? '-' }}</div>
            <div class="info-item"><span class="info-label">Action efficace</span> {{ $actions_amelioration->action_efficace === true ? 'Oui' : ($actions_amelioration->action_efficace === false ? 'Non' : '-') }}</div>
            <div class="info-item"><span class="info-label">Besoin d'autre action</span> {{ $actions_amelioration->besoin_action_amelioration === true ? 'Oui' : ($actions_amelioration->besoin_action_amelioration === false ? 'Non' : '-') }}</div>
            <div class="info-item"><span class="info-label">Statut</span> {{ ucfirst($actions_amelioration->statut) }}</div>
            <div class="info-item"><span class="info-label">Date clôture</span> {{ $actions_amelioration->date_cloture ? $actions_amelioration->date_cloture->format('d/m/Y') : '-' }}</div>
        </div>
        @endif

        <!-- Buttons for next stages -->
        <div class="mt-4">
            <a href="{{ route('actions-amelioration.index') }}" class="btn-secondary">Retour</a>
            @if(auth()->user()->role !== 'rbo')
                @if(!$actions_amelioration->responsable_suivi_id)
                    <a href="{{ route('actions-amelioration.edit-suivi', $actions_amelioration) }}" class="btn-primary">Ajouter le suivi</a>
                @elseif(!$actions_amelioration->responsable_efficacite_id)
                    <a href="{{ route('actions-amelioration.edit-efficacite', $actions_amelioration) }}" class="btn-primary">Ajouter l'évaluation</a>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
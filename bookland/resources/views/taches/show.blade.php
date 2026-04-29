@extends('layouts.app')

@section('content')
<div class="dr-page">
    <div class="dr-header">
        <h1>{{ $tache->objet }}</h1>
        <p>Détail de la tâche</p>
    </div>
    <div class="dr-card">
        <div class="info-grid">
            <div class="info-item"><span class="info-label">Objet</span> {{ $tache->objet }}</div>
            <div class="info-item"><span class="info-label">Description</span> {{ $tache->description ?? '-' }}</div>
            <div class="info-item"><span class="info-label">Date</span> {{ $tache->date_planification->format('d/m/Y') }}</div>
            <div class="info-item"><span class="info-label">Date fin</span> {{ $tache->date_fin ? $tache->date_fin->format('d/m/Y') : '-' }}</div>
            <div class="info-item"><span class="info-label">Lieu</span> {{ $tache->lieu ?? '-' }}</div>
            <div class="info-item"><span class="info-label">Toute la journée</span> {{ $tache->all_day ? 'Oui' : 'Non' }}</div>
            <div class="info-item"><span class="info-label">Statut</span> {{ $tache->is_validated ? 'Validée' : 'En attente' }}</div>
            <div class="info-item"><span class="info-label">Délégué</span> {{ $tache->delegate->prenom }} {{ $tache->delegate->nom }}</div>
            <div class="info-item"><span class="info-label">Contacts</span> {{ $tache->contactsList->pluck('prenom')->join(', ') ?: '-' }}</div>
        </div>
        <div class="mt-4">
            <a href="{{ route('taches.index') }}" class="btn-secondary">Retour</a>
            @if(!$tache->is_validated && (auth()->user()->role === 'delegue' && $tache->delegue_id === auth()->id()))
                <a href="{{ route('taches.edit', $tache) }}" class="btn-primary">Modifier</a>
            @endif
            @if(!$tache->is_validated && in_array(auth()->user()->role, ['admin','rbo']))
                <form method="POST" action="{{ route('taches.validate', $tache) }}" style="display:inline;">
                    @csrf <button type="submit" class="btn-primary">Valider</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
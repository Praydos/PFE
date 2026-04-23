@extends('layouts.app')

@section('content')
<div class="dr-page">
    <div class="dr-header">
        <h1>{{ $action->objet }}</h1>
        <p>Action du {{ $action->date_planification->format('d/m/Y') }} – {{ $action->compte->etablissement }}</p>
    </div>
    <div class="dr-card">
        <div class="dr-card-body p-4">
            <div class="row mb-3">
                <div class="col-md-3"><strong>Compte</strong><br>{{ $action->compte->etablissement }}</div>
                <div class="col-md-3"><strong>Date</strong><br>{{ $action->date_planification->format('d/m/Y') }}</div>
                <div class="col-md-2"><strong>Heure</strong><br>{{ $action->heure ?? '-' }}</div>
                <div class="col-md-2"><strong>Durée</strong><br>{{ $action->duree ? $action->duree.' min' : '-' }}</div>
                <div class="col-md-2"><strong>Lieu</strong><br>{{ $action->lieu ?? '-' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3"><strong>Statut</strong><br><span class="dr-badge bd-{{ $action->statut }}">{{ ucfirst($action->statut) }}</span></div>
                <div class="col-md-3"><strong>Type</strong><br>{{ ucfirst($action->type) }}</div>
                @if($action->statut == 'realise')<div class="col-md-3"><strong>Réalisée le</strong><br>{{ $action->date_realisation->format('d/m/Y H:i') }}</div>@endif
                @if($action->statut == 'valide')<div class="col-md-3"><strong>Validée le</strong><br>{{ $action->date_validation->format('d/m/Y H:i') }}</div>@endif
            </div>
            <hr>
            <h5>Lignes d'action</h5>
            @foreach($action->lignes as $line)
            <div class="card bg-light mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3"><strong>Catégorie</strong><br>{{ $line->categorie }}</div>
                        <div class="col-md-3"><strong>Type</strong><br>{{ $line->action_type }}</div>
                        <div class="col-md-2"><strong>Moyen</strong><br>{{ $line->moyen ?? '-' }}</div>
                        <div class="col-md-4"><strong>Description</strong><br>{{ $line->description ?? '-' }}</div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4"><strong>Contacts</strong><br>{{ $line->contacts->pluck('prenom')->join(', ') ?: '-' }}</div>
                        <div class="col-md-4"><strong>Produits</strong><br>{{ $line->products->pluck('titre')->join(', ') ?: '-' }}</div>
                        <div class="col-md-4"><strong>Examens</strong><br>{{ $line->examens->pluck('titre')->join(', ') ?: '-' }}</div>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('actions.index') }}" class="btn-ghost">Retour</a>
                @if(in_array(auth()->user()->role, ['admin','rbo']) || (auth()->user()->role === 'delegue' && $action->delegue_id === auth()->id()))
                    @if($action->statut === 'planifie')
                        <form method="POST" action="{{ route('actions.realiser', $action) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-primary" onclick="return confirm('Marquer comme réalisée ?')">Réaliser</button>
                        </form>
                        <form method="POST" action="{{ route('actions.annuler', $action) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-danger" onclick="return confirm('Annuler ?')">Annuler</button>
                        </form>
                        <button type="button" class="btn-ghost" data-bs-toggle="modal" data-bs-target="#reportModal">Reporter</button>
                    @elseif($action->statut === 'realise' && in_array(auth()->user()->role, ['admin','rbo']))
                        <form method="POST" action="{{ route('actions.valider', $action) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-success" onclick="return confirm('Valider ?')">Valider</button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal for reporting -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('actions.reporter', $action) }}">
                @csrf
                <div class="modal-header"><h5>Reporter l'action</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <label>Nouvelle date *</label>
                    <input type="date" name="nouvelle_date" class="form-control" required>
                    <label class="mt-2">Nouvelle heure</label>
                    <input type="time" name="nouvelle_heure" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn-primary">Reporter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
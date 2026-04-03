@extends('layouts.app')

@section('content')
<div class="dr-page">
    <div class="dr-header">
        <div class="dr-header-left">
            <h1>BSS {{ $bss->numero }}</h1>
            <p>{{ $bss->compte->etablissement }} - {{ $bss->date_bss->format('d/m/Y') }}</p>
        </div>
        <div class="dr-header-actions">
            @if(in_array($bss->statut, ['brouillon', 'en_attente']) && in_array(auth()->user()->role, ['admin','rbo']))
                <a href="{{ route('bss.edit', $bss) }}" class="btn-dr btn-dr-warning">Modifier</a>
            @endif
            @if($bss->statut == 'livre' && auth()->user()->role == 'admin')
                <a href="{{ route('retours.create', $bss) }}" class="btn-dr btn-dr-primary">Retour</a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="dr-card">
                <div class="dr-card-header">Informations générales</div>
                <div class="dr-card-body">
                    <p><strong>Compte:</strong> {{ $bss->compte->etablissement }}</p>
                    <p><strong>Contact:</strong> {{ $bss->contact->prenom }} {{ $bss->contact->nom }}</p>
                    <p><strong>Moyen contact:</strong> {{ $bss->moyen_contact ?? '-' }}</p>
                    <p><strong>Délégué:</strong> {{ $bss->delegate->prenom }} {{ $bss->delegate->nom }}</p>
                    <p><strong>Année scolaire:</strong> {{ $bss->anneeScolaire->libelle }}</p>
                    <p><strong>Source:</strong> {{ ucfirst($bss->source) }}</p>
                    <p><strong>Statut:</strong> <span class="dr-badge bd-{{ $bss->statut == 'valide' ? 'teal' : ($bss->statut == 'livre' ? 'blue' : 'none') }}">{{ ucfirst($bss->statut) }}</span></p>
                    @if($bss->motif_validation)<p><strong>Motif validation:</strong> {{ $bss->motif_validation }}</p>@endif
                    <p><strong>Observation:</strong> {{ $bss->observation ?? '-' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="dr-card">
                <div class="dr-card-header">Livraison & Contrôle</div>
                <div class="dr-card-body">
                    <p><strong>Date BSS:</strong> {{ $bss->date_bss->format('d/m/Y') }}</p>
                    <p><strong>Date livraison:</strong> {{ $bss->date_livraison ? $bss->date_livraison->format('d/m/Y') : '-' }}</p>
                    <p><strong>Récupéré par:</strong> 
                        @if($bss->recupere_par_type == 'contact' && $bss->recupereParContact)
                            {{ $bss->recupereParContact->prenom }} {{ $bss->recupereParContact->nom }}
                        @elseif($bss->numero_expedition)
                            Expédition: {{ $bss->numero_expedition }}
                        @else
                            -
                        @endif
                    </p>
                    <p><strong>Contrôle document:</strong> {{ $bss->controle ?? '-' }}</p>
                    <p><strong>Feedback:</strong> {{ $bss->feedback_statut ?? '-' }} @if($bss->feedback_date) ({{ $bss->feedback_date->format('d/m/Y') }}) @endif</p>
                </div>
                @if(in_array(auth()->user()->role, ['admin','rbo']))
                <div class="dr-card-footer">
                    @if($bss->statut == 'valide')
                        <form method="POST" action="{{ route('bss.delivered', $bss) }}" style="display:inline;">
                            @csrf
                            <input type="hidden" name="date_livraison" value="{{ date('Y-m-d') }}">
                            <button type="submit" class="btn-dr btn-dr-primary">Marquer livré</button>
                        </form>
                    @endif
                    @if($bss->statut == 'livre')
                        <form method="POST" action="{{ route('bss.control', $bss) }}" style="display:inline;">
                            @csrf
                            <select name="controle">
                                <option value="OK">OK</option>
                                <option value="absence_signature">Absence signature</option>
                                <option value="absence_cachet">Absence cachet</option>
                                <option value="absence_document">Absence document</option>
                            </select>
                            <button type="submit" class="btn-dr btn-dr-sm">Enregistrer contrôle</button>
                        </form>
                        <form method="POST" action="{{ route('bss.feedback', $bss) }}" style="display:inline;">
                            @csrf
                            <select name="feedback_statut">
                                <option value="confirme">Confirmé</option>
                                <option value="defavorable">Défavorable</option>
                            </select>
                            <input type="date" name="feedback_date">
                            <button type="submit" class="btn-dr btn-dr-sm">Feedback</button>
                        </form>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="dr-card mt-3">
        <div class="dr-card-header">Produits</div>
        <div class="dr-card-body">
            <table class="dr-table">
                <thead>
                    <tr><th>Produit</th><th>Quantité</th><th>Statut ligne</th><th>Date retour</th></tr>
                </thead>
                <tbody>
                    @foreach($bss->lignes as $line)
                    <tr>
                        <td>{{ $line->product->titre }}<br><small>{{ $line->product->isbn_13 ?? $line->product->isbn_10 }}</small></td>
                        <td>{{ $line->quantite }}</td>
                        <td>{{ ucfirst($line->statut_ligne) }}</td>
                        <td>{{ $line->date_retour ? $line->date_retour->format('d/m/Y') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
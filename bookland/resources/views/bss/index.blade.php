@extends('layouts.app')

@section('content')
<div class="dr-page">
    <div class="dr-header">
        <div class="dr-header-left">
            <h1>Bons de Spécimens (BSS)</h1>
            <p>Gérez les livraisons de spécimens aux écoles</p>
        </div>
        <div class="dr-header-actions">
            <a href="{{ route('bss.create') }}" class="btn-dr btn-dr-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Nouveau BSS
            </a>
        </div>
    </div>

    <div class="dr-search-bar">
        <form method="GET" action="{{ route('bss.index') }}" style="display:flex; gap:1rem; flex-wrap:wrap;">
            <div class="dr-search-wrap">
                <label style="font-size:0.7rem;">Statut</label>
                <select name="statut" class="form-select">
                    <option value="">Tous</option>
                    @foreach($statuses as $s)
                        <option value="{{ $s }}" {{ request('statut') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            @if(auth()->user()->role !== 'delegue')
            <div class="dr-search-wrap">
                <label style="font-size:0.7rem;">Délégué</label>
                <select name="delegate_id" class="form-select">
                    <option value="">Tous</option>
                    @foreach($delegates as $del)
                        <option value="{{ $del->id }}" {{ request('delegate_id') == $del->id ? 'selected' : '' }}>{{ $del->prenom }} {{ $del->nom }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="dr-search-wrap">
                <label style="font-size:0.7rem;">Année</label>
                <select name="annee_scolaire_id" class="form-select">
                    @foreach($years as $y)
                        <option value="{{ $y->id }}" {{ request('annee_scolaire_id') == $y->id ? 'selected' : '' }}>{{ $y->libelle }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-dr btn-dr-ghost">Filtrer</button>
            <a href="{{ route('bss.index') }}" class="btn-dr btn-dr-danger-ghost">Réinitialiser</a>
        </form>
    </div>

    <div class="dr-card">
        <div class="dr-card-header">
            <div class="dr-card-title">Liste des BSS</div>
        </div>
        <div style="overflow-x:auto;">
            <table class="dr-table">
                <thead>
                    <tr>
                        <th>N° BSS</th><th>Établissement</th><th>Délégué</th><th>Date</th><th>Statut</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bssList as $b)
                    <tr>
                        <td><strong>{{ $b->numero }}</strong></td>
                        <td>{{ $b->compte->etablissement }}<br><small>{{ $b->compte->ville->nom }}</small></td>
                        <td>{{ $b->delegate->prenom }} {{ $b->delegate->nom }}</td>
                        <td>{{ $b->date_bss->format('d/m/Y') }}</td>
                        <td><span class="dr-badge bd-{{ $b->statut == 'valide' ? 'teal' : ($b->statut == 'livre' ? 'blue' : 'none') }}">{{ ucfirst($b->statut) }}</span></td>
                        <td>
                            <a href="{{ route('bss.show', $b) }}" class="btn-dr btn-dr-sm btn-dr-ghost">Détail</a>
                            @if($b->statut == 'en_attente' && in_array(auth()->user()->role, ['admin','rbo']))
                                <form action="{{ route('bss.validate', $b) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-dr btn-dr-sm btn-dr-primary">Valider</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $bssList->links() }}
    </div>
</div>
@endsection
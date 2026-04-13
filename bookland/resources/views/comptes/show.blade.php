@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
<style>
    /* Copy the same CSS from bss/show.blade.php (the zn-page, zn-card, info-grid, etc.) */
    /* For brevity, assume the same CSS is included – you can copy it from your bss/show file */
    .zn-page { padding: 2rem 2.5rem 3rem; max-width: 1200px; margin: 0 auto; }
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .info-item { font-size: 0.84rem; color: var(--text-secondary); border-bottom: 1px solid var(--border); padding-bottom: 0.5rem; }
    .info-label { font-weight: 600; color: var(--text-primary); margin-right: 0.5rem; font-size: 0.75rem; text-transform: uppercase; }
    .dr-badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.7rem; font-weight: 600; }
    .bd-teal { background: #e0f2f1; color: #00897b; }
    .bd-blue { background: #e3f2fd; color: #1976d2; }
    .bd-green { background: #e8f5e9; color: #388e3c; }
    .bd-amber { background: #fff8e1; color: #ff8f00; }
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('comptes.index') }}">Comptes</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">{{ $compte->etablissement }}</span>
    </div>

    <div class="zn-header">
        <h1>{{ $compte->etablissement }}</h1>
        <p>Détail du compte client</p>
    </div>

    {{-- Basic info card --}}
    <div class="zn-card">
        <div class="zn-card-header">
            <span class="zn-card-pip"></span>
            <span class="zn-card-title">Informations générales</span>
        </div>
        <div class="zn-card-body">
            <div class="info-grid">
                <div class="info-item"><span class="info-label">Type</span> {{ ucfirst(str_replace('_', ' ', $compte->type)) }}</div>
                <div class="info-item"><span class="info-label">Statut</span> <span class="dr-badge {{ $compte->status == 'actif' ? 'bd-green' : 'bd-none' }}">{{ $compte->status }}</span></div>
                <div class="info-item"><span class="info-label">Ville</span> {{ $compte->ville->nom }}</div>
                <div class="info-item"><span class="info-label">Zone</span> {{ $compte->zone->name ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Quartier</span> {{ $compte->quartier->nom ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Adresse</span> {{ $compte->adresse }}</div>
                <div class="info-item"><span class="info-label">Téléphone</span> {{ $compte->tel_bureau_1 ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Email</span> {{ $compte->email ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Cycle</span> {{ $compte->cycle ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Délégué</span> {{ optional($compte->delegue)->prenom }} {{ optional($compte->delegue)->nom }}</div>
                <div class="info-item"><span class="info-label">Taille établissement</span> <span class="dr-badge bd-amber">{{ $compte->taille }}</span></div>
            </div>
        </div>
    </div>

    {{-- Contacts card --}}
    <div class="zn-card mt-4">
        <div class="zn-card-header">
            <span class="zn-card-pip"></span>
            <span class="zn-card-title">Contacts</span>
        </div>
        <div class="zn-card-body">
            @if($compte->contacts->isEmpty())
                <p class="text-muted">Aucun contact associé.</p>
            @else
                <div class="table-responsive">
                    <table class="zn-table">
                        <thead>
                            <tr><th>Nom</th><th>Fonction</th><th>Téléphone</th><th>Email</th><th>Décideur</th></tr>
                        </thead>
                        <tbody>
                            @foreach($compte->contacts as $contact)
                            <tr>
                                <td>{{ $contact->prenom }} {{ $contact->nom }}</td>
                                <td>{{ $contact->fonction ?? '-' }}</td>
                                <td>{{ $contact->telephone ?? '-' }}</td>
                                <td>{{ $contact->email ?? '-' }}</td>
                                <td>{{ $contact->pivot->decideur ? 'Oui' : 'Non' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Effectifs card with year selector --}}
    <div class="zn-card mt-4">
        <div class="zn-card-header">
            <span class="zn-card-pip"></span>
            <span class="zn-card-title">Effectifs scolaires</span>
        </div>
        <div class="zn-card-body">
            <form method="GET" action="{{ route('comptes.show', $compte) }}" class="mb-3">
                <label>Sélectionner une année :</label>
                <select name="year_id" class="form-select w-auto d-inline-block ms-2" onchange="this.form.submit()">
                    @foreach($years as $y)
                        <option value="{{ $y->id }}" {{ request('year_id', $currentYear->id ?? '') == $y->id ? 'selected' : '' }}>{{ $y->libelle }}</option>
                    @endforeach
                </select>
            </form>

            @php
                $selectedYearId = request('year_id', $currentYear->id ?? null);
                $yearEffectifs = $effectifsByYear->get($selectedYearId, collect());
            @endphp

            @if($yearEffectifs->isEmpty())
                <p class="text-muted">Aucun effectif renseigné pour cette année.</p>
            @else
                <div class="table-responsive">
                    <table class="zn-table">
                        <thead>
                            <tr>
                                <th>Niveau</th>
                                <th>Cycle</th>
                                <th>Massar</th>
                                <th>Source 1 (classes)</th>
                                <th>Source 2 (classes)</th>
                                <th>Source 3 (classes)</th>
                                <th>Effectif validé</th>
                                <th>Statut validation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($yearEffectifs as $eff)
                            <tr>
                                <td>{{ $eff->niveau }}</td>
                                <td>{{ $eff->cycle ?? '-' }}</td>
                                <td>{{ $eff->massar ?? '-' }}</td>
                                <td>
                                    @if($eff->source_1)
                                        {{ optional($eff->sourceContact1)->prenom }} {{ optional($eff->sourceContact1)->nom }}<br>
                                        <span class="dr-badge bd-teal">{{ $eff->nombre_classes_1 }} classe(s)</span>
                                    @else -
                                    @endif
                                 </td>
                                <td>
                                    @if($eff->source_2)
                                        {{ optional($eff->sourceContact2)->prenom }} {{ optional($eff->sourceContact2)->nom }}<br>
                                        <span class="dr-badge bd-teal">{{ $eff->nombre_classes_2 }} classe(s)</span>
                                    @else -
                                    @endif
                                 </td>
                                <td>
                                    @if($eff->source_3)
                                        {{ optional($eff->sourceContact3)->prenom }} {{ optional($eff->sourceContact3)->nom }}<br>
                                        <span class="dr-badge bd-teal">{{ $eff->nombre_classes_3 }} classe(s)</span>
                                    @else -
                                    @endif
                                 </td>
                                <td>
                                    @if($eff->effectif_valide)
                                        <span class="dr-badge bd-blue">{{ $eff->effectif_valide }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                 </td>
                                <td>
                                    @if($eff->is_validated)
                                        <span class="dr-badge bd-green">Validé</span>
                                    @else
                                        <span class="dr-badge bd-none">En attente</span>
                                    @endif
                                 </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @php
                            $totalEffectif = $yearEffectifs->sum('effectif_valide');
                        @endphp
                        <tfoot>
                            <tr>
                                <th colspan="6" class="text-end">Total effectif validé :</th>
                                <th colspan="2"><span class="dr-badge bd-amber">{{ $totalEffectif }}</span></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('comptes.index') }}" class="btn-zn btn-zn-ghost">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Retour
        </a>
        @can('update', $compte) <!-- optional, if you have policies -->
        <a href="{{ route('comptes.edit', $compte) }}" class="btn-zn btn-zn-primary">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
            </svg>
            Modifier
        </a>
        @endcan
    </div>
</div>
@endsection
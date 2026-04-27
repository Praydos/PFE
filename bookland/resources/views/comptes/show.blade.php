@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ===== FULL CSS FROM ZONES EXAMPLE ===== */
    /* This is the complete design system – identical to the zones index view */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --bg-base:       #f5f6fa;
        --bg-card:       #ffffff;
        --bg-hover:      #f8f9fd;
        --bg-subtle:     #f0f2f8;
        --border:        #e4e7f0;
        --border-md:     #d0d5e8;
        --blue:          #5b8dee;
        --blue-dark:     #3d6fd6;
        --blue-light:    #eef3fd;
        --blue-mid:      #dce8fb;
        --teal:          #0cb8b6;
        --teal-light:    #e6faf9;
        --violet:        #7c6fcd;
        --violet-light:  #f0eeff;
        --amber:         #e8a020;
        --amber-light:   #fff8ec;
        --rose:          #e8506a;
        --rose-light:    #fef0f2;
        --green:         #28c76f;
        --green-light:   #e8fbf0;
        --text-primary:   #1a1f36;
        --text-secondary: #525f7f;
        --text-muted:     #9ba8c5;
        --text-hint:      #bcc5dc;
        --r-xs: 6px; --r-sm: 8px; --r-md: 12px; --r-lg: 16px; --r-xl: 20px;
        --shadow-xs: 0 1px 3px rgba(31,45,80,.06), 0 1px 2px rgba(31,45,80,.04);
        --shadow-sm: 0 2px 8px rgba(31,45,80,.08), 0 1px 3px rgba(31,45,80,.05);
        --shadow-md: 0 8px 24px rgba(31,45,80,.10), 0 2px 8px rgba(31,45,80,.06);
        --shadow-blue: 0 4px 14px rgba(91,141,238,.35);
        --font: 'DM Sans', sans-serif;
        --font-mono: 'DM Mono', monospace;
        --ease: cubic-bezier(.4,0,.2,1);
        --t: .18s var(--ease);
    }

    body { font-family: var(--font); background: var(--bg-base); color: var(--text-primary); -webkit-font-smoothing: antialiased; }

    .zn-page { padding: 2rem 2.5rem 3rem; animation: pageIn .4s var(--ease) both; max-width: 1400px; margin: 0 auto; }
    @keyframes pageIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Breadcrumb ──────────────────────────────────────── */
    .zn-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.4rem; }
    .zn-bc a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
    .zn-bc a:hover { color: var(--blue); }
    .zn-bc-sep { color: var(--text-hint); }
    .zn-bc-cur { color: var(--text-secondary); }

    /* ── Header ──────────────────────────────────────────── */
    .zn-header { margin-bottom: 2rem; }
    .zn-header h1 { font-size: 1.65rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.15; margin: 0; }
    .zn-header p { font-size: .83rem; color: var(--text-muted); margin-top: .3rem; }

    /* ── Buttons ─────────────────────────────────────────── */
    .btn-zn {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .56rem 1.1rem; border-radius: var(--r-sm);
        font-family: var(--font); font-size: .82rem; font-weight: 600;
        cursor: pointer; border: 1px solid transparent;
        transition: all var(--t); text-decoration: none;
        white-space: nowrap; letter-spacing: -.01em; line-height: 1;
    }
    .btn-zn svg { flex-shrink: 0; }
    .btn-zn-primary { background: var(--blue); color: #fff; border-color: var(--blue); box-shadow: var(--shadow-blue); }
    .btn-zn-primary:hover { background: var(--blue-dark); border-color: var(--blue-dark); color: #fff; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(91,141,238,.4); }
    .btn-zn-ghost { background: var(--bg-card); color: var(--text-secondary); border-color: var(--border); box-shadow: var(--shadow-xs); }
    .btn-zn-ghost:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--border-md); text-decoration: none; }

    /* ── Card ────────────────────────────────────────────── */
    .zn-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-xl); box-shadow: var(--shadow-sm); overflow: hidden; margin-bottom: 1.5rem; }
    .zn-card-header { padding: 1.1rem 1.6rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: .55rem; background: linear-gradient(to bottom, #fafbff, #fff); }
    .zn-card-pip { width: 7px; height: 7px; border-radius: 50%; background: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); }
    .zn-card-title { font-size: .88rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.01em; }
    .zn-card-body { padding: 1.5rem 1.6rem; }

    /* ── Info grid ───────────────────────────────────────── */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1rem;
    }
    .info-item {
        font-size: 0.84rem;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border);
        padding-bottom: 0.5rem;
    }
    .info-label {
        font-weight: 600;
        color: var(--text-primary);
        margin-right: 0.5rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .info-value { color: var(--text-secondary); font-weight: 500; }

    /* ── Badges ─────────────────────────────────────────── */
    .dr-badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .22rem .65rem;
        border-radius: 20px;
        font-size: .7rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .bd-teal { background: var(--teal-light); color: var(--teal); }
    .bd-blue { background: var(--blue-light); color: var(--blue); }
    .bd-green { background: var(--green-light); color: var(--green); }
    .bd-amber { background: var(--amber-light); color: var(--amber); }
    .bd-none { background: var(--bg-subtle); color: var(--text-muted); }

    /* ── Table ───────────────────────────────────────────── */
    .zn-table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
    .zn-table thead tr { border-bottom: 1px solid var(--border); }
    .zn-table th {
        padding: .85rem 1.2rem; font-size: .69rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .08em;
        color: var(--text-hint); text-align: left;
        background: var(--bg-base); white-space: nowrap;
    }
    .zn-table td { padding: .95rem 1.2rem; font-size: .83rem; color: var(--text-secondary); border-bottom: 1px solid var(--border); vertical-align: middle; }
    .zn-table tbody tr:hover { background: #f8f9fd; }
    .zn-table tfoot tr td, .zn-table tfoot tr th { background: var(--bg-subtle); font-weight: 600; }

    /* Year selector */
    .year-selector {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .year-selector label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-secondary);
        margin: 0;
    }
    .zn-select {
        padding: 0.5rem 1rem;
        border: 1px solid var(--border);
        border-radius: var(--r-sm);
        background: var(--bg-card);
        font-family: var(--font);
        font-size: 0.82rem;
        color: var(--text-primary);
        cursor: pointer;
        outline: none;
        transition: all var(--t);
    }
    .zn-select:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px var(--blue-mid);
    }

    /* Footer actions */
    .card-actions {
        display: flex;
        gap: 0.8rem;
        flex-wrap: wrap;
        margin-top: 1rem;
    }

    @media (max-width: 768px) {
        .zn-page { padding: 1.25rem 1rem 2rem; }
        .info-grid { grid-template-columns: 1fr; }
        .zn-table th, .zn-table td { padding: .75rem .9rem; }
        .card-actions { flex-direction: column-reverse; }
        .btn-zn { width: 100%; justify-content: center; }
    }
</style>
@endpush


@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('comptes.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">{{ $compte->etablissement }}</span>
    </div>

    <div class="zn-header">
        <h1>{{ $compte->etablissement }}</h1>
        <p>Détail du compte client</p>
    </div>

    {{-- Actions rapides --}}
<div class="zn-card mt-4">
    <div class="zn-card-header">
        <span class="zn-card-pip"></span>
        <span class="zn-card-title">Actions rapides</span>
    </div>
    <div class="zn-card-body">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('bss.create', ['compte_id' => $compte->id]) }}" class="btn-zn btn-zn-primary">Nouveau BSS</a>
            <a href="{{ route('examens.create', ['compte_id' => $compte->id]) }}" class="btn-zn btn-zn-primary">Nouvel examen</a>
            <a href="{{ route('actions.create', ['compte_id' => $compte->id]) }}" class="btn-zn btn-zn-primary">Nouvelle action</a>
            <a href="{{ route('events.create', ['compte_id' => $compte->id]) }}" class="btn-zn btn-zn-primary">Nouvel événement</a>
            <a href="{{ route('formations.create', ['compte_id' => $compte->id]) }}" class="btn-zn btn-zn-primary">Nouvelle formation</a>
            <a href="{{ route('demandes-specimens.create', ['compte_id' => $compte->id]) }}" class="btn-zn btn-zn-primary">Demande spéciale</a>
        </div>
    </div>
</div>

    {{-- Carte Informations générales --}}
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
                <div class="info-item">
    <span class="info-label">Cycles</span>
    <span class="info-value">
        @php $cyclesArr = is_array($compte->cycles) ? $compte->cycles : []; @endphp
        @if($cyclesArr)
            @foreach($cyclesArr as $c)
                <span class="dr-badge bd-blue" style="margin-right:.2rem;">{{ $c }}</span>
            @endforeach
        @else
            —
        @endif
    </span>
</div>
                <div class="info-item"><span class="info-label">Taille établissement</span> <span class="dr-badge bd-amber">{{ $compte->taille }}</span></div>
            </div>
        </div>
    </div>

    {{-- Carte Délégué assigné --}}
    <div class="zn-card mt-4">
        <div class="zn-card-header">
            <span class="zn-card-pip"></span>
            <span class="zn-card-title">Délégué pédagogique</span>
        </div>
        <div class="zn-card-body">
            @if($compte->delegue)
                <div class="info-grid">
                    <div class="info-item"><span class="info-label">Nom</span> {{ $compte->delegue->prenom }} {{ $compte->delegue->nom }}</div>
                    <div class="info-item"><span class="info-label">Email</span> {{ $compte->delegue->email }}</div>
                    <div class="info-item"><span class="info-label">Téléphone</span> {{ $compte->delegue->telephone ?? '-' }}</div>
                    <div class="info-item"><span class="info-label">Ville</span> {{ $compte->delegue->ville->nom ?? '-' }}</div>
                </div>
            @else
                <p class="text-muted">Aucun délégué assigné.</p>
            @endif
        </div>
    </div>

    {{-- Carte RBO de la zone --}}
    <div class="zn-card mt-4">
        <div class="zn-card-header">
            <span class="zn-card-pip"></span>
            <span class="zn-card-title">Responsable Back Office (RBO) de la zone</span>
        </div>
        <div class="zn-card-body">
            @if($compte->zone && $compte->zone->rbo)
                <div class="info-grid">
                    <div class="info-item"><span class="info-label">Nom</span> {{ $compte->zone->rbo->prenom }} {{ $compte->zone->rbo->nom }}</div>
                    <div class="info-item"><span class="info-label">Email</span> {{ $compte->zone->rbo->email }}</div>
                    <div class="info-item"><span class="info-label">Téléphone</span> {{ $compte->zone->rbo->telephone ?? '-' }}</div>
                    <div class="info-item"><span class="info-label">Ville</span> {{ $compte->zone->rbo->ville->nom ?? '-' }}</div>
                </div>
            @else
                <p class="text-muted">Aucun RBO trouvé pour cette zone.</p>
            @endif
        </div>
    </div>

    {{-- Carte Contacts du compte --}}
    <div class="zn-card mt-4">
        <div class="zn-card-header">
            <span class="zn-card-pip"></span>
            <span class="zn-card-title">Contacts associés</span>
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

    {{-- Carte Effectifs (année sélectionnable) --}}
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
        @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'rbo' && $compte->zone->rbo_id === auth()->id()) || (auth()->user()->role === 'delegue' && $compte->delegue_id === auth()->id()))
            <a href="{{ route('comptes.edit', $compte) }}" class="btn-zn btn-zn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                </svg>
                Modifier
            </a>
        @endif
    </div>
</div>
@endsection

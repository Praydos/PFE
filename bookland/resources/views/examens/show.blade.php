@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ===== FULL CSS FROM ZONES EXAMPLE ===== */
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

/* ── Page ──────────────────────────────────────────── */
.zn-page { padding: 2rem 2.5rem 3rem; animation: pageIn .4s var(--ease) both; max-width: 1400px; margin: 0 auto; }
@keyframes pageIn {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Breadcrumb ────────────────────────────────────── */
.zn-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.4rem; }
.zn-bc a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
.zn-bc a:hover { color: var(--blue); }
.zn-bc-sep { color: var(--text-hint); }
.zn-bc-cur { color: var(--text-secondary); }

/* ── Header ────────────────────────────────────────── */
.zn-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap; }
.zn-header-left h1 { font-size: 1.65rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.15; margin: 0; }
.zn-header-left p { font-size: .83rem; color: var(--text-muted); margin-top: .3rem; }

/* ── Buttons ───────────────────────────────────────── */
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
.btn-zn-danger-ghost { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.2); }
.btn-zn-danger-ghost:hover { background: #fddde2; color: var(--rose); text-decoration: none; }
.btn-zn-warning { background: var(--amber-light); color: var(--amber); border-color: rgba(232,160,32,.2); }
.btn-zn-warning:hover { background: #ffefd4; color: var(--amber); text-decoration: none; }
.btn-zn-danger { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.18); }
.btn-zn-danger:hover { background: #fddde2; color: var(--rose); text-decoration: none; }
.btn-zn-info { background: var(--violet-light); color: var(--violet); border-color: rgba(124,111,205,.2); }
.btn-zn-info:hover { background: #e8e5ff; color: var(--violet); text-decoration: none; }
.btn-zn-sm { padding: .38rem .72rem; font-size: .75rem; }

/* ── Card ──────────────────────────────────────────── */
.zn-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-xl); box-shadow: var(--shadow-sm); overflow: hidden; margin-bottom: 1.5rem; }
.zn-card-header { padding: 1.1rem 1.6rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: .55rem; background: linear-gradient(to bottom, #fafbff, #fff); }
.title-pip { width: 7px; height: 7px; border-radius: 50%; background: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); }
.zn-card-title { font-size: .88rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.01em; }
.zn-card-body { padding: 1.5rem 1.6rem; }

/* ── Info grid ──────────────────────────────────────── */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
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
hr { border: none; border-top: 1px solid var(--border); margin: 1rem 0; }

/* ── Table ─────────────────────────────────────────── */
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

/* Badges */
.dr-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .22rem .65rem; border-radius: 20px;
    font-size: .7rem; font-weight: 600; white-space: nowrap;
}
.bd-teal { background: var(--teal-light); color: var(--teal); }
.bd-blue { background: var(--blue-light); color: var(--blue); }
.bd-green { background: var(--green-light); color: var(--green); }
.bd-amber { background: var(--amber-light); color: var(--amber); }
.bd-none { background: var(--bg-subtle); color: var(--text-muted); }

/* Status update box */
.status-update-box {
    margin-top: 1.5rem;
    padding: 1rem;
    background: var(--bg-subtle);
    border-radius: var(--r-lg);
}
.frm-select-wrap {
    position: relative;
    display: inline-block;
    width: auto;
    min-width: 200px;
}
.frm-select-wrap .frm-select {
    width: 100%;
    padding: .62rem .9rem;
    border: 1px solid var(--border);
    border-radius: var(--r-sm);
    background: var(--bg-card);
    font-family: var(--font);
    font-size: .84rem;
    color: var(--text-primary);
    box-shadow: var(--shadow-xs);
    transition: all var(--t);
    outline: none;
    cursor: pointer;
    padding-right: 2.2rem;
}
.frm-select-wrap::after {
    content: '';
    position: absolute;
    right: .9rem;
    top: 50%;
    transform: translateY(-50%);
    width: 0;
    height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-top: 5px solid var(--text-muted);
    pointer-events: none;
}
.frm-label {
    font-size: .8rem;
    font-weight: 600;
    color: var(--text-secondary);
    letter-spacing: -.01em;
}

/* Footer */
.card-footer {
    padding: 1.1rem 1.6rem;
    border-top: 1px solid var(--border);
    background: var(--bg-base);
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: .6rem;
}

/* Responsive */
@media (max-width: 768px) {
    .zn-page { padding: 1.25rem 1rem 2rem; }
    .info-grid { grid-template-columns: 1fr; }
    .zn-table th, .zn-table td { padding: .75rem .9rem; }
    .card-footer { flex-direction: column-reverse; }
    .btn-zn { width: 100%; justify-content: center; }
    .status-form { flex-direction: column; align-items: stretch; }
    .status-form .frm-select-wrap { width: 100%; }
}
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('examens.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            Examens
        </a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">{{ $examen->titre }}</span>
    </div>

    {{-- Header --}}
    <div class="zn-header">
        <div class="zn-header-left">
            <h1>{{ $examen->titre }}</h1>
            <p>Demande d'examen #{{ $examen->id }}</p>
        </div>
    </div>

    {{-- Main card --}}
    <div class="zn-card">
        <div class="zn-card-header">
            <span class="title-pip"></span>
            <span class="zn-card-title">Informations générales</span>
        </div>
        <div class="zn-card-body">
            <div class="info-grid">
                <div class="info-item"><span class="info-label">Compte</span> {{ $examen->compte->etablissement }}</div>
                <div class="info-item"><span class="info-label">Contact</span> {{ $examen->contact->prenom }} {{ $examen->contact->nom }}</div>
                <div class="info-item"><span class="info-label">Délégué</span> {{ $examen->delegate->prenom }} {{ $examen->delegate->nom }}</div>
                <div class="info-item"><span class="info-label">Année scolaire</span> {{ $examen->anneeScolaire->libelle }}</div>
                <div class="info-item"><span class="info-label">Date demande</span> {{ $examen->date_demande->format('d/m/Y') }}</div>
                <div class="info-item"><span class="info-label">Date examen</span> {{ $examen->date_examen ? $examen->date_examen->format('d/m/Y') : '-' }}</div>
                <div class="info-item"><span class="info-label">Langue</span> {{ $examen->langue }}</div>
                <div class="info-item"><span class="info-label">Organisme</span> {{ $examen->organisme }}</div>
                <div class="info-item"><span class="info-label">Abréviation</span> {{ $examen->abreviation ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Niveau CECR</span> {{ $examen->niveau_cecr ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Niveaux scolaires</span> {{ implode(', ', $examen->niveaux_scolaires ?? []) }}</div>
                <div class="info-item">
                    <span class="info-label">Statut</span>
                    @php
                        $statuts = [
                            'en_attente_feedback' => 'En attente du feedback',
                            'avis_favorable' => 'Avis favorable',
                            'avis_defavorable' => 'Avis défavorable',
                            'signature_convention' => 'Signature de la convention',
                            'commande' => 'Commandé',
                            'planifie' => 'Planifié',
                            'annule' => 'Annulé',
                            'decline' => 'Décliné',
                            'reporte' => 'Reporté',
                            'en_attente_resultats' => 'En attente des résultats',
                            'communication_resultats' => 'Communication des résultats électronique',
                            'livraison_attestations' => 'Livraison des attestations',
                        ];
                        $badgeClass = match($examen->statut) {
                            'en_attente_feedback' => 'bd-blue',
                            'avis_favorable' => 'bd-teal',
                            'avis_defavorable' => 'bd-none',
                            'signature_convention' => 'bd-amber',
                            'commande' => 'bd-green',
                            'planifie' => 'bd-blue',
                            'annule' => 'bd-none',
                            'decline' => 'bd-none',
                            'reporte' => 'bd-amber',
                            'en_attente_resultats' => 'bd-blue',
                            'communication_resultats' => 'bd-teal',
                            'livraison_attestations' => 'bd-green',
                            default => 'bd-none'
                        };
                    @endphp
                    <span class="dr-badge {{ $badgeClass }}">{{ $statuts[$examen->statut] }}</span>
                </div>
                @if($examen->description)
                <div class="info-item"><span class="info-label">Description</span> {{ $examen->description }}</div>
                @endif
                @if($examen->observations)
                <div class="info-item"><span class="info-label">Observations</span> {{ $examen->observations }}</div>
                @endif
            </div>

            @if($examen->epreuves->count())
            <hr>
            <h3 style="font-size: 0.9rem; font-weight: 600; margin-bottom: 1rem;">Épreuves</h3>
            <div class="table-responsive">
                <table class="zn-table">
                    <thead>
                        <tr>
                            <th>Épreuve</th>
                            <th>Durée (min)</th>
                            <th>Date réalisation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($examen->epreuves as $ep)
                        <tr>
                            <td>{{ $ep->epreuve }}</td>
                            <td>{{ $ep->duree ?? '-' }}</td>
                            <td>{{ $ep->date_realisation ? $ep->date_realisation->format('d/m/Y') : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            @if(in_array(auth()->user()->role, ['admin', 'rbo']) || (auth()->user()->role === 'delegue' && $examen->delegue_id === auth()->id()))
            <div class="status-update-box" style="margin-top: 1.5rem; padding: 1rem; background: var(--bg-subtle); border-radius: var(--r-lg);">
                <form method="POST" action="{{ route('examens.change-status', $examen) }}" class="status-form" style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                    @csrf
                    <label class="frm-label" style="margin:0;">Changer le statut :</label>
                    <div class="frm-select-wrap" style="width: auto; min-width: 200px;">
                        <select name="statut" class="frm-select">
                            @foreach($statuts as $key => $label)
                                <option value="{{ $key }}" {{ $examen->statut == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-zn btn-zn-primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Mettre à jour
                    </button>
                </form>
            </div>
            @endif
        </div>

        <div class="card-footer" style="padding: 1.1rem 1.6rem; border-top: 1px solid var(--border); background: var(--bg-base); display: flex; justify-content: flex-end; gap: 0.6rem;">
            <a href="{{ route('examens.index') }}" class="btn-zn btn-zn-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour
            </a>
            @if(in_array(auth()->user()->role, ['admin','rbo']) || (auth()->user()->role === 'delegue' && $examen->delegate_id === auth()->id()))
                <a href="{{ route('examens.edit', $examen) }}" class="btn-zn btn-zn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                    </svg>
                    Modifier
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
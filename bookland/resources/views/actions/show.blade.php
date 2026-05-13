@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ===== FULL CSS FROM THE EXAMENS SHOW VIEW (provided) ===== */
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
.bd-planifie { background: var(--blue-light); color: var(--blue); }
.bd-valide { background: var(--green-light); color: var(--green); }
.bd-annule { background: var(--bg-subtle); color: var(--text-muted); }
.bd-realise { background: var(--amber-light); color: var(--amber); }

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


/* ── Modal Overlay ───────────────────────── */
.dlg-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(20, 25, 40, 0.45);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transition: all var(--t);
}

.dlg-modal-overlay.visible {
    opacity: 1;
    visibility: visible;
}

/* ── Modal Box ───────────────────────────── */
.dlg-modal {
    width: 100%;
    max-width: 420px;
    background: var(--bg-card);
    border-radius: var(--r-xl);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    transform: translateY(20px) scale(.96);
    transition: all var(--t);
}

.dlg-modal.realiser-modal {
    max-width: 480px;
}

.dlg-modal-overlay.visible .dlg-modal {
    transform: translateY(0) scale(1);
}

/* ── Header ──────────────────────────────── */
.dlg-modal-hd {
    display: flex;
    align-items: center;
    gap: .8rem;
    padding: 1rem 1.2rem;
    border-bottom: 1px solid var(--border);
    background: linear-gradient(to bottom, #fafbff, #fff);
}

.dlg-modal-icon {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: var(--blue-light);
    color: var(--blue);
    display: flex;
    align-items: center;
    justify-content: center;
}

.dlg-modal-titles h2 {
    font-size: .95rem;
    font-weight: 700;
    margin: 0;
}

.dlg-modal-titles p {
    font-size: .75rem;
    color: var(--text-muted);
    margin: 0;
}

.dlg-modal-close {
    margin-left: auto;
    border: none;
    background: transparent;
    cursor: pointer;
    color: var(--text-muted);
    padding: .3rem;
    border-radius: var(--r-sm);
    transition: all var(--t);
}

.dlg-modal-close:hover {
    background: var(--bg-hover);
    color: var(--text-primary);
}

/* ── Body ────────────────────────────────── */
.dlg-modal-body {
    padding: 1.2rem;
}

/* ── Form ────────────────────────────────── */
.frm-group {
    margin-bottom: .9rem;
}

.frm-input {
    width: 100%;
    padding: .6rem .75rem;
    border-radius: var(--r-sm);
    border: 1px solid var(--border);
    font-size: .82rem;
    font-family: var(--font);
    background: var(--bg-card);
    transition: all var(--t);
}

.frm-input:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px var(--blue-mid);
    outline: none;
}

.frm-textarea {
    width: 100%;
    min-height: 120px;
    padding: .6rem .75rem;
    border-radius: var(--r-sm);
    border: 1px solid var(--border);
    font-size: .82rem;
    font-family: var(--font);
    background: var(--bg-card);
    transition: all var(--t);
    resize: vertical;
}
.frm-textarea:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px var(--blue-mid);
    outline: none;
}

.req {
    color: var(--rose);
}
</style>
@endpush


@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('actions.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('actions.index') }}">Actions</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">{{ $action->objet }}</span>
    </div>

    {{-- Header --}}
    <div class="zn-header">
        <div class="zn-header-left">
            <h1>{{ $action->objet }}</h1>
            <p>Action du {{ $action->date_planification->format('d/m/Y') }} – {{ $action->compte->etablissement }}</p>
        </div>
    </div>

    <div class="zn-card">
        <div class="zn-card-header">
            <span class="title-pip"></span>
            <span class="zn-card-title">Détails de l'action</span>
        </div>
        <div class="zn-card-body">
            <div class="info-grid">
                <div class="info-item"><span class="info-label">Compte</span> {{ $action->compte->etablissement }}</div>
                <div class="info-item"><span class="info-label">Date</span> {{ $action->date_planification->format('d/m/Y') }}</div>
                <div class="info-item"><span class="info-label">Lieu</span> {{ $action->lieu ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Statut</span> <span class="dr-badge bd-{{ $action->statut }}">{{ ucfirst($action->statut) }}</span></div>
                <div class="info-item"><span class="info-label">Type</span> {{ ucfirst($action->type) }}</div>
                @if($action->statut === 'realise' && $action->date_realisation)
                    <div class="info-item"><span class="info-label">Réalisée le</span> {{ $action->date_realisation->format('d/m/Y H:i') }}</div>
                @endif
                @if($action->statut === 'valide' && $action->date_validation)
                    <div class="info-item"><span class="info-label">Validée le</span> {{ $action->date_validation->format('d/m/Y H:i') }}</div>
                @endif
            </div>

            @if($action->rapport_titre)
            <hr>
            <h3 style="font-size: 0.9rem; font-weight: 600; margin-bottom: 1rem;">Rapport de réalisation</h3>
            <div class="info-grid" style="margin-bottom: 0;">
                <div class="info-item"><span class="info-label">Titre</span> {{ $action->rapport_titre }}</div>
                @if($action->rapport_date)
                    <div class="info-item"><span class="info-label">Date du rapport</span> {{ $action->rapport_date->format('d/m/Y') }}</div>
                @endif
                <div class="info-item" style="grid-column: 1 / -1;"><span class="info-label">Description</span>
                    <div style="margin-top: 0.35rem; white-space: pre-wrap; color: var(--text-secondary);">{{ $action->rapport_description }}</div>
                </div>
            </div>
            @endif

            @if($errors->hasAny(['rapport_titre', 'rapport_description', 'rapport_date']))
            <div style="margin-top: 1rem; padding: 0.85rem 1rem; background: var(--rose-light); border: 1px solid rgba(232,80,106,.2); border-radius: var(--r-md); font-size: 0.82rem; color: var(--rose);">
                <strong>Veuillez corriger le formulaire du rapport.</strong>
                <ul style="margin: 0.5rem 0 0 1rem;">
                    @foreach($errors->get('rapport_titre') as $e)<li>{{ $e }}</li>@endforeach
                    @foreach($errors->get('rapport_description') as $e)<li>{{ $e }}</li>@endforeach
                    @foreach($errors->get('rapport_date') as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif

            <hr>

            <h3 style="font-size: 0.9rem; font-weight: 600; margin-bottom: 1rem;">Lignes d'action</h3>
            @foreach($action->lignes as $line)
                <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: var(--r-lg); padding: 1rem; margin-bottom: 1rem;">
                    <div class="info-grid" style="margin-bottom: 0.5rem;">
                        <div class="info-item"><span class="info-label">Catégorie</span> {{ $line->categorie }}</div>
                        <div class="info-item"><span class="info-label">Type</span> {{ $line->action_type }}</div>
                        <div class="info-item"><span class="info-label">Moyen</span> {{ $line->moyen ?? '-' }}</div>
                        <div class="info-item"><span class="info-label">Description</span> {{ $line->description ?? '-' }}</div>
                    </div>
                    <div class="info-grid" style="margin-bottom: 0.5rem;">
                        <div class="info-item"><span class="info-label">Contacts</span>
                            {{ $line->contacts->map(fn($c) => $c->prenom . ' ' . $c->nom)->join(', ') ?: '-' }}
                        </div>
                        <div class="info-item"><span class="info-label">Produits</span>
                            @if($line->products->count())
                                @foreach($line->products as $product)
                                    <a href="{{ route('products.show', $product) }}">{{ $product->titre }}</a>@if(!$loop->last), @endif
                                @endforeach
                            @else -
                            @endif
                        </div>
                        <div class="info-item"><span class="info-label">Examens</span>
                            @if($line->examens->count())
                                @foreach($line->examens as $examen)
                                    <a href="{{ route('examens.show', $examen) }}">{{ $examen->titre }}</a>@if(!$loop->last), @endif
                                @endforeach
                            @else -
                            @endif
                        </div>
                    </div>
                    <div class="info-grid">
                        @if($line->bss)
                            <div class="info-item"><span class="info-label">BSS associé</span>
                                <a href="{{ route('bss.show', $line->bss) }}">{{ $line->bss->numero }}</a>
                            </div>
                        @endif
                        @if($line->retour)
                            <div class="info-item"><span class="info-label">Bon de retour associé</span>
                                <a href="{{ route('retours.index', $line->retour) }}">{{ $line->retour->numero }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card-footer">
            <a href="{{ route('actions.index') }}" class="btn-zn btn-zn-ghost">Retour</a>
            @php
                $u = auth()->user();
                $isOwnerDelegue = $u->role === 'delegue' && (int) $action->delegue_id === (int) $u->id;
            @endphp

            @if($action->statut === 'planifie' && $isOwnerDelegue)
                <button type="button" class="btn-zn btn-zn-primary" id="openRealiserModalBtn">Réaliser</button>
                <form method="POST" action="{{ route('actions.annuler', $action) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-zn btn-zn-danger" onclick="return confirm('Annuler ?')">Annuler</button>
                </form>
                <button type="button" class="btn-zn btn-zn-warning" id="openReportModalBtn">Reporter</button>
            @elseif($action->statut === 'planifie' && $u->role === 'admin')
                <form method="POST" action="{{ route('actions.annuler', $action) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-zn btn-zn-danger" onclick="return confirm('Annuler ?')">Annuler</button>
                </form>
                <button type="button" class="btn-zn btn-zn-warning" id="openReportModalBtn">Reporter</button>
            @elseif($action->statut === 'realise' && !empty($canValiderLegacy))
                <form method="POST" action="{{ route('actions.valider', $action) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-zn btn-zn-primary" onclick="return confirm('Valider ?')">Valider</button>
                </form>
            @elseif($action->statut === 'valide' && !empty($canDevalider))
                <form method="POST" action="{{ route('actions.devalider', $action) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-zn btn-zn-warning" onclick="return confirm('Dévalider cette action ? Le délégué pourra la modifier et soumettre un nouveau rapport.')">Dévalider</button>
                </form>
            @endif
        </div>
    </div>

    {{-- Reporting modal (styled exactly like the delegates modal) --}}
    <div class="dlg-modal-overlay" id="reportModalOverlay">
        <div class="dlg-modal">
            <div class="dlg-modal-hd">
                <div class="dlg-modal-icon">
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <div class="dlg-modal-titles">
                    <h2>Reporter l'action</h2>
                    <p>Choisissez une nouvelle date et heure</p>
                </div>
                <button class="dlg-modal-close" id="closeReportModal" aria-label="Fermer">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="dlg-modal-body">
                <form method="POST" action="{{ route('actions.reporter', $action) }}">
                    @csrf
                    <div class="frm-group">
                        <label class="frm-label">Nouvelle date <span class="req">*</span></label>
                        <input type="date" name="nouvelle_date" class="frm-input" required>
                    </div>
                    {{-- <div class="frm-group">
                        <label class="frm-label">Nouvelle heure</label>
                        <input type="time" name="nouvelle_heure" class="frm-input">
                    </div> --}}
                    <div style="display: flex; justify-content: flex-end; gap: 0.6rem; margin-top: 1rem;">
                        <button type="button" class="btn-zn btn-zn-ghost" id="cancelReportBtn">Annuler</button>
                        <button type="submit" class="btn-zn btn-zn-primary">Reporter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($action->statut === 'planifie' && auth()->user()->role === 'delegue' && (int) $action->delegue_id === (int) auth()->id())
    <div class="dlg-modal-overlay" id="realiserModalOverlay">
        <div class="dlg-modal realiser-modal">
            <div class="dlg-modal-hd">
                <div class="dlg-modal-icon">
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                </div>
                <div class="dlg-modal-titles">
                    <h2>Rapport de réalisation</h2>
                    <p>Renseignez le rapport pour clôturer et valider l'action</p>
                </div>
                <button type="button" class="dlg-modal-close" id="closeRealiserModal" aria-label="Fermer">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="dlg-modal-body">
                <form method="POST" action="{{ route('actions.realiser', $action) }}" id="realiserForm">
                    @csrf
                    <div class="frm-group">
                        <label class="frm-label">Titre du rapport <span class="req">*</span></label>
                        <input type="text" name="rapport_titre" class="frm-input" value="{{ old('rapport_titre') }}" required maxlength="255">
                    </div>
                    <div class="frm-group">
                        <label class="frm-label">Description <span class="req">*</span></label>
                        <textarea name="rapport_description" class="frm-textarea" required>{{ old('rapport_description') }}</textarea>
                    </div>
                    <div class="frm-group">
                        <label class="frm-label">Date <span class="req">*</span></label>
                        <input type="date" name="rapport_date" class="frm-input" value="{{ old('rapport_date', now()->toDateString()) }}" required>
                    </div>
                    <div style="display: flex; justify-content: flex-end; gap: 0.6rem; margin-top: 1rem;">
                        <button type="button" class="btn-zn btn-zn-ghost" id="cancelRealiserBtn">Annuler</button>
                        <button type="submit" class="btn-zn btn-zn-primary">Valider le rapport et clôturer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    (function() {
        const reportOverlay = document.getElementById('reportModalOverlay');
        const openReportBtn = document.getElementById('openReportModalBtn');
        const closeReportBtn = document.getElementById('closeReportModal');
        const cancelReportBtn = document.getElementById('cancelReportBtn');

        function openReportModal() {
            if (reportOverlay) {
                reportOverlay.classList.add('visible');
                document.body.style.overflow = 'hidden';
            }
        }
        function closeReportModal() {
            if (reportOverlay) {
                reportOverlay.classList.remove('visible');
                document.body.style.overflow = '';
            }
        }
        if (openReportBtn) openReportBtn.addEventListener('click', openReportModal);
        if (closeReportBtn) closeReportBtn.addEventListener('click', closeReportModal);
        if (cancelReportBtn) cancelReportBtn.addEventListener('click', closeReportModal);
        if (reportOverlay) {
            reportOverlay.addEventListener('click', (e) => {
                if (e.target === reportOverlay) closeReportModal();
            });
        }

        const realiserOverlay = document.getElementById('realiserModalOverlay');
        const openRealiserBtn = document.getElementById('openRealiserModalBtn');
        const closeRealiserBtn = document.getElementById('closeRealiserModal');
        const cancelRealiserBtn = document.getElementById('cancelRealiserBtn');

        function openRealiserModal() {
            if (realiserOverlay) {
                realiserOverlay.classList.add('visible');
                document.body.style.overflow = 'hidden';
            }
        }
        function closeRealiserModal() {
            if (realiserOverlay) {
                realiserOverlay.classList.remove('visible');
                document.body.style.overflow = '';
            }
        }
        if (openRealiserBtn) openRealiserBtn.addEventListener('click', openRealiserModal);
        if (closeRealiserBtn) closeRealiserBtn.addEventListener('click', closeRealiserModal);
        if (cancelRealiserBtn) cancelRealiserBtn.addEventListener('click', closeRealiserModal);
        if (realiserOverlay) {
            realiserOverlay.addEventListener('click', (e) => {
                if (e.target === realiserOverlay) closeRealiserModal();
            });
        }

        @if($errors->hasAny(['rapport_titre', 'rapport_description', 'rapport_date']))
        openRealiserModal();
        @else
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('realiser') === '1') openRealiserModal();
        @endif

        document.addEventListener('keydown', (e) => {
            if (e.key !== 'Escape') return;
            if (reportOverlay && reportOverlay.classList.contains('visible')) closeReportModal();
            if (realiserOverlay && realiserOverlay.classList.contains('visible')) closeRealiserModal();
        });
    })();
</script>
@endpush
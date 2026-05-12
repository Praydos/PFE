@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
/* ── Reset ─────────────────────────────────────────── */
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

    --r-xs: 6px;
    --r-sm: 8px;
    --r-md: 12px;
    --r-lg: 16px;
    --r-xl: 20px;

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
.cp-page {
    padding: 2rem 1.5rem 0rem;
    animation: pageIn .4s var(--ease) both;
}
@keyframes pageIn {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Breadcrumb ────────────────────────────────────── */
.cp-breadcrumb {
    display: flex; align-items: center; gap: .4rem;
    font-size: .76rem; color: var(--text-muted); font-weight: 500;
    margin-bottom: 1.4rem;
}
.cp-breadcrumb a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
.cp-breadcrumb a:hover { color: var(--blue); }
.cp-breadcrumb-sep { color: var(--text-hint); }
.cp-breadcrumb-current { color: var(--text-secondary); }

/* ── Header ────────────────────────────────────────── */
.cp-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap;
}
.cp-header-left h1 {
    font-size: 1.65rem; font-weight: 700;
    letter-spacing: -.03em; color: var(--text-primary); line-height: 1.15;
}
.cp-header-left p { font-size: .83rem; color: var(--text-muted); margin-top: .3rem; }
.cp-header-actions { display: flex; gap: .5rem; align-items: center; flex-wrap: wrap; }

/* ── Buttons ───────────────────────────────────────── */
.btn-cp {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .56rem 1.1rem; border-radius: var(--r-sm);
    font-family: var(--font); font-size: .82rem; font-weight: 600;
    cursor: pointer; border: 1px solid transparent;
    transition: all var(--t); text-decoration: none;
    white-space: nowrap; letter-spacing: -.01em; line-height: 1;
}
.btn-cp svg { flex-shrink: 0; }

.btn-cp-primary {
    background: var(--blue); color: #fff;
    border-color: var(--blue); box-shadow: var(--shadow-blue);
}
.btn-cp-primary:hover {
    background: var(--blue-dark); border-color: var(--blue-dark);
    color: #fff; text-decoration: none;
    transform: translateY(-1px); box-shadow: 0 6px 20px rgba(91,141,238,.4);
}

.btn-cp-ghost {
    background: var(--bg-card); color: var(--text-secondary);
    border-color: var(--border); box-shadow: var(--shadow-xs);
}
.btn-cp-ghost:hover {
    background: var(--bg-hover); color: var(--text-primary);
    border-color: var(--border-md); text-decoration: none;
}

.btn-cp-danger-ghost {
    background: var(--rose-light); color: var(--rose);
    border-color: rgba(232,80,106,.2);
}
.btn-cp-danger-ghost:hover { background: #fddde2; color: var(--rose); text-decoration: none; }

.btn-cp-sm { padding: .38rem .72rem; font-size: .75rem; }

.btn-cp-warning { background: var(--amber-light); color: var(--amber); border-color: rgba(232,160,32,.2); }
.btn-cp-warning:hover { background: #ffefd4; color: var(--amber); text-decoration: none; }

.btn-cp-danger { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.18); }
.btn-cp-danger:hover { background: #fddde2; color: var(--rose); text-decoration: none; }


.btn-zn-info { background: var(--violet-light); color: var(--violet); border-color: rgba(124,111,205,.2); }
    .btn-zn-info:hover { background: #e8e5ff; color: var(--violet); text-decoration: none; }
/* ── Stat Cards ────────────────────────────────────── */
.cp-stats {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem; margin-bottom: 2rem;
}
.cp-stat {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--r-lg); padding: 1.35rem 1.5rem;
    display: flex; align-items: center; gap: 1.1rem;
    box-shadow: var(--shadow-xs); transition: all var(--t);
    animation: pageIn .5s var(--ease) both;
    position: relative; overflow: hidden;
}
.cp-stat::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px; opacity: 0; transition: opacity var(--t);
    border-radius: var(--r-lg) var(--r-lg) 0 0;
}
.cp-stat:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); border-color: var(--border-md); }
.cp-stat:hover::before { opacity: 1; }
.cp-stat:nth-child(1) { animation-delay: .06s; } .cp-stat:nth-child(1)::before { background: var(--blue); }
.cp-stat:nth-child(2) { animation-delay: .11s; } .cp-stat:nth-child(2)::before { background: var(--green); }
.cp-stat:nth-child(3) { animation-delay: .16s; } .cp-stat:nth-child(3)::before { background: var(--rose); }
.cp-stat:nth-child(4) { animation-delay: .21s; } .cp-stat:nth-child(4)::before { background: var(--violet); }

.stat-ico {
    width: 44px; height: 44px; border-radius: var(--r-md);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.si-blue   { background: var(--blue-light);   color: var(--blue); }
.si-green  { background: var(--green-light);  color: var(--green); }
.si-rose   { background: var(--rose-light);   color: var(--rose); }
.si-violet { background: var(--violet-light); color: var(--violet); }

.stat-label { font-size: .72rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .05em; }
.stat-value { font-size: 1.7rem; font-weight: 700; color: var(--text-primary); line-height: 1.1; letter-spacing: -.04em; margin-top: .06rem; }

/* ── Search bar ────────────────────────────────────── */
.cp-search-bar {
    display: flex; align-items: center; gap: .6rem;
    margin-bottom: 1.25rem; flex-wrap: wrap;
}
.cp-search-wrap {
    position: relative; flex: 1; min-width: 220px; max-width: 380px;
}
.cp-search-wrap svg {
    position: absolute; left: .85rem; top: 50%; transform: translateY(-50%);
    color: var(--text-muted); pointer-events: none; flex-shrink: 0;
}
.cp-search-input {
    width: 100%; padding: .56rem .9rem .56rem 2.35rem;
    border: 1px solid var(--border); border-radius: var(--r-sm);
    background: var(--bg-card); font-family: var(--font);
    font-size: .83rem; color: var(--text-primary);
    box-shadow: var(--shadow-xs); transition: all var(--t);
    outline: none;
}
.cp-search-input::placeholder { color: var(--text-muted); }
.cp-search-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); }

/* ── Card ──────────────────────────────────────────── */
.cp-card {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--r-xl); box-shadow: var(--shadow-sm); overflow: hidden;
}
.cp-card-header {
    padding: 1.1rem 1.6rem; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between; gap: 1rem;
}
.cp-card-title {
    font-size: .88rem; font-weight: 700; color: var(--text-primary);
    display: flex; align-items: center; gap: .55rem; letter-spacing: -.01em;
}
.title-pip {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid);
}
.cp-result-count {
    font-size: .76rem; color: var(--text-muted); font-weight: 500;
}

/* ── Table ─────────────────────────────────────────── */
.cp-table { width: 100%; border-collapse: collapse; }
.cp-table thead tr { border-bottom: 1px solid var(--border); }
.cp-table th {
    padding: .85rem 1.2rem;
    font-size: .69rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: var(--text-hint); text-align: left;
    background: var(--bg-base); white-space: nowrap;
}
.cp-table td {
    padding: .9rem 1.2rem; font-size: .83rem;
    color: var(--text-secondary); border-bottom: 1px solid var(--border);
    vertical-align: middle;
}
.cp-table tbody tr { transition: background var(--t); }
.cp-table tbody tr:hover { background: #f8f9fd; }
.cp-table tbody tr:last-child td { border-bottom: none; }

/* ID cell */
.id-cell {
    font-family: var(--font-mono); font-size: .76rem;
    color: var(--text-muted); font-weight: 500;
    background: var(--bg-subtle); border-radius: var(--r-xs);
    padding: .18rem .5rem; display: inline-block;
}

/* Etablissement cell */
.etab-cell { display: flex; align-items: center; gap: .75rem; }
.etab-avatar {
    width: 34px; height: 34px; border-radius: var(--r-sm);
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .76rem; color: #fff; flex-shrink: 0;
    letter-spacing: .02em;
}
.ea-a { background: linear-gradient(135deg, #5b8dee, #6c63ff); }
.ea-b { background: linear-gradient(135deg, #0cb8b6, #00d4aa); }
.ea-c { background: linear-gradient(135deg, #7c6fcd, #b06ab3); }
.ea-d { background: linear-gradient(135deg, #e8a020, #f97316); }
.ea-e { background: linear-gradient(135deg, #e8506a, #ff6b9d); }

.etab-name  { font-weight: 600; color: var(--text-primary); font-size: .84rem; letter-spacing: -.01em; }
.etab-type  { font-size: .73rem; color: var(--text-muted); margin-top: .08rem; }

/* Location cells */
.loc-cell { display: flex; align-items: center; gap: .35rem; font-size: .81rem; }
.loc-dot {
    width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0;
    background: var(--teal);
}

/* Delegue cell */
.dlg-cell { display: flex; align-items: center; gap: .5rem; }
.dlg-mini-av {
    width: 24px; height: 24px; border-radius: 50%;
    background: linear-gradient(135deg, #5b8dee, #6c63ff);
    display: flex; align-items: center; justify-content: center;
    font-size: .58rem; font-weight: 700; color: #fff; flex-shrink: 0;
}

/* Contact cell */
.contact-cell { font-family: var(--font-mono); font-size: .76rem; color: var(--text-muted); }

/* Badges */
.cp-badge {
    display: inline-flex; align-items: center; gap: .28rem;
    padding: .22rem .65rem; border-radius: 20px;
    font-size: .71rem; font-weight: 600;
    border: 1px solid transparent; letter-spacing: .01em;
}
.cp-badge-dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }

.badge-actif {
    background: var(--green-light); color: #1da35e;
    border-color: rgba(40,199,111,.22);
}
.badge-actif .cp-badge-dot { background: var(--green); }

.badge-ferme {
    background: var(--bg-subtle); color: var(--text-muted);
    border-color: var(--border);
}
.badge-ferme .cp-badge-dot { background: var(--text-hint); }

/* Type badge */
.type-badge {
    display: inline-block; padding: .18rem .6rem;
    border-radius: var(--r-xs); font-size: .71rem; font-weight: 600;
    background: var(--blue-light); color: var(--blue);
    border: 1px solid var(--blue-mid); white-space: nowrap;
}

/* Actions */
.actions-cell { display: flex; align-items: center; gap: .35rem; }

/* Empty state */
.cp-empty {
    padding: 4rem 2rem; text-align: center;
}
.cp-empty-icon {
    width: 52px; height: 52px; border-radius: var(--r-md);
    background: var(--bg-subtle); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1rem; color: var(--text-hint);
}
.cp-empty h3 { font-size: .95rem; font-weight: 700; color: var(--text-secondary); }
.cp-empty p  { font-size: .82rem; color: var(--text-muted); margin-top: .3rem; }

/* Active search indicator */
.cp-search-active {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .3rem .75rem; border-radius: 20px;
    background: var(--blue-light); color: var(--blue);
    border: 1px solid var(--blue-mid);
    font-size: .76rem; font-weight: 600;
}

/* ── Modal ─────────────────────────────────────────── */
.dr-modal-overlay {
    position: fixed; inset: 0;
    background: rgba(26,31,54,.42);
    backdrop-filter: blur(6px);
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}
.dr-modal-overlay.visible { display: flex; animation: oIn .2s ease both; }
@keyframes oIn { from { opacity: 0; } to { opacity: 1; } }

.dr-modal {
    background: var(--bg-card);
    border: 1px solid var(--border-md);
    border-radius: var(--r-xl);
    width: 100%; max-width: 480px;
    box-shadow: var(--shadow-md);
    overflow: hidden;
    animation: mIn .28s cubic-bezier(.34,1.4,.64,1) both;
}
@keyframes mIn {
    from { opacity: 0; transform: scale(.94) translateY(8px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
.dr-modal-hd {
    padding: 1.35rem 1.6rem 1.2rem;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem;
    background: linear-gradient(to bottom, #fafbff, #fff);
}
.modal-icon {
    width: 40px; height: 40px; border-radius: var(--r-md);
    background: var(--blue-light); border: 1px solid var(--blue-mid);
    display: flex; align-items: center; justify-content: center;
    color: var(--blue); flex-shrink: 0;
}
.modal-title-grp { flex: 1; }
.modal-title-grp h2 { font-size: 1rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.02em; }
.modal-title-grp p  { font-size: .78rem; color: var(--text-muted); margin-top: .2rem; }
.modal-close {
    width: 30px; height: 30px; border-radius: var(--r-xs);
    background: var(--bg-subtle); border: 1px solid var(--border);
    color: var(--text-muted); display: flex; align-items: center;
    justify-content: center; cursor: pointer; transition: all var(--t); flex-shrink: 0;
}
.modal-close:hover { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.2); }
.dr-modal-body {
    padding: 1.25rem 1.6rem; max-height: 60vh; overflow-y: auto;
}
.dr-modal-body::-webkit-scrollbar { width: 4px; }
.dr-modal-body::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
.loc-detail-row {
    display: flex; align-items: baseline; gap: .75rem;
    padding: .85rem 0; border-bottom: 1px solid var(--border);
}
.loc-detail-icon { width: 28px; flex-shrink: 0; color: var(--text-muted); }
.loc-detail-label { width: 80px; font-size: .78rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .05em; }
.loc-detail-value { flex: 1; font-size: .85rem; color: var(--text-primary); font-weight: 500; }
.dr-modal-ft {
    padding: 1rem 1.6rem; border-top: 1px solid var(--border);
    display: flex; justify-content: flex-end; gap: .6rem;
    background: var(--bg-base);
}

/* ── Responsive ────────────────────────────────────── */
@media (max-width: 768px) {
    .cp-page { padding: 1.25rem 1rem 2rem; }
    .cp-header { flex-direction: column; gap: 1rem; }
    .cp-stats { grid-template-columns: 1fr 1fr; }
    .cp-table th, .cp-table td { padding: .75rem .9rem; }
    .etab-type { display: none; }
}
@media (max-width: 480px) {
    .cp-stats { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="cp-page">

    {{-- Breadcrumb --}}
    <div class="cp-breadcrumb">
        <a href="{{ route('comptes.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </a>
        <span class="cp-breadcrumb-sep">›</span>
        <a href="#">CRM</a>
        <span class="cp-breadcrumb-sep">›</span>
        <span class="cp-breadcrumb-current">Comptes clients</span>
    </div>

    {{-- Header --}}
    <div class="cp-header">
        <div class="cp-header-left">
            <h1>Comptes clients</h1>
            <p>
                @if(auth()->user()->role === 'delegue')
                    Vos établissements assignés
                @elseif(auth()->user()->role === 'rbo')
                    Comptes des délégués que vous supervisez
                @else
                    Gérez l'ensemble de vos établissements et clients référencés
                @endif
            </p>
        </div>
        <div class="cp-header-actions">
            {{-- Only admin and rbo can create comptes --}}
            @if(in_array(auth()->user()->role, ['admin', 'rbo']))
                <a href="{{ route('comptes.create') }}" class="btn-cp btn-cp-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Nouveau compte
                </a>
            @endif
        </div>
    </div>


    {{-- Search bar --}}
    <div class="cp-search-bar">
        <form method="GET" action="{{ route('comptes.index') }}" style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;">
            <div class="cp-search-wrap">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input
                    type="text"
                    name="search"
                    class="cp-search-input"
                    placeholder="Rechercher un établissement, ville, délégué…"
                    value="{{ request('search') }}"
                    autocomplete="off"
                >
            </div>
            <button type="submit" class="btn-cp btn-cp-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Filtrer
            </button>
            @if(request('search'))
                <span class="cp-search-active">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    «&nbsp;{{ request('search') }}&nbsp;»
                </span>
                <a href="{{ route('comptes.index') }}" class="btn-cp btn-cp-danger-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- Table Card --}}
    <div class="cp-card">
        <div class="cp-card-header">
            <div class="cp-card-title">
                <span class="title-pip"></span>
                Liste des comptes
            </div>
            <span class="cp-result-count">
                {{ $comptes->total() }} résultat{{ $comptes->total() > 1 ? 's' : '' }}
                @if(request('search')) · filtrés @endif
            </span>
        </div>

        <div style="overflow-x: auto;">
            <table class="cp-table">
                <thead>
                    <tr>
                        {{-- <th>#</th> --}}
                        <th>Établissement</th>
                        <th>Géographie</th>
                        {{-- Hide delegue column for delegues (they only see their own comptes) --}}
                        @if(auth()->user()->role !== 'delegue')
                            <th>Délégué</th>
                        @endif
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $eavs = ['ea-a','ea-b','ea-c','ea-d','ea-e']; @endphp
                    @forelse($comptes as $i => $compte)
                    <tr>
                        {{-- ID --}}
                        {{-- <td><span class="id-cell">{{ $compte->id }}</span></td> --}}

                        {{-- Établissement + type --}}
                        <td>
                            <div class="etab-cell">
                                <div class="etab-avatar {{ $eavs[$i % count($eavs)] }}">
                                    {{ strtoupper(substr($compte->etablissement, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="etab-name">{{ $compte->etablissement }}</div>
                                    <div class="etab-type">
                                        <span class="type-badge">{{ ucfirst(str_replace('_',' ',$compte->type)) }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Géographie --}}
                        <td>
                            @php
                                $villeName    = $compte->ville?->nom ?? '—';
                                $zoneName     = $compte->zone?->name ?? '—';
                                $quartierName = $compte->quartier?->nom ?? '—';
                                $address      = $compte->adresse ?? '—';
                                $hasLocation  = $compte->ville || $compte->zone || $compte->quartier;
                            @endphp
                            <div class="loc-cell" style="cursor:pointer;"
                                data-ville="{{ $villeName }}"
                                data-zone="{{ $zoneName }}"
                                data-quartier="{{ $quartierName }}"
                                data-address="{{ $address }}"
                                data-etablissement="{{ $compte->etablissement }}">
                                <span class="loc-dot"></span>
                                <span style="font-size:.81rem;color:var(--text-secondary);">
                                    {{ $hasLocation ? ($quartierName != '—' ? $quartierName : ($zoneName != '—' ? $zoneName : $villeName)) : '—' }}
                                </span>
                                @if($hasLocation)
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                                @endif
                            </div>
                        </td>

                        {{-- Délégué (hidden for delegues) --}}
                        @if(auth()->user()->role !== 'delegue')
                            <td>
                                @if($compte->delegue)
                                    <div class="dlg-cell">
                                        <div class="dlg-mini-av">
                                            {{ strtoupper(substr($compte->delegue->prenom,0,1).substr($compte->delegue->nom,0,1)) }}
                                        </div>
                                        <span style="font-size:.82rem;font-weight:500;color:var(--text-primary);">
                                            {{ $compte->delegue->prenom }} {{ $compte->delegue->nom }}
                                        </span>
                                    </div>
                                @else
                                    <span style="color:var(--text-hint);">—</span>
                                @endif
                            </td>
                        @endif

                        {{-- Téléphone --}}
                        <td>
                            @if($compte->tel_bureau_1)
                                <span class="contact-cell">{{ $compte->tel_bureau_1 }}</span>
                            @else
                                <span style="color:var(--text-hint);">—</span>
                            @endif
                        </td>

                        {{-- Email --}}
                        <td>
                            @if($compte->email)
                                <span class="contact-cell">{{ $compte->email }}</span>
                            @else
                                <span style="color:var(--text-hint);">—</span>
                            @endif
                        </td>

                        {{-- Statut --}}
                        <td>
                            @if($compte->status == 'actif')
                                <span class="cp-badge badge-actif">
                                    <span class="cp-badge-dot"></span>
                                    Actif
                                </span>
                            @else
                                <span class="cp-badge badge-ferme">
                                    <span class="cp-badge-dot"></span>
                                    Fermé
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div class="actions-cell">
                                {{-- Edit: visible to all roles (controller scopes what they can change) --}}
                                <a href="{{ route('comptes.edit', $compte) }}" class="btn-cp btn-cp-sm btn-cp-warning">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                                </a>

                                <a href="{{ route('comptes.show', $compte) }}" class="btn-cp btn-cp-sm btn-zn-info">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                    Voir
                                </a>

                                {{-- Delete: admin and rbo only, not delegues --}}
                                @if(in_array(auth()->user()->role, ['admin', 'rbo']))
                                    <form action="{{ route('comptes.destroy', $compte) }}" method="POST"
                                          style="display:inline;" onsubmit="return confirm('Supprimer ce compte ?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-cp btn-cp-sm btn-cp-danger">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                        

                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role === 'delegue' ? 7 : 8 }}">
                            <div class="cp-empty">
                                <div class="cp-empty-icon">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                </div>
                                <h3>Aucun compte trouvé</h3>
                                <p>{{ request('search') ? 'Aucun résultat pour «&nbsp;'.request('search').'&nbsp;». Essayez un autre terme.' : 'Aucun compte ne vous est assigné pour le moment.' }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($comptes->hasPages())
        <div class="cp-pagination">
            {{ $comptes->withQueryString()->links('vendor.pagination.custom') }}
        </div>
        @endif
    </div>

</div>

{{-- Geography modal --}}
<div class="dr-modal-overlay" id="drModalGeo">
    <div class="dr-modal" role="dialog" aria-modal="true" aria-labelledby="drModalGeoTitle">
        <div class="dr-modal-hd">
            <div class="modal-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div class="modal-title-grp">
                <h2 id="drModalGeoTitle">Détails géographiques</h2>
                <p id="drModalGeoSubtitle">Établissement</p>
            </div>
            <button class="modal-close" id="drModalGeoClose" aria-label="Fermer">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="dr-modal-body" id="drModalGeoBody">
            <div class="loc-detail-row">
                <div class="loc-detail-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
                <div class="loc-detail-label">Quartier</div>
                <div class="loc-detail-value" id="geo-quartier">—</div>
            </div>
            <div class="loc-detail-row">
                <div class="loc-detail-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></div>
                <div class="loc-detail-label">Zone</div>
                <div class="loc-detail-value" id="geo-zone">—</div>
            </div>
            <div class="loc-detail-row">
                <div class="loc-detail-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
                <div class="loc-detail-label">Ville</div>
                <div class="loc-detail-value" id="geo-ville">—</div>
            </div>
            <div class="loc-detail-row">
                <div class="loc-detail-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 2a8 8 0 0 0-8 8c0 4 8 12 8 12s8-8 8-12a8 8 0 0 0-8-8z"/><circle cx="12" cy="10" r="3"/></svg></div>
                <div class="loc-detail-label">Adresse</div>
                <div class="loc-detail-value" id="geo-address">—</div>
            </div>
        </div>
        <div class="dr-modal-ft">
            <button class="btn-cp btn-cp-ghost" id="drModalGeoCancel">Fermer</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const input = document.querySelector('.cp-search-input');
    if (!input) return;
    input.addEventListener('keydown', e => {
        if (e.key === 'Escape') { input.value = ''; input.closest('form').submit(); }
    });
})();

const geoModal    = document.getElementById('drModalGeo');
const geoTitle    = document.getElementById('drModalGeoSubtitle');
const geoQuartier = document.getElementById('geo-quartier');
const geoZone     = document.getElementById('geo-zone');
const geoVille    = document.getElementById('geo-ville');
const geoAddress  = document.getElementById('geo-address');

function openGeoModal(etablissement, quartier, zone, ville, address) {
    geoTitle.textContent    = etablissement;
    geoQuartier.textContent = quartier !== '—' ? quartier : 'Non renseigné';
    geoZone.textContent     = zone     !== '—' ? zone     : 'Non renseigné';
    geoVille.textContent    = ville    !== '—' ? ville    : 'Non renseigné';
    geoAddress.textContent  = address  !== '—' ? address  : 'Non renseigné';
    geoModal.classList.add('visible');
    document.body.style.overflow = 'hidden';
}
function closeGeoModal() {
    geoModal.classList.remove('visible');
    document.body.style.overflow = '';
}
document.getElementById('drModalGeoClose').addEventListener('click', closeGeoModal);
document.getElementById('drModalGeoCancel').addEventListener('click', closeGeoModal);
geoModal.addEventListener('click', e => { if (e.target === geoModal) closeGeoModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeGeoModal(); });

document.querySelectorAll('.loc-cell').forEach(cell => {
    cell.addEventListener('click', () => {
        openGeoModal(
            cell.dataset.etablissement,
            cell.dataset.quartier,
            cell.dataset.zone,
            cell.dataset.ville,
            cell.dataset.address
        );
    });
});
</script>
@endpush
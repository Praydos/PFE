@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
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
.vl-page { padding: 2rem 2.5rem 3rem; animation: pageIn .4s var(--ease) both; }
@keyframes pageIn {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Breadcrumb ────────────────────────────────────── */
.vl-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.4rem; }
.vl-bc a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
.vl-bc a:hover { color: var(--blue); }
.vl-bc-sep { color: var(--text-hint); }
.vl-bc-cur { color: var(--text-secondary); }

/* ── Header ────────────────────────────────────────── */
.vl-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap; }
.vl-header-left h1 { font-size: 1.65rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.15; }
.vl-header-left p { font-size: .83rem; color: var(--text-muted); margin-top: .3rem; }

/* ── Buttons ───────────────────────────────────────── */
.btn-vl {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .56rem 1.1rem; border-radius: var(--r-sm);
    font-family: var(--font); font-size: .82rem; font-weight: 600;
    cursor: pointer; border: 1px solid transparent;
    transition: all var(--t); text-decoration: none;
    white-space: nowrap; letter-spacing: -.01em; line-height: 1;
}
.btn-vl svg { flex-shrink: 0; }
.btn-vl-primary { background: var(--blue); color: #fff; border-color: var(--blue); box-shadow: var(--shadow-blue); }
.btn-vl-primary:hover { background: var(--blue-dark); border-color: var(--blue-dark); color: #fff; text-decoration: none; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(91,141,238,.4); }
.btn-vl-ghost { background: var(--bg-card); color: var(--text-secondary); border-color: var(--border); box-shadow: var(--shadow-xs); }
.btn-vl-ghost:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--border-md); text-decoration: none; }
.btn-vl-danger-ghost { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.2); }
.btn-vl-danger-ghost:hover { background: #fddde2; color: var(--rose); text-decoration: none; }
.btn-vl-sm { padding: .38rem .72rem; font-size: .75rem; }
.btn-vl-warning { background: var(--amber-light); color: var(--amber); border-color: rgba(232,160,32,.2); }
.btn-vl-warning:hover { background: #ffefd4; color: var(--amber); text-decoration: none; }
.btn-vl-danger { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.18); }
.btn-vl-danger:hover { background: #fddde2; color: var(--rose); text-decoration: none; }

/* ── Stat Cards ────────────────────────────────────── */
.vl-stats { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
.vl-stat {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--r-lg); padding: 1.35rem 1.5rem;
    display: flex; align-items: center; gap: 1.1rem;
    box-shadow: var(--shadow-xs); transition: all var(--t);
    animation: pageIn .5s var(--ease) both;
    position: relative; overflow: hidden;
}
.vl-stat::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px; opacity: 0; transition: opacity var(--t);
    border-radius: var(--r-lg) var(--r-lg) 0 0;
}
.vl-stat:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); border-color: var(--border-md); }
.vl-stat:hover::before { opacity: 1; }
.vl-stat:nth-child(1) { animation-delay:.06s; } .vl-stat:nth-child(1)::before { background: var(--blue); }
.vl-stat:nth-child(2) { animation-delay:.11s; } .vl-stat:nth-child(2)::before { background: var(--teal); }
.vl-stat:nth-child(3) { animation-delay:.16s; } .vl-stat:nth-child(3)::before { background: var(--violet); }

.stat-ico { width: 44px; height: 44px; border-radius: var(--r-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.si-blue   { background: var(--blue-light);   color: var(--blue); }
.si-teal   { background: var(--teal-light);   color: var(--teal); }
.si-violet { background: var(--violet-light); color: var(--violet); }

.stat-label { font-size: .72rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .05em; }
.stat-value { font-size: 1.7rem; font-weight: 700; color: var(--text-primary); line-height: 1.1; letter-spacing: -.04em; margin-top: .06rem; }

/* ── Search ────────────────────────────────────────── */
.vl-search-bar { display: flex; align-items: center; gap: .6rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
.vl-search-wrap { position: relative; flex: 1; min-width: 220px; max-width: 380px; }
.vl-search-wrap svg { position: absolute; left: .85rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; }
.vl-search-input {
    width: 100%; padding: .56rem .9rem .56rem 2.35rem;
    border: 1px solid var(--border); border-radius: var(--r-sm);
    background: var(--bg-card); font-family: var(--font);
    font-size: .83rem; color: var(--text-primary);
    box-shadow: var(--shadow-xs); transition: all var(--t); outline: none;
}
.vl-search-input::placeholder { color: var(--text-muted); }
.vl-search-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); }
.search-pill {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .3rem .75rem; border-radius: 20px;
    background: var(--blue-light); color: var(--blue);
    border: 1px solid var(--blue-mid); font-size: .76rem; font-weight: 600;
}

/* ── Card ──────────────────────────────────────────── */
.vl-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-xl); box-shadow: var(--shadow-sm); overflow: hidden; }
.vl-card-header { padding: 1.1rem 1.6rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
.vl-card-title { font-size: .88rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: .55rem; letter-spacing: -.01em; }
.title-pip { width: 7px; height: 7px; border-radius: 50%; background: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); }
.vl-count { font-size: .76rem; color: var(--text-muted); font-weight: 500; }

/* ── Table ─────────────────────────────────────────── */
.vl-table { width: 100%; border-collapse: collapse; }
.vl-table thead tr { border-bottom: 1px solid var(--border); }
.vl-table th {
    padding: .85rem 1.2rem; font-size: .69rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: var(--text-hint); text-align: left;
    background: var(--bg-base); white-space: nowrap;
}
.vl-table td { padding: .95rem 1.2rem; font-size: .83rem; color: var(--text-secondary); border-bottom: 1px solid var(--border); vertical-align: middle; }
.vl-table tbody tr { transition: background var(--t); }
.vl-table tbody tr:hover { background: #f8f9fd; }
.vl-table tbody tr:last-child td { border-bottom: none; }

/* ID pill */
.id-pill { font-family: var(--font-mono); font-size: .75rem; color: var(--text-muted); font-weight: 500; background: var(--bg-subtle); border-radius: var(--r-xs); padding: .18rem .5rem; display: inline-block; }

/* Ville name cell */
.vl-name-cell { display: flex; align-items: center; gap: .75rem; }
.vl-tile {
    width: 36px; height: 36px; border-radius: var(--r-sm);
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .75rem; color: #fff; flex-shrink: 0;
}
.vt-a { background: linear-gradient(135deg, #5b8dee, #6c63ff); }
.vt-b { background: linear-gradient(135deg, #0cb8b6, #00d4aa); }
.vt-c { background: linear-gradient(135deg, #7c6fcd, #b06ab3); }
.vt-d { background: linear-gradient(135deg, #e8a020, #f97316); }
.vt-e { background: linear-gradient(135deg, #e8506a, #ff6b9d); }
.vl-name-text { font-weight: 600; color: var(--text-primary); font-size: .84rem; letter-spacing: -.01em; }

/* Zones count cell */
.zones-count-cell { display: flex; align-items: center; gap: .55rem; }
.zones-count-badge {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .22rem .72rem; border-radius: 20px;
    font-size: .78rem; font-weight: 700;
    background: var(--teal-light); color: #0a9997;
    border: 1px solid rgba(12,184,182,.22);
    font-family: var(--font-mono);
}
.zones-count-badge svg { flex-shrink: 0; }
.zones-count-zero {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .22rem .72rem; border-radius: 20px;
    font-size: .78rem; font-weight: 600;
    background: var(--bg-subtle); color: var(--text-hint);
    border: 1px solid var(--border);
}

/* Clickable zone badge trigger */
.zones-trigger {
    cursor: pointer;
    transition: all var(--t);
    border-radius: 20px;
    outline: none;
}
.zones-trigger:hover .zones-count-badge {
    background: #b2f0ee;
    border-color: rgba(12,184,182,.45);
    box-shadow: 0 0 0 3px rgba(12,184,182,.12);
}
.zones-trigger:hover .zones-count-badge,
.zones-trigger:focus .zones-count-badge {
    transform: translateY(-1px);
}

/* ── Zones Modal ─────────────────────────────────────── */
.zn-modal-overlay {
    position: fixed; inset: 0;
    background: rgba(26,31,54,.42);
    backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
    z-index: 1000;
    display: none; align-items: center; justify-content: center; padding: 1rem;
}
.zn-modal-overlay.visible { display: flex; animation: oIn .2s ease both; }
@keyframes oIn { from { opacity:0; } to { opacity:1; } }

.zn-modal {
    background: var(--bg-card);
    border: 1px solid var(--border-md);
    border-radius: var(--r-xl);
    width: 100%; max-width: 500px;
    box-shadow: 0 20px 48px rgba(31,45,80,.13), 0 6px 16px rgba(31,45,80,.07);
    overflow: hidden;
    animation: mIn .28s cubic-bezier(.34,1.4,.64,1) both;
}
@keyframes mIn {
    from { opacity:0; transform:scale(.94) translateY(8px); }
    to   { opacity:1; transform:scale(1)   translateY(0); }
}

.zn-modal-hd {
    padding: 1.25rem 1.5rem 1.1rem;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem;
    background: linear-gradient(to bottom, #fafbff, #fff);
}
.zn-modal-icon {
    width: 38px; height: 38px; border-radius: var(--r-md);
    background: var(--teal-light); border: 1px solid rgba(12,184,182,.22);
    display: flex; align-items: center; justify-content: center;
    color: var(--teal); flex-shrink: 0;
}
.zn-modal-titles { flex: 1; }
.zn-modal-titles h2 { font-size: .95rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.02em; }
.zn-modal-titles p  { font-size: .76rem; color: var(--text-muted); margin-top: .18rem; }
.zn-modal-close {
    width: 28px; height: 28px; border-radius: var(--r-xs);
    background: var(--bg-subtle); border: 1px solid var(--border);
    color: var(--text-muted); display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all var(--t); flex-shrink: 0;
}
.zn-modal-close:hover { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.2); }

.zn-modal-body {
    padding: 1rem 1.5rem 1.5rem;
    max-height: 60vh; overflow-y: auto;
}
.zn-modal-body::-webkit-scrollbar { width: 4px; }
.zn-modal-body::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

.zn-modal-empty {
    padding: 2rem; text-align: center;
    font-size: .84rem; color: var(--text-muted); font-style: italic;
}

.zn-modal-list { display: flex; flex-direction: column; gap: .45rem; }

.zn-modal-item {
    display: flex; align-items: center; gap: .85rem;
    padding: .8rem 1rem;
    border: 1px solid var(--border); border-radius: var(--r-md);
    background: var(--bg-card); transition: all var(--t);
}
.zn-modal-item:hover { border-color: rgba(12,184,182,.3); background: var(--teal-light); }

.zn-modal-tile {
    width: 36px; height: 36px; border-radius: var(--r-sm);
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .72rem; color: #fff; flex-shrink: 0;
}

.zn-modal-info { flex: 1; min-width: 0; }
.zn-modal-name { font-size: .85rem; font-weight: 600; color: var(--text-primary); letter-spacing: -.01em; }
.zn-modal-meta { font-size: .73rem; color: var(--text-muted); margin-top: .1rem; display: flex; align-items: center; gap: .4rem; }
.zn-modal-meta-dot { width: 4px; height: 4px; border-radius: 50%; background: var(--text-hint); }

/* Actions */
.actions-cell { display: flex; align-items: center; gap: .35rem; }

/* Empty */
.vl-empty { padding: 4rem 2rem; text-align: center; }
.vl-empty-icon { width: 52px; height: 52px; border-radius: var(--r-md); background: var(--bg-subtle); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--text-hint); }
.vl-empty h3 { font-size: .95rem; font-weight: 700; color: var(--text-secondary); }
.vl-empty p  { font-size: .82rem; color: var(--text-muted); margin-top: .3rem; }

/* Pagination */

/* Responsive */
@media (max-width: 768px) {
    .vl-page { padding: 1.25rem 1rem 2rem; }
    .vl-header { flex-direction: column; gap: 1rem; }
    .vl-stats { grid-template-columns: 1fr 1fr; }
    .vl-table th, .vl-table td { padding: .75rem .9rem; }
}
@media (max-width: 480px) { .vl-stats { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="vl-page">

    {{-- Breadcrumb --}}
    <div class="vl-bc">
        <a href="{{ route('villes.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </a>
        <span class="vl-bc-sep">›</span>
        <a href="#">Géographie</a>
        <span class="vl-bc-sep">›</span>
        <span class="vl-bc-cur">Villes</span>
    </div>

    {{-- Header --}}
    <div class="vl-header">
        <div class="vl-header-left">
            <h1>Villes</h1>
            <p>Gérez les villes et consultez leurs zones associées</p>
        </div>
        <a href="{{ route('villes.create') }}" class="btn-vl btn-vl-primary">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouvelle ville
        </a>
    </div>

    {{-- Stat Cards --}}
    @php
        $col         = $villes->getCollection();
        $totalVilles = $villes->total();
        $totalZones  = $col->sum('zones_count');
        $avgsZones   = $totalVilles ? round($totalZones / $totalVilles, 1) : 0;
    @endphp
    <div class="vl-stats">
        <div class="vl-stat">
            <div class="stat-ico si-blue">
                <svg width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div>
                <div class="stat-label">Villes</div>
                <div class="stat-value">{{ $totalVilles }}</div>
            </div>
        </div>
        <div class="vl-stat">
            <div class="stat-ico si-teal">
                <svg width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
            </div>
            <div>
                <div class="stat-label">Zones totales</div>
                <div class="stat-value">{{ $totalZones }}</div>
            </div>
        </div>
        <div class="vl-stat">
            <div class="stat-ico si-violet">
                <svg width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            </div>
            <div>
                <div class="stat-label">Moy. zones/ville</div>
                <div class="stat-value">{{ $avgsZones }}</div>
            </div>
        </div>
    </div>

    {{-- Search --}}
    <div class="vl-search-bar">
        <form method="GET" action="{{ route('villes.index') }}" style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;">
            <div class="vl-search-wrap">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input
                    type="text"
                    name="search"
                    class="vl-search-input"
                    placeholder="Rechercher une ville…"
                    value="{{ request('search') }}"
                    autocomplete="off"
                >
            </div>
            <button type="submit" class="btn-vl btn-vl-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Filtrer
            </button>
            @if(request('search'))
                <span class="search-pill">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    «&nbsp;{{ request('search') }}&nbsp;»
                </span>
                <a href="{{ route('villes.index') }}" class="btn-vl btn-vl-danger-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- Table Card --}}
    <div class="vl-card">
        <div class="vl-card-header">
            <div class="vl-card-title">
                <span class="title-pip"></span>
                Liste des villes
            </div>
            <span class="vl-count">{{ $villes->total() }} ville{{ $villes->total() > 1 ? 's' : '' }}</span>
        </div>

        <div style="overflow-x: auto;">
            <table class="vl-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ville</th>
                        <th>Zones</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $tiles = ['vt-a','vt-b','vt-c','vt-d','vt-e']; @endphp
                    @forelse($villes as $i => $ville)
                    <tr>
                        {{-- ID --}}
                        <td><span class="id-pill">{{ $ville->id }}</span></td>

                        {{-- Ville name --}}
                        <td>
                            <div class="vl-name-cell">
                                <div class="vl-tile {{ $tiles[$i % count($tiles)] }}">
                                    {{ strtoupper(substr($ville->nom, 0, 2)) }}
                                </div>
                                <span class="vl-name-text">{{ $ville->nom }}</span>
                            </div>
                        </td>

                        {{-- Zones count — clickable if zones exist --}}
                        <td>
                            <div class="zones-count-cell">
                                @if($ville->zones_count > 0)
                                    @php
                                        $zonesData = $ville->zones->map(fn($z) => [
                                            'name' => $z->name,
                                            'rbo'  => $z->rbo ? $z->rbo->prenom.' '.$z->rbo->nom : null,
                                            'delegates_count' => $z->delegates_count ?? $z->delegates->count(),
                                        ])->values();
                                    @endphp
                                    <div class="zones-trigger"
                                         role="button"
                                         tabindex="0"
                                         data-ville="{{ $ville->nom }}"
                                         data-zones="{{ json_encode($zonesData) }}"
                                         title="Voir les zones de {{ $ville->nom }}">
                                        <span class="zones-count-badge">
                                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                                            {{ $ville->zones_count }} zone{{ $ville->zones_count > 1 ? 's' : '' }}
                                        </span>
                                    </div>
                                @else
                                    <span class="zones-count-zero">
                                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                                        Aucune zone
                                    </span>
                                @endif
                            </div>
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div class="actions-cell">
                                <a href="{{ route('villes.edit', $ville) }}" class="btn-vl btn-vl-sm btn-vl-warning">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                                    Modifier
                                </a>
                                <form action="{{ route('villes.destroy', $ville) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette ville ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-vl btn-vl-sm btn-vl-danger">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="vl-empty">
                                <div class="vl-empty-icon">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                </div>
                                <h3>Aucune ville trouvée</h3>
                                <p>{{ request('search') ? 'Aucun résultat pour «\u00a0'.request('search').'\u00a0». Essayez un autre terme.' : 'Commencez par créer votre première ville.' }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($villes->hasPages())
        <div class="vl-pagination">
            {{ $villes->withQueryString()->links('vendor.pagination.custom') }}
        </div>
        @endif
    </div>

</div>

{{-- ── Zones Modal ───────────────────────────────────── --}}
<div class="zn-modal-overlay" id="znModalOverlay">
    <div class="zn-modal" role="dialog" aria-modal="true" aria-labelledby="znModalTitle">
        <div class="zn-modal-hd">
            <div class="zn-modal-icon">
                <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
            </div>
            <div class="zn-modal-titles">
                <h2 id="znModalTitle">Zones assignées</h2>
                <p id="znModalVille">—</p>
            </div>
            <button class="zn-modal-close" id="znModalClose" aria-label="Fermer">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="zn-modal-body" id="znModalBody"></div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    /* ── Search Escape ──────────────────────── */
    const searchInput = document.querySelector('.vl-search-input');
    if (searchInput) {
        searchInput.addEventListener('keydown', e => {
            if (e.key === 'Escape') { searchInput.value = ''; searchInput.closest('form').submit(); }
        });
    }

    /* ── Zones Modal ────────────────────────── */
    const overlay  = document.getElementById('znModalOverlay');
    const body     = document.getElementById('znModalBody');
    const villeLbl = document.getElementById('znModalVille');

    const tileGradients = [
        'linear-gradient(135deg,#5b8dee,#6c63ff)',
        'linear-gradient(135deg,#0cb8b6,#00d4aa)',
        'linear-gradient(135deg,#7c6fcd,#b06ab3)',
        'linear-gradient(135deg,#e8a020,#f97316)',
        'linear-gradient(135deg,#e8506a,#ff6b9d)',
    ];

    function openModal(villeName, zones) {
        villeLbl.textContent = 'Ville · ' + villeName;

        if (!zones.length) {
            body.innerHTML = '<div class="zn-modal-empty">Aucune zone assignée à cette ville.</div>';
        } else {
            body.innerHTML = '<div class="zn-modal-list">' +
                zones.map((z, i) => {
                    const initials = z.name.substring(0, 2).toUpperCase();
                    const grad = tileGradients[i % tileGradients.length];

                    const metaParts = [];
                    if (z.rbo) metaParts.push(`RBO : ${z.rbo}`);
                    if (z.delegates_count !== undefined) {
                        metaParts.push(`${z.delegates_count} délégué${z.delegates_count !== 1 ? 's' : ''}`);
                    }

                    const metaHtml = metaParts.length
                        ? metaParts.map((p, pi) =>
                            (pi > 0 ? '<span class="zn-modal-meta-dot"></span>' : '') + p
                          ).join('')
                        : '';

                    return `
                    <div class="zn-modal-item">
                        <div class="zn-modal-tile" style="background:${grad}">${initials}</div>
                        <div class="zn-modal-info">
                            <div class="zn-modal-name">${z.name}</div>
                            ${metaHtml ? `<div class="zn-modal-meta">${metaHtml}</div>` : ''}
                        </div>
                    </div>`;
                }).join('') +
            '</div>';
        }

        overlay.classList.add('visible');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        overlay.classList.remove('visible');
        document.body.style.overflow = '';
    }

    /* Trigger clicks */
    document.querySelectorAll('.zones-trigger').forEach(el => {
        const open = () => {
            const ville = el.dataset.ville;
            const zones = JSON.parse(el.dataset.zones || '[]');
            openModal(ville, zones);
        };
        el.addEventListener('click', open);
        el.addEventListener('keydown', e => {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(); }
        });
    });

    document.getElementById('znModalClose').addEventListener('click', closeModal);
    overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
})();
</script>
@endpush
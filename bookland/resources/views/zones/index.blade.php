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

.zn-page { padding: 2rem 2.5rem 3rem; animation: pageIn .4s var(--ease) both; }
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
.zn-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap; }
.zn-header-left h1 { font-size: 1.65rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.15; }
.zn-header-left p { font-size: .83rem; color: var(--text-muted); margin-top: .3rem; }

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
.btn-zn-primary:hover { background: var(--blue-dark); border-color: var(--blue-dark); color: #fff; text-decoration: none; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(91,141,238,.4); }
.btn-zn-ghost { background: var(--bg-card); color: var(--text-secondary); border-color: var(--border); box-shadow: var(--shadow-xs); }
.btn-zn-ghost:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--border-md); text-decoration: none; }
.btn-zn-danger-ghost { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.2); }
.btn-zn-danger-ghost:hover { background: #fddde2; color: var(--rose); text-decoration: none; }
.btn-zn-sm { padding: .38rem .72rem; font-size: .75rem; }
.btn-zn-warning { background: var(--amber-light); color: var(--amber); border-color: rgba(232,160,32,.2); }
.btn-zn-warning:hover { background: #ffefd4; color: var(--amber); text-decoration: none; }
.btn-zn-danger { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.18); }
.btn-zn-danger:hover { background: #fddde2; color: var(--rose); text-decoration: none; }

/* ── Stat Cards ──────────────────────────────────────── */
.zn-stats { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
.zn-stat {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--r-lg); padding: 1.35rem 1.5rem;
    display: flex; align-items: center; gap: 1.1rem;
    box-shadow: var(--shadow-xs); transition: all var(--t);
    animation: pageIn .5s var(--ease) both;
    position: relative; overflow: hidden;
}
.zn-stat::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px; opacity: 0; transition: opacity var(--t);
    border-radius: var(--r-lg) var(--r-lg) 0 0;
}
.zn-stat:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); border-color: var(--border-md); }
.zn-stat:hover::before { opacity: 1; }
.zn-stat:nth-child(1) { animation-delay:.06s; } .zn-stat:nth-child(1)::before { background: var(--blue); }
.zn-stat:nth-child(2) { animation-delay:.11s; } .zn-stat:nth-child(2)::before { background: var(--teal); }
.zn-stat:nth-child(3) { animation-delay:.16s; } .zn-stat:nth-child(3)::before { background: var(--violet); }
.zn-stat:nth-child(4) { animation-delay:.21s; } .zn-stat:nth-child(4)::before { background: var(--green); }

.stat-ico { width: 44px; height: 44px; border-radius: var(--r-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.si-blue   { background: var(--blue-light);   color: var(--blue); }
.si-teal   { background: var(--teal-light);   color: var(--teal); }
.si-violet { background: var(--violet-light); color: var(--violet); }
.si-green  { background: var(--green-light);  color: var(--green); }

.stat-label { font-size: .72rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .05em; }
.stat-value { font-size: 1.7rem; font-weight: 700; color: var(--text-primary); line-height: 1.1; letter-spacing: -.04em; margin-top: .06rem; }

/* ── Search ──────────────────────────────────────────── */
.zn-search-bar { display: flex; align-items: center; gap: .6rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
.zn-search-wrap { position: relative; flex: 1; min-width: 220px; max-width: 380px; }
.zn-search-wrap svg { position: absolute; left: .85rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; }
.zn-search-input {
    width: 100%; padding: .56rem .9rem .56rem 2.35rem;
    border: 1px solid var(--border); border-radius: var(--r-sm);
    background: var(--bg-card); font-family: var(--font);
    font-size: .83rem; color: var(--text-primary);
    box-shadow: var(--shadow-xs); transition: all var(--t); outline: none;
}
.zn-search-input::placeholder { color: var(--text-muted); }
.zn-search-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); }
.search-pill {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .3rem .75rem; border-radius: 20px;
    background: var(--blue-light); color: var(--blue);
    border: 1px solid var(--blue-mid); font-size: .76rem; font-weight: 600;
}

/* ── Card ────────────────────────────────────────────── */
.zn-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-xl); box-shadow: var(--shadow-sm); overflow: hidden; }
.zn-card-header { padding: 1.1rem 1.6rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
.zn-card-title { font-size: .88rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: .55rem; letter-spacing: -.01em; }
.title-pip { width: 7px; height: 7px; border-radius: 50%; background: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); }
.zn-count { font-size: .76rem; color: var(--text-muted); font-weight: 500; }

/* ── Table ───────────────────────────────────────────── */
.zn-table { width: 100%; border-collapse: collapse; }
.zn-table thead tr { border-bottom: 1px solid var(--border); }
.zn-table th {
    padding: .85rem 1.2rem; font-size: .69rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: var(--text-hint); text-align: left;
    background: var(--bg-base); white-space: nowrap;
}
.zn-table td { padding: .95rem 1.2rem; font-size: .83rem; color: var(--text-secondary); border-bottom: 1px solid var(--border); vertical-align: middle; }
.zn-table tbody tr { transition: background var(--t); }
.zn-table tbody tr:hover { background: #f8f9fd; }
.zn-table tbody tr:last-child td { border-bottom: none; }

/* ID pill */
.id-pill { font-family: var(--font-mono); font-size: .75rem; color: var(--text-muted); font-weight: 500; background: var(--bg-subtle); border-radius: var(--r-xs); padding: .18rem .5rem; display: inline-block; }

/* Zone name cell */
.zn-name-cell { display: flex; align-items: center; gap: .75rem; }
.zn-tile {
    width: 34px; height: 34px; border-radius: var(--r-sm);
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .72rem; color: #fff; flex-shrink: 0;
}
.zt-a { background: linear-gradient(135deg, #5b8dee, #6c63ff); }
.zt-b { background: linear-gradient(135deg, #0cb8b6, #00d4aa); }
.zt-c { background: linear-gradient(135deg, #7c6fcd, #b06ab3); }
.zt-d { background: linear-gradient(135deg, #e8a020, #f97316); }
.zt-e { background: linear-gradient(135deg, #e8506a, #ff6b9d); }
.zn-name-text { font-weight: 600; color: var(--text-primary); font-size: .84rem; letter-spacing: -.01em; }

/* Ville cell */
.ville-cell { display: flex; align-items: center; gap: .38rem; font-size: .81rem; color: var(--text-secondary); }
.ville-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--teal); flex-shrink: 0; }

/* RBO cell */
.rbo-cell { display: flex; align-items: center; gap: .5rem; }
.rbo-av {
    width: 28px; height: 28px; border-radius: 50%;
    background: linear-gradient(135deg, #7c6fcd, #b06ab3);
    display: flex; align-items: center; justify-content: center;
    font-size: .6rem; font-weight: 700; color: #fff; flex-shrink: 0;
}
.rbo-name { font-size: .82rem; font-weight: 500; color: var(--text-primary); }

/* Delegate avatar stack */
.delegates-cell { display: flex; align-items: center; gap: .55rem; flex-wrap: wrap; }
.dlg-stack { display: flex; align-items: center; }
.dlg-av {
    width: 26px; height: 26px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .58rem; font-weight: 700; color: #fff; flex-shrink: 0;
    border: 2px solid var(--bg-card); margin-left: -6px;
    transition: transform var(--t); cursor: default;
}
.dlg-av:first-child { margin-left: 0; }
.dlg-av:hover { transform: translateY(-2px); z-index: 2; position: relative; }
.dlg-av-0 { background: linear-gradient(135deg, #5b8dee, #6c63ff); }
.dlg-av-1 { background: linear-gradient(135deg, #0cb8b6, #00d4aa); }
.dlg-av-2 { background: linear-gradient(135deg, #e8a020, #f97316); }
.dlg-av-3 { background: linear-gradient(135deg, #e8506a, #ff6b9d); }
.dlg-overflow {
    width: 26px; height: 26px; border-radius: 50%;
    background: var(--bg-subtle); border: 2px solid var(--bg-card);
    display: flex; align-items: center; justify-content: center;
    font-size: .58rem; font-weight: 700; color: var(--text-muted); margin-left: -6px;
}
.dlg-names { font-size: .79rem; color: var(--text-secondary); line-height: 1.3; }
.dlg-none  { font-size: .79rem; color: var(--text-hint); font-style: italic; }

/* Clickable delegates trigger */
.dlg-trigger {
    cursor: pointer;
    border-radius: var(--r-sm);
    padding: .25rem .4rem;
    margin: -.25rem -.4rem;
    transition: background var(--t);
    display: inline-flex; align-items: center; gap: .55rem;
}
.dlg-trigger:hover { background: var(--blue-light); }
.dlg-trigger:hover .dlg-names { color: var(--blue); }
.dlg-trigger-hint {
    font-size: .68rem; color: var(--text-hint); font-weight: 500;
    display: flex; align-items: center; gap: .2rem;
    transition: color var(--t);
}
.dlg-trigger:hover .dlg-trigger-hint { color: var(--blue); }

/* ── Delegates Modal ─────────────────────────────────── */
.dlg-modal-overlay {
    position: fixed; inset: 0;
    background: rgba(26,31,54,.42);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    z-index: 1000;
    display: none; align-items: center; justify-content: center; padding: 1rem;
}
.dlg-modal-overlay.visible { display: flex; animation: oIn .2s ease both; }
@keyframes oIn { from { opacity: 0; } to { opacity: 1; } }

.dlg-modal {
    background: var(--bg-card);
    border: 1px solid var(--border-md);
    border-radius: var(--r-xl);
    width: 100%; max-width: 480px;
    box-shadow: 0 20px 48px rgba(31,45,80,.13), 0 6px 16px rgba(31,45,80,.07);
    overflow: hidden;
    animation: mIn .28s cubic-bezier(.34,1.4,.64,1) both;
}
@keyframes mIn {
    from { opacity: 0; transform: scale(.94) translateY(8px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}

.dlg-modal-hd {
    padding: 1.25rem 1.5rem 1.1rem;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem;
    background: linear-gradient(to bottom, #fafbff, #fff);
}
.dlg-modal-icon {
    width: 38px; height: 38px; border-radius: var(--r-md);
    background: var(--blue-light); border: 1px solid var(--blue-mid);
    display: flex; align-items: center; justify-content: center;
    color: var(--blue); flex-shrink: 0;
}
.dlg-modal-titles { flex: 1; }
.dlg-modal-titles h2 { font-size: .95rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.02em; }
.dlg-modal-titles p  { font-size: .76rem; color: var(--text-muted); margin-top: .18rem; }
.dlg-modal-close {
    width: 28px; height: 28px; border-radius: var(--r-xs);
    background: var(--bg-subtle); border: 1px solid var(--border);
    color: var(--text-muted); display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all var(--t); flex-shrink: 0;
}
.dlg-modal-close:hover { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.2); }

.dlg-modal-body {
    padding: 1rem 1.5rem 1.5rem;
    max-height: 60vh; overflow-y: auto;
}
.dlg-modal-body::-webkit-scrollbar { width: 4px; }
.dlg-modal-body::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

.dlg-modal-empty {
    padding: 2rem; text-align: center;
    font-size: .84rem; color: var(--text-muted); font-style: italic;
}

.dlg-modal-list { display: flex; flex-direction: column; gap: .45rem; }

.dlg-modal-item {
    display: flex; align-items: center; gap: .85rem;
    padding: .75rem 1rem;
    border: 1px solid var(--border); border-radius: var(--r-md);
    background: var(--bg-card);
    transition: all var(--t);
}
.dlg-modal-item:hover { border-color: var(--blue-mid); background: var(--blue-light); }

.dlg-modal-av {
    width: 36px; height: 36px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .76rem; color: #fff; flex-shrink: 0;
}
.dlg-modal-info { flex: 1; min-width: 0; }
.dlg-modal-name  { font-size: .84rem; font-weight: 600; color: var(--text-primary); letter-spacing: -.01em; }
.dlg-modal-email { font-size: .73rem; color: var(--text-muted); font-family: var(--font-mono); margin-top: .08rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* Actions */
.actions-cell { display: flex; align-items: center; gap: .35rem; }

/* Empty */
.zn-empty { padding: 4rem 2rem; text-align: center; }
.zn-empty-icon { width: 52px; height: 52px; border-radius: var(--r-md); background: var(--bg-subtle); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--text-hint); }
.zn-empty h3 { font-size: .95rem; font-weight: 700; color: var(--text-secondary); }
.zn-empty p  { font-size: .82rem; color: var(--text-muted); margin-top: .3rem; }

/* Pagination */
/* ── Pagination fix ──────────────────────────────────── */

/* Responsive */
@media (max-width: 768px) {
    .zn-page { padding: 1.25rem 1rem 2rem; }
    .zn-header { flex-direction: column; gap: 1rem; }
    .zn-stats { grid-template-columns: 1fr 1fr; }
    .zn-table th, .zn-table td { padding: .75rem .9rem; }
    .dlg-names { display: none; }
}
@media (max-width: 480px) { .zn-stats { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('zones.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="#">Géographie</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Zones</span>
    </div>

    {{-- Header --}}
    <div class="zn-header">
        <div class="zn-header-left">
            <h1>Zones</h1>
            <p>Gérez les zones commerciales, leurs RBOs et délégués assignés</p>
        </div>
        <a href="{{ route('zones.create') }}" class="btn-zn btn-zn-primary">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouvelle zone
        </a>
    </div>

    

    {{-- Search --}}
    <div class="zn-search-bar">
        <form method="GET" action="{{ route('zones.index') }}" style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;">
            <div class="zn-search-wrap">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input
                    type="text"
                    name="search"
                    class="zn-search-input"
                    placeholder="Rechercher une zone, ville, RBO…"
                    value="{{ request('search') }}"
                    autocomplete="off"
                >
            </div>
            <button type="submit" class="btn-zn btn-zn-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Filtrer
            </button>
            @if(request('search'))
                <span class="search-pill">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    «&nbsp;{{ request('search') }}&nbsp;»
                </span>
                <a href="{{ route('zones.index') }}" class="btn-zn btn-zn-danger-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- Table Card --}}
    <div class="zn-card">
        <div class="zn-card-header">
            <div class="zn-card-title">
                <span class="title-pip"></span>
                Liste des zones
            </div>
            <span class="zn-count">{{ $zones->total() }} zone{{ $zones->total() > 1 ? 's' : '' }}</span>
        </div>

        <div style="overflow-x: auto;">
            <table class="zn-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Zone</th>
                        <th>Ville</th>
                        <th>RBO</th>
                        <th>Délégués</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $tiles = ['zt-a','zt-b','zt-c','zt-d','zt-e']; @endphp
                    @forelse($zones as $i => $zone)
                    <tr>
                        {{-- ID --}}
                        <td><span class="id-pill">{{ $zone->id }}</span></td>

                        {{-- Zone name --}}
                        <td>
                            <div class="zn-name-cell">
                                <div class="zn-tile {{ $tiles[$i % count($tiles)] }}">
                                    {{ strtoupper(substr($zone->name, 0, 2)) }}
                                </div>
                                <span class="zn-name-text">{{ $zone->name }}</span>
                            </div>
                        </td>

                        {{-- Ville --}}
                        <td>
                            <div class="ville-cell">
                                <span class="ville-dot"></span>
                                {{ $zone->ville->nom }}
                            </div>
                        </td>

                        {{-- RBO --}}
                        <td>
                            @if($zone->rbo)
                                <div class="rbo-cell">
                                    <div class="rbo-av">
                                        {{ strtoupper(substr($zone->rbo->prenom,0,1).substr($zone->rbo->nom,0,1)) }}
                                    </div>
                                    <span class="rbo-name">{{ $zone->rbo->prenom }} {{ $zone->rbo->nom }}</span>
                                </div>
                            @else
                                <span style="color:var(--text-hint);font-size:.8rem;">—</span>
                            @endif
                        </td>

                        {{-- Delegates — clickable avatar stack → opens modal --}}
                        <td>
                            @if($zone->delegates->isEmpty())
                                <span class="dlg-none">Aucun délégué</span>
                            @else
                                @php
                                    $dlgData = $zone->delegates->map(fn($d) => [
                                        'prenom' => $d->prenom,
                                        'nom'    => $d->nom,
                                        'email'  => $d->email ?? '',
                                    ])->values();
                                @endphp
                                <div class="delegates-cell">
                                    <div class="dlg-trigger"
                                         role="button"
                                         tabindex="0"
                                         data-zone="{{ $zone->name }}"
                                         data-delegates="{{ json_encode($dlgData) }}"
                                         title="Voir tous les délégués">
                                        <div class="dlg-stack">
                                            @foreach($zone->delegates->take(4) as $di => $dlg)
                                                <div class="dlg-av dlg-av-{{ $di % 4 }}">
                                                    {{ strtoupper(substr($dlg->prenom,0,1).substr($dlg->nom,0,1)) }}
                                                </div>
                                            @endforeach
                                            @if($zone->delegates->count() > 4)
                                                <div class="dlg-overflow">+{{ $zone->delegates->count() - 4 }}</div>
                                            @endif
                                        </div>
                                        <span class="dlg-names">
                                            {{ $zone->delegates->take(2)->map(fn($d) => $d->prenom)->join(', ') }}{{ $zone->delegates->count() > 2 ? ' +'.($zone->delegates->count()-2) : '' }}
                                        </span>
                                        <span class="dlg-trigger-hint">
                                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div class="actions-cell">
                                <a href="{{ route('zones.edit', $zone) }}" class="btn-zn btn-zn-sm btn-zn-warning">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                                    Modifier
                                </a>
                                <form action="{{ route('zones.destroy', $zone) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette zone ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-zn btn-zn-sm btn-zn-danger">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="zn-empty">
                                <div class="zn-empty-icon">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                                </div>
                                <h3>Aucune zone trouvée</h3>
                                <p>{{ request('search') ? 'Aucun résultat pour «\u00a0'.request('search').'\u00a0». Essayez un autre terme.' : 'Commencez par créer votre première zone.' }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($zones->hasPages())
        <div class="zn-pagination">
            {{ $zones->withQueryString()->links('vendor.pagination.custom') }}
        </div>
        @endif
    </div>

</div>

{{-- ── Delegates Modal ──────────────────────────────── --}}
<div class="dlg-modal-overlay" id="dlgModalOverlay">
    <div class="dlg-modal" role="dialog" aria-modal="true" aria-labelledby="dlgModalTitle">
        <div class="dlg-modal-hd">
            <div class="dlg-modal-icon">
                <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div class="dlg-modal-titles">
                <h2 id="dlgModalTitle">Délégués assignés</h2>
                <p id="dlgModalZone">—</p>
            </div>
            <button class="dlg-modal-close" id="dlgModalClose" aria-label="Fermer">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="dlg-modal-body" id="dlgModalBody"></div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    /* ── Search Escape ──────────────────────── */
    const searchInput = document.querySelector('.zn-search-input');
    if (searchInput) {
        searchInput.addEventListener('keydown', e => {
            if (e.key === 'Escape') { searchInput.value = ''; searchInput.closest('form').submit(); }
        });
    }

    /* ── Delegate Modal ─────────────────────── */
    const overlay  = document.getElementById('dlgModalOverlay');
    const body     = document.getElementById('dlgModalBody');
    const zoneLbl  = document.getElementById('dlgModalZone');

    const avatarGradients = [
        'linear-gradient(135deg,#5b8dee,#6c63ff)',
        'linear-gradient(135deg,#0cb8b6,#00d4aa)',
        'linear-gradient(135deg,#7c6fcd,#b06ab3)',
        'linear-gradient(135deg,#e8a020,#f97316)',
        'linear-gradient(135deg,#e8506a,#ff6b9d)',
    ];

    function openModal(zoneName, delegates) {
        zoneLbl.textContent = 'Zone · ' + zoneName;

        if (!delegates.length) {
            body.innerHTML = '<div class="dlg-modal-empty">Aucun délégué assigné à cette zone.</div>';
        } else {
            body.innerHTML = '<div class="dlg-modal-list">' +
                delegates.map((d, i) => {
                    const initials = (d.prenom.charAt(0) + d.nom.charAt(0)).toUpperCase();
                    const grad = avatarGradients[i % avatarGradients.length];
                    return `
                    <div class="dlg-modal-item">
                        <div class="dlg-modal-av" style="background:${grad}">${initials}</div>
                        <div class="dlg-modal-info">
                            <div class="dlg-modal-name">${d.prenom} ${d.nom}</div>
                            ${d.email ? `<div class="dlg-modal-email">${d.email}</div>` : ''}
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
    document.querySelectorAll('.dlg-trigger').forEach(el => {
        const open = () => {
            const zone      = el.dataset.zone;
            const delegates = JSON.parse(el.dataset.delegates || '[]');
            openModal(zone, delegates);
        };
        el.addEventListener('click', open);
        el.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(); } });
    });

    document.getElementById('dlgModalClose').addEventListener('click', closeModal);
    overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
})();
</script>
@endpush
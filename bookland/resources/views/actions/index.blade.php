@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --bg:       #f5f6fa;
    --card:     #ffffff;
    --hover:    #f8f9fd;
    --subtle:   #f0f2f8;
    --border:   #e4e7f0;
    --border-2: #d0d5e8;
    --blue:     #5b8dee;
    --blue-d:   #3d6fd6;
    --blue-l:   #eef3fd;
    --blue-m:   #dce8fb;
    --teal:     #0cb8b6;
    --teal-l:   #e6faf9;
    --violet:   #7c6fcd;
    --violet-l: #f0eeff;
    --amber:    #e8a020;
    --amber-l:  #fff8ec;
    --rose:     #e8506a;
    --rose-l:   #fef0f2;
    --green:    #28c76f;
    --green-l:  #e8fbf0;
    --t1: #1a1f36; --t2: #525f7f; --t3: #9ba8c5; --t4: #bcc5dc;
    --r1:6px; --r2:8px; --r3:12px; --r4:16px; --r5:20px;
    --s1: 0 1px 3px rgba(31,45,80,.06);
    --s2: 0 2px 8px rgba(31,45,80,.08);
    --s3: 0 8px 24px rgba(31,45,80,.10);
    --sb: 0 4px 14px rgba(91,141,238,.32);
    --font: 'DM Sans', sans-serif;
    --mono: 'DM Mono', monospace;
    --ease: cubic-bezier(.4,0,.2,1);
    --t: .17s var(--ease);
}

body { font-family: var(--font); background: var(--bg); color: var(--t1); -webkit-font-smoothing: antialiased; }

/* ── Page ──────────────────────────────────────────── */
.ai-page { padding: 2rem 2.5rem 3rem; animation: rise .4s var(--ease) both; }
@keyframes rise { from{opacity:0;transform:translateY(12px);}to{opacity:1;transform:translateY(0);} }

/* ── Breadcrumb ────────────────────────────────────── */
.ai-bc { display:flex; align-items:center; gap:.4rem; font-size:.75rem; font-weight:500; color:var(--t3); margin-bottom:1.5rem; }
.ai-bc a { color:var(--t3); text-decoration:none; transition:color var(--t); }
.ai-bc a:hover { color:var(--blue); }
.ai-bc-s { color:var(--t4); }

/* ── Header ────────────────────────────────────────── */
.ai-header { display:flex; align-items:flex-start; justify-content:space-between; gap:1.5rem; margin-bottom:2rem; flex-wrap:wrap; }
.ai-header-left h1 { font-size:1.6rem; font-weight:800; letter-spacing:-.03em; color:var(--t1); }
.ai-header-left p  { font-size:.83rem; color:var(--t3); margin-top:.3rem; }

/* ── Buttons ───────────────────────────────────────── */
.btn-ai { display:inline-flex; align-items:center; gap:.4rem; padding:.54rem 1.1rem; border-radius:var(--r2); font-family:var(--font); font-size:.81rem; font-weight:600; cursor:pointer; border:1px solid transparent; transition:all var(--t); text-decoration:none; white-space:nowrap; letter-spacing:-.01em; line-height:1; }
.btn-ai svg { flex-shrink:0; }
.btn-ai-primary { background:var(--blue); color:#fff; border-color:var(--blue); box-shadow:var(--sb); }
.btn-ai-primary:hover { background:var(--blue-d); color:#fff; text-decoration:none; transform:translateY(-1px); }
.btn-ai-ghost { background:var(--card); color:var(--t2); border-color:var(--border); box-shadow:var(--s1); }
.btn-ai-ghost:hover { background:var(--hover); color:var(--t1); border-color:var(--border-2); text-decoration:none; }
.btn-ai-danger-ghost { background:var(--rose-l); color:var(--rose); border-color:rgba(232,80,106,.2); }
.btn-ai-danger-ghost:hover { background:#fddde2; color:var(--rose); text-decoration:none; }
.btn-ai-warning { background:var(--amber-l); color:var(--amber); border-color:rgba(232,160,32,.2); }
.btn-ai-warning:hover { background:#ffefd4; color:var(--amber); text-decoration:none; }
.btn-ai-danger { background:var(--rose-l); color:var(--rose); border-color:rgba(232,80,106,.18); }
.btn-ai-danger:hover { background:#fddde2; color:var(--rose); text-decoration:none; }
.btn-ai-success { background:var(--green-l); color:#1aaa5e; border-color:rgba(40,199,111,.22); }
.btn-ai-success:hover { background:#d0f5e1; color:#1aaa5e; text-decoration:none; }
.btn-ai-sm { padding:.33rem .7rem; font-size:.74rem; }
.btn-zn-info { background: var(--violet-light); color: var(--violet); border-color: rgba(124,111,205,.2); }
.btn-zn-info:hover { background: #e8e5ff; color: var(--violet); text-decoration: none; }

/* ── Stat cards ────────────────────────────────────── */
.ai-stats { display:grid; grid-template-columns:repeat(auto-fill, minmax(180px,1fr)); gap:1rem; margin-bottom:2rem; }
.ai-stat {
    background:var(--card); border:1px solid var(--border);
    border-radius:var(--r4); padding:1.25rem 1.4rem;
    display:flex; align-items:center; gap:1rem;
    box-shadow:var(--s1); transition:all var(--t);
    animation:rise .5s var(--ease) both;
    position:relative; overflow:hidden;
}
.ai-stat::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; opacity:0; transition:opacity var(--t); border-radius:var(--r4) var(--r4) 0 0; }
.ai-stat:hover { box-shadow:var(--s3); transform:translateY(-2px); border-color:var(--border-2); }
.ai-stat:hover::before { opacity:1; }
.ai-stat:nth-child(1){animation-delay:.05s;} .ai-stat:nth-child(1)::before{background:var(--blue);}
.ai-stat:nth-child(2){animation-delay:.10s;} .ai-stat:nth-child(2)::before{background:var(--amber);}
.ai-stat:nth-child(3){animation-delay:.15s;} .ai-stat:nth-child(3)::before{background:var(--green);}
.ai-stat:nth-child(4){animation-delay:.20s;} .ai-stat:nth-child(4)::before{background:var(--violet);}
.ai-stat:nth-child(5){animation-delay:.25s;} .ai-stat:nth-child(5)::before{background:var(--rose);}

.stat-ico { width:42px; height:42px; border-radius:var(--r3); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.si-blue   { background:var(--blue-l);   color:var(--blue); }
.si-amber  { background:var(--amber-l);  color:var(--amber); }
.si-green  { background:var(--green-l);  color:var(--green); }
.si-violet { background:var(--violet-l); color:var(--violet); }
.si-rose   { background:var(--rose-l);   color:var(--rose); }

.stat-label { font-size:.7rem; font-weight:600; color:var(--t3); text-transform:uppercase; letter-spacing:.05em; }
.stat-value { font-size:1.65rem; font-weight:800; color:var(--t1); line-height:1.1; letter-spacing:-.04em; margin-top:.05rem; }

/* ── Filter bar ────────────────────────────────────── */
.ai-filters {
    background:var(--card);
    border:1px solid var(--border);
    border-radius:var(--r4);
    box-shadow:var(--s1);
    padding:1.1rem 1.4rem;
    margin-bottom:1.25rem;
    display:flex; align-items:flex-end; gap:.85rem; flex-wrap:wrap;
}
.ai-filter-grp { display:flex; flex-direction:column; gap:.35rem; min-width:160px; flex:1; }
.ai-filter-label { font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--t4); }

.ai-sel-wrap { position:relative; }
.ai-sel-wrap::after { content:''; position:absolute; right:.8rem; top:50%; transform:translateY(-50%); width:0; height:0; border-left:4px solid transparent; border-right:4px solid transparent; border-top:5px solid var(--t3); pointer-events:none; }
.ai-select {
    width:100%; padding:.52rem 2rem .52rem .85rem;
    border:1px solid var(--border); border-radius:var(--r2);
    background:var(--card); font-family:var(--font);
    font-size:.82rem; color:var(--t1);
    box-shadow:var(--s1); transition:all var(--t);
    outline:none; appearance:none; -webkit-appearance:none; cursor:pointer;
}
.ai-select:focus { border-color:var(--blue); box-shadow:0 0 0 3px var(--blue-m); }
.ai-filter-actions { display:flex; gap:.5rem; align-items:flex-end; flex-shrink:0; }

/* Active filter pills */
.ai-active-filters { display:flex; flex-wrap:wrap; gap:.4rem; margin-bottom:1.1rem; }
.ai-filter-pill {
    display:inline-flex; align-items:center; gap:.35rem;
    padding:.22rem .7rem; border-radius:20px;
    background:var(--blue-l); color:var(--blue);
    border:1px solid var(--blue-m);
    font-size:.74rem; font-weight:600;
}

/* ── Card ──────────────────────────────────────────── */
.ai-card { background:var(--card); border:1px solid var(--border); border-radius:var(--r5); box-shadow:var(--s2); overflow:hidden; }
.ai-card-hd { padding:1rem 1.6rem; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; gap:1rem; }
.ai-card-title { font-size:.87rem; font-weight:700; color:var(--t1); display:flex; align-items:center; gap:.5rem; }
.ai-pip { width:7px; height:7px; border-radius:50%; background:var(--blue); box-shadow:0 0 0 3px var(--blue-m); flex-shrink:0; }
.ai-count { font-size:.76rem; color:var(--t3); font-weight:500; }

/* ── Table ─────────────────────────────────────────── */
.ai-table { width:100%; border-collapse:collapse; }
.ai-table thead tr { border-bottom:1px solid var(--border); }
.ai-table th { padding:.8rem 1.2rem; font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.09em; color:var(--t4); text-align:left; background:var(--subtle); white-space:nowrap; }
.ai-table td { padding:.9rem 1.2rem; font-size:.83rem; color:var(--t2); border-bottom:1px solid var(--border); vertical-align:middle; }
.ai-table tbody tr { transition:background var(--t); }
.ai-table tbody tr:hover { background:var(--hover); }
.ai-table tbody tr:last-child td { border-bottom:none; }

/* Objet cell */
.objet-cell { display:flex; flex-direction:column; gap:.1rem; }
.objet-main { font-weight:700; color:var(--t1); font-size:.84rem; letter-spacing:-.01em; }
.objet-sub  { font-size:.73rem; color:var(--t3); }

/* Compte cell */
.compte-cell { display:flex; align-items:center; gap:.45rem; }
.compte-dot  { width:6px; height:6px; border-radius:50%; background:var(--teal); flex-shrink:0; }

/* Date cell */
.date-val { font-family:var(--mono); font-size:.78rem; color:var(--t2); }

/* Status badges */
.st-badge {
    display:inline-flex; align-items:center; gap:.28rem;
    padding:.22rem .65rem; border-radius:20px;
    font-size:.7rem; font-weight:600; border:1px solid transparent;
    white-space:nowrap;
}
.st-dot { width:5px; height:5px; border-radius:50%; flex-shrink:0; }

.st-planifie  { background:var(--blue-l);   color:var(--blue);   border-color:var(--blue-m); }
.st-planifie  .st-dot { background:var(--blue); }
.st-realise   { background:var(--amber-l);  color:var(--amber);  border-color:rgba(232,160,32,.22); }
.st-realise   .st-dot { background:var(--amber); }
.st-valide    { background:var(--green-l);  color:#1aaa5e;       border-color:rgba(40,199,111,.22); }
.st-valide    .st-dot { background:var(--green); }
.st-annule    { background:var(--subtle);   color:var(--t3);     border-color:var(--border); }
.st-annule    .st-dot { background:var(--t4); }
.st-default   { background:var(--subtle);   color:var(--t3);     border-color:var(--border); }

/* Type badge */
.type-badge { display:inline-block; padding:.18rem .55rem; border-radius:var(--r1); font-size:.69rem; font-weight:600; background:var(--violet-l); color:var(--violet); border:1px solid rgba(124,111,205,.2); }

/* Actions */
.actions-cell { display:flex; align-items:center; gap:.3rem; flex-wrap:wrap; }

/* Empty state */
.ai-empty { padding:4rem 2rem; text-align:center; }
.ai-empty-icon { width:52px; height:52px; border-radius:var(--r3); background:var(--subtle); border:1px solid var(--border); display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; color:var(--t4); }
.ai-empty h3 { font-size:.95rem; font-weight:700; color:var(--t2); }
.ai-empty p  { font-size:.82rem; color:var(--t3); margin-top:.3rem; }

/* Pagination */
.ai-pagination { padding:1rem 1.6rem; border-top:1px solid var(--border); background:var(--bg); display:flex; align-items:center; justify-content:flex-end; }

/* Responsive */
@media(max-width:768px) {
    .ai-page { padding:1.25rem 1rem 2rem; }
    .ai-header { flex-direction:column; gap:1rem; }
    .ai-stats { grid-template-columns:1fr 1fr; }
    .ai-filters { flex-direction:column; }
    .ai-filter-grp { min-width:100%; }
    .ai-table th, .ai-table td { padding:.7rem .9rem; }
    .objet-sub { display:none; }
}
@media(max-width:480px) { .ai-stats { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<div class="ai-page">

    {{-- Breadcrumb --}}
    <div class="ai-bc">
        <a href="{{ route('actions.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </a>
        <span class="ai-bc-s">›</span>
        <a href="#">Commercial</a>
        <span class="ai-bc-s">›</span>
        <span style="color:var(--t2);font-weight:600;">Actions</span>
    </div>

    {{-- Header --}}
    <div class="ai-header">
        <div class="ai-header-left">
            <h1>Actions commerciales</h1>
            <p>Gestion des actions et tâches planifiées</p>
        </div>
        @if(auth()->user()->role === 'delegue')
        <a href="{{ route('actions.create') }}" class="btn-ai btn-ai-primary">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouvelle action
        </a>
        @endif
    </div>

    {{-- Stat Cards --}}
    @php
        $col       = $actions->getCollection();
        $total     = $actions->total();
        $planifie  = $col->where('statut','planifie')->count();
        $realise   = $col->where('statut','realise')->count();
        $valide    = $col->where('statut','valide')->count();
        $annule    = $col->where('statut','annule')->count();
    @endphp
    

    {{-- Active filter pills --}}
    @if(request('statut') || request('type') || request('compte_id'))
    <div class="ai-active-filters">
        @if(request('statut'))
            <span class="ai-filter-pill">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Statut : {{ ucfirst(request('statut')) }}
            </span>
        @endif
        @if(request('type'))
            <span class="ai-filter-pill">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/></svg>
                Type : {{ ucfirst(request('type')) }}
            </span>
        @endif
        @if(request('compte_id'))
            <span class="ai-filter-pill">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                Compte filtré
            </span>
        @endif
    </div>
    @endif

    {{-- Filter bar --}}
    <div class="ai-filters">
        <form method="GET" action="{{ route('actions.index') }}"
              style="display:contents;">

            <div class="ai-filter-grp">
                <label class="ai-filter-label">Statut</label>
                <div class="ai-sel-wrap">
                    <select name="statut" class="ai-select">
                        <option value="">Tous statuts</option>
                        @foreach($statuts as $s)
                            <option value="{{ $s }}" {{ request('statut') == $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="ai-filter-grp">
                <label class="ai-filter-label">Type</label>
                <div class="ai-sel-wrap">
                    <select name="type" class="ai-select">
                        <option value="">Tous types</option>
                        @foreach($types as $t)
                            <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>
                                {{ ucfirst($t) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="ai-filter-grp">
                <label class="ai-filter-label">Compte</label>
                <div class="ai-sel-wrap">
                    <select name="compte_id" class="ai-select">
                        <option value="">Tous les comptes</option>
                        @foreach($comptes as $c)
                            <option value="{{ $c->id }}" {{ request('compte_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->etablissement }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="ai-filter-actions">
                <button type="submit" class="btn-ai btn-ai-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    Filtrer
                </button>
                <a href="{{ route('actions.index') }}" class="btn-ai btn-ai-danger-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    {{-- Table card --}}
    <div class="ai-card">
        <div class="ai-card-hd">
            <div class="ai-card-title">
                <span class="ai-pip"></span>
                Liste des actions
            </div>
            <span class="ai-count">
                {{ $actions->total() }} action{{ $actions->total() > 1 ? 's' : '' }}
                @if(request('statut') || request('type') || request('compte_id')) · filtrées @endif
            </span>
        </div>

        <div style="overflow-x:auto;">
            <table class="ai-table">
                <thead>
                    <tr>
                        <th>Objet</th>
                        <th>Compte</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actions as $a)
                    <tr>
                        {{-- Objet --}}
                        <td>
                            <div class="objet-cell">
                                <span class="objet-main">{{ $a->objet }}</span>
                                @if($a->lieu)
                                    <span class="objet-sub">
                                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        {{ $a->lieu }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        {{-- Compte --}}
                        <td>
                            <div class="compte-cell">
                                <span class="compte-dot"></span>
                                {{ $a->compte->etablissement }}
                            </div>
                        </td>

                        {{-- Date --}}
                        <td>
                            <span class="date-val">{{ $a->date_planification->format('d/m/Y') }}</span>
                        </td>

                        {{-- Statut --}}
                        <td>
                            @php
                                $stClass = match($a->statut) {
                                    'planifie' => 'st-planifie',
                                    'realise'  => 'st-realise',
                                    'valide'   => 'st-valide',
                                    'annule'   => 'st-annule',
                                    default    => 'st-default',
                                };
                            @endphp
                            <span class="st-badge {{ $stClass }}">
                                <span class="st-dot"></span>
                                {{ ucfirst($a->statut) }}
                            </span>
                        </td>

                        {{-- Type --}}
                        <td>
                            @if($a->type)
                                <span class="type-badge">{{ ucfirst($a->type) }}</span>
                            @else
                                <span style="color:var(--t4);">—</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div class="actions-cell">

                                {{-- View --}}
                                <a href="{{ route('actions.show', $a) }}" class="btn-ai btn-ai-sm btn-zn-info">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Voir
                                </a>

                                @if(in_array(auth()->user()->role, ['admin','rbo']) || (auth()->user()->role === 'delegue' && $a->delegue_id === auth()->id()))

                                    @if($a->statut === 'planifie')

                                        {{-- Edit --}}
                                        <a href="{{ route('actions.edit', $a) }}" class="btn-ai btn-ai-sm btn-ai-warning">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                                        </a>

                                        {{-- Réaliser --}}
                                        <form method="POST" action="{{ route('actions.realiser', $a) }}" style="display:inline;" onsubmit="return confirm('Marquer comme réalisée ?')">
                                            @csrf
                                            <button type="submit" class="btn-ai btn-ai-sm btn-ai-success">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                                Réaliser
                                            </button>
                                        </form>

                                        {{-- Annuler --}}
                                        <form method="POST" action="{{ route('actions.annuler', $a) }}" style="display:inline;" onsubmit="return confirm('Annuler cette action ?')">
                                            @csrf
                                            <button type="submit" class="btn-ai btn-ai-sm btn-ai-danger">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            </button>
                                        </form>

                                    @elseif($a->statut === 'realise' && in_array(auth()->user()->role, ['admin','rbo']))

                                        {{-- Valider --}}
                                        <form method="POST" action="{{ route('actions.valider', $a) }}" style="display:inline;" onsubmit="return confirm('Valider cette action ?')">
                                            @csrf
                                            <button type="submit" class="btn-ai btn-ai-sm btn-ai-success">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                                Valider
                                            </button>
                                        </form>

                                    @endif

                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="ai-empty">
                                <div class="ai-empty-icon">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                </div>
                                <h3>Aucune action trouvée</h3>
                                <p>{{ request('statut') || request('type') || request('compte_id') ? 'Aucun résultat pour les filtres sélectionnés.' : 'Commencez par créer une nouvelle action.' }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($actions->hasPages())
        <div class="ai-pagination">
            {{ $actions->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
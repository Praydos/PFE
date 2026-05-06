@extends('layouts.app')

@push('styles')
{{-- Include the full agenda CSS from your previous message --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ===== FULL AGENDA CSS (copy from your agenda view) ===== */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --bg:       #f5f6fa; --card:   #ffffff; --hover:  #f8f9fd; --subtle: #f0f2f8;
    --border:   #e4e7f0; --border-2:#d0d5e8;
    --blue:     #5b8dee; --blue-d: #3d6fd6; --blue-l: #eef3fd; --blue-m: #dce8fb;
    --teal:     #0cb8b6; --teal-l: #e6faf9;
    --violet:   #7c6fcd; --violet-l:#f0eeff;
    --amber:    #e8a020; --amber-l:#fff8ec;
    --rose:     #e8506a; --rose-l: #fef0f2;
    --green:    #28c76f; --green-l:#e8fbf0;
    --t1:#1a1f36; --t2:#525f7f; --t3:#9ba8c5; --t4:#bcc5dc;
    --r1:6px; --r2:8px; --r3:12px; --r4:16px; --r5:20px;
    --s1:0 1px 3px rgba(31,45,80,.06); --s2:0 2px 8px rgba(31,45,80,.08); --s3:0 8px 24px rgba(31,45,80,.10);
    --sb:0 4px 14px rgba(91,141,238,.32);
    --font:'DM Sans',sans-serif; --mono:'DM Mono',monospace;
    --ease:cubic-bezier(.4,0,.2,1); --t:.17s var(--ease);
}

body { font-family:var(--font); background:var(--bg); color:var(--t1); -webkit-font-smoothing:antialiased; }

/* ── Page ──────────────────────────────────────────── */
.ag-page { padding:2rem 2.5rem 3rem; animation:rise .4s var(--ease) both; }
@keyframes rise { from{opacity:0;transform:translateY(12px);}to{opacity:1;transform:translateY(0);} }

/* ── Breadcrumb ────────────────────────────────────── */
.ag-bc { display:flex; align-items:center; gap:.4rem; font-size:.75rem; font-weight:500; color:var(--t3); margin-bottom:1.5rem; }
.ag-bc a { color:var(--t3); text-decoration:none; transition:color var(--t); }
.ag-bc a:hover { color:var(--blue); }
.ag-bc-s { color:var(--t4); }

/* ── Header ────────────────────────────────────────── */
.ag-header { display:flex; align-items:flex-start; justify-content:space-between; gap:1.5rem; margin-bottom:2rem; flex-wrap:wrap; }
.ag-header-left h1 { font-size:1.6rem; font-weight:800; letter-spacing:-.03em; color:var(--t1); }
.ag-header-left p  { font-size:.83rem; color:var(--t3); margin-top:.3rem; }

/* ── Stat cards ────────────────────────────────────── */
.ag-stats { display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:1rem; margin-bottom:2rem; }
.ag-stat {
    background:var(--card); border:1px solid var(--border);
    border-radius:var(--r4); padding:1.2rem 1.35rem;
    display:flex; align-items:center; gap:.9rem;
    box-shadow:var(--s1); transition:all var(--t);
    animation:rise .5s var(--ease) both;
    position:relative; overflow:hidden;
}
.ag-stat::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; opacity:0; transition:opacity var(--t); border-radius:var(--r4) var(--r4) 0 0; }
.ag-stat:hover { box-shadow:var(--s3); transform:translateY(-2px); border-color:var(--border-2); }
.ag-stat:hover::before { opacity:1; }
.ag-stat:nth-child(1){animation-delay:.05s;} .ag-stat:nth-child(1)::before{background:var(--blue);}
.ag-stat:nth-child(2){animation-delay:.09s;} .ag-stat:nth-child(2)::before{background:var(--green);}
.ag-stat:nth-child(3){animation-delay:.13s;} .ag-stat:nth-child(3)::before{background:var(--violet);}
.ag-stat:nth-child(4){animation-delay:.17s;} .ag-stat:nth-child(4)::before{background:var(--teal);}
.ag-stat:nth-child(5){animation-delay:.21s;} .ag-stat:nth-child(5)::before{background:var(--amber);}

.stat-ico { width:38px; height:38px; border-radius:var(--r3); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.si-blue   { background:var(--blue-l);   color:var(--blue); }
.si-green  { background:var(--green-l);  color:var(--green); }
.si-violet { background:var(--violet-l); color:var(--violet); }
.si-teal   { background:var(--teal-l);   color:var(--teal); }
.si-amber  { background:var(--amber-l);  color:var(--amber); }

.stat-label { font-size:.68rem; font-weight:600; color:var(--t3); text-transform:uppercase; letter-spacing:.05em; }
.stat-value { font-size:1.5rem; font-weight:800; color:var(--t1); line-height:1.1; letter-spacing:-.04em; margin-top:.04rem; }

/* ── Toolbar row ───────────────────────────────────── */
.ag-toolbar { display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:1.1rem; flex-wrap:wrap; }

/* ── Tab bar ───────────────────────────────────────── */
.ag-tabs { display:flex; gap:.2rem; background:var(--card); border:1px solid var(--border); border-radius:var(--r3); padding:.3rem; box-shadow:var(--s1); flex-wrap:wrap; }
.ag-tab {
    padding:.44rem 1rem; border-radius:var(--r2);
    font-size:.78rem; font-weight:600; color:var(--t3);
    cursor:pointer; border:none; background:transparent;
    font-family:var(--font); transition:all var(--t);
    display:flex; align-items:center; gap:.35rem; white-space:nowrap;
}
.ag-tab:hover { color:var(--t1); background:var(--subtle); }
.ag-tab.active { background:var(--blue); color:#fff; box-shadow:var(--sb); }
.ag-tab .tab-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }

/* ── View toggle ───────────────────────────────────── */
.ag-view-toggle { display:flex; gap:.25rem; background:var(--card); border:1px solid var(--border); border-radius:var(--r2); padding:.25rem; box-shadow:var(--s1); }
.ag-view-btn { padding:.38rem .8rem; border-radius:6px; font-size:.77rem; font-weight:600; color:var(--t3); text-decoration:none; transition:all var(--t); display:flex; align-items:center; gap:.35rem; white-space:nowrap; }
.ag-view-btn:hover { color:var(--t1); background:var(--subtle); text-decoration:none; }
.ag-view-btn.active { background:var(--blue); color:#fff; box-shadow:var(--sb); }

/* ── Primary button (extension for create buttons) ─── */
.btn-ag-primary {
    background: var(--blue);
    color: #fff;
    border-color: var(--blue);
    box-shadow: var(--sb);
}
.btn-ag-primary:hover {
    background: var(--blue-d);
    border-color: var(--blue-d);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(91,141,238,.4);
    text-decoration: none;
}

/* ── Main card ─────────────────────────────────────── */
.ag-card { background:var(--card); border:1px solid var(--border); border-radius:var(--r5); box-shadow:var(--s2); overflow:hidden; }
.ag-card-hd { padding:1rem 1.6rem; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; gap:1rem; }
.ag-card-title { font-size:.87rem; font-weight:700; color:var(--t1); display:flex; align-items:center; gap:.5rem; }
.ag-pip { width:7px; height:7px; border-radius:50%; background:var(--blue); box-shadow:0 0 0 3px var(--blue-m); }
.ag-card-count { font-size:.75rem; color:var(--t3); font-weight:500; }
.ag-card-body { padding:1.5rem 1.6rem; }
.ag-card-footer { padding:1rem 1.6rem; border-top:1px solid var(--border); background:var(--bg); display:flex; justify-content:flex-end; gap:0.6rem; }

/* ── FullCalendar overrides ────────────────────────── */
.ag-calendar-wrap { padding:1.5rem; }

.fc { font-family:var(--font) !important; color:var(--t1) !important; }

/* Toolbar */
.fc .fc-toolbar.fc-header-toolbar { margin-bottom:1.25rem !important; flex-wrap:wrap; gap:.5rem; }
.fc .fc-toolbar-title { font-size:1rem !important; font-weight:700 !important; color:var(--t1) !important; letter-spacing:-.02em; }

/* Buttons */
.fc .fc-button { font-family:var(--font) !important; font-size:.78rem !important; font-weight:600 !important; padding:.38rem .9rem !important; border-radius:var(--r2) !important; border:1px solid var(--border) !important; background:var(--card) !important; color:var(--t2) !important; box-shadow:var(--s1) !important; transition:all var(--t) !important; }
.fc .fc-button:hover { background:var(--hover) !important; color:var(--t1) !important; border-color:var(--border-2) !important; }
.fc .fc-button-primary:not(:disabled).fc-button-active,
.fc .fc-button-primary:not(:disabled):active { background:var(--blue) !important; border-color:var(--blue) !important; color:#fff !important; box-shadow:var(--sb) !important; }
.fc .fc-button-primary:focus { box-shadow:0 0 0 3px var(--blue-m) !important; }

/* Table headers */
.fc .fc-col-header-cell { background:var(--subtle) !important; border-color:var(--border) !important; }
.fc .fc-col-header-cell-cushion { font-size:.73rem !important; font-weight:700 !important; text-transform:uppercase !important; letter-spacing:.07em !important; color:var(--t3) !important; text-decoration:none !important; padding:.55rem .5rem !important; }

/* Day cells */
.fc .fc-daygrid-day { background:var(--card) !important; border-color:var(--border) !important; transition:background var(--t); }
.fc .fc-daygrid-day:hover { background:var(--hover) !important; }
.fc .fc-daygrid-day.fc-day-today { background:var(--blue-l) !important; }
.fc .fc-daygrid-day-number { font-size:.78rem !important; font-weight:600 !important; color:var(--t2) !important; text-decoration:none !important; padding:.4rem .5rem !important; }
.fc .fc-day-today .fc-daygrid-day-number { color:var(--blue) !important; font-weight:800 !important; }

/* Events */
.fc .fc-event { border:none !important; border-radius:var(--r1) !important; font-size:.72rem !important; font-weight:600 !important; padding:.15rem .45rem !important; cursor:pointer; transition:opacity var(--t), transform var(--t); }
.fc .fc-event:hover { opacity:.85; transform:translateY(-1px); }
.fc .fc-event-title { font-weight:600 !important; }
.fc .fc-event-time { font-family:var(--mono) !important; font-size:.65rem !important; opacity:.8; }

/* Scrollbar */
.fc .fc-scroller::-webkit-scrollbar { width:4px; height:4px; }
.fc .fc-scroller::-webkit-scrollbar-thumb { background:var(--border); border-radius:4px; }

/* List view */
.fc .fc-list-event:hover td { background:var(--hover) !important; }
.fc .fc-list-event-title a { color:var(--t1) !important; font-weight:600 !important; font-size:.84rem !important; text-decoration:none !important; }
.fc .fc-list-day-cushion { background:var(--subtle) !important; font-size:.73rem !important; font-weight:700 !important; text-transform:uppercase !important; letter-spacing:.07em !important; color:var(--t3) !important; }
.fc .fc-list-table td { border-color:var(--border) !important; }
.fc .fc-list-empty { color:var(--t3) !important; font-size:.84rem !important; }

/* ── List / table view ─────────────────────────────── */
.ag-table { width:100%; border-collapse:collapse; }
.ag-table thead tr { border-bottom:1px solid var(--border); }
.ag-table th { padding:.8rem 1.2rem; font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.09em; color:var(--t4); text-align:left; background:var(--subtle); white-space:nowrap; }
.ag-table td { padding:.88rem 1.2rem; font-size:.83rem; color:var(--t2); border-bottom:1px solid var(--border); vertical-align:middle; }
.ag-table tbody tr { transition:background var(--t); }
.ag-table tbody tr:hover { background:var(--hover); }
.ag-table tbody tr:last-child td { border-bottom:none; }

/* Date cell */
.ag-date-cell { font-family:var(--mono); font-size:.78rem; color:var(--t2); }
.ag-time-cell { font-family:var(--mono); font-size:.76rem; color:var(--t3); }

/* Title cell */
.ag-title-cell a { color:var(--t1); font-weight:600; text-decoration:none; font-size:.84rem; letter-spacing:-.01em; }
.ag-title-cell a:hover { color:var(--blue); }

/* Type badges */
.ag-type-badge { display:inline-flex; align-items:center; gap:.25rem; padding:.18rem .6rem; border-radius:20px; font-size:.69rem; font-weight:600; border:1px solid transparent; white-space:nowrap; }
.type-action    { background:var(--blue-l);   color:var(--blue);   border-color:var(--blue-m); }
.type-examen    { background:var(--violet-l); color:var(--violet); border-color:rgba(124,111,205,.2); }
.type-formation { background:var(--teal-l);   color:#0a9997;       border-color:rgba(12,184,182,.2); }
.type-event     { background:var(--amber-l);  color:var(--amber);  border-color:rgba(232,160,32,.2); }
.type-specimen  { background:var(--green-l);  color:#1aaa5e;       border-color:rgba(40,199,111,.2); }
.type-task      { background:var(--subtle);   color:var(--t2);     border-color:var(--border); }
.type-default   { background:var(--subtle);   color:var(--t3);     border-color:var(--border); }

/* Compte cell */
.ag-compte { display:flex; align-items:center; gap:.35rem; font-size:.81rem; }
.ag-compte-dot { width:5px; height:5px; border-radius:50%; background:var(--teal); flex-shrink:0; }

/* Delegate cell */
.ag-dlg { display:flex; align-items:center; gap:.4rem; }
.ag-dlg-av { width:22px; height:22px; border-radius:50%; background:linear-gradient(135deg,#5b8dee,#6c63ff); display:flex; align-items:center; justify-content:center; font-size:.55rem; font-weight:700; color:#fff; flex-shrink:0; }

/* General button (ghost) */
.btn-ag { display:inline-flex; align-items:center; gap:.35rem; padding:.34rem .7rem; border-radius:var(--r2); font-family:var(--font); font-size:.75rem; font-weight:600; cursor:pointer; border:1px solid transparent; transition:all var(--t); text-decoration:none; white-space:nowrap; line-height:1; }
.btn-ag-ghost { background:var(--card); color:var(--t2); border-color:var(--border); box-shadow:var(--s1); }
.btn-ag-ghost:hover { background:var(--hover); color:var(--t1); border-color:var(--border-2); text-decoration:none; }

/* Empty state */
.ag-empty { padding:4rem 2rem; text-align:center; }
.ag-empty-icon { width:52px; height:52px; border-radius:var(--r3); background:var(--subtle); border:1px solid var(--border); display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; color:var(--t4); }
.ag-empty h3 { font-size:.95rem; font-weight:700; color:var(--t2); }
.ag-empty p  { font-size:.82rem; color:var(--t3); margin-top:.3rem; }

/* Pagination */
.ag-pagination { padding:1rem 1.6rem; border-top:1px solid var(--border); background:var(--bg); display:flex; align-items:center; justify-content:flex-end; }

/* Badges (from original style) */
.dr-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .22rem .65rem; border-radius: 20px;
    font-size: .7rem; font-weight: 600; white-space: nowrap;
}
.bd-green { background: var(--green-l); color: var(--green); }
.bd-amber { background: var(--amber-l); color: var(--amber); }
.bd-blue  { background: var(--blue-l);  color: var(--blue); }
.bd-teal  { background: var(--teal-l);  color: var(--teal); }
.bd-none  { background: var(--subtle);  color: var(--t3); }

/* Info grid (for show views) */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.info-item {
    font-size: 0.84rem;
    color: var(--t2);
    border-bottom: 1px solid var(--border);
    padding-bottom: 0.5rem;
}
.info-label {
    font-weight: 600;
    color: var(--t1);
    margin-right: 0.5rem;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}
hr { border: none; border-top: 1px solid var(--border); margin: 1rem 0; }

/* Responsive */
@media(max-width:768px) {
    .ag-page { padding:1.25rem 1rem 2rem; }
    .ag-header { flex-direction:column; gap:1rem; }
    .ag-stats { grid-template-columns:1fr 1fr; }
    .ag-toolbar { flex-direction:column; align-items:flex-start; }
    .ag-table th, .ag-table td { padding:.7rem .9rem; }
    .info-grid { grid-template-columns: 1fr; }
}
@media(max-width:480px) { .ag-stats { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<div class="ag-page">

    {{-- Breadcrumb --}}
    <div class="ag-bc">
        <a href="{{ route('taches.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="ag-bc-s">›</span>
        <a href="{{ route('taches.index') }}">Tâches</a>
        <span class="ag-bc-s">›</span>
        <span style="color:var(--t2);font-weight:600;">{{ $tache->objet }}</span>
    </div>

    {{-- Header --}}
    <div class="ag-header">
        <div class="ag-header-left">
            <h1>{{ $tache->objet }}</h1>
            <p>Détail de la tâche</p>
        </div>
    </div>

    {{-- Card --}}
    <div class="ag-card">
        <div class="ag-card-hd">
            <div class="ag-card-title">
                <span class="ag-pip"></span>
                Informations
            </div>
        </div>
        <div class="ag-card-body" style="padding:1.5rem 1.6rem;">
            <div class="info-grid">
                <div class="info-item"><span class="info-label">Objet</span> {{ $tache->objet }}</div>
                <div class="info-item"><span class="info-label">Description</span> {{ $tache->description ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Date</span> {{ $tache->date_planification->format('d/m/Y') }}</div>
                <div class="info-item"><span class="info-label">Date fin</span> {{ $tache->date_fin ? $tache->date_fin->format('d/m/Y') : '-' }}</div>
                <div class="info-item"><span class="info-label">Lieu</span> {{ $tache->lieu ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Toute la journée</span> {{ $tache->all_day ? 'Oui' : 'Non' }}</div>
                <div class="info-item">
                    <span class="info-label">Statut</span>
                    <span class="dr-badge {{ $tache->is_validated ? 'bd-green' : 'bd-amber' }}">
                        {{ $tache->is_validated ? 'Validée' : 'En attente' }}
                    </span>
                </div>
                <div class="info-item"><span class="info-label">Délégué</span> {{ $tache->delegate->prenom }} {{ $tache->delegate->nom }}</div>
                <div class="info-item"><span class="info-label">Contacts</span> {{ $tache->contactsList->pluck('prenom')->join(', ') ?: '-' }}</div>
            </div>
        </div>

        {{-- Buttons --}}
        {{-- cancel Recurence --}}
        {{-- Show only if this task is part of a recurring series --}}
        @if($tache->recurrence_frequence || $tache->parent_tache_id)
        <div style="margin-top:1.5rem; padding:1.25rem; background:#fef0f2; border:1px solid rgba(232,80,106,.2); border-radius:12px;">
            <div style="font-size:.85rem; font-weight:700; color:#b83450; margin-bottom:.75rem; display:flex; align-items:center; gap:.4rem;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/>
                    <path d="M3.5 9a9 9 0 0114.5-3.5L23 10M1 14l5 4.5A9 9 0 0020.5 15"/>
                </svg>
                Tâche récurrente — Annuler la récurrence
            </div>

            <form method="POST" action="{{ route('taches.cancelRecurrence', $tache) }}"
                onsubmit="return confirm('Confirmer la suppression des occurrences sélectionnées ?')">
                @csrf

                <div style="display:flex; flex-direction:column; gap:.5rem; margin-bottom:1rem;">
                    <label style="font-size:.82rem; color:#525f7f; display:flex; align-items:center; gap:.5rem; cursor:pointer;">
                        <input type="radio" name="scope" value="this_and_following" checked>
                        Supprimer <strong>cette occurrence et les suivantes</strong>
                    </label>
                    <label style="font-size:.82rem; color:#525f7f; display:flex; align-items:center; gap:.5rem; cursor:pointer;">
                        <input type="radio" name="scope" value="all">
                        Supprimer <strong>toute la série</strong> (toutes les occurrences)
                    </label>
                </div>

                <button type="submit"
                        style="display:inline-flex; align-items:center; gap:.35rem; padding:.45rem 1rem;
                            border-radius:8px; font-size:.8rem; font-weight:600; cursor:pointer;
                            background:#e8506a; color:#fff; border:none;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    Annuler la récurrence
                </button>
            </form>
        </div>
        @endif
        {{-- Buttons --}}
        <div class="ag-card-footer" style="padding:1rem 1.6rem; border-top:1px solid var(--border); background:var(--bg); display:flex; justify-content:flex-end; gap:0.6rem;">
            <a href="{{ route('taches.index') }}" class="btn-ag btn-ag-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour
            </a>
            @if(!$tache->is_validated && (auth()->user()->role === 'delegue' && $tache->delegue_id === auth()->id()))
                <a href="{{ route('taches.edit', $tache) }}" class="btn-ag btn-ag-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                    </svg>
                    Modifier
                </a>
            @endif
            @if(!$tache->is_validated && in_array(auth()->user()->role, ['admin','rbo']))
                <form method="POST" action="{{ route('taches.validate', $tache) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-ag btn-ag-primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Valider
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- No specific script needed for show view --}}
@endpush
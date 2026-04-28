@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
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

/* ── Main card ─────────────────────────────────────── */
.ag-card { background:var(--card); border:1px solid var(--border); border-radius:var(--r5); box-shadow:var(--s2); overflow:hidden; }
.ag-card-hd { padding:1rem 1.6rem; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; gap:1rem; }
.ag-card-title { font-size:.87rem; font-weight:700; color:var(--t1); display:flex; align-items:center; gap:.5rem; }
.ag-pip { width:7px; height:7px; border-radius:50%; background:var(--blue); box-shadow:0 0 0 3px var(--blue-m); }
.ag-card-count { font-size:.75rem; color:var(--t3); font-weight:500; }

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

/* Btn */
.btn-ag { display:inline-flex; align-items:center; gap:.35rem; padding:.34rem .7rem; border-radius:var(--r2); font-family:var(--font); font-size:.75rem; font-weight:600; cursor:pointer; border:1px solid transparent; transition:all var(--t); text-decoration:none; white-space:nowrap; line-height:1; }
.btn-ag-ghost { background:var(--card); color:var(--t2); border-color:var(--border); box-shadow:var(--s1); }
.btn-ag-ghost:hover { background:var(--hover); color:var(--t1); border-color:var(--border-2); text-decoration:none; }

/* Empty */
.ag-empty { padding:4rem 2rem; text-align:center; }
.ag-empty-icon { width:52px; height:52px; border-radius:var(--r3); background:var(--subtle); border:1px solid var(--border); display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; color:var(--t4); }
.ag-empty h3 { font-size:.95rem; font-weight:700; color:var(--t2); }
.ag-empty p  { font-size:.82rem; color:var(--t3); margin-top:.3rem; }

/* Pagination */
.ag-pagination { padding:1rem 1.6rem; border-top:1px solid var(--border); background:var(--bg); display:flex; align-items:center; justify-content:flex-end; }

/* Responsive */
@media(max-width:768px) {
    .ag-page { padding:1.25rem 1rem 2rem; }
    .ag-header { flex-direction:column; gap:1rem; }
    .ag-stats { grid-template-columns:1fr 1fr; }
    .ag-toolbar { flex-direction:column; align-items:flex-start; }
    .ag-table th, .ag-table td { padding:.7rem .9rem; }
}
@media(max-width:480px) { .ag-stats { grid-template-columns:1fr; } }



/* Primary button (blue background, white text, shadow) */
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

/* inhncing the drag and drop animation*/

/* ── Drag & Drop Enhancements ───────────────────── */

/* While dragging */
.fc-event.fc-event-dragging {
    opacity: 0.9 !important;
    transform: scale(1.03);
    box-shadow: 0 12px 30px rgba(0,0,0,0.18);
}

/* Mirror clone */
.fc .fc-event-mirror {
    transform: scale(1.03) translateY(2px);
    box-shadow: 0 16px 40px rgba(0,0,0,0.25);
}

/* Hover day highlight */
.fc-daygrid-day.fc-day-highlight {
    background: rgba(91,141,238,0.12) !important;
    transition: background 0.2s ease;
}

/* Drop bounce animation */
@keyframes dropBounce {
    0%   { transform: scale(1.05); }
    50%  { transform: scale(0.95); }
    100% { transform: scale(1); }
}

.fc-event.drop-animate {
    animation: dropBounce 0.25s ease;
}

/* Error shake */
@keyframes shake {
    0%,100% { transform: translateX(0); }
    25% { transform: translateX(-4px); }
    75% { transform: translateX(4px); }
}

.fc-event.shake {
    animation: shake 0.25s ease;
}

/* Toast */
.ag-toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #1a1f36;
    color: #fff;
    padding: 0.7rem 1rem;
    border-radius: 8px;
    font-size: 0.8rem;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.25s ease;
    z-index: 9999;
}

.ag-toast.show {
    opacity: 1;
    transform: translateY(0);
}
</style>
@endpush

@section('content')
<div class="ag-page">

    {{-- Breadcrumb --}}
    <div class="ag-bc">
        <a href="{{ route('agenda.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </a>
        <span class="ag-bc-s">›</span>
        <span style="color:var(--t2);font-weight:600;">Agenda</span>
    </div>

    {{-- Header --}}
    <div class="ag-header">
        <div class="ag-header-left">
            <h1>Agenda</h1>
            <p>Planning unifié des actions, examens, formations et événements</p>
        </div>
    </div>

   <div class="create-actions-toolbar" style="margin-bottom: 1rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <a href="{{ route('bss.create') }}" class="btn-ag btn-ag-primary">Nouveau BSS</a>
        <a href="{{ route('examens.create') }}" class="btn-ag btn-ag-primary">Nouvel examen</a>
        <a href="{{ route('actions.create') }}" class="btn-ag btn-ag-primary">Nouvelle action</a>
        <a href="{{ route('formations.create') }}" class="btn-ag btn-ag-primary">Nouvelle formation</a>
        <a href="{{ route('events.create') }}" class="btn-ag btn-ag-primary">Nouvel événement</a>
        <a href="{{ route('demandes-specimens.create') }}" class="btn-ag btn-ag-primary">Demande spéciale</a>
    </div>

    

    {{-- Toolbar: tabs + view toggle --}}
    <div class="ag-toolbar">

        {{-- Tab pills --}}
        <div class="ag-tabs">
            @php
                $tabDefs = [
                    'all'        => ['label'=>'Fusionné',   'dot'=>'#5b8dee'],
                    'tasks'      => ['label'=>'Tâches',     'dot'=>'#9ba8c5'],
                    'actions'    => ['label'=>'Actions',    'dot'=>'#5b8dee'],
                    'examens'    => ['label'=>'Examens',    'dot'=>'#7c6fcd'],
                    'formations' => ['label'=>'Formations', 'dot'=>'#0cb8b6'],
                    'events'     => ['label'=>'Événements', 'dot'=>'#e8a020'],
                    'specimens'  => ['label'=>'Spécimens',  'dot'=>'#28c76f'],
                ];
            @endphp
            @foreach($tabDefs as $key => $def)
            <button
                class="ag-tab {{ $tab === $key ? 'active' : '' }}"
                data-tab="{{ $key }}"
                type="button">
                <span class="tab-dot" style="background:{{ $tab === $key ? 'rgba(255,255,255,.6)' : $def['dot'] }};"></span>
                {{ $def['label'] }}
            </button>
            @endforeach
        </div>

        {{-- View toggle --}}
        <div class="ag-view-toggle">
            <a href="{{ route('agenda.index', ['view'=>'calendar','tab'=>$tab]) }}"
               class="ag-view-btn {{ $viewMode === 'calendar' ? 'active' : '' }}">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/></svg>
                Calendrier
            </a>
            <a href="{{ route('agenda.index', ['view'=>'list','tab'=>$tab]) }}"
               class="ag-view-btn {{ $viewMode === 'list' ? 'active' : '' }}">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                Liste
            </a>
        </div>
    </div>

    {{-- ═══ Calendar view ═══ --}}
    @if($viewMode === 'calendar')
    <div class="ag-card">
        <div class="ag-card-hd">
            <div class="ag-card-title">
                <span class="ag-pip"></span>
                Calendrier — {{ $tabDefs[$tab]['label'] ?? 'Fusionné' }}
            </div>
        </div>
        <div class="ag-calendar-wrap">
            <div id="calendar"></div>
        </div>
    </div>
    @endif

    {{-- ═══ List view ═══ --}}
    @if($viewMode === 'list')
    <div class="ag-card">
        <div class="ag-card-hd">
            <div class="ag-card-title">
                <span class="ag-pip"></span>
                {{ $tabDefs[$tab]['label'] ?? 'Tous les événements' }}
            </div>
            @if($viewMode === 'list')
            <span class="ag-card-count">{{ $events->total() }} événement{{ $events->total() > 1 ? 's' : '' }}</span>
            @endif
        </div>

        <div style="overflow-x:auto;">
            <table class="ag-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Compte</th>
                        <th>Délégué</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    @php
                        $typeClass = match($event['type'] ?? '') {
                            'actions'    => 'type-action',
                            'examens'    => 'type-examen',
                            'formations' => 'type-formation',
                            'events'     => 'type-event',
                            'specimens'  => 'type-specimen',
                            'tasks'      => 'type-task',
                            default      => 'type-default',
                        };
                        $typeLabel = match($event['type'] ?? '') {
                            'actions'    => 'Action',
                            'examens'    => 'Examen',
                            'formations' => 'Formation',
                            'events'     => 'Événement',
                            'specimens'  => 'Spécimen',
                            'tasks'      => 'Tâche',
                            default      => ucfirst($event['type'] ?? '—'),
                        };
                        $delegate  = $event['delegate'] ?? null;
                        $initials  = $delegate ? strtoupper(substr($delegate, 0, 1)) : '?';
                    @endphp
                    <tr>
                        <td><span class="ag-date-cell">{{ $event['date']->format('d/m/Y') }}</span></td>
                        <td><span class="ag-time-cell">{{ $event['heure'] ?? '—' }}</span></td>
                        <td class="ag-title-cell"><a href="{{ $event['url'] }}">{{ $event['title'] }}</a></td>
                        <td><span class="ag-type-badge {{ $typeClass }}">{{ $typeLabel }}</span></td>
                        <td>
                            @if($event['compte'] ?? null)
                                <div class="ag-compte">
                                    <span class="ag-compte-dot"></span>
                                    {{ $event['compte'] }}
                                </div>
                            @else
                                <span style="color:var(--t4);">—</span>
                            @endif
                        </td>
                        <td>
                            @if($delegate)
                                <div class="ag-dlg">
                                    <div class="ag-dlg-av">{{ $initials }}</div>
                                    <span style="font-size:.81rem;color:var(--t2);">{{ $delegate }}</span>
                                </div>
                            @else
                                <span style="color:var(--t4);">—</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ $event['url'] }}" class="btn-ag btn-ag-ghost">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                Voir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="ag-empty">
                                <div class="ag-empty-icon">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                </div>
                                <h3>Aucun événement</h3>
                                <p>Aucun événement à afficher pour cette période.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($events->hasPages())
        <div class="ag-pagination">{{ $events->withQueryString()->links() }}</div>
        @endif
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
(function () {
    const viewMode   = '{{ $viewMode }}';
    const currentTab = '{{ $tab }}';

    /* ── Toast helper ───────────────────────── */
    function showToast(message) {
        let toast = document.createElement('div');
        toast.className = 'ag-toast';
        toast.innerText = message;
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 50);

        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    }

    /* ── Tab switching ──────────────────────── */
    document.querySelectorAll('.ag-tab').forEach(btn => {
        btn.addEventListener('click', () => {
            window.location.href = '{{ route("agenda.index") }}?view=' + viewMode + '&tab=' + btn.dataset.tab;
        });
    });

    /* ── FullCalendar ───────────────────────── */
    if (viewMode === 'calendar') {
        const calendarEl = document.getElementById('calendar');
        if (!calendarEl) return;

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            events: '{{ route("agenda.events") }}?tab=' + currentTab,
            editable: true,
            droppable: true,


            /* ✅ FIXES */
            snapDuration: { days: 1 },
            allDayMaintainDuration: true,
            eventDragMinDistance: 5,
            fixedMirrorParent: document.body,

            eventDrop: function(info) {
                const el = info.el;

                // 🎯 Bounce animation
                el.classList.add('drop-animate');
                setTimeout(() => el.classList.remove('drop-animate'), 300);

                const event = info.event;
                const newDate = event.start.toISOString().slice(0, 10);
                const type = event.extendedProps.type;
                const id = event.id;

                if (!type || !id) {
                    el.classList.add('shake');
                    setTimeout(() => el.classList.remove('shake'), 300);
                    showToast("Impossible de modifier ❌");
                    info.revert();
                    return;
                }

                const baseUrl = '{{ url("/agenda/event") }}';

                fetch(`${baseUrl}/${type}/${id}/reschedule`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ new_date: newDate })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        el.classList.add('shake');
                        setTimeout(() => el.classList.remove('shake'), 300);

                        showToast(data.error || "Erreur lors du déplacement ❌");
                        info.revert();
                    } else {
                        showToast("Événement déplacé ✔");
                        calendar.refetchEvents();
                    }
                })
                .catch(() => {
                    el.classList.add('shake');
                    setTimeout(() => el.classList.remove('shake'), 300);

                    showToast("Erreur réseau ⚠");
                    info.revert();
                });
            },

            eventClick: function(info) {
                if (info.event.url) {
                    window.location.href = info.event.url;
                }
            },

            height: 'auto',
        });

        calendar.render();
    }
})();
</script>
@endpush
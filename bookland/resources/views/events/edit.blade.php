@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ===== EXACT STYLE PROVIDED (same as create view) ===== */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --bg-base:        #f5f6fa;
    --bg-card:        #ffffff;
    --bg-hover:       #f8f9fd;
    --bg-subtle:      #f0f2f8;
    --border:         #e4e7f0;
    --border-md:      #d0d5e8;
    --blue:           #5b8dee;
    --blue-dark:      #3d6fd6;
    --blue-light:     #eef3fd;
    --blue-mid:       #dce8fb;
    --amber:          #e8a020;
    --amber-light:    #fff8ec;
    --rose:           #e8506a;
    --rose-light:     #fef0f2;
    --text-primary:   #1a1f36;
    --text-secondary: #525f7f;
    --text-muted:     #9ba8c5;
    --text-hint:      #bcc5dc;
    --r-xs: 6px; --r-sm: 8px; --r-md: 12px; --r-lg: 16px; --r-xl: 20px;
    --shadow-xs:   0 1px 3px rgba(31,45,80,.06), 0 1px 2px rgba(31,45,80,.04);
    --shadow-sm:   0 2px 8px rgba(31,45,80,.08), 0 1px 3px rgba(31,45,80,.05);
    --shadow-blue: 0 4px 14px rgba(91,141,238,.35);
    --font: 'DM Sans', sans-serif;
    --ease: cubic-bezier(.4,0,.2,1);
    --t: .18s var(--ease);
}

body { font-family: var(--font); background: var(--bg-base); color: var(--text-primary); -webkit-font-smoothing: antialiased; }

/* ── Page: full-width, no centering ── */
.zn-page {
    padding: 2rem 2.5rem 3rem;
    animation: pageIn .4s var(--ease) both;
}
@keyframes pageIn {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Breadcrumb ── */
.zn-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.4rem; }
.zn-bc a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
.zn-bc a:hover { color: var(--blue); }
.zn-bc-sep { color: var(--text-hint); }
.zn-bc-cur { color: var(--text-secondary); }

/* ── Header ── */
.zn-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap; }
.zn-header-left h1 { font-size: 1.55rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.2; margin: 0; }
.zn-header-left p  { font-size: .83rem; color: var(--text-muted); margin-top: .3rem; }

/* ── Card: constrained width, NOT centered ── */
.zn-card {
    max-width: 780px;           /* constrain the form width */
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--r-xl);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}
.zn-card-header {
    padding: 1.1rem 1.6rem;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: .55rem;
    background: linear-gradient(to bottom, #fafbff, #fff);
}
.zn-card-title {
    font-size: .88rem; font-weight: 700;
    color: var(--text-primary); letter-spacing: -.01em;
    display: flex; align-items: center; gap: .55rem;
}
.title-pip {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--amber);
    box-shadow: 0 0 0 3px rgba(232,160,32,.2);
    flex-shrink: 0;
}
.zn-card-body { padding: 1.75rem 1.6rem; }

/* ── Footer ── */
.card-footer {
    padding: 1.1rem 1.6rem;
    border-top: 1px solid var(--border);
    background: var(--bg-base);
    display: flex; align-items: center;
    justify-content: flex-end;
    gap: .6rem;
}

/* ── Buttons ── */
.btn-zn {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .58rem 1.2rem; border-radius: var(--r-sm);
    font-family: var(--font); font-size: .83rem; font-weight: 600;
    cursor: pointer; border: 1px solid transparent;
    transition: all var(--t); text-decoration: none;
    white-space: nowrap; letter-spacing: -.01em; line-height: 1;
}
.btn-zn svg { flex-shrink: 0; }
.btn-zn-primary {
    background: var(--blue); color: #fff;
    border-color: var(--blue); box-shadow: var(--shadow-blue);
}
.btn-zn-primary:hover {
    background: var(--blue-dark); color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(91,141,238,.4);
    text-decoration: none;
}
.btn-zn-ghost {
    background: var(--bg-card); color: var(--text-secondary);
    border-color: var(--border); box-shadow: var(--shadow-xs);
}
.btn-zn-ghost:hover {
    background: var(--bg-hover); color: var(--text-primary);
    border-color: var(--border-md); text-decoration: none;
}
.btn-zn-danger { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.18); }
.btn-zn-danger:hover { background: #fddde2; color: var(--rose); text-decoration: none; }
.btn-zn-sm { padding: .38rem .72rem; font-size: .75rem; }

/* ── Form elements ── */
.frm-group { display: flex; flex-direction: column; gap: .45rem; margin-bottom: 1.25rem; }
.frm-group:last-of-type { margin-bottom: 0; }
.frm-label { font-size: .8rem; font-weight: 600; color: var(--text-secondary); letter-spacing: -.01em; }
.frm-label .req { color: var(--rose); margin-left: .2rem; }
.frm-input,
.frm-select {
    width: 100%; padding: .62rem .9rem;
    border: 1px solid var(--border); border-radius: var(--r-sm);
    background: var(--bg-card); font-family: var(--font);
    font-size: .84rem; color: var(--text-primary);
    box-shadow: var(--shadow-xs);
    transition: border-color var(--t), box-shadow var(--t);
    outline: none; appearance: none; -webkit-appearance: none;
}
.frm-input::placeholder { color: var(--text-hint); }
.frm-input:focus,
.frm-select:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px var(--blue-mid);
}
.frm-select-wrap { position: relative; }
.frm-select-wrap::after {
    content: ''; position: absolute; right: .9rem; top: 50%; transform: translateY(-50%);
    width: 0; height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-top: 5px solid var(--text-muted);
    pointer-events: none;
}
.frm-select { padding-right: 2.2rem; cursor: pointer; }

/* ── Info grid (for show views) ── */
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

/* ── Table ── */
.zn-table { width: 100%; border-collapse: collapse; }
.zn-table thead tr { border-bottom: 1px solid var(--border); }
.zn-table th {
    padding: .85rem 1.2rem; font-size: .69rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: var(--text-hint); text-align: left;
    background: var(--bg-base); white-space: nowrap;
}
.zn-table td { padding: .95rem 1.2rem; font-size: .83rem; color: var(--text-secondary); border-bottom: 1px solid var(--border); vertical-align: middle; }
.zn-table tbody tr:hover { background: #f8f9fd; }
.zn-table tbody tr:last-child td { border-bottom: none; }

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

/* Actions cell */
.actions-cell { display: flex; align-items: center; gap: .35rem; flex-wrap: wrap; }

/* Empty state */
.zn-empty { padding: 4rem 2rem; text-align: center; }
.zn-empty-icon { width: 52px; height: 52px; border-radius: var(--r-md); background: var(--bg-subtle); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--text-hint); }
.zn-empty h3 { font-size: .95rem; font-weight: 700; color: var(--text-secondary); }
.zn-empty p  { font-size: .82rem; color: var(--text-muted); margin-top: .3rem; }

/* Pagination */
.zn-pagination { padding: 1rem 1.5rem; border-top: 1px solid var(--border); background: var(--bg-card); display: flex; justify-content: center; gap: 0.3rem; flex-wrap: wrap; }
.zn-pagination .page-link {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 2rem; padding: 0.3rem 0.6rem; border-radius: var(--r-sm);
    background: var(--bg-card); border: 1px solid var(--border);
    color: var(--text-secondary); font-size: 0.8rem; text-decoration: none;
    transition: all var(--t);
}
.zn-pagination .page-link:hover { background: var(--bg-hover); border-color: var(--border-md); }
.zn-pagination .active .page-link { background: var(--blue); border-color: var(--blue); color: white; }
.zn-pagination .disabled .page-link { opacity: 0.5; pointer-events: none; }

/* Filter bar (for index views) */
.zn-search-bar {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow-xs);
}
.zn-filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
    min-width: 160px;
}
.zn-filter-label {
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--text-secondary);
    letter-spacing: 0.03em;
    text-transform: uppercase;
}
.zn-select {
    width: 100%;
    padding: 0.55rem 0.85rem;
    border: 1px solid var(--border);
    border-radius: var(--r-sm);
    background: var(--bg-card);
    font-family: var(--font);
    font-size: 0.82rem;
    color: var(--text-primary);
    box-shadow: var(--shadow-xs);
    transition: all var(--t);
    outline: none;
    cursor: pointer;
}
.zn-select:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px var(--blue-mid);
}
.filter-actions {
    display: flex;
    align-items: flex-end;
    gap: 0.6rem;
    flex-wrap: wrap;
}

/* Responsive */
@media (max-width: 768px) {
    .zn-page   { padding: 1.25rem 1rem 2rem; }
    .zn-card   { max-width: 100%; }
    .card-footer { flex-direction: column-reverse; }
    .btn-zn    { width: 100%; justify-content: center; }
    .zn-table th, .zn-table td { padding: .75rem .9rem; }
    .zn-search-bar form { flex-direction: column; align-items: stretch; }
    .filter-actions { margin-top: 0.5rem; justify-content: flex-end; }
    .info-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('events.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('events.index') }}">Événements</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Modifier</span>
    </div>

    {{-- Header --}}
    <div class="zn-header">
        <div class="zn-header-left">
            <h1>Modifier l'événement</h1>
            <p>Mettez à jour les informations</p>
        </div>
    </div>

    {{-- Card --}}
    <div class="zn-card">
        <div class="zn-card-header">
            <div class="zn-card-title">
                <span class="title-pip"></span>
                Formulaire
            </div>
        </div>

        <form method="POST" action="{{ route('events.update', $event) }}" novalidate>
            @csrf
            @method('PUT')
            <div class="zn-card-body">
                @include('events._form')
            </div>
            <div class="card-footer">
                <a href="{{ route('events.show', $event) }}" class="btn-zn btn-zn-ghost">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="btn-zn btn-zn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
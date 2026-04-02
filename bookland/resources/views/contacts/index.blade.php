@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
/* ── Reset ──────────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    /* Surface */
    --bg-base:       #f5f6fa;
    --bg-card:       #ffffff;
    --bg-hover:      #f8f9fd;
    --bg-subtle:     #f0f2f8;

    /* Borders */
    --border:        #e4e7f0;
    --border-md:     #d0d5e8;
    --border-focus:  #5b8dee;

    /* Brand */
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

    /* Text */
    --text-primary:   #1a1f36;
    --text-secondary: #525f7f;
    --text-muted:     #9ba8c5;
    --text-hint:      #bcc5dc;

    /* Radii */
    --r-xs: 6px;
    --r-sm: 8px;
    --r-md: 12px;
    --r-lg: 16px;
    --r-xl: 20px;

    /* Shadows */
    --shadow-xs: 0 1px 3px rgba(31,45,80,.06), 0 1px 2px rgba(31,45,80,.04);
    --shadow-sm: 0 2px 8px rgba(31,45,80,.08), 0 1px 3px rgba(31,45,80,.05);
    --shadow-md: 0 8px 24px rgba(31,45,80,.10), 0 2px 8px rgba(31,45,80,.06);
    --shadow-lg: 0 20px 48px rgba(31,45,80,.13), 0 6px 16px rgba(31,45,80,.07);
    --shadow-blue: 0 4px 14px rgba(91,141,238,.35);

    --font: 'DM Sans', sans-serif;
    --font-mono: 'DM Mono', monospace;
    --ease: cubic-bezier(.4,0,.2,1);
    --t: .18s var(--ease);
}

body { font-family: var(--font); background: var(--bg-base); color: var(--text-primary); -webkit-font-smoothing: antialiased; }

/* ── Page ────────────────────────────────────────────── */
.dr-page {
    padding: 2rem 2.5rem 3rem;
    animation: pageIn .4s var(--ease) both;
    /* Smooth reflow when sidebar collapses/expands */
    transition: padding var(--t);
}
@keyframes pageIn {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Breadcrumb ──────────────────────────────────────── */
.dr-breadcrumb {
    display: flex;
    align-items: center;
    gap: .4rem;
    font-size: .76rem;
    color: var(--text-muted);
    font-weight: 500;
    margin-bottom: 1.4rem;
}
.dr-breadcrumb a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
.dr-breadcrumb a:hover { color: var(--blue); }
.dr-breadcrumb-sep { color: var(--text-hint); }
.dr-breadcrumb-current { color: var(--text-secondary); }

/* ── Header ──────────────────────────────────────────── */
.dr-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1.5rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}
.dr-header-left h1 {
    font-size: 1.65rem;
    font-weight: 700;
    letter-spacing: -.03em;
    color: var(--text-primary);
    line-height: 1.15;
}
.dr-header-left p {
    font-size: .83rem;
    color: var(--text-muted);
    margin-top: .3rem;
}
.dr-header-actions { display: flex; gap: .5rem; align-items: center; flex-wrap: wrap; }

/* ── Buttons ─────────────────────────────────────────── */
.btn-dr {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .56rem 1.1rem;
    border-radius: var(--r-sm);
    font-family: var(--font);
    font-size: .82rem;
    font-weight: 600;
    cursor: pointer;
    border: 1px solid transparent;
    transition: all var(--t);
    text-decoration: none;
    white-space: nowrap;
    letter-spacing: -.01em;
    line-height: 1;
}
.btn-dr svg { flex-shrink: 0; }

.btn-dr-primary {
    background: var(--blue);
    color: #fff;
    border-color: var(--blue);
    box-shadow: var(--shadow-blue);
}
.btn-dr-primary:hover {
    background: var(--blue-dark);
    border-color: var(--blue-dark);
    color: #fff;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(91,141,238,.4);
}

.btn-dr-teal {
    background: var(--teal-light);
    color: var(--teal);
    border-color: rgba(12,184,182,.22);
}
.btn-dr-teal:hover {
    background: #d1f5f4;
    color: var(--teal);
    text-decoration: none;
    transform: translateY(-1px);
}

.btn-dr-ghost {
    background: var(--bg-card);
    color: var(--text-secondary);
    border-color: var(--border);
    box-shadow: var(--shadow-xs);
}
.btn-dr-ghost:hover {
    background: var(--bg-hover);
    color: var(--text-primary);
    border-color: var(--border-md);
    text-decoration: none;
}

.btn-dr-sm { padding: .38rem .72rem; font-size: .75rem; }

.btn-dr-warning { background: var(--amber-light); color: var(--amber); border-color: rgba(232,160,32,.2); }
.btn-dr-warning:hover { background: #ffefd4; color: var(--amber); text-decoration: none; }

.btn-dr-danger { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.18); }
.btn-dr-danger:hover { background: #fddde2; color: var(--rose); text-decoration: none; }

.btn-dr-info { background: var(--violet-light); color: var(--violet); border-color: rgba(124,111,205,.2); }
.btn-dr-info:hover { background: #e4deff; color: var(--violet); text-decoration: none; }

.btn-dr-danger-ghost { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.2); }
.btn-dr-danger-ghost:hover { background: #fddde2; color: var(--rose); text-decoration: none; }

/* ── Stat Cards ──────────────────────────────────────── */
.dr-stats {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}
.dr-stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    padding: 1.4rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.1rem;
    box-shadow: var(--shadow-xs);
    transition: all var(--t);
    animation: pageIn .5s var(--ease) both;
    position: relative;
    overflow: hidden;
}
.dr-stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    opacity: 0;
    transition: opacity var(--t);
    border-radius: var(--r-lg) var(--r-lg) 0 0;
}
.dr-stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); border-color: var(--border-md); }
.dr-stat-card:hover::before { opacity: 1; }
.dr-stat-card:nth-child(1) { animation-delay: .06s; }
.dr-stat-card:nth-child(2) { animation-delay: .12s; }
.dr-stat-card:nth-child(3) { animation-delay: .18s; }
.dr-stat-card:nth-child(1)::before { background: var(--blue); }
.dr-stat-card:nth-child(2)::before { background: var(--teal); }
.dr-stat-card:nth-child(3)::before { background: var(--violet); }

.dr-stat-icon {
    width: 46px; height: 46px;
    border-radius: var(--r-md);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.si-blue   { background: var(--blue-light);   color: var(--blue); }
.si-teal   { background: var(--teal-light);   color: var(--teal); }
.si-violet { background: var(--violet-light); color: var(--violet); }

.dr-stat-label { font-size: .72rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .05em; }
.dr-stat-value { font-size: 1.75rem; font-weight: 700; color: var(--text-primary); line-height: 1.1; letter-spacing: -.04em; margin-top: .08rem; }

/* ── Card ────────────────────────────────────────────── */
.dr-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--r-xl);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}
.dr-card-header {
    padding: 1.1rem 1.6rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}
.dr-card-title {
    font-size: .88rem;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: .55rem;
    letter-spacing: -.01em;
}
.title-pip {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: var(--blue);
    box-shadow: 0 0 0 3px var(--blue-mid);
}

/* ── Search bar ──────────────────────────────────────── */
.dr-search-bar {
    display: flex;
    align-items: center;
    gap: .6rem;
    margin-bottom: 1.25rem;
    flex-wrap: wrap;
}
.dr-search-wrap {
    position: relative;
    flex: 1;
    min-width: 220px;
    max-width: 380px;
}
.dr-search-wrap svg {
    position: absolute;
    left: .85rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    pointer-events: none;
    flex-shrink: 0;
}
.dr-search-input {
    width: 100%;
    padding: .56rem .9rem .56rem 2.35rem;
    border: 1px solid var(--border);
    border-radius: var(--r-sm);
    background: var(--bg-card);
    font-family: var(--font);
    font-size: .83rem;
    color: var(--text-primary);
    box-shadow: var(--shadow-xs);
    transition: all var(--t);
    outline: none;
}
.dr-search-input::placeholder { color: var(--text-muted); }
.dr-search-input:focus {
    border-color: var(--border-focus);
    box-shadow: 0 0 0 3px var(--blue-mid);
}

.search-pill {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .3rem .75rem;
    border-radius: 20px;
    background: var(--blue-light);
    color: var(--blue);
    border: 1px solid var(--blue-mid);
    font-size: .76rem;
    font-weight: 600;
}

/* ── Table ───────────────────────────────────────────── */
.dr-table { width: 100%; border-collapse: collapse; }
.dr-table thead tr { border-bottom: 1px solid var(--border); }
.dr-table th {
    padding: .85rem 1.6rem;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--text-hint);
    text-align: left;
    background: var(--bg-base);
    white-space: nowrap;
}
.dr-table td {
    padding: 1rem 1.6rem;
    font-size: .84rem;
    color: var(--text-secondary);
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
}
.dr-table tbody tr { transition: background var(--t); }
.dr-table tbody tr:hover { background: #f8f9fd; }
.dr-table tbody tr:last-child td { border-bottom: none; }

/* ── Table scroll wrapper ────────────────────────────── */
/* Allows horizontal scroll on tight viewports (sidebar open/collapsed) */
.dr-table-scroll {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.dr-table-scroll::-webkit-scrollbar { height: 4px; }
.dr-table-scroll::-webkit-scrollbar-thumb { background: var(--border-md); border-radius: 4px; }

/* ── ID pill ─────────────────────────────────────────── */
.id-pill {
    display: inline-flex;
    align-items: center;
    padding: .18rem .55rem;
    border-radius: 20px;
    font-size: .71rem;
    font-weight: 700;
    font-family: var(--font-mono);
    background: var(--bg-subtle);
    color: var(--text-muted);
    border: 1px solid var(--border);
    letter-spacing: .02em;
}

/* ── User cell ───────────────────────────────────────── */
.user-cell { display: flex; align-items: center; gap: .85rem; }
.user-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .78rem;
    color: #fff; flex-shrink: 0; letter-spacing: .02em;
}
.av-a { background: linear-gradient(135deg, #5b8dee, #6c63ff); }
.av-b { background: linear-gradient(135deg, #0cb8b6, #00d4aa); }
.av-c { background: linear-gradient(135deg, #7c6fcd, #b06ab3); }
.av-d { background: linear-gradient(135deg, #e8a020, #f97316); }
.av-e { background: linear-gradient(135deg, #e8506a, #ff6b9d); }

.user-name  { font-weight: 600; color: var(--text-primary); font-size: .84rem; letter-spacing: -.01em; }
.user-email { font-size: .74rem; color: var(--text-muted); margin-top: .1rem; font-family: var(--font-mono); }

/* ── Ville cell ──────────────────────────────────────── */
.ville-cell {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
}
.ville-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: var(--teal);
    box-shadow: 0 0 0 2px var(--teal-light);
    flex-shrink: 0;
}

/* ── Badges ──────────────────────────────────────────── */
.dr-badge {
    display: inline-flex; align-items: center; gap: .28rem;
    padding: .2rem .6rem;
    border-radius: 20px;
    font-size: .71rem; font-weight: 600;
    border: 1px solid transparent;
    letter-spacing: .01em;
}
.dr-badge + .dr-badge { margin-left: .3rem; }
.bd-blue   { background: var(--blue-light);   color: var(--blue);   border-color: var(--blue-mid); }
.bd-teal   { background: var(--teal-light);   color: #0a9997;       border-color: rgba(12,184,182,.2); }
.bd-violet { background: var(--violet-light); color: var(--violet); border-color: rgba(124,111,205,.2); }
.bd-none   { background: var(--bg-subtle);    color: var(--text-muted); border-color: var(--border); }

/* ── Actions cell ────────────────────────────────────── */
.actions-cell { display: flex; align-items: center; gap: .35rem; flex-wrap: nowrap; }

/* ── Empty ───────────────────────────────────────────── */
.dr-empty { padding: 4rem 2rem; text-align: center; }
.dr-empty-icon {
    width: 52px; height: 52px;
    border-radius: var(--r-md);
    background: var(--bg-subtle);
    border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1rem; color: var(--text-hint);
}
.dr-empty h3 { font-size: .95rem; font-weight: 700; color: var(--text-secondary); }
.dr-empty p  { font-size: .82rem; color: var(--text-muted); margin-top: .3rem; }

/* ── Pagination ──────────────────────────────────────── */
.dr-pagination {
    padding: 1rem 1.6rem;
    border-top: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-base);
}

/* ── Modal overlay ───────────────────────────────────── */
.dr-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(26,31,54,.42);
    backdrop-filter: blur(6px);
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}
.dr-modal-overlay.visible {
    display: flex;
    animation: oIn .2s ease both;
}
@keyframes oIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}

.dr-modal {
    background: var(--bg-card);
    border: 1px solid var(--border-md);
    border-radius: var(--r-xl);
    width: 100%;
    max-width: 520px;
    box-shadow: var(--shadow-lg);
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
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    background: linear-gradient(to bottom, #fafbff, #fff);
}
.modal-icon {
    width: 40px; height: 40px;
    border-radius: var(--r-md);
    background: var(--blue-light);
    border: 1px solid var(--blue-mid);
    display: flex; align-items: center; justify-content: center;
    color: var(--blue);
    flex-shrink: 0;
}
.modal-title-grp { flex: 1; }
.modal-title-grp h2 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-primary);
    letter-spacing: -.02em;
}
.modal-title-grp p {
    font-size: .78rem;
    color: var(--text-muted);
    margin-top: .2rem;
}
.modal-close {
    width: 30px; height: 30px;
    border-radius: var(--r-xs);
    background: var(--bg-subtle);
    border: 1px solid var(--border);
    color: var(--text-muted);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: all var(--t);
    flex-shrink: 0;
}
.modal-close:hover {
    background: var(--rose-light);
    color: var(--rose);
    border-color: rgba(232,80,106,.2);
}

.dr-modal-body {
    padding: 1.25rem 1.6rem;
    max-height: 60vh;
    overflow-y: auto;
}
.dr-modal-body::-webkit-scrollbar { width: 4px; }
.dr-modal-body::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

.dr-modal-ft {
    padding: 1rem 1.6rem;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: flex-end;
    gap: .6rem;
    background: var(--bg-base);
}

/* ── Zone check items (used in modals) ───────────────── */
.zone-check {
    display: flex;
    align-items: center;
    gap: .85rem;
    padding: .8rem 1rem;
    border-radius: var(--r-sm);
    border: 1px solid var(--border);
    background: var(--bg-card);
    cursor: pointer;
    transition: all var(--t);
    margin-bottom: .45rem;
}
.zone-check:last-child { margin-bottom: 0; }
.zone-check:hover { border-color: var(--blue-mid); background: var(--blue-light); }
.zone-check:has(input:checked) {
    border-color: var(--blue);
    background: var(--blue-light);
    box-shadow: 0 0 0 3px var(--blue-mid);
}
.zone-check input[type="checkbox"] {
    width: 16px; height: 16px;
    accent-color: var(--blue);
    cursor: pointer; flex-shrink: 0;
}
.zc-icon {
    width: 32px; height: 32px;
    border-radius: var(--r-xs);
    background: var(--teal-light);
    border: 1px solid rgba(12,184,182,.2);
    display: flex; align-items: center; justify-content: center;
    color: var(--teal); flex-shrink: 0;
    transition: all var(--t);
}
.zone-check:has(input:checked) .zc-icon {
    background: var(--blue-light);
    border-color: var(--blue-mid);
    color: var(--blue);
}
.zc-label { font-size: .85rem; font-weight: 600; color: var(--text-primary); }
.zc-sub { font-size: .74rem; color: var(--text-muted); margin-top: .1rem; font-family: var(--font-mono); }

/* ── Spinner / Loading ───────────────────────────────── */
.dr-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .6rem;
    padding: 2.5rem;
    color: var(--text-muted);
    font-size: .84rem;
}
.dr-spinner {
    width: 18px; height: 18px;
    border: 2px solid var(--border-md);
    border-top-color: var(--blue);
    border-radius: 50%;
    animation: spin .7s linear infinite;
    flex-shrink: 0;
}
@keyframes spin { to { transform: rotate(360deg); } }

.dr-empty-message {
    text-align: center;
    padding: 2rem;
    color: var(--text-muted);
    font-size: .84rem;
}

/* ── Modal group separator ───────────────────────────── */
.modal-group-sep {
    display: flex;
    align-items: center;
    gap: .5rem;
    margin: .9rem 0 .55rem;
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: var(--text-hint);
}
.modal-group-sep::before,
.modal-group-sep::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
}
.modal-group-sep--first {
    margin-top: 0;
}

/* ── Responsive ──────────────────────────────────────── */

/* Medium screens – sidebar is full-width (260px) */
@media (max-width: 1100px) {
    .dr-page { padding: 1.75rem 1.75rem 2.5rem; }
}

/* Small screens / mobile */
@media (max-width: 768px) {
    .dr-page { padding: 1.25rem 1rem 2rem; }
    .dr-table th,
    .dr-table td { padding: .8rem 1rem; }
    .dr-header { flex-direction: column; gap: 1rem; }
    .dr-stats { grid-template-columns: 1fr 1fr; }
    .dr-card-header { flex-direction: column; align-items: flex-start; gap: .6rem; }
    /* Hide less-important columns on small screens */
    .dr-table .col-email { display: none; }
}

@media (max-width: 480px) {
    .dr-stats { grid-template-columns: 1fr; }
    .dr-search-wrap { min-width: 100%; max-width: 100%; }
    .dr-search-bar form { width: 100%; }
    /* Also hide phone col on very small screens */
    .dr-table .col-phone { display: none; }
}
</style>
@endpush

@section('content')
<div class="dr-page">

    {{-- Breadcrumb --}}
    <div class="dr-breadcrumb">
        <a href="{{ route('contacts.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </a>
        <span class="dr-breadcrumb-sep">›</span>
        <a href="#">Gestion</a>
        <span class="dr-breadcrumb-sep">›</span>
        <span class="dr-breadcrumb-current">Contacts</span>
    </div>

    {{-- Header --}}
    <div class="dr-header">
        <div class="dr-header-left">
            <h1>Contacts</h1>
            <p>Gérez vos contacts clients, fournisseurs et partenaires</p>
        </div>
        <div class="dr-header-actions">
            <a href="{{ route('contacts.create') }}" class="btn-dr btn-dr-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nouveau contact
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="dr-stats">
        <div class="dr-stat-card">
            <div class="dr-stat-icon si-blue">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <div class="dr-stat-label">Total contacts</div>
                <div class="dr-stat-value">{{ $contacts->total() }}</div>
            </div>
        </div>
        <div class="dr-stat-card">
            <div class="dr-stat-icon si-teal">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div>
                <div class="dr-stat-label">Page actuelle</div>
                <div class="dr-stat-value">{{ $contacts->currentPage() }}/{{ $contacts->lastPage() }}</div>
            </div>
        </div>
    </div>

    {{-- Search bar --}}
    <div class="dr-search-bar">
        <form method="GET" action="{{ route('contacts.index') }}" style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;width:100%;">
            <div class="dr-search-wrap">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="search" class="dr-search-input" placeholder="Rechercher nom, email, téléphone…" value="{{ request('search') }}" autocomplete="off">
            </div>
            <button type="submit" class="btn-dr btn-dr-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Filtrer
            </button>
            @if(request('search'))
                <span class="search-pill">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    «&nbsp;{{ request('search') }}&nbsp;»
                </span>
                <a href="{{ route('contacts.index') }}" class="btn-dr btn-dr-danger-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- Table card --}}
    <div class="dr-card">
        <div class="dr-card-header">
            <div class="dr-card-title">
                <span class="title-pip"></span>
                Liste des contacts
            </div>
            <span class="dr-badge bd-blue">{{ $contacts->total() }} contact{{ $contacts->total() > 1 ? 's' : '' }}</span>
        </div>

        <div class="dr-table-scroll">
            <table class="dr-table">
                <thead>
                    <tr>
                        {{-- <th>ID</th> --}}
                        <th>Nom complet</th>
                        {{-- <th class="col-email">Email</th> --}}
                        <th class="col-phone">Téléphone</th>
                        <th>Ville</th>
                        <th>Catégories</th>
                        <th>Cycles</th>
                        <th>Comptes</th>
                        @if (auth()->user()->role == 'admin')
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @forelse ($contacts as $contact)

                        @php
                            $categories = is_array($contact->categories)
                                ? $contact->categories
                                : json_decode($contact->categories, true);

                            $cycles = is_array($contact->cycles)
                                ? $contact->cycles
                                : json_decode($contact->cycles, true);
                        @endphp

                        <tr>

                            {{-- ID --}}
                            {{-- <td>
                                <span class="id-pill">{{ $contact->id }}</span>
                            </td> --}}

                            {{-- Nom --}}
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar av-a">
                                        {{ strtoupper(substr($contact->prenom,0,1) . substr($contact->nom,0,1)) }}
                                    </div>
                                    <div>
                                        <div class="user-name">{{ $contact->prenom }} {{ $contact->nom }}</div>
                                        <div class="user-email">{{ $contact->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Email --}}
                            {{-- <td class="col-email">{{ $contact->email ?? '-' }}</td> --}}

                            {{-- Téléphone --}}
                            <td class="col-phone">{{ $contact->telephone ?? '-' }}</td>

                            {{-- Ville --}}
                            <td>
                                <div class="ville-cell">
                                    <span class="ville-dot"></span>
                                    {{ $contact->ville->nom ?? '-' }}
                                </div>
                            </td>

                            {{-- Categories --}}
                            <td>
                                @if(empty($categories))
                                    <span class="dr-badge bd-none">Aucune catégorie</span>
                                @else
                                    <button
                                        class="btn-dr btn-dr-sm btn-dr-info show-categories-btn"
                                        data-contact="{{ $contact->prenom }} {{ $contact->nom }}"
                                        data-items='@json($categories)'
                                    >
                                        {{ count($categories) }} catégorie(s)
                                    </button>
                                @endif
                            </td>

                            {{-- Cycles --}}
                            <td>
                                @if(empty($cycles))
                                    <span class="dr-badge bd-none">Aucun cycle</span>
                                @else
                                    <button
                                        class="btn-dr btn-dr-sm btn-dr-info show-cycles-btn"
                                        data-contact="{{ $contact->prenom }} {{ $contact->nom }}"
                                        data-items='@json($cycles)'
                                    >
                                        {{ count($cycles) }} cycle(s)
                                    </button>
                                @endif
                            </td>

                            {{-- Comptes --}}
                            <td>
                                <div class="actions-cell">
                                    @if (auth()->user()->role !== 'admin')
                                        <button
                                        class="btn-dr btn-dr-sm btn-dr-ghost show-comptes-btn"
                                        data-contact-id="{{ $contact->id }}"
                                        data-contact-name="{{ $contact->prenom }} {{ $contact->nom }}"
                                    >
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Afficher
                                    </button>
                                    @endif

                                    @if(auth()->user()->role === 'admin')
                                        <button
                                            class="btn-dr btn-dr-sm btn-dr-primary manage-comptes-btn"
                                            data-contact-id="{{ $contact->id }}"
                                            data-contact-name="{{ $contact->prenom }} {{ $contact->nom }}"
                                        >
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                                            Gérer
                                        </button>
                                    @endif
                                </div>
                            </td>

                            {{-- Actions --}}
                            @if (auth()->user()->role == "admin")
                            <td>
                                <div class="actions-cell">
                                    <a
                                        href="{{ route('contacts.edit', $contact) }}"
                                        class="btn-dr btn-dr-sm btn-dr-warning"
                                        title="Modifier"
                                    >
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                                    </a>

                                    <form
                                        action="{{ route('contacts.destroy', $contact) }}"
                                        method="POST"
                                        style="display:inline"
                                        onsubmit="return confirm('Supprimer ce contact ?')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-dr btn-dr-sm btn-dr-danger" title="Supprimer">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                                
                            @endif

                        </tr>

                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="dr-empty">
                                    <div class="dr-empty-icon">
                                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    </div>
                                    <h3>Aucun contact trouvé</h3>
                                    <p>
                                        {{ request('search')
                                            ? 'Aucun résultat pour « '.request('search').' »'
                                            : 'Commencez par créer votre premier contact.'
                                        }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($contacts->hasPages())
            <div class="dr-pagination">
                {{ $contacts->withQueryString()->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>

</div>

{{-- ── Modal: Categories (view only) ──────────────────────────────────── --}}
<div class="dr-modal-overlay" id="modalCategories">
    <div class="dr-modal" role="dialog" aria-modal="true">
        <div class="dr-modal-hd">
            <div class="modal-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
            </div>
            <div class="modal-title-grp">
                <h2>Catégories du contact</h2>
                <p id="modalCategoriesSubtitle">—</p>
            </div>
            <button class="modal-close" id="modalCategoriesClose" aria-label="Fermer">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="dr-modal-body" id="modalCategoriesBody">
            <div class="dr-loading"><div class="dr-spinner"></div>Chargement…</div>
        </div>
        <div class="dr-modal-ft">
            <button class="btn-dr btn-dr-ghost" id="modalCategoriesCancel">Fermer</button>
        </div>
    </div>
</div>

{{-- ── Modal: Cycles (view only) ───────────────────────────────────────── --}}
<div class="dr-modal-overlay" id="modalCycles">
    <div class="dr-modal" role="dialog" aria-modal="true">
        <div class="dr-modal-hd">
            <div class="modal-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
            <div class="modal-title-grp">
                <h2>Cycles du contact</h2>
                <p id="modalCyclesSubtitle">—</p>
            </div>
            <button class="modal-close" id="modalCyclesClose" aria-label="Fermer">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="dr-modal-body" id="modalCyclesBody">
            <div class="dr-loading"><div class="dr-spinner"></div>Chargement…</div>
        </div>
        <div class="dr-modal-ft">
            <button class="btn-dr btn-dr-ghost" id="modalCyclesCancel">Fermer</button>
        </div>
    </div>
</div>

{{-- ── Modal: View assigned comptes (read-only) ────────────────────────── --}}
<div class="dr-modal-overlay" id="modalViewComptes">
    <div class="dr-modal" role="dialog" aria-modal="true">
        <div class="dr-modal-hd">
            <div class="modal-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </div>
            <div class="modal-title-grp">
                <h2>Comptes assignés</h2>
                <p id="modalViewComptesSubtitle">—</p>
            </div>
            <button class="modal-close" id="modalViewComptesClose" aria-label="Fermer">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="dr-modal-body" id="modalViewComptesBody">
            <div class="dr-loading"><div class="dr-spinner"></div>Chargement…</div>
        </div>
        <div class="dr-modal-ft">
            <button class="btn-dr btn-dr-ghost" id="modalViewComptesCancel">Fermer</button>
        </div>
    </div>
</div>

{{-- ── Modal: Manage comptes (with checkboxes) ─────────────────────────── --}}
<div class="dr-modal-overlay" id="modalManageComptes">
    <div class="dr-modal" style="max-width: 600px;" role="dialog" aria-modal="true">
        <div class="dr-modal-hd">
            <div class="modal-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <div class="modal-title-grp">
                <h2>Gérer les comptes du contact</h2>
                <p id="modalManageComptesSubtitle">—</p>
            </div>
            <button class="modal-close" id="modalManageComptesClose" aria-label="Fermer">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="dr-modal-body" id="modalManageComptesBody">
            <div class="dr-loading"><div class="dr-spinner"></div>Chargement…</div>
        </div>
        <div class="dr-modal-ft">
            <button class="btn-dr btn-dr-ghost" id="modalManageComptesCancel">Annuler</button>
            <button class="btn-dr btn-dr-primary" id="modalManageComptesSave">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                Enregistrer
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ── Helper functions ──────────────────────────────────────────────
    function closeModal(overlay) {
        overlay.classList.remove('visible');
        document.body.style.overflow = '';
    }

    function escapeHtml(text) {
        if (!text) return '';
        return text.replace(/&/g, "&amp;")
                   .replace(/</g, "&lt;")
                   .replace(/>/g, "&gt;")
                   .replace(/"/g, "&quot;")
                   .replace(/'/g, "&#39;");
    }

    // ── Categories modal ──────────────────────────────────────────────
    const catOverlay  = document.getElementById('modalCategories');
    const catClose    = document.getElementById('modalCategoriesClose');
    const catCancel   = document.getElementById('modalCategoriesCancel');
    const catSubtitle = document.getElementById('modalCategoriesSubtitle');
    const catBody     = document.getElementById('modalCategoriesBody');

    function openCatModal(contactName, items) {
        catSubtitle.textContent = contactName;
        catBody.innerHTML = !items.length
            ? '<p class="dr-empty-message">Aucune catégorie.</p>'
            : `<div>${items.map(item => `
                <div class="zone-check" style="cursor:default;">
                    <div class="zc-icon">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                    </div>
                    <div class="zc-label">${escapeHtml(item)}</div>
                </div>`).join('')}</div>`;
        catOverlay.classList.add('visible');
        document.body.style.overflow = 'hidden';
    }

    document.querySelectorAll('.show-categories-btn').forEach(btn => {
        btn.addEventListener('click', () => openCatModal(btn.dataset.contact, JSON.parse(btn.dataset.items)));
    });
    catClose.addEventListener('click',  () => closeModal(catOverlay));
    catCancel.addEventListener('click', () => closeModal(catOverlay));
    catOverlay.addEventListener('click', e => { if (e.target === catOverlay) closeModal(catOverlay); });

    // ── Cycles modal ──────────────────────────────────────────────────
    const cycleOverlay  = document.getElementById('modalCycles');
    const cycleClose    = document.getElementById('modalCyclesClose');
    const cycleCancel   = document.getElementById('modalCyclesCancel');
    const cycleSubtitle = document.getElementById('modalCyclesSubtitle');
    const cycleBody     = document.getElementById('modalCyclesBody');

    function openCycleModal(contactName, items) {
        cycleSubtitle.textContent = contactName;
        cycleBody.innerHTML = !items.length
            ? '<p class="dr-empty-message">Aucun cycle.</p>'
            : `<div>${items.map(item => `
                <div class="zone-check" style="cursor:default;">
                    <div class="zc-icon">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </div>
                    <div class="zc-label">${escapeHtml(item)}</div>
                </div>`).join('')}</div>`;
        cycleOverlay.classList.add('visible');
        document.body.style.overflow = 'hidden';
    }

    document.querySelectorAll('.show-cycles-btn').forEach(btn => {
        btn.addEventListener('click', () => openCycleModal(btn.dataset.contact, JSON.parse(btn.dataset.items)));
    });
    cycleClose.addEventListener('click',  () => closeModal(cycleOverlay));
    cycleCancel.addEventListener('click', () => closeModal(cycleOverlay));
    cycleOverlay.addEventListener('click', e => { if (e.target === cycleOverlay) closeModal(cycleOverlay); });

    // ── View assigned comptes (read-only) ─────────────────────────────
    const viewOverlay  = document.getElementById('modalViewComptes');
    const viewClose    = document.getElementById('modalViewComptesClose');
    const viewCancel   = document.getElementById('modalViewComptesCancel');
    const viewSubtitle = document.getElementById('modalViewComptesSubtitle');
    const viewBody     = document.getElementById('modalViewComptesBody');

    function openViewModal(contactId, contactName) {
        viewSubtitle.textContent = contactName;
        viewBody.innerHTML = '<div class="dr-loading"><div class="dr-spinner"></div>Chargement…</div>';
        viewOverlay.classList.add('visible');
        document.body.style.overflow = 'hidden';

        fetch(`/contacts/${contactId}/comptes`)
            .then(r => r.json())
            .then(data => {
                const assigned = (data.all_comptes || []).filter(c => c.assigned);
                if (!assigned.length) {
                    viewBody.innerHTML = '<p class="dr-empty-message">Aucun compte assigné.</p>';
                    return;
                }
                viewBody.innerHTML = `<div>${assigned.map(c => `
                    <div class="zone-check" style="cursor:default;">
                        <div class="zc-icon">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        </div>
                        <div><div class="zc-label">${escapeHtml(c.name)}</div></div>
                    </div>`).join('')}</div>`;
            })
            .catch(() => {
                viewBody.innerHTML = '<p class="dr-empty-message" style="color:var(--rose);">Erreur de chargement.</p>';
            });
    }

    document.querySelectorAll('.show-comptes-btn').forEach(btn => {
        btn.addEventListener('click', () => openViewModal(btn.dataset.contactId, btn.dataset.contactName));
    });
    viewClose.addEventListener('click',  () => closeModal(viewOverlay));
    viewCancel.addEventListener('click', () => closeModal(viewOverlay));
    viewOverlay.addEventListener('click', e => { if (e.target === viewOverlay) closeModal(viewOverlay); });

    // ── Manage comptes (with checkboxes) ──────────────────────────────
    const manageOverlay  = document.getElementById('modalManageComptes');
    const manageClose    = document.getElementById('modalManageComptesClose');
    const manageCancel   = document.getElementById('modalManageComptesCancel');
    const manageSave     = document.getElementById('modalManageComptesSave');
    const manageSubtitle = document.getElementById('modalManageComptesSubtitle');
    const manageBody     = document.getElementById('modalManageComptesBody');
    let currentManageContactId = null;

    function openManageModal(contactId, contactName) {
        currentManageContactId = contactId;
        manageSubtitle.textContent = contactName;
        manageBody.innerHTML = '<div class="dr-loading"><div class="dr-spinner"></div>Chargement…</div>';
        manageOverlay.classList.add('visible');
        document.body.style.overflow = 'hidden';

        fetch(`/contacts/${contactId}/comptes`)
            .then(r => r.json())
            .then(data => {
                const allComptes = data.all_comptes || [];
                if (!allComptes.length) {
                    manageBody.innerHTML = '<p class="dr-empty-message">Aucun compte disponible.</p>';
                    return;
                }

                // Sort: assigned first, then unassigned
                const selected   = allComptes.filter(c =>  c.assigned);
                const unselected = allComptes.filter(c => !c.assigned);

                const buildItem = compte => `
                    <label class="zone-check">
                        <input class="compte-checkbox" type="checkbox" value="${compte.id}" ${compte.assigned ? 'checked' : ''}>
                        <div class="zc-icon">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        </div>
                        <div><div class="zc-label">${escapeHtml(compte.name)}</div></div>
                    </label>`;

                const selectedHtml   = selected.map(buildItem).join('');
                const unselectedHtml = unselected.map(buildItem).join('');

                const separatorHtml = (selected.length && unselected.length) ? `
                    <div class="modal-group-sep">
                        <span>Non assignés</span>
                    </div>` : '';

                const assignedLabelHtml = selected.length ? `
                    <div class="modal-group-sep modal-group-sep--first">
                        <span>Assignés (${selected.length})</span>
                    </div>` : '';

                manageBody.innerHTML = `
                    <div class="dr-search-wrap" style="margin-bottom: 1rem;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" class="dr-search-input modal-search-input" placeholder="Rechercher un compte..." autocomplete="off">
                    </div>
                    <div id="modal-comptes-container">
                        ${assignedLabelHtml}
                        ${selectedHtml}
                        ${separatorHtml}
                        ${unselectedHtml}
                    </div>`;

                manageBody.querySelector('.modal-search-input').addEventListener('keyup', function() {
                    const term = this.value.toLowerCase();
                    manageBody.querySelectorAll('#modal-comptes-container .zone-check').forEach(el => {
                        el.style.display = el.innerText.toLowerCase().includes(term) ? '' : 'none';
                    });
                    // Hide separators when searching
                    manageBody.querySelectorAll('.modal-group-sep').forEach(sep => {
                        sep.style.display = term ? 'none' : '';
                    });
                });
            })
            .catch(() => {
                manageBody.innerHTML = '<p class="dr-empty-message" style="color:var(--rose);">Erreur de chargement.</p>';
            });
    }

    document.querySelectorAll('.manage-comptes-btn').forEach(btn => {
        btn.addEventListener('click', () => openManageModal(btn.dataset.contactId, btn.dataset.contactName));
    });

    manageSave.addEventListener('click', () => {
        const selectedIds = Array.from(manageBody.querySelectorAll('.compte-checkbox:checked')).map(cb => cb.value);
        manageSave.disabled = true;
        manageSave.innerHTML = '<div class="dr-spinner" style="width:13px;height:13px;border-width:2px;border-top-color:#fff;border-color:rgba(255,255,255,.35);"></div> Enregistrement…';

        fetch(`/contacts/${currentManageContactId}/comptes`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ compte_ids: selectedIds })
        })
        .then(r => { if (!r.ok) return r.text().then(t => { throw new Error(`HTTP ${r.status}: ${t}`); }); return r.json(); })
        .then(data => {
            if (data.success) { closeModal(manageOverlay); location.reload(); }
            else alert('Erreur : ' + (data.error || 'inconnue'));
        })
        .catch(err => { console.error(err); alert('Erreur réseau : ' + err.message); })
        .finally(() => {
            manageSave.disabled = false;
            manageSave.innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg> Enregistrer';
        });
    });

    manageClose.addEventListener('click',  () => closeModal(manageOverlay));
    manageCancel.addEventListener('click', () => closeModal(manageOverlay));
    manageOverlay.addEventListener('click', e => { if (e.target === manageOverlay) closeModal(manageOverlay); });

    // ── Global Escape key ─────────────────────────────────────────────
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            [catOverlay, cycleOverlay, viewOverlay, manageOverlay].forEach(o => {
                if (o.classList.contains('visible')) closeModal(o);
            });
        }
    });

});
</script>
@endpush
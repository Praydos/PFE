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
.si-blue   { background: var(--blue-light); color: var(--blue); }
.si-teal   { background: var(--teal-light); color: var(--teal); }
.si-violet { background: var(--violet-light); color: var(--violet); }

.dr-stat-label { font-size: .72rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .05em; }
.dr-stat-value { font-size: 1.75rem; font-weight: 700; color: var(--text-primary); line-height: 1.1; letter-spacing: -.04em; margin-top: .08rem; }

/* ── Tabs ────────────────────────────────────────────── */
.dr-tabs-bar {
    display: flex;
    align-items: center;
    margin-bottom: 1.25rem;
}
.dr-tabs {
    display: flex;
    gap: .2rem;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--r-md);
    padding: .3rem;
    box-shadow: var(--shadow-xs);
}
.dr-tab {
    padding: .5rem 1.2rem;
    border-radius: var(--r-sm);
    font-size: .82rem;
    font-weight: 600;
    color: var(--text-muted);
    cursor: pointer;
    border: none;
    background: transparent;
    font-family: var(--font);
    transition: all var(--t);
    display: flex;
    align-items: center;
    gap: .4rem;
    letter-spacing: -.01em;
}
.dr-tab .tc {
    font-size: .7rem;
    padding: .12rem .42rem;
    border-radius: 20px;
    background: var(--bg-subtle);
    color: var(--text-muted);
    font-weight: 700;
    transition: all var(--t);
    font-family: var(--font-mono);
}
.dr-tab:hover { color: var(--text-secondary); background: var(--bg-subtle); }
.dr-tab.active { background: var(--blue); color: #fff; box-shadow: var(--shadow-blue); }
.dr-tab.active .tc { background: rgba(255,255,255,.25); color: #fff; }

.dr-tab-pane { display: none; }
.dr-tab-pane.active { display: block; animation: pageIn .28s var(--ease) both; }

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
/* ── Search bar (matching villes, zones, users) ────── */
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
.dr-search-input::placeholder {
    color: var(--text-muted);
}
.dr-search-input:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px var(--blue-mid);
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

.actions-cell { display: flex; align-items: center; gap: .35rem; flex-wrap: wrap; }

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

/* ── Accordion ───────────────────────────────────────── */
.dr-accordion { display: flex; flex-direction: column; gap: .65rem; }
.dr-acc-item {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    overflow: hidden;
    box-shadow: var(--shadow-xs);
    transition: border-color var(--t), box-shadow var(--t);
}
.dr-acc-item.open { border-color: var(--blue-mid); box-shadow: var(--shadow-sm); }

.dr-acc-trigger {
    width: 100%; padding: 1.1rem 1.5rem;
    display: flex; align-items: center; gap: .9rem;
    background: transparent; border: none;
    cursor: pointer; font-family: var(--font); text-align: left;
    transition: background var(--t);
}
.dr-acc-trigger:hover { background: var(--bg-hover); }
.dr-acc-item.open .dr-acc-trigger { background: #fafbff; }

.rbo-av {
    width: 42px; height: 42px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .84rem; color: #fff;
    flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,.12);
}
.acc-meta { flex: 1; min-width: 0; }
.acc-name { font-size: .9rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.02em; }
.acc-sub  { font-size: .75rem; color: var(--text-muted); margin-top: .15rem; font-family: var(--font-mono); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.acc-chevron { color: var(--text-hint); transition: transform var(--t); flex-shrink: 0; }
.dr-acc-item.open .acc-chevron { transform: rotate(180deg); color: var(--blue); }

.dr-acc-body { display: none; border-top: 1px solid var(--border); background: #fafbff; }
.dr-acc-item.open .dr-acc-body { display: block; animation: pageIn .22s var(--ease) both; }

.dr-acc-actions {
    display: flex; gap: .5rem; flex-wrap: wrap;
    padding: 1rem 1.5rem .8rem;
    border-bottom: 1px solid var(--border);
    background: var(--bg-card);
}
.dr-zones-wrap { padding: 1rem 1.5rem; }

/* ── Section label ───────────────────────────────────── */
.sec-label {
    font-size: .68rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .1em;
    color: var(--text-hint);
    display: flex; align-items: center; gap: .5rem;
    margin-bottom: .75rem;
}
.sec-label::after { content: ''; flex: 1; height: 1px; background: var(--border); }

/* ── Zone Card ───────────────────────────────────────── */
.dr-zone-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--r-md);
    overflow: hidden; margin-bottom: .6rem;
    box-shadow: var(--shadow-xs);
    transition: border-color var(--t);
}
.dr-zone-card:last-child { margin-bottom: 0; }
.dr-zone-card:hover { border-color: var(--border-md); }

.dr-zone-hd {
    padding: .85rem 1.2rem;
    display: flex; align-items: center; justify-content: space-between;
    gap: .75rem; flex-wrap: wrap;
    background: var(--bg-subtle);
    border-bottom: 1px solid var(--border);
}
.zone-title-grp { display: flex; align-items: center; gap: .6rem; }
.zone-icon {
    width: 30px; height: 30px; border-radius: var(--r-xs);
    background: var(--teal-light); border: 1px solid rgba(12,184,182,.2);
    display: flex; align-items: center; justify-content: center; color: var(--teal);
}
.zone-name { font-size: .85rem; font-weight: 700; color: var(--text-primary); }

.dr-zone-dlg { padding: .6rem .9rem; }
.dlg-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: .55rem .7rem; border-radius: var(--r-sm); gap: .5rem; flex-wrap: wrap;
    transition: background var(--t);
}
.dlg-row:hover { background: var(--bg-subtle); }
.dlg-info { display: flex; align-items: center; gap: .6rem; }
.dlg-av {
    width: 28px; height: 28px; border-radius: 50%;
    background: linear-gradient(135deg, #5b8dee, #6c63ff);
    display: flex; align-items: center; justify-content: center;
    font-size: .62rem; font-weight: 700; color: #fff; flex-shrink: 0;
}
.dlg-name  { font-size: .82rem; font-weight: 600; color: var(--text-primary); }
.dlg-email { font-size: .72rem; color: var(--text-muted); font-family: var(--font-mono); }
.dlg-actions { display: flex; gap: .3rem; }

/* ── Modal ───────────────────────────────────────────── */
.dr-modal-overlay {
    position: fixed; inset: 0;
    background: rgba(26,31,54,.42);
    backdrop-filter: blur(6px);
    z-index: 1000;
    display: none; align-items: center; justify-content: center; padding: 1rem;
}
.dr-modal-overlay.visible { display: flex; animation: oIn .2s ease both; }
@keyframes oIn { from { opacity: 0; } to { opacity: 1; } }

.dr-modal {
    background: var(--bg-card);
    border: 1px solid var(--border-md);
    border-radius: var(--r-xl);
    width: 100%; max-width: 520px;
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
    display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem;
    background: linear-gradient(to bottom, #fafbff, #fff);
}
.modal-icon {
    width: 40px; height: 40px; border-radius: var(--r-md);
    background: var(--blue-light); border: 1px solid var(--blue-mid);
    display: flex; align-items: center; justify-content: center; color: var(--blue); flex-shrink: 0;
}
.modal-title-grp { flex: 1; }
.modal-title-grp h2 { font-size: 1rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.02em; }
.modal-title-grp p  { font-size: .78rem; color: var(--text-muted); margin-top: .2rem; }

.modal-close {
    width: 30px; height: 30px; border-radius: var(--r-xs);
    background: var(--bg-subtle); border: 1px solid var(--border);
    color: var(--text-muted); display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all var(--t); flex-shrink: 0;
}
.modal-close:hover { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.2); }

.dr-modal-body {
    padding: 1.25rem 1.6rem; max-height: 60vh; overflow-y: auto;
}
.dr-modal-body::-webkit-scrollbar { width: 4px; }
.dr-modal-body::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

.zone-check {
    display: flex; align-items: center; gap: .85rem;
    padding: .8rem 1rem; border-radius: var(--r-sm);
    border: 1px solid var(--border); background: var(--bg-card);
    cursor: pointer; transition: all var(--t); margin-bottom: .45rem;
}
.zone-check:last-child { margin-bottom: 0; }
.zone-check:hover { border-color: var(--blue-mid); background: var(--blue-light); }
.zone-check:has(input:checked) {
    border-color: var(--blue);
    background: var(--blue-light);
    box-shadow: 0 0 0 3px var(--blue-mid);
}
.zone-check input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--blue); cursor: pointer; flex-shrink: 0; }
.zc-icon {
    width: 32px; height: 32px; border-radius: var(--r-xs);
    background: var(--teal-light); border: 1px solid rgba(12,184,182,.2);
    display: flex; align-items: center; justify-content: center;
    color: var(--teal); flex-shrink: 0; transition: all var(--t);
}
.zone-check:has(input:checked) .zc-icon { background: var(--blue-light); border-color: var(--blue-mid); color: var(--blue); }
.zc-label { font-size: .85rem; font-weight: 600; color: var(--text-primary); }
.zc-sub   { font-size: .74rem; color: var(--text-muted); margin-top: .1rem; font-family: var(--font-mono); }

.dr-modal-ft {
    padding: 1rem 1.6rem;
    border-top: 1px solid var(--border);
    display: flex; justify-content: flex-end; gap: .6rem;
    background: var(--bg-base);
}

/* ── Spinner / Loading ───────────────────────────────── */
.dr-loading {
    display: flex; align-items: center; justify-content: center;
    gap: .6rem; padding: 2.5rem;
    color: var(--text-muted); font-size: .84rem;
}
.dr-spinner {
    width: 18px; height: 18px;
    border: 2px solid var(--border-md);
    border-top-color: var(--blue);
    border-radius: 50%;
    animation: spin .7s linear infinite; flex-shrink: 0;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Responsive ──────────────────────────────────────── */
@media (max-width: 768px) {
    .dr-page { padding: 1.25rem 1rem 2rem; }
    .dr-table th, .dr-table td { padding: .8rem 1rem; }
    .dr-header { flex-direction: column; gap: 1rem; }
    .dr-stats { grid-template-columns: 1fr 1fr; }
    .acc-sub { display: none; }
}
@media (max-width: 480px) {
    .dr-stats { grid-template-columns: 1fr; }
    .dr-tabs { width: 100%; }
    .dr-tab { flex: 1; justify-content: center; }
}
</style>
@endpush

@section('content')
<div class="dr-page">

    {{-- Breadcrumb --}}
    <div class="dr-breadcrumb">
        <a href="{{ route('users.roles') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </a>
        <span class="dr-breadcrumb-sep">›</span>
        <a href="{{ route('users.roles') }}">Gestion RH</a>
        <span class="dr-breadcrumb-sep">›</span>
        <span class="dr-breadcrumb-current">Délégués &amp; RBOs</span>
    </div>

    {{-- Header --}}
    <div class="dr-header">
        <div class="dr-header-left">
            <h1>Délégués &amp; RBOs</h1>
            <p>Gérez vos délégués commerciaux et responsables de bureaux opérationnels</p>
        </div>
        @if (auth()->user()->role == 'admin')
            <div class="dr-header-actions">
            <a href="{{ route('users.create') }}?role=delegue" class="btn-dr btn-dr-teal">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nouveau Délégué
            </a>
            <a href="{{ route('users.create') }}?role=rbo" class="btn-dr btn-dr-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nouveau RBO
            </a>
            <a href="{{ route('users.index') }}" class="btn-dr btn-dr-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Tous les utilisateurs
            </a>
        </div>
            
        @endif
    </div>

    {{-- Stat Cards
    <div class="dr-stats">
        @if (auth()->user()->role == 'rbo' || auth()->user()->role == 'admin')
            <div class="dr-stat-card">
                <div class="dr-stat-icon si-blue">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                </div>
                <div>
                    <div class="dr-stat-label">Délégués</div>
                    <div class="dr-stat-value">{{ $totalDelegues }}</div>
                </div>
            </div>
            <div class="dr-stat-card">
            <div class="dr-stat-icon si-violet">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <div class="dr-stat-label">Total équipe</div>
                <div class="dr-stat-value">{{ $totalDelegues + $totalRbos }}</div>
            </div>
        </div>
        @endif
        @if (auth()->user()->role == 'admin')
            <div class="dr-stat-card">
            <div class="dr-stat-icon si-teal">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
            </div>
            <div>
                <div class="dr-stat-label">RBOs</div>
                <div class="dr-stat-value">{{ $totalRbos }}</div>
            </div>
        </div>
        @endif
        
    </div> --}}

    {{-- Tabs --}}
    <div class="dr-tabs-bar">
        <div class="dr-tabs" role="tablist">
            <button class="dr-tab active" data-target="pane-delegues" role="tab" aria-selected="true">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                Délégués <span class="tc">{{ $delegues->count() }}</span>
            </button>
            @if (auth()->user()->role !== 'delegue')
                <button class="dr-tab" data-target="pane-rbos" role="tab" aria-selected="false">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                    RBOs <span class="tc">{{ $rbos->count() }}</span>
                </button>
            @endif
        </div>
    </div>

    {{-- ── Délégués pane ────────────────────────────── --}}
    <div class="dr-tab-pane active" id="pane-delegues">
        {{-- Search bar for delegates --}}
        <form method="GET" action="{{ route('users.roles') }}" style="display:flex; gap: .6rem; margin-bottom: 1rem;">
            <div class="dr-search-wrap" style="flex:1;">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="delegue_search" class="dr-search-input" placeholder="Rechercher un délégué..." value="{{ request('delegue_search') }}" autocomplete="off">
            </div>
            <button type="submit" class="btn-dr btn-dr-ghost">Filtrer</button>
            <input type="hidden" name="tab" value="delegues">
            @if(request('delegue_search'))
                <a href="{{ route('users.roles') }}?tab=delegues" class="btn-dr btn-dr-danger-ghost">Réinitialiser</a>
            @endif
        </form>
        @if($delegues->isEmpty())
            <div class="dr-card">
                <div class="dr-empty">
                    <div class="dr-empty-icon">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    </div>
                    <h3>Aucun délégué trouvé</h3>
                    <p>Commencez par créer votre premier délégué commercial.</p>
                </div>
            </div>
        @else
            <div class="dr-card">
                <div class="dr-card-header">
                    <div class="dr-card-title">
                        <span class="title-pip"></span>
                        Liste des délégués
                    </div>
                    <span class="dr-badge bd-blue">{{ $delegues->count() }} collaborateurs</span>
                </div>
                <div style="overflow-x:auto;">
                    <table class="dr-table">
                        <thead>
                            <tr>
                                <th>Collaborateur</th>
                                <th>Comptes assignés</th>
                                @if (auth()->user()->role !== 'admin')
                                    <th>Zones assignées</th>
                                @endif
                                
                                <th>RBO superviseur</th>
                                @if (auth()->user()->role === 'admin')
                                    <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php $avs = ['av-a','av-b','av-c','av-d','av-e']; @endphp
                            @foreach($delegues as $i => $delegue)
                            <tr>
                                {{-- user --}}
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar {{ $avs[$i % count($avs)] }}">
                                            {{ strtoupper(substr($delegue->prenom,0,1).substr($delegue->nom,0,1)) }}
                                        </div>
                                        <div>
                                            <div class="user-name">{{ $delegue->prenom }} {{ $delegue->nom }}</div>
                                            <div class="user-email">{{ $delegue->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                {{-- compte assignes --}}
                                <td>
                                    @php $comptesCount = $delegue->comptes->count(); @endphp
                                    <div style="display:flex; align-items:center; gap:.5rem; flex-wrap:wrap;">
                                        @if (auth()->user()->role !== 'admin')
                                            <span class="dr-badge bd-teal view-comptes-btn" style="cursor:pointer;" data-user-id="{{ $delegue->id }}" data-user-name="{{ $delegue->prenom }} {{ $delegue->nom }}">
                                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                            {{ $comptesCount }} compte{{ $comptesCount > 1 ? 's' : '' }}
                                        </span>
                                        @endif
                                        @if (auth()->user()->role == 'admin')
                                            <button class="btn-dr btn-dr-sm btn-dr-info assign-comptes-btn"
                                                data-user-id="{{ $delegue->id }}"
                                                data-user-name="{{ $delegue->prenom }} {{ $delegue->nom }}">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                            Gérer
                                        </button>
                                        @endif
                                    </div>
                                </td>
                                {{-- zones assignées --}}
                                @if(auth()->user()->role !== 'admin')
                                <td>
                                    <button class="btn-dr btn-dr-sm btn-dr-info view-zones-btn"
                                            data-user-id="{{ $delegue->id }}"
                                            data-user-name="{{ $delegue->prenom }} {{ $delegue->nom }}">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        Voir zones ({{ $delegue->zones->count() }})
                                    </button>
                                </td>

                                @endif
                                {{-- RBO superviseur --}}
                                <td>
                                    <span class="dr-badge bd-blue" style="white-space: normal;">
                                        {{ $delegue->supervising_rbos }}
                                    </span>
                                </td>

                                {{-- actions --}}
                                

                                @if (auth()->user()->role == 'admin')
                                <td>
                                    <div class="actions-cell">
                                        <a href="{{ route('users.edit', $delegue) }}" class="btn-dr btn-dr-sm btn-dr-warning">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                                            Modifier
                                        </a>
                                        <button class="btn-dr btn-dr-sm btn-dr-info assign-zones-btn"
                                                data-user-id="{{ $delegue->id }}"
                                                data-user-role="delegue"
                                                data-user-name="{{ $delegue->prenom }} {{ $delegue->nom }}">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                            Zones
                                        </button>
                                        <form action="{{ route('users.destroy', $delegue) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-dr btn-dr-sm btn-dr-danger" onclick="return confirm('Supprimer ce délégué ?')">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                    
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> 
            </div>
            {{-- pagination --}}
                    @if($delegues->hasPages())
                    <div class="dr-pagination">
                        {{ $delegues->appends(['delegue_search' => request('delegue_search'), 'tab' => 'delegues'])->links('vendor.pagination.custom') }}
                    </div>
                    @endif
        @endif
    </div>

    {{-- ── RBOs pane ────────────────────────────────── --}}
    <div class="dr-tab-pane" id="pane-rbos">
        {{-- Search bar for RBOs --}}
        <form method="GET" action="{{ route('users.roles') }}" style="display:flex; gap: .6rem; margin-bottom: 1rem;">
            <div class="dr-search-wrap" style="flex:1;">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="rbo_search" class="dr-search-input" placeholder="Rechercher un RBO..." value="{{ request('rbo_search') }}" autocomplete="off">
            </div>
            <button type="submit" class="btn-dr btn-dr-ghost">Filtrer</button>
            <input type="hidden" name="tab" value="rbos">
            @if(request('rbo_search'))
                <a href="{{ route('users.roles') }}?tab=rbos" class="btn-dr btn-dr-danger-ghost">Réinitialiser</a>
            @endif
        </form>
        @if($rbos->isEmpty())
            <div class="dr-card">
                <div class="dr-empty">
                    <div class="dr-empty-icon">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                    </div>
                    <h3>Aucun RBO trouvé</h3>
                    <p>Ajoutez votre premier responsable de bureau opérationnel.</p>
                </div>
            </div>
        @else
            <div class="dr-accordion" id="drAccordion">
                @php $rboAvs = ['av-a','av-b','av-c','av-d','av-e']; @endphp
                @foreach($rbos as $ri => $rbo)
                <div class="dr-acc-item {{ $ri === 0 ? 'open' : '' }}">
                    <button class="dr-acc-trigger" type="button" aria-expanded="{{ $ri === 0 ? 'true' : 'false' }}">
                        <div class="rbo-av {{ $rboAvs[$ri % count($rboAvs)] }}">
                            {{ strtoupper(substr($rbo->prenom,0,1).substr($rbo->nom,0,1)) }}
                        </div>
                        <div class="acc-meta">
                            <div class="acc-name">{{ $rbo->prenom }} {{ $rbo->nom }}</div>
                            <div class="acc-sub">{{ $rbo->email }}{{ $rbo->ville ? ' · '.$rbo->ville->nom : '' }}</div>
                        </div>
                        @if($rbo->zonesAsRbo->isNotEmpty())
                            <span class="dr-badge bd-teal" style="flex-shrink:0;">
                                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                {{ $rbo->zonesAsRbo->count() }} zones
                            </span>
                        @else
                            <span class="dr-badge bd-none" style="flex-shrink:0;">Aucune zone</span>
                        @endif
                        <svg class="acc-chevron" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>

                    <div class="dr-acc-body">
                        @if (auth()->user()->role == 'admin')
                        <div class="dr-acc-actions">
                            <a href="{{ route('users.edit', $rbo) }}" class="btn-dr btn-dr-sm btn-dr-warning">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                                Modifier
                            </a>
                            <button class="btn-dr btn-dr-sm btn-dr-info assign-zones-btn"
                                    data-user-id="{{ $rbo->id }}"
                                    data-user-role="rbo"
                                    data-user-name="{{ $rbo->prenom }} {{ $rbo->nom }}">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                Gérer zones
                            </button>
                            <button class="btn-dr btn-dr-sm btn-dr-info assign-villes-btn"
                                    data-user-id="{{ $rbo->id }}"
                                    data-user-name="{{ $rbo->prenom }} {{ $rbo->nom }}">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                Gérer villes
                            </button>
                            <form action="{{ route('users.destroy', $rbo) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-dr btn-dr-sm btn-dr-danger" onclick="return confirm('Supprimer ce RBO ?')">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                            
                        @endif

                        <div class="dr-zones-wrap">
                            @if($rbo->zonesAsRbo->isEmpty())
                                <p style="color:var(--text-muted);font-size:.82rem;text-align:center;padding:.5rem 0 .25rem;">Ce RBO ne supervise aucune zone pour l'instant.</p>
                            @else
                                <div class="sec-label">Zones supervisées</div>
                                @foreach($rbo->zonesAsRbo as $zone)
                                <div class="dr-zone-card">
                                    <div class="dr-zone-hd">
                                        <div class="zone-title-grp">
                                            <div class="zone-icon">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                            </div>
                                            <div class="zone-name">{{ $zone->name }}</div>
                                        </div>
                                        @if (auth()->user()->role == 'admin')
                                        <div style="display:flex;align-items:center;gap:.5rem;">
                                            <span class="dr-badge bd-blue">{{ $zone->ville->nom }}</span>
                                            <a href="{{ route('zones.edit', $zone) }}" class="btn-dr btn-dr-sm btn-dr-ghost">Gérer</a>
                                        </div>
                                            
                                        @endif
                                    </div>
                                    <div class="dr-zone-dlg">
                                        <div class="sec-label" style="font-size:.65rem;margin:.6rem 0 .4rem;">
                                            Délégués · {{ $zone->delegates->count() }}
                                        </div>
                                        @if($zone->delegates->isEmpty())
                                            <p style="color:var(--text-muted);font-size:.79rem;padding:.3rem .5rem;">Aucun délégué assigné à cette zone.</p>
                                        @else
                                            @foreach($zone->delegates as $delegue)
                                            <div class="dlg-row">
                                                <div class="dlg-info">
                                                    <div class="dlg-av">{{ strtoupper(substr($delegue->prenom,0,1).substr($delegue->nom,0,1)) }}</div>
                                                    <div>
                                                        <div class="dlg-name">{{ $delegue->prenom }} {{ $delegue->nom }}</div>
                                                        <div class="dlg-email">{{ $delegue->email }}</div>
                                                    </div>
                                                </div>
                                                @if(auth()->user()->role == 'admin')
                                                    <div class="dlg-actions">
                                                        {{-- Edit button – unchanged --}}
                                                        <a href="{{ route('users.edit', $delegue) }}" class="btn-dr btn-dr-sm btn-dr-warning">
                                                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                                                        </a>

                                                        {{-- Detach from zone – replaces the old delete form --}}
                                                        <form action="{{ route('zones.detachDelegate', ['zone' => $zone->id, 'delegate' => $delegue->id]) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn-dr btn-dr-sm btn-dr-danger" onclick="return confirm('Retirer ce délégué de la zone « {{ $zone->name }} » ?')">
                                                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            {{-- pagination --}}
            @if($rbos->hasPages())
            <div class="dr-pagination">
                {{ $rbos->appends(['rbo_search' => request('rbo_search'), 'tab' => 'rbos'])->links('vendor.pagination.custom') }}
            </div>
            @endif
        @endif
    </div>

</div>
{{-- ===================================================================================================== --}}
{{-- ── Modal ─────────────────────────────────────────── --}}
<div class="dr-modal-overlay" id="drModalOverlay">
    <div class="dr-modal" role="dialog" aria-modal="true" aria-labelledby="drModalTitle">
        <div class="dr-modal-hd">
            <div class="modal-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div class="modal-title-grp">
                <h2 id="drModalTitle">Assigner des zones</h2>
                <p id="drModalSubtitle">Sélectionnez les zones à attribuer</p>
            </div>
            <button class="modal-close" id="drModalClose" aria-label="Fermer">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="dr-modal-body" id="drModalBody">
            <div class="dr-loading"><div class="dr-spinner"></div>Chargement des zones…</div>
        </div>
        <div class="dr-modal-ft">
            <button class="btn-dr btn-dr-ghost" id="drModalCancel">Annuler</button>
            <button class="btn-dr btn-dr-primary" id="drModalSave">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                Enregistrer
            </button>
        </div>
    </div>
    
</div>
{{-- ── Modal for Zones ─────────────────────────────── --}}
{{-- <div class="dr-modal-overlay" id="drModalOverlay">
    <div class="dr-modal" role="dialog" aria-modal="true" aria-labelledby="drModalTitle">
        <div class="dr-modal-hd">...</div>
        <div class="dr-modal-body" id="drModalBody">...</div>
        <div class="dr-modal-ft">...</div>
    </div>
</div> --}}

{{-- ── Modal for Comptes ─────────────────────────────── --}}
<div class="dr-modal-overlay" id="drModalComptes">
    <div class="dr-modal" role="dialog" aria-modal="true" aria-labelledby="drModalComptesTitle">
        <div class="dr-modal-hd">
            <div class="modal-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <div class="modal-title-grp">
                <h2 id="drModalComptesTitle">Assigner des comptes clients</h2>
                <p id="drModalComptesSubtitle">Sélectionnez les comptes à attribuer</p>
            </div>
            <button class="modal-close" id="drModalComptesClose" aria-label="Fermer">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="dr-modal-body" id="drModalComptesBody">
            <div class="dr-loading"><div class="dr-spinner"></div>Chargement des comptes…</div>
        </div>
        <div class="dr-modal-ft">
            <button class="btn-dr btn-dr-ghost" id="drModalComptesCancel">Annuler</button>
            <button class="btn-dr btn-dr-primary" id="drModalComptesSave">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                Enregistrer
            </button>
        </div>
    </div>
</div>


{{-- ── Modal for viewing assigned comptes (delegate) ── --}}
<div class="dr-modal-overlay" id="drModalAssignedComptes">
    <div class="dr-modal" role="dialog" aria-modal="true" aria-labelledby="drModalAssignedComptesTitle">
        <div class="dr-modal-hd">
            <div class="modal-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <div class="modal-title-grp">
                <h2 id="drModalAssignedComptesTitle">Comptes assignés</h2>
                <p id="drModalAssignedComptesSubtitle"></p>
            </div>
            <button class="modal-close" id="drModalAssignedComptesClose" aria-label="Fermer">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="dr-modal-body" id="drModalAssignedComptesBody">
            <div class="dr-loading"><div class="dr-spinner"></div>Chargement…</div>
        </div>
        <div class="dr-modal-ft">
            <button class="btn-dr btn-dr-ghost" id="drModalAssignedComptesCancel">Fermer</button>
        </div>
    </div>
</div>

{{-- ── Modal for Villes (RBO) ─────────────────────────────── --}}
<div class="dr-modal-overlay" id="drModalVilles">
    <div class="dr-modal" role="dialog" aria-modal="true" aria-labelledby="drModalVillesTitle">
        <div class="dr-modal-hd">
            <div class="modal-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <div class="modal-title-grp">
                <h2 id="drModalVillesTitle">Assigner des villes</h2>
                <p id="drModalVillesSubtitle">Sélectionnez les villes que ce RBO supervisera</p>
            </div>
            <button class="modal-close" id="drModalVillesClose" aria-label="Fermer">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="dr-modal-body" id="drModalVillesBody">
            <div class="dr-loading"><div class="dr-spinner"></div>Chargement des villes…</div>
        </div>
        <div class="dr-modal-ft">
            <button class="btn-dr btn-dr-ghost" id="drModalVillesCancel">Annuler</button>
            <button class="btn-dr btn-dr-primary" id="drModalVillesSave">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                Enregistrer
            </button>
        </div>
    </div>
</div>
{{-- ── Modal for viewing assigned zones (delegate) ── ===============================================================--}}
<div class="dr-modal-overlay" id="drModalAssignedZones">
    <div class="dr-modal" role="dialog" aria-modal="true" aria-labelledby="drModalAssignedZonesTitle">
        <div class="dr-modal-hd">
            <div class="modal-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div class="modal-title-grp">
                <h2 id="drModalAssignedZonesTitle">Zones assignées</h2>
                <p id="drModalAssignedZonesSubtitle"></p>
            </div>
            <button class="modal-close" id="drModalAssignedZonesClose" aria-label="Fermer">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="dr-modal-body" id="drModalAssignedZonesBody">
            <div class="dr-loading"><div class="dr-spinner"></div>Chargement…</div>
        </div>
        <div class="dr-modal-ft">
            <button class="btn-dr btn-dr-ghost" id="drModalAssignedZonesCancel">Fermer</button>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
(function () {
    /* ── Tabs with persistence ────────────────────────── */
    const tabs = document.querySelectorAll('.dr-tab');
    const setActiveTab = (targetId) => {
        tabs.forEach(t => {
            t.classList.remove('active');
            t.setAttribute('aria-selected', 'false');
        });
        document.querySelectorAll('.dr-tab-pane').forEach(p => p.classList.remove('active'));
        const activeTab = Array.from(tabs).find(t => t.dataset.target === targetId);
        if (activeTab) {
            activeTab.classList.add('active');
            activeTab.setAttribute('aria-selected', 'true');
            document.getElementById(targetId).classList.add('active');
        }
    };

    tabs.forEach(tab => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = tab.dataset.target;
            setActiveTab(targetId);
            // Update URL parameter without reload
            const url = new URL(window.location.href);
            url.searchParams.set('tab', targetId === 'pane-delegues' ? 'delegues' : 'rbos');
            window.history.replaceState({}, '', url);
        });
    });

    // On page load, set active tab based on URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const activeTabParam = urlParams.get('tab');
    if (activeTabParam === 'rbos') {
        setActiveTab('pane-rbos');
    } else {
        setActiveTab('pane-delegues');
    }

    /* ── Accordion ───────────────────────────────────── */
    document.querySelectorAll('.dr-acc-trigger').forEach(btn => {
        btn.addEventListener('click', () => {
            const item = btn.closest('.dr-acc-item');
            const isOpen = item.classList.contains('open');
            document.querySelectorAll('.dr-acc-item').forEach(i => {
                i.classList.remove('open');
                i.querySelector('.dr-acc-trigger').setAttribute('aria-expanded','false');
            });
            if (!isOpen) {
                item.classList.add('open');
                btn.setAttribute('aria-expanded','true');
            }
        });
    });

    /* ── Modal for Zones ─────────────────────────────── */
    let currentUserId = null;
    const overlay   = document.getElementById('drModalOverlay');
    const modalBody = document.getElementById('drModalBody');
    const subtitle  = document.getElementById('drModalSubtitle');

    const openModal  = () => { overlay.classList.add('visible'); document.body.style.overflow = 'hidden'; };
    const closeModal = () => { overlay.classList.remove('visible'); document.body.style.overflow = ''; };

    document.getElementById('drModalClose').addEventListener('click', closeModal);
    document.getElementById('drModalCancel').addEventListener('click', closeModal);
    overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    document.querySelectorAll('.assign-zones-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            currentUserId = btn.dataset.userId;
            subtitle.textContent = `${btn.dataset.userName} · ${btn.dataset.userRole.toUpperCase()}`;
            modalBody.innerHTML = '<div class="dr-loading"><div class="dr-spinner"></div>Chargement des zones…</div>';
            openModal();

            fetch(`/users/${currentUserId}/zones`)
                .then(r => r.json())
                .then(data => {
                    if (!data.all_zones.length) {
                        modalBody.innerHTML = '<p style="color:var(--text-muted);text-align:center;padding:2.5rem;font-size:.84rem;">Aucune zone disponible.</p>';
                        return;
                    }
                    const items = data.all_zones.map(zone => {
                        const checked = data.assigned_ids.includes(zone.id) ? 'checked' : '';
                        return `
                        <label class="zone-check">
                            <input class="zone-checkbox" type="checkbox" value="${zone.id}" ${checked}>
                            <div class="zc-icon">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div>
                                <div class="zc-label">${zone.name}</div>
                                <div class="zc-sub">${zone.ville.nom}</div>
                            </div>
                        </label>`;
                    }).join('');
                    modalBody.innerHTML = `
                        <div class="dr-search-wrap" style="margin-bottom: 1rem;">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" class="dr-search-input modal-search-input" placeholder="Rechercher une zone..." autocomplete="off">
                        </div>
                        <div id="modal-items-container">${items}</div>
                    `;
                    const searchInput = modalBody.querySelector('.modal-search-input');
                    searchInput.addEventListener('keyup', function() {
                        const term = this.value.toLowerCase();
                        const labels = modalBody.querySelectorAll('#modal-items-container .zone-check');
                        labels.forEach(label => {
                            const text = label.innerText.toLowerCase();
                            label.style.display = text.includes(term) ? '' : 'none';
                        });
                    });
                })
                .catch(() => {
                    modalBody.innerHTML = '<p style="color:var(--rose);text-align:center;padding:2rem;font-size:.84rem;">Erreur lors du chargement des zones.</p>';
                });
        });
    });

    document.getElementById('drModalSave').addEventListener('click', function () {
        const selected = [...document.querySelectorAll('.zone-checkbox:checked')].map(cb => cb.value);
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<div class="dr-spinner" style="width:13px;height:13px;border-width:2px;border-top-color:#fff;border-color:rgba(255,255,255,.35);"></div> Enregistrement…';

        fetch(`/users/${currentUserId}/zones`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ zone_ids: selected })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { closeModal(); location.reload(); }
            else { alert('Erreur : ' + (data.error || 'inconnue')); }
        })
        .catch(() => alert('Erreur réseau.'))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg> Enregistrer';
        });
    });

    /* ── Modal for Comptes ───────────────────────────── */
    let currentComptesUserId = null;
    const comptesOverlay = document.getElementById('drModalComptes');
    const comptesModalBody = document.getElementById('drModalComptesBody');
    const comptesSubtitle = document.getElementById('drModalComptesSubtitle');

    function openComptesModal() {
        comptesOverlay.classList.add('visible');
        document.body.style.overflow = 'hidden';
    }
    function closeComptesModal() {
        comptesOverlay.classList.remove('visible');
        document.body.style.overflow = '';
    }

    document.getElementById('drModalComptesClose')?.addEventListener('click', closeComptesModal);
    document.getElementById('drModalComptesCancel')?.addEventListener('click', closeComptesModal);
    comptesOverlay?.addEventListener('click', e => { if (e.target === comptesOverlay) closeComptesModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeComptesModal(); });

    document.querySelectorAll('.assign-comptes-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            currentComptesUserId = btn.dataset.userId;
            comptesSubtitle.textContent = btn.dataset.userName;
            comptesModalBody.innerHTML = '<div class="dr-loading"><div class="dr-spinner"></div>Chargement des comptes…</div>';
            openComptesModal();

            fetch(`/users/${currentComptesUserId}/comptes`)
                .then(r => r.json())
                .then(data => {
                    if (!data.all_comptes.length) {
                        comptesModalBody.innerHTML = '<p style="color:var(--text-muted);text-align:center;padding:2.5rem;font-size:.84rem;">Aucun compte client disponible.</p>';
                        return;
                    }
                    const items = data.all_comptes.map(compte => {
                        const checked = data.assigned_ids.includes(compte.id) ? 'checked' : '';
                        const clientName = compte.etablissement || 'Sans nom';
                        const type = compte.type ? `(${compte.type})` : '';
                        let location = '—';
                        if (compte.quartier) {
                            const villeName = compte.quartier.zone?.ville?.nom || '?';
                            location = `${compte.quartier.nom} (${villeName})`;
                        } else if (compte.ville) {
                            location = compte.ville.nom;
                        } else if (compte.zone) {
                            location = compte.zone.name;
                        }
                        return `
                        <label class="zone-check">
                            <input class="compte-checkbox" type="checkbox" value="${compte.id}" ${checked}>
                            <div class="zc-icon">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            </div>
                            <div>
                                <div class="zc-label">${clientName} ${type}</div>
                                <div class="zc-sub">${location}</div>
                            </div>
                        </label>`;
                    }).join('');
                    comptesModalBody.innerHTML = `
                        <div class="dr-search-wrap" style="margin-bottom: 1rem;">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" class="dr-search-input modal-search-input" placeholder="Rechercher un compte..." autocomplete="off">
                        </div>
                        <div id="modal-items-container">${items}</div>
                    `;
                    const searchInput = comptesModalBody.querySelector('.modal-search-input');
                    searchInput.addEventListener('keyup', function() {
                        const term = this.value.toLowerCase();
                        const labels = comptesModalBody.querySelectorAll('#modal-items-container .zone-check');
                        labels.forEach(label => {
                            const text = label.innerText.toLowerCase();
                            label.style.display = text.includes(term) ? '' : 'none';
                        });
                    });
                })
                .catch(() => {
                    comptesModalBody.innerHTML = '<p style="color:var(--rose);text-align:center;padding:2rem;font-size:.84rem;">Erreur lors du chargement des comptes.</p>';
                });
        });
    });

    document.getElementById('drModalComptesSave')?.addEventListener('click', function () {
        const selected = [...document.querySelectorAll('.compte-checkbox:checked')].map(cb => cb.value);
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<div class="dr-spinner" style="width:13px;height:13px;border-width:2px;border-top-color:#fff;border-color:rgba(255,255,255,.35);"></div> Enregistrement…';

        fetch(`/users/${currentComptesUserId}/comptes`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ compte_ids: selected })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                closeComptesModal();
                location.reload();
            } else {
                alert('Erreur : ' + (data.error || 'inconnue'));
            }
        })
        .catch(() => alert('Erreur réseau.'))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg> Enregistrer';
        });
    });

    /* ── Modal for Villes (RBO) ─────────────────────── */
    let currentVillesUserId = null;
    const villesOverlay = document.getElementById('drModalVilles');
    const villesModalBody = document.getElementById('drModalVillesBody');
    const villesSubtitle = document.getElementById('drModalVillesSubtitle');

    function openVillesModal() {
        villesOverlay.classList.add('visible');
        document.body.style.overflow = 'hidden';
    }
    function closeVillesModal() {
        villesOverlay.classList.remove('visible');
        document.body.style.overflow = '';
    }

    document.getElementById('drModalVillesClose')?.addEventListener('click', closeVillesModal);
    document.getElementById('drModalVillesCancel')?.addEventListener('click', closeVillesModal);
    villesOverlay?.addEventListener('click', e => { if (e.target === villesOverlay) closeVillesModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeVillesModal(); });

    document.querySelectorAll('.assign-villes-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            currentVillesUserId = btn.dataset.userId;
            villesSubtitle.textContent = btn.dataset.userName;
            villesModalBody.innerHTML = '<div class="dr-loading"><div class="dr-spinner"></div>Chargement des villes…</div>';
            openVillesModal();

            fetch(`/users/${currentVillesUserId}/villes`)
                .then(r => r.json())
                .then(data => {
                    if (!data.all_villes.length) {
                        villesModalBody.innerHTML = '<p style="color:var(--text-muted);text-align:center;padding:2.5rem;font-size:.84rem;">Aucune ville disponible.</p>';
                        return;
                    }
                    const items = data.all_villes.map(ville => {
                        const checked = data.assigned_ids.includes(ville.id) ? 'checked' : '';
                        return `
                        <label class="zone-check">
                            <input class="ville-checkbox" type="checkbox" value="${ville.id}" ${checked}>
                            <div class="zc-icon">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            </div>
                            <div>
                                <div class="zc-label">${ville.nom}</div>
                            </div>
                        </label>`;
                    }).join('');
                    villesModalBody.innerHTML = `
                        <div class="dr-search-wrap" style="margin-bottom: 1rem;">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" class="dr-search-input modal-search-input" placeholder="Rechercher une ville..." autocomplete="off">
                        </div>
                        <div id="modal-items-container">${items}</div>
                    `;
                    const searchInput = villesModalBody.querySelector('.modal-search-input');
                    searchInput.addEventListener('keyup', function() {
                        const term = this.value.toLowerCase();
                        const labels = villesModalBody.querySelectorAll('#modal-items-container .zone-check');
                        labels.forEach(label => {
                            const text = label.innerText.toLowerCase();
                            label.style.display = text.includes(term) ? '' : 'none';
                        });
                    });
                })
                .catch(() => {
                    villesModalBody.innerHTML = '<p style="color:var(--rose);text-align:center;padding:2rem;font-size:.84rem;">Erreur lors du chargement des villes.</p>';
                });
        });
    });

    document.getElementById('drModalVillesSave')?.addEventListener('click', function () {
        const selected = [...document.querySelectorAll('.ville-checkbox:checked')].map(cb => cb.value);
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<div class="dr-spinner" style="width:13px;height:13px;border-width:2px;border-top-color:#fff;border-color:rgba(255,255,255,.35);"></div> Enregistrement…';

        fetch(`/users/${currentVillesUserId}/villes`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ ville_ids: selected })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                closeVillesModal();
                location.reload();
            } else {
                alert('Erreur : ' + (data.error || 'inconnue'));
            }
        })
        .catch(() => alert('Erreur réseau.'))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg> Enregistrer';
        });
    });

})();

// ---- View assigned zones modal (delegate) ----==============================================================
let currentAssignedZonesUserId = null;
const assignedZonesOverlay = document.getElementById('drModalAssignedZones');
const assignedZonesModalBody = document.getElementById('drModalAssignedZonesBody');
const assignedZonesSubtitle = document.getElementById('drModalAssignedZonesSubtitle');

function openAssignedZonesModal() {
    assignedZonesOverlay.classList.add('visible');
    document.body.style.overflow = 'hidden';
}
function closeAssignedZonesModal() {
    assignedZonesOverlay.classList.remove('visible');
    document.body.style.overflow = '';
}

document.getElementById('drModalAssignedZonesClose')?.addEventListener('click', closeAssignedZonesModal);
document.getElementById('drModalAssignedZonesCancel')?.addEventListener('click', closeAssignedZonesModal);
assignedZonesOverlay?.addEventListener('click', e => { if (e.target === assignedZonesOverlay) closeAssignedZonesModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAssignedZonesModal(); });

document.querySelectorAll('.view-zones-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        currentAssignedZonesUserId = btn.dataset.userId;
        assignedZonesSubtitle.textContent = btn.dataset.userName;
        assignedZonesModalBody.innerHTML = '<div class="dr-loading"><div class="dr-spinner"></div>Chargement des zones…</div>';
        openAssignedZonesModal();

        fetch(`/users/${currentAssignedZonesUserId}/assigned-zones`)
            .then(r => r.json())
            .then(data => {
                if (!data.zones.length) {
                    assignedZonesModalBody.innerHTML = '<p style="color:var(--text-muted);text-align:center;padding:2.5rem;font-size:.84rem;">Aucune zone assignée.</p>';
                    return;
                }
                const items = data.zones.map(zone => {
                    const rboName = zone.rbo ? `${zone.rbo.prenom} ${zone.rbo.nom}` : '—';
                    return `
                    <div class="zone-check" style="cursor:default;">
                        <div class="zc-icon">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <div class="zc-label">${zone.name}</div>
                            <div class="zc-sub">${zone.ville.nom} · RBO : ${rboName}</div>
                        </div>
                    </div>`;
                }).join('');
                assignedZonesModalBody.innerHTML = items;
            })
            .catch(() => {
                assignedZonesModalBody.innerHTML = '<p style="color:var(--rose);text-align:center;padding:2rem;font-size:.84rem;">Erreur lors du chargement des zones.</p>';
            });
    });
});



// ---- View assigned comptes modal (delegate) ----
let currentAssignedComptesUserId = null;
const assignedComptesOverlay = document.getElementById('drModalAssignedComptes');
const assignedComptesModalBody = document.getElementById('drModalAssignedComptesBody');
const assignedComptesSubtitle = document.getElementById('drModalAssignedComptesSubtitle');

function openAssignedComptesModal() {
    assignedComptesOverlay.classList.add('visible');
    document.body.style.overflow = 'hidden';
}
function closeAssignedComptesModal() {
    assignedComptesOverlay.classList.remove('visible');
    document.body.style.overflow = '';
}

document.getElementById('drModalAssignedComptesClose')?.addEventListener('click', closeAssignedComptesModal);
document.getElementById('drModalAssignedComptesCancel')?.addEventListener('click', closeAssignedComptesModal);
assignedComptesOverlay?.addEventListener('click', e => { if (e.target === assignedComptesOverlay) closeAssignedComptesModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAssignedComptesModal(); });

document.querySelectorAll('.view-comptes-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        currentAssignedComptesUserId = btn.dataset.userId;
        assignedComptesSubtitle.textContent = btn.dataset.userName;
        assignedComptesModalBody.innerHTML = '<div class="dr-loading"><div class="dr-spinner"></div>Chargement des comptes…</div>';
        openAssignedComptesModal();

        fetch(`/users/${currentAssignedComptesUserId}/assigned-comptes`)
            .then(r => r.json())
            .then(data => {
                if (!data.comptes.length) {
                    assignedComptesModalBody.innerHTML = '<p style="color:var(--text-muted);text-align:center;padding:2.5rem;font-size:.84rem;">Aucun compte assigné.</p>';
                    return;
                }
                const items = data.comptes.map(compte => {
                    const clientName = compte.etablissement || 'Sans nom';
                    const type = compte.type ? `(${compte.type})` : '';
                    let location = '—';
                    if (compte.quartier) {
                        const villeName = compte.quartier.zone?.ville?.nom || '?';
                        location = `${compte.quartier.nom} (${villeName})`;
                    } else if (compte.ville) {
                        location = compte.ville.nom;
                    } else if (compte.zone) {
                        location = compte.zone.name;
                    }
                    return `
                    <div class="zone-check" style="cursor:default;">
                        <div class="zc-icon">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        </div>
                        <div>
                            <div class="zc-label">${clientName} ${type}</div>
                            <div class="zc-sub">${location}</div>
                        </div>
                    </div>`;
                }).join('');
                assignedComptesModalBody.innerHTML = items;
            })
            .catch(() => {
                assignedComptesModalBody.innerHTML = '<p style="color:var(--rose);text-align:center;padding:2rem;font-size:.84rem;">Erreur lors du chargement des comptes.</p>';
            });
    });
});
</script>
@endpush
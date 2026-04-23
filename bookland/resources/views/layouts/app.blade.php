<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CRM Bookland</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
    /* ════════════════════════════════════════════════════
       RESET & TOKENS
    ════════════════════════════════════════════════════ */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --sidebar-w:     260px;
        --sidebar-w-collapsed: 68px;

        /* Page surfaces */
        --bg-base:       #f5f6fa;
        --bg-card:       #ffffff;
        --bg-hover:      #f0f4ff;
        --bg-subtle:     #f0f2f8;

        /* Borders */
        --border:        #e4e7f0;
        --border-md:     #d0d5e8;

        /* Sidebar */
        --sb-bg:         #1a1f36;
        --sb-hover:      rgba(255,255,255,.06);
        --sb-active:     rgba(91,141,238,.2);
        --sb-border:     rgba(255,255,255,.07);
        --sb-text:       #8b96b5;
        --sb-text-hover: #c8d0e7;
        --sb-text-active:#ffffff;
        --sb-accent:     #5b8dee;
        --sb-group:      rgba(255,255,255,.25);

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
        --r-xs: 6px; --r-sm: 8px; --r-md: 12px; --r-lg: 16px; --r-xl: 20px;

        /* Shadows */
        --shadow-xs: 0 1px 3px rgba(31,45,80,.06);
        --shadow-sm: 0 2px 8px rgba(31,45,80,.08), 0 1px 3px rgba(31,45,80,.05);
        --shadow-md: 0 8px 32px rgba(31,45,80,.14);
        --shadow-sidebar: 4px 0 24px rgba(26,31,54,.18);

        --font: 'DM Sans', sans-serif;
        --font-mono: 'DM Mono', monospace;
        --ease: cubic-bezier(.4,0,.2,1);
        --t: .2s var(--ease);


        /* new addition  */
         --bg-base: #f5f6fa;
        --bg-card: #ffffff;
        --border: #e4e7f0;
        --blue: #5b8dee;
        --blue-mid: #dce8fb;
        --rose: #e8506a;
        --text-primary: #1a1f36;
        --text-muted: #9ba8c5;
        --font: 'DM Sans', sans-serif;
        --r-sm: 8px;
        --t: .18s ease;
    }

    html, body {
        height: 100%;
        font-family: var(--font);
        background: var(--bg-base);
        color: var(--text-primary);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* ════════════════════════════════════════════════════
       LAYOUT SHELL
    ════════════════════════════════════════════════════ */
    .layout {
        display: flex;
        min-height: 100vh;
    }

    /* ════════════════════════════════════════════════════
       SIDEBAR
    ════════════════════════════════════════════════════ */
    .sidebar {
        width: var(--sidebar-w);
        background: var(--sb-bg);
        position: fixed;
        top: 0; left: 0; bottom: 0;
        z-index: 200;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        overflow-x: hidden;
        transition: transform var(--t);
        box-shadow: var(--shadow-sidebar);
    }

    /* thin scrollbar */
    .sidebar::-webkit-scrollbar { width: 3px; }
    .sidebar::-webkit-scrollbar-track { background: transparent; }
    .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.08); border-radius: 3px; }

    /* ── Logo ── */
    .sb-logo {
        display: flex;
        align-items: center;
        gap: .9rem;
        padding: 1.5rem 1.35rem 1.3rem;
        border-bottom: 1px solid var(--sb-border);
        text-decoration: none;
        flex-shrink: 0;
        transition: opacity var(--t);
    }
    .sb-logo:hover { opacity: .85; text-decoration: none; }

    .sb-logo-mark {
        width: 38px; height: 38px;
        border-radius: var(--r-md);
        background: linear-gradient(135deg, #5b8dee 0%, #6c63ff 100%);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 14px rgba(91,141,238,.5);
    }
    .sb-logo-mark svg { color: #fff; }

    .sb-logo-info {}
    .sb-logo-name {
        font-size: .98rem;
        font-weight: 800;
        color: #fff;
        letter-spacing: -.025em;
        line-height: 1.1;
    }
    .sb-logo-tag {
        font-size: .64rem;
        font-weight: 600;
        color: var(--sb-text);
        text-transform: uppercase;
        letter-spacing: .1em;
        margin-top: .12rem;
    }

    /* ── Nav ── */
    .sb-nav {
        flex: 1;
        padding: .75rem 0 1rem;
    }

    .sb-section {
        margin-top: .5rem;
    }

    .sb-section-label {
        padding: .9rem 1.35rem .3rem;
        font-size: .6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .12em;
        color: var(--sb-group);
        user-select: none;
    }

    .sb-item {
        display: flex;
        align-items: center;
        gap: .7rem;
        padding: .58rem 1rem .58rem 1.35rem;
        margin: .1rem .65rem;
        border-radius: var(--r-sm);
        text-decoration: none;
        color: var(--sb-text);
        font-size: .845rem;
        font-weight: 500;
        letter-spacing: -.01em;
        white-space: nowrap;
        transition: background var(--t), color var(--t);
        position: relative;
    }
    .sb-item:hover {
        background: var(--sb-hover);
        color: var(--sb-text-hover);
        text-decoration: none;
    }
    .sb-item.active {
        background: var(--sb-active);
        color: var(--sb-text-active);
        font-weight: 600;
    }
    /* left accent bar on active */
    .sb-item.active::before {
        content: '';
        position: absolute;
        left: calc(-.65rem);
        top: 20%; bottom: 20%;
        width: 3px;
        border-radius: 0 3px 3px 0;
        background: var(--sb-accent);
    }

    .sb-item-icon {
        width: 17px; height: 17px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        opacity: .65;
        transition: opacity var(--t);
    }
    .sb-item:hover  .sb-item-icon,
    .sb-item.active .sb-item-icon { opacity: 1; }

    /* ── Collapse toggle button ── */
    .sb-collapse-btn {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding: .5rem .9rem .5rem 1.35rem;
        margin-bottom: .25rem;
    }
    .sb-collapse-trigger {
        width: 26px; height: 26px;
        border-radius: var(--r-xs);
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.1);
        display: flex; align-items: center; justify-content: center;
        color: var(--sb-text);
        cursor: pointer;
        transition: all var(--t);
        flex-shrink: 0;
    }
    .sb-collapse-trigger:hover { background: rgba(255,255,255,.12); color: #fff; }
    .sb-collapse-trigger svg { transition: transform var(--t); }

    /* ── Collapsed state ── */
    .sidebar.collapsed {
        width: var(--sidebar-w-collapsed);
    }

    /* hide text labels when collapsed */
    .sidebar.collapsed .sb-logo-info,
    .sidebar.collapsed .sb-section-label,
    .sidebar.collapsed .sb-item-label,
    .sidebar.collapsed .sb-user-info,
    .sidebar.collapsed .sb-user-chevron,
    .sidebar.collapsed .sb-dd-panel { display: none !important; }

    /* center icons when collapsed */
    .sidebar.collapsed .sb-logo {
        justify-content: center;
        padding: 1.5rem .75rem 1.3rem;
    }
    .sidebar.collapsed .sb-item {
        justify-content: center;
        padding: .6rem 0;
        margin: .1rem .55rem;
    }
    .sidebar.collapsed .sb-item-icon { opacity: .75; width: 19px; height: 19px; }
    .sidebar.collapsed .sb-item:hover .sb-item-icon,
    .sidebar.collapsed .sb-item.active .sb-item-icon { opacity: 1; }
    .sidebar.collapsed .sb-item.active::before { display: none; }
    .sidebar.collapsed .sb-collapse-btn { justify-content: center; padding: .5rem; }
    .sidebar.collapsed .sb-footer { padding: .85rem .55rem; }
    .sidebar.collapsed .sb-user-btn {
        justify-content: center;
        padding: .65rem .35rem;
    }

    /* rotate arrow when collapsed */
    .sidebar.collapsed .sb-collapse-trigger svg { transform: rotate(180deg); }

    /* tooltip on hover when collapsed */
    .sidebar.collapsed .sb-item { position: relative; }
    .sidebar.collapsed .sb-item::after {
        content: attr(data-label);
        position: absolute;
        left: calc(100% + .75rem);
        top: 50%; transform: translateY(-50%);
        background: #2d3352;
        color: #fff;
        font-size: .78rem;
        font-weight: 600;
        padding: .3rem .75rem;
        border-radius: var(--r-sm);
        white-space: nowrap;
        pointer-events: none;
        opacity: 0;
        transition: opacity .15s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,.3);
        z-index: 300;
        border: 1px solid rgba(255,255,255,.1);
    }
    .sidebar.collapsed .sb-item:hover::after { opacity: 1; }

    /* main wrap adjusts */
    .main-wrap.expanded { margin-left: var(--sidebar-w-collapsed); }

    /* ── Sidebar footer (user) ── */
    .sb-footer {
        flex-shrink: 0;
        padding: .85rem 1rem;
        border-top: 1px solid var(--sb-border);
    }

    .sb-user {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .65rem .75rem;
        border-radius: var(--r-sm);
        cursor: default;
        transition: background var(--t);
    }
    .sb-user:hover { background: var(--sb-hover); }

    .sb-user-av {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #5b8dee, #6c63ff);
        display: flex; align-items: center; justify-content: center;
        font-size: .68rem; font-weight: 800; color: #fff;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(91,141,238,.4);
    }
    .sb-user-info { flex: 1; min-width: 0; }
    .sb-user-name {
        font-size: .8rem; font-weight: 600; color: #fff;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .sb-user-role {
        font-size: .67rem; color: var(--sb-text);
        margin-top: .06rem; text-transform: capitalize;
    }

    /* ════════════════════════════════════════════════════
       MAIN AREA
    ════════════════════════════════════════════════════ */
    .main-wrap {
        margin-left: var(--sidebar-w);
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        transition: margin-left var(--t);
    }

    /* ── Topbar (mobile only) ── */
    .topbar {
        display: none;
        align-items: center;
        gap: 1rem;
        padding: .8rem 1.25rem;
        background: var(--bg-card);
        border-bottom: 1px solid var(--border);
        position: sticky; top: 0; z-index: 100;
        box-shadow: var(--shadow-sm);
    }

    .topbar-burger {
        width: 36px; height: 36px;
        border-radius: var(--r-sm);
        background: var(--bg-subtle);
        border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: center;
        color: var(--text-secondary);
        cursor: pointer;
        transition: all var(--t);
        flex-shrink: 0;
    }
    .topbar-burger:hover { background: var(--blue-light); color: var(--blue); border-color: var(--blue-mid); }

    .topbar-logo {
        display: flex; align-items: center; gap: .6rem;
        text-decoration: none;
    }
    .topbar-logo-mark {
        width: 28px; height: 28px; border-radius: var(--r-sm);
        background: linear-gradient(135deg, #5b8dee, #6c63ff);
        display: flex; align-items: center; justify-content: center;
    }
    .topbar-logo-mark svg { color: #fff; }
    .topbar-name {
        font-size: .9rem; font-weight: 800;
        color: var(--text-primary); letter-spacing: -.025em;
    }

    /* ── Content area ── */
    .main-content { flex: 1; }

    /* ════════════════════════════════════════════════════
       FLASH ALERTS
    ════════════════════════════════════════════════════ */
    .flash-wrap {
        padding: 1.5rem 2.5rem 0;
    }

    .flash {
        display: flex;
        align-items: flex-start;
        gap: .75rem;
        padding: .9rem 1.1rem;
        border-radius: var(--r-md);
        border: 1px solid transparent;
        font-size: .84rem;
        font-weight: 500;
        margin-bottom: .6rem;
        animation: flashIn .3s var(--ease) both;
        line-height: 1.45;
    }
    @keyframes flashIn {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .flash-icon { flex-shrink: 0; margin-top: .08rem; }

    .flash-success {
        background: var(--green-light);
        color: #14532d;
        border-color: rgba(40,199,111,.22);
    }
    .flash-error {
        background: var(--rose-light);
        color: #881337;
        border-color: rgba(232,80,106,.2);
    }
    .flash-error ul { padding-left: 1.15rem; margin: .3rem 0 0; }
    .flash-error li { margin-top: .2rem; font-size: .82rem; }

    .flash-dismiss {
        margin-left: auto;
        width: 22px; height: 22px; flex-shrink: 0;
        border-radius: var(--r-xs);
        border: none; background: transparent;
        cursor: pointer; color: inherit;
        display: flex; align-items: center; justify-content: center;
        opacity: .45; transition: opacity var(--t), background var(--t);
    }
    .flash-dismiss:hover { opacity: 1; background: rgba(0,0,0,.07); }

    /* ════════════════════════════════════════════════════
       USER DROPDOWN
    ════════════════════════════════════════════════════ */
    .sb-user-dropdown {
        position: relative;
    }

    .sb-user-btn {
        width: 100%;
        display: flex; align-items: center; gap: .7rem;
        padding: .65rem .75rem;
        border-radius: var(--r-sm);
        background: transparent;
        border: none; cursor: pointer;
        font-family: var(--font);
        transition: background var(--t);
        text-align: left;
    }
    .sb-user-btn:hover { background: var(--sb-hover); }
    .sb-user-btn[aria-expanded="true"] { background: var(--sb-hover); }

    .sb-user-av {
        width: 32px; height: 32px; border-radius: 50%;
        background: linear-gradient(135deg, #5b8dee, #6c63ff);
        display: flex; align-items: center; justify-content: center;
        font-size: .7rem; font-weight: 700; color: #fff; flex-shrink: 0;
    }
    .sb-user-info { flex: 1; min-width: 0; }
    .sb-user-name { font-size: .8rem; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .sb-user-role { font-size: .68rem; color: var(--sb-text); margin-top: .06rem; text-transform: capitalize; }
    .sb-user-chevron { flex-shrink: 0; transition: transform var(--t); }
    .sb-user-btn[aria-expanded="true"] .sb-user-chevron { transform: rotate(180deg); }

    /* Dropdown panel — pops upward */
    .sb-dd-panel {
        position: absolute;
        bottom: calc(100% + .5rem);
        left: 0; right: 0;
        background: #242840;
        border: 1px solid rgba(255,255,255,.1);
        border-radius: var(--r-md);
        box-shadow: 0 -8px 32px rgba(0,0,0,.4), 0 -2px 8px rgba(0,0,0,.2);
        overflow: hidden;
        /* hidden by default */
        opacity: 0;
        transform: translateY(8px);
        pointer-events: none;
        transition: opacity .18s var(--ease), transform .18s var(--ease);
        z-index: 200;
    }
    .sb-dd-panel.open {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
    }

    .sb-dd-header {
        display: flex; align-items: center; gap: .75rem;
        padding: 1rem 1.1rem .75rem;
    }
    .sb-dd-av {
        width: 36px; height: 36px; border-radius: 50%;
        background: linear-gradient(135deg, #5b8dee, #6c63ff);
        display: flex; align-items: center; justify-content: center;
        font-size: .75rem; font-weight: 700; color: #fff; flex-shrink: 0;
    }
    .sb-dd-name  { font-size: .83rem; font-weight: 600; color: #fff; }
    .sb-dd-email { font-size: .72rem; color: var(--sb-text); margin-top: .1rem; font-family: var(--font-mono); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 170px; }

    .sb-dd-role-row { padding: 0 1.1rem .75rem; }
    .sb-dd-role-chip {
        display: inline-flex; align-items: center;
        padding: .18rem .65rem; border-radius: 20px;
        font-size: .68rem; font-weight: 700;
        border: 1px solid transparent;
        letter-spacing: .06em;
    }

    .sb-dd-divider { height: 1px; background: rgba(255,255,255,.07); margin: 0; }

    .sb-dd-item {
        display: flex; align-items: center; gap: .65rem;
        width: 100%; padding: .75rem 1.1rem;
        background: transparent; border: none;
        font-family: var(--font); font-size: .83rem; font-weight: 500;
        color: var(--sb-text); cursor: pointer;
        transition: background var(--t), color var(--t);
        text-align: left;
    }
    .sb-dd-item:hover { background: var(--sb-hover); }
    .sb-dd-item-danger { color: #fca5a5; }
    .sb-dd-item-danger:hover { background: rgba(232,80,106,.15); color: #fca5a5; }

    /* ════════════════════════════════════════════════════
       MOBILE OVERLAY
    ════════════════════════════════════════════════════ */
    .sb-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(26,31,54,.5);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 199;
    }
    .sb-overlay.open { display: block; animation: fadeIn .2s ease; }
    @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }

    /* ════════════════════════════════════════════════════
       RESPONSIVE — ≤ 1024px
    ════════════════════════════════════════════════════ */
    @media (max-width: 1024px) {
        .sidebar { transform: translateX(-100%); }
        .sidebar.open { transform: translateX(0); box-shadow: var(--shadow-md); }
        .main-wrap { margin-left: 0; }
        .topbar { display: flex; }
        .flash-wrap { padding: 1rem 1.25rem 0; }
    }
    @media (max-width: 600px) {
        .flash-wrap { padding: .75rem .75rem 0; }
    }
    </style>
    @stack('styles')
</head>
<body>
<div class="layout">

    {{-- ══════════════════════════════════════════════════
         SIDEBAR
    ══════════════════════════════════════════════════ --}}
    <aside class="sidebar" id="sidebar">

        {{-- Logo --}}
        <a href="{{ route('comptes.index') }}" class="sb-logo">
            <div class="sb-logo-mark">
                <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            </div>
            <div class="sb-logo-info">
                <div class="sb-logo-name">Bookland</div>
                <div class="sb-logo-tag">CRM · Dashboard</div>
            </div>
        </a>

        {{-- Navigation --}}
        <nav class="sb-nav" aria-label="Navigation principale">

            {{-- Collapse toggle --}}
            <div class="sb-collapse-btn">
                <button class="sb-collapse-trigger" id="sbCollapseBtn" aria-label="Réduire le menu" title="Réduire">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                </button>
            </div>

            {{-- ── Commercial ───────────────────────────── --}}
            <div class="sb-section">
                <div class="sb-section-label">Commercial</div>

                

                <a href="{{ route('products.index') }}"
                   class="sb-item {{ request()->routeIs('products.*') ? 'active' : '' }}"
                   data-label="Produits">
                    <span class="sb-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73L13 2.27a2 2 0 0 0-2 0L4 6.27A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4.03a2 2 0 0 0 2 0l7-4.03A2 2 0 0 0 21 16z"/>
                            <path d="M3.3 7L12 12l8.7-5"/>
                            <path d="M12 22V12"/>
                        </svg>
                    </span>
                    <span class="sb-item-label">Produits</span>
                </a>

                <a href="{{ route('consignations.index') }}"
                   class="sb-item {{ request()->routeIs('consignations.*') ? 'active' : '' }}"
                   data-label="Consignations">
                    <span class="sb-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M3 7l9-4 9 4-9 4-9-4z"/>
                            <path d="M3 7v10l9 4 9-4V7"/>
                            <path d="M12 11v10"/>
                        </svg>
                    </span>
                    <span class="sb-item-label">Consignations</span>
                </a>
                <a href="{{ route('bss.index') }}"
                   class="sb-item {{ request()->routeIs('bss.*') ? 'active' : '' }}"
                   data-label="Specimens & BSS">
                    <span class="sb-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 12h-8"/>
                            <path d="M17 8l4 4-4 4"/>
                            <path d="M3 7l9-4 9 4-9 4-9-4z"/>
                            <path d="M3 7v10l9 4 9-4v-3"/>
                        </svg>
                    </span>
                    <span class="sb-item-label">Specimens & BSS</span>
                </a>
                <a href="{{ route('retours.index') }}"
                   class="sb-item {{ request()->routeIs('retours.*') ? 'active' : '' }}"
                   data-label="Les Retours">
                    <span class="sb-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 14L4 9l5-5"/>
                            <path d="M4 9h11a5 5 0 0 1 5 5v1"/>
                        </svg>
                    </span>
                    <span class="sb-item-label">Les Retours</span>
                </a>

                <a href="{{ route('adoptions.index') }}"
                   class="sb-item {{ request()->routeIs('adoptions.*') ? 'active' : '' }}"
                   data-label="Adoptions">
                    <span class="sb-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 14L4 9l5-5"/>
                            <path d="M4 9h11a5 5 0 0 1 5 5v1"/>
                        </svg>
                    </span>
                    <span class="sb-item-label">Adoptions</span>
                </a>

                <a href="{{ route('effectifs.index') }}"
                   class="sb-item {{ request()->routeIs('effectifs.*') ? 'active' : '' }}"
                   data-label="Effectifs">
                    <span class="sb-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 14L4 9l5-5"/>
                            <path d="M4 9h11a5 5 0 0 1 5 5v1"/>
                        </svg>
                    </span>
                    <span class="sb-item-label">Effectifs</span>
                </a>


                <a href="{{ route('examens.index') }}"
                   class="sb-item {{ request()->routeIs('examens.*') ? 'active' : '' }}"
                   data-label="Examens">
                    <span class="sb-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 14L4 9l5-5"/>
                            <path d="M4 9h11a5 5 0 0 1 5 5v1"/>
                        </svg>
                    </span>
                    <span class="sb-item-label">Examens</span>
                </a>

                <a href="{{ route('formations.index') }}"
                   class="sb-item {{ request()->routeIs('formations.*') ? 'active' : '' }}"
                   data-label="Formations">
                    <span class="sb-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 14L4 9l5-5"/>
                            <path d="M4 9h11a5 5 0 0 1 5 5v1"/>
                        </svg>
                    </span>
                    <span class="sb-item-label">Formations</span>
                </a>

                @if (auth()->user()->role == 'admin')
                    <a href="{{ route('annees-scolaires.index') }}"
                    class="sb-item {{ request()->routeIs('annees-scolaires.*') ? 'active' : '' }}"
                    data-label="Années scolaires">
                        <span class="sb-item-icon">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </span>
                        <span class="sb-item-label">Années scolaires</span>
                    </a>
                @endif

                
            </div>

            {{-- ── Équipe ───────────────────────────────── --}}
            @if(in_array(auth()->user()->role, ['admin', 'rbo', 'delegue']))
            <div class="sb-section">
                <div class="sb-section-label">Équipe</div>

                <a href="{{ route('users.roles') }}"
                   class="sb-item {{ request()->routeIs('users.roles') ? 'active' : '' }}"
                   data-label="Rôles">
                    <span class="sb-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/><path d="M16 3.5a4 4 0 0 1 0 7"/><path d="M20 20c0-3-2-5-4-6"/></svg>
                    </span>
                    <span class="sb-item-label">Rôles</span>
                </a>

                <a href="{{ route('comptes.index') }}"
                   class="sb-item {{ request()->routeIs('comptes.*') ? 'active' : '' }}"
                   data-label="Comptes">
                    <span class="sb-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </span>
                    <span class="sb-item-label">Comptes</span>
                </a>

                <a href="{{ route('contacts.index') }}"
                   class="sb-item {{ request()->routeIs('contacts.*') ? 'active' : '' }}"
                   data-label="Contacts">
                    <span class="sb-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </span>
                    <span class="sb-item-label">Contacts</span>
                </a>

                

                @if(auth()->user()->role === 'admin')
                <a href="{{ route('users.index') }}"
                   class="sb-item {{ request()->routeIs('users.index', 'users.create', 'users.edit') ? 'active' : '' }}"
                   data-label="Utilisateurs">
                    <span class="sb-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </span>
                    <span class="sb-item-label">Utilisateurs</span>
                </a>
                @endif
            </div>
            @endif

            {{-- ── Géographie: admin only ───────────────── --}}
            @if(auth()->user()->role === 'admin')
            <div class="sb-section">
                <div class="sb-section-label">Géographie</div>

                <a href="{{ route('villes.index') }}"
                   class="sb-item {{ request()->routeIs('villes.*') ? 'active' : '' }}"
                   data-label="Villes">
                    <span class="sb-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </span>
                    <span class="sb-item-label">Villes</span>
                </a>

                <a href="{{ route('zones.index') }}"
                   class="sb-item {{ request()->routeIs('zones.*') ? 'active' : '' }}"
                   data-label="Zones">
                    <span class="sb-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                    </span>
                    <span class="sb-item-label">Zones</span>
                </a>

                <a href="{{ route('quartiers.index') }}"
                   class="sb-item {{ request()->routeIs('quartiers.*') ? 'active' : '' }}"
                   data-label="Quartiers">
                    <span class="sb-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><line x1="9" y1="22" x2="9" y2="12"/><line x1="15" y1="12" x2="15" y2="22"/><line x1="3" y1="12" x2="21" y2="12"/></svg>
                    </span>
                    <span class="sb-item-label">Quartiers</span>
                </a>
            </div>
            @endif

        </nav>

        {{-- ── User footer with dropdown ───────────────── --}}
        <div class="sb-footer">
            <div class="sb-user-dropdown" id="sbUserDropdown">

                {{-- Trigger --}}
                <button class="sb-user-btn" id="sbUserBtn" aria-expanded="false" aria-haspopup="true">
                    <div class="sb-user-av">
                        {{ strtoupper(substr(auth()->user()->prenom ?? 'U', 0, 1).substr(auth()->user()->nom ?? '', 0, 1)) }}
                    </div>
                    <div class="sb-user-info">
                        <div class="sb-user-name">
                            {{ trim((auth()->user()->prenom ?? '').' '.(auth()->user()->nom ?? '')) ?: 'Utilisateur' }}
                        </div>
                        <div class="sb-user-role">{{ ucfirst(auth()->user()->role ?? 'admin') }}</div>
                    </div>
                    <svg class="sb-user-chevron" width="13" height="13" fill="none" stroke="rgba(255,255,255,.35)" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                </button>

                {{-- Dropdown panel (pops upward) --}}
                <div class="sb-dd-panel" id="sbDdPanel" aria-hidden="true">
                    {{-- User info header --}}
                    <div class="sb-dd-header">
                        <div class="sb-dd-av">
                            {{ strtoupper(substr(auth()->user()->prenom ?? 'U', 0, 1).substr(auth()->user()->nom ?? '', 0, 1)) }}
                        </div>
                        <div class="sb-dd-meta">
                            <div class="sb-dd-name">{{ auth()->user()->prenom ?? '' }} {{ auth()->user()->nom ?? '' }}</div>
                            <div class="sb-dd-email">{{ auth()->user()->email ?? '' }}</div>
                        </div>
                    </div>

                    {{-- Role chip --}}
                    <div class="sb-dd-role-row">
                        @php
                            $roleColors = [
                                'admin'   => ['bg'=>'rgba(167,139,250,.18)', 'color'=>'#c4b5fd', 'border'=>'rgba(167,139,250,.3)'],
                                'rbo'     => ['bg'=>'rgba(45,212,191,.15)',  'color'=>'#5ee8e6', 'border'=>'rgba(45,212,191,.3)'],
                                'delegue' => ['bg'=>'rgba(91,141,238,.18)', 'color'=>'#89b4fa', 'border'=>'rgba(91,141,238,.3)'],
                                'abo'     => ['bg'=>'rgba(232,160,32,.15)',  'color'=>'#fbbf72', 'border'=>'rgba(232,160,32,.3)'],
                            ];
                            $rc = $roleColors[auth()->user()->role ?? 'admin'] ?? $roleColors['admin'];
                        @endphp
                        <span class="sb-dd-role-chip" style="background:{{ $rc['bg'] }};color:{{ $rc['color'] }};border-color:{{ $rc['border'] }};">
                            {{ strtoupper(auth()->user()->role ?? 'admin') }}
                        </span>
                    </div>

                    <div class="sb-dd-divider"></div>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sb-dd-item sb-dd-item-danger">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Déconnexion
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </aside>

    {{-- Mobile overlay --}}
    <div class="sb-overlay" id="sbOverlay"></div>

    {{-- ══════════════════════════════════════════════════
         MAIN
    ══════════════════════════════════════════════════ --}}
    <div class="main-wrap">

        {{-- Mobile topbar --}}
        <header class="topbar">
            <button class="topbar-burger" id="sidebarToggle" aria-label="Menu">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <a href="{{ route('comptes.index') }}" class="topbar-logo">
                <div class="topbar-logo-mark">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                </div>
                <span class="topbar-name">Bookland CRM</span>
            </a>
        </header>

        {{-- Flash messages --}}
        @if($errors->any() || session('success') || session('error'))
        <div class="flash-wrap">

            @if($errors->any())
            <div class="flash flash-error" role="alert">
                <span class="flash-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </span>
                <div>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button class="flash-dismiss" onclick="this.closest('.flash').remove()" aria-label="Fermer">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            @endif

            @if(session('success'))
            <div class="flash flash-success" role="alert">
                <span class="flash-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </span>
                <span>{{ session('success') }}</span>
                <button class="flash-dismiss" onclick="this.closest('.flash').remove()" aria-label="Fermer">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div class="flash flash-error" role="alert">
                <span class="flash-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </span>
                <span>{{ session('error') }}</span>
                <button class="flash-dismiss" onclick="this.closest('.flash').remove()" aria-label="Fermer">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            @endif

        </div>
        @endif

        {{-- Page content --}}
        <main class="main-content">
            @yield('content')
        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
<script>
(function () {
    const sidebar   = document.getElementById('sidebar');
    const mainWrap  = document.querySelector('.main-wrap');
    const overlay   = document.getElementById('sbOverlay');
    const toggle    = document.getElementById('sidebarToggle');
    const collapseBtn = document.getElementById('sbCollapseBtn');

    /* ── Mobile open/close ───────────────── */
    function openMobile()  {
        sidebar.classList.add('open');
        overlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeMobile() {
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
        document.body.style.overflow = '';
    }

    if (toggle)  toggle.addEventListener('click', openMobile);
    if (overlay) overlay.addEventListener('click', closeMobile);

    /* ── Desktop collapse ────────────────── */
    const STORAGE_KEY = 'crm_sidebar_collapsed';

    function setCollapsed(collapsed) {
        if (collapsed) {
            sidebar.classList.add('collapsed');
            mainWrap.classList.add('expanded');
        } else {
            sidebar.classList.remove('collapsed');
            mainWrap.classList.remove('expanded');
        }
        try { localStorage.setItem(STORAGE_KEY, collapsed ? '1' : '0'); } catch(e) {}
    }

    // Restore state on load
    try {
        if (localStorage.getItem(STORAGE_KEY) === '1') setCollapsed(true);
    } catch(e) {}

    if (collapseBtn) {
        collapseBtn.addEventListener('click', () => {
            setCollapsed(!sidebar.classList.contains('collapsed'));
        });
    }

    /* ── Shared Escape ───────────────────── */
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { closeMobile(); closeDropdown(); }
    });

    /* ── User dropdown ───────────────────── */
    const userBtn = document.getElementById('sbUserBtn');
    const ddPanel = document.getElementById('sbDdPanel');

    function openDropdown()  { ddPanel.classList.add('open');    userBtn.setAttribute('aria-expanded', 'true'); }
    function closeDropdown() {
        if (ddPanel) { ddPanel.classList.remove('open'); }
        if (userBtn) userBtn.setAttribute('aria-expanded', 'false');
    }

    if (userBtn) userBtn.addEventListener('click', e => {
        e.stopPropagation();
        ddPanel.classList.contains('open') ? closeDropdown() : openDropdown();
    });
    document.addEventListener('click', e => {
        if (ddPanel && !ddPanel.contains(e.target) && userBtn && e.target !== userBtn) closeDropdown();
    });

    /* ── Auto-dismiss flash after 5 s ────── */
    document.querySelectorAll('.flash').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity .35s ease, transform .35s ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-5px)';
            setTimeout(() => el.remove(), 380);
        }, 5000);
    });
})();
</script>
</body>
</html>
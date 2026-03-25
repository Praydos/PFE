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
.us-page {
    padding: 2rem 2.5rem 3rem;
    animation: pageIn .4s var(--ease) both;
}
@keyframes pageIn {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Breadcrumb ────────────────────────────────────── */
.us-breadcrumb {
    display: flex; align-items: center; gap: .4rem;
    font-size: .76rem; color: var(--text-muted); font-weight: 500;
    margin-bottom: 1.4rem;
}
.us-breadcrumb a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
.us-breadcrumb a:hover { color: var(--blue); }
.us-breadcrumb-sep { color: var(--text-hint); }
.us-breadcrumb-current { color: var(--text-secondary); }

/* ── Header ────────────────────────────────────────── */
.us-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap;
}
.us-header-left h1 {
    font-size: 1.65rem; font-weight: 700;
    letter-spacing: -.03em; color: var(--text-primary); line-height: 1.15;
}
.us-header-left p { font-size: .83rem; color: var(--text-muted); margin-top: .3rem; }
.us-header-actions { display: flex; gap: .5rem; align-items: center; flex-wrap: wrap; }

/* ── Buttons ───────────────────────────────────────── */
.btn-us {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .56rem 1.1rem; border-radius: var(--r-sm);
    font-family: var(--font); font-size: .82rem; font-weight: 600;
    cursor: pointer; border: 1px solid transparent;
    transition: all var(--t); text-decoration: none;
    white-space: nowrap; letter-spacing: -.01em; line-height: 1;
}
.btn-us svg { flex-shrink: 0; }

.btn-us-primary {
    background: var(--blue); color: #fff;
    border-color: var(--blue); box-shadow: var(--shadow-blue);
}
.btn-us-primary:hover {
    background: var(--blue-dark); border-color: var(--blue-dark);
    color: #fff; text-decoration: none;
    transform: translateY(-1px); box-shadow: 0 6px 20px rgba(91,141,238,.4);
}

.btn-us-ghost {
    background: var(--bg-card); color: var(--text-secondary);
    border-color: var(--border); box-shadow: var(--shadow-xs);
}
.btn-us-ghost:hover {
    background: var(--bg-hover); color: var(--text-primary);
    border-color: var(--border-md); text-decoration: none;
}

.btn-us-danger-ghost {
    background: var(--rose-light); color: var(--rose);
    border-color: rgba(232,80,106,.2);
}
.btn-us-danger-ghost:hover { background: #fddde2; color: var(--rose); text-decoration: none; }

.btn-us-sm { padding: .38rem .72rem; font-size: .75rem; }
.btn-us-warning { background: var(--amber-light); color: var(--amber); border-color: rgba(232,160,32,.2); }
.btn-us-warning:hover { background: #ffefd4; color: var(--amber); text-decoration: none; }
.btn-us-danger { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.18); }
.btn-us-danger:hover { background: #fddde2; color: var(--rose); text-decoration: none; }

/* ── Stat Cards ────────────────────────────────────── */
.us-stats {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem; margin-bottom: 2rem;
}
.us-stat {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--r-lg); padding: 1.35rem 1.5rem;
    display: flex; align-items: center; gap: 1.1rem;
    box-shadow: var(--shadow-xs); transition: all var(--t);
    animation: pageIn .5s var(--ease) both;
    position: relative; overflow: hidden;
}
.us-stat::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px; opacity: 0; transition: opacity var(--t);
    border-radius: var(--r-lg) var(--r-lg) 0 0;
}
.us-stat:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); border-color: var(--border-md); }
.us-stat:hover::before { opacity: 1; }
.us-stat:nth-child(1) { animation-delay:.06s; } .us-stat:nth-child(1)::before { background: var(--blue); }
.us-stat:nth-child(2) { animation-delay:.11s; } .us-stat:nth-child(2)::before { background: var(--green); }
.us-stat:nth-child(3) { animation-delay:.16s; } .us-stat:nth-child(3)::before { background: var(--rose); }
.us-stat:nth-child(4) { animation-delay:.21s; } .us-stat:nth-child(4)::before { background: var(--teal); }

.stat-ico {
    width: 44px; height: 44px; border-radius: var(--r-md);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.si-blue   { background: var(--blue-light);   color: var(--blue); }
.si-green  { background: var(--green-light);  color: var(--green); }
.si-rose   { background: var(--rose-light);   color: var(--rose); }
.si-teal   { background: var(--teal-light);   color: var(--teal); }

.stat-label { font-size: .72rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .05em; }
.stat-value { font-size: 1.7rem; font-weight: 700; color: var(--text-primary); line-height: 1.1; letter-spacing: -.04em; margin-top: .06rem; }

/* ── Search bar ────────────────────────────────────── */
.us-search-bar {
    display: flex; align-items: center; gap: .6rem;
    margin-bottom: 1.25rem; flex-wrap: wrap;
}
.us-search-wrap {
    position: relative; flex: 1; min-width: 220px; max-width: 380px;
}
.us-search-wrap svg {
    position: absolute; left: .85rem; top: 50%; transform: translateY(-50%);
    color: var(--text-muted); pointer-events: none;
}
.us-search-input {
    width: 100%; padding: .56rem .9rem .56rem 2.35rem;
    border: 1px solid var(--border); border-radius: var(--r-sm);
    background: var(--bg-card); font-family: var(--font);
    font-size: .83rem; color: var(--text-primary);
    box-shadow: var(--shadow-xs); transition: all var(--t); outline: none;
}
.us-search-input::placeholder { color: var(--text-muted); }
.us-search-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); }

/* Role filter pills */
.us-role-filters { display: flex; gap: .35rem; flex-wrap: wrap; }
.role-pill {
    padding: .3rem .8rem; border-radius: 20px;
    font-size: .75rem; font-weight: 600;
    border: 1px solid var(--border); background: var(--bg-card);
    color: var(--text-muted); cursor: pointer;
    text-decoration: none; transition: all var(--t); white-space: nowrap;
    font-family: var(--font);
}
.role-pill:hover { border-color: var(--blue-mid); color: var(--blue); background: var(--blue-light); text-decoration: none; }
.role-pill.active { background: var(--blue); color: #fff; border-color: var(--blue); box-shadow: var(--shadow-blue); }

/* ── Card ──────────────────────────────────────────── */
.us-card {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--r-xl); box-shadow: var(--shadow-sm); overflow: hidden;
}
.us-card-header {
    padding: 1.1rem 1.6rem; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between; gap: 1rem;
}
.us-card-title {
    font-size: .88rem; font-weight: 700; color: var(--text-primary);
    display: flex; align-items: center; gap: .55rem; letter-spacing: -.01em;
}
.title-pip {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid);
}
.us-result-count { font-size: .76rem; color: var(--text-muted); font-weight: 500; }

/* ── Table ─────────────────────────────────────────── */
.us-table { width: 100%; border-collapse: collapse; }
.us-table thead tr { border-bottom: 1px solid var(--border); }
.us-table th {
    padding: .85rem 1.2rem;
    font-size: .69rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: var(--text-hint); text-align: left;
    background: var(--bg-base); white-space: nowrap;
}
.us-table td {
    padding: .95rem 1.2rem; font-size: .83rem;
    color: var(--text-secondary); border-bottom: 1px solid var(--border);
    vertical-align: middle;
}
.us-table tbody tr { transition: background var(--t); }
.us-table tbody tr:hover { background: #f8f9fd; }
.us-table tbody tr:last-child td { border-bottom: none; }

/* ID pill */
.id-pill {
    font-family: var(--font-mono); font-size: .75rem;
    color: var(--text-muted); font-weight: 500;
    background: var(--bg-subtle); border-radius: var(--r-xs);
    padding: .18rem .5rem; display: inline-block;
}

/* User cell */
.user-cell { display: flex; align-items: center; gap: .8rem; }
.user-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .78rem; color: #fff;
    flex-shrink: 0; letter-spacing: .02em;
}
.av-a { background: linear-gradient(135deg, #5b8dee, #6c63ff); }
.av-b { background: linear-gradient(135deg, #0cb8b6, #00d4aa); }
.av-c { background: linear-gradient(135deg, #7c6fcd, #b06ab3); }
.av-d { background: linear-gradient(135deg, #e8a020, #f97316); }
.av-e { background: linear-gradient(135deg, #e8506a, #ff6b9d); }

.user-name  { font-weight: 600; color: var(--text-primary); font-size: .84rem; letter-spacing: -.01em; }
.user-email { font-size: .73rem; color: var(--text-muted); margin-top: .08rem; font-family: var(--font-mono); }

/* Role badge */
.role-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .2rem .65rem; border-radius: 20px;
    font-size: .71rem; font-weight: 600; border: 1px solid transparent;
    letter-spacing: .02em; white-space: nowrap;
}
.role-admin   { background: var(--violet-light); color: var(--violet); border-color: rgba(124,111,205,.22); }
.role-delegue { background: var(--blue-light);   color: var(--blue);   border-color: var(--blue-mid); }
.role-rbo     { background: var(--teal-light);   color: #0a9997;       border-color: rgba(12,184,182,.22); }
.role-default { background: var(--bg-subtle);    color: var(--text-muted); border-color: var(--border); }

/* Status badge */
.status-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .22rem .65rem; border-radius: 20px;
    font-size: .71rem; font-weight: 600; border: 1px solid transparent;
}
.status-dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
.status-actif  { background: var(--green-light); color: #1da35e; border-color: rgba(40,199,111,.22); }
.status-actif  .status-dot { background: var(--green); }
.status-inactif { background: var(--bg-subtle); color: var(--text-muted); border-color: var(--border); }
.status-inactif .status-dot { background: var(--text-hint); }

/* Ville cell */
.ville-cell { display: flex; align-items: center; gap: .35rem; font-size: .81rem; }
.ville-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--teal); flex-shrink: 0; }

/* Actions */
.actions-cell { display: flex; align-items: center; gap: .35rem; }

/* Empty state */
.us-empty { padding: 4rem 2rem; text-align: center; }
.us-empty-icon {
    width: 52px; height: 52px; border-radius: var(--r-md);
    background: var(--bg-subtle); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1rem; color: var(--text-hint);
}
.us-empty h3 { font-size: .95rem; font-weight: 700; color: var(--text-secondary); }
.us-empty p  { font-size: .82rem; color: var(--text-muted); margin-top: .3rem; }

/* Pagination */
/* ── Pagination fix ──────────────────────────────────── */


/* Search active pill */
.search-active-pill {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .3rem .75rem; border-radius: 20px;
    background: var(--blue-light); color: var(--blue);
    border: 1px solid var(--blue-mid);
    font-size: .76rem; font-weight: 600;
}

/* ── Responsive ────────────────────────────────────── */
@media (max-width: 768px) {
    .us-page { padding: 1.25rem 1rem 2rem; }
    .us-header { flex-direction: column; gap: 1rem; }
    .us-stats { grid-template-columns: 1fr 1fr; }
    .us-table th, .us-table td { padding: .75rem .9rem; }
    .user-email { display: none; }
}
@media (max-width: 480px) {
    .us-stats { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="us-page">

    {{-- Breadcrumb --}}
    <div class="us-breadcrumb">
        <a href="{{ route('users.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </a>
        <span class="us-breadcrumb-sep">›</span>
        <a href="#">Administration</a>
        <span class="us-breadcrumb-sep">›</span>
        <span class="us-breadcrumb-current">Utilisateurs</span>
    </div>

    {{-- Header --}}
    <div class="us-header">
        <div class="us-header-left">
            <h1>Utilisateurs</h1>
            <p>Gérez les comptes, rôles et accès de votre équipe</p>
        </div>
        <div class="us-header-actions">
            <a href="{{ route('users.create') }}" class="btn-us btn-us-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nouvel utilisateur
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    @php
        $allUsers    = $users->getCollection();
        $totalUsers  = $users->total();
        $activeCount = $allUsers->where('is_active', true)->count();
        $inactiveCount = $allUsers->where('is_active', false)->count();
        $roleGroups  = $allUsers->groupBy('role');
    @endphp
    <div class="us-stats">
        <div class="us-stat">
            <div class="stat-ico si-blue">
                <svg width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <div class="stat-label">Total</div>
                <div class="stat-value">{{ $totalUsers }}</div>
            </div>
        </div>
        <div class="us-stat">
            <div class="stat-ico si-green">
                <svg width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div>
                <div class="stat-label">Actifs</div>
                <div class="stat-value">{{ $activeCount }}</div>
            </div>
        </div>
        <div class="us-stat">
            <div class="stat-ico si-rose">
                <svg width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
            <div>
                <div class="stat-label">Inactifs</div>
                <div class="stat-value">{{ $inactiveCount }}</div>
            </div>
        </div>
        <div class="us-stat">
            <div class="stat-ico si-teal">
                <svg width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
            </div>
            <div>
                <div class="stat-label">Rôles</div>
                <div class="stat-value">{{ $roleGroups->count() }}</div>
            </div>
        </div>
    </div>

    {{-- Search + Role filters --}}
    <div class="us-search-bar">
        <form method="GET" action="{{ route('users.index') }}" style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;">
            <div class="us-search-wrap">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input
                    type="text"
                    name="search"
                    class="us-search-input"
                    placeholder="Rechercher un nom, email, ville…"
                    value="{{ request('search') }}"
                    autocomplete="off"
                >
            </div>
            <button type="submit" class="btn-us btn-us-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Filtrer
            </button>
            @if(request('search'))
                <span class="search-active-pill">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    «&nbsp;{{ request('search') }}&nbsp;»
                </span>
                <a href="{{ route('users.index') }}" class="btn-us btn-us-danger-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Réinitialiser
                </a>
            @endif
        </form>

        {{-- Role quick-filter pills --}}
        <div class="us-role-filters">
            <a href="{{ route('users.index', array_merge(request()->except('role'), [])) }}"
               class="role-pill {{ !request('role') ? 'active' : '' }}">
                Tous
            </a>
            @foreach(['admin','delegue','rbo'] as $r)
            <a href="{{ route('users.index', array_merge(request()->all(), ['role' => $r])) }}"
               class="role-pill {{ request('role') === $r ? 'active' : '' }}">
                {{ ucfirst($r) }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- Table Card --}}
    <div class="us-card">
        <div class="us-card-header">
            <div class="us-card-title">
                <span class="title-pip"></span>
                Liste des utilisateurs
            </div>
            <span class="us-result-count">
                {{ $users->total() }} utilisateur{{ $users->total() > 1 ? 's' : '' }}
            </span>
        </div>

        <div style="overflow-x: auto;">
            <table class="us-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Utilisateur</th>
                        <th>Rôle</th>
                        <th>Ville</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $avs = ['av-a','av-b','av-c','av-d','av-e']; @endphp
                    @forelse($users as $i => $user)
                    <tr>
                        {{-- ID --}}
                        <td><span class="id-pill">{{ $user->id }}</span></td>

                        {{-- User: avatar + name + email --}}
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar {{ $avs[$i % count($avs)] }}">
                                    {{ strtoupper(substr($user->prenom,0,1).substr($user->nom,0,1)) }}
                                </div>
                                <div>
                                    <div class="user-name">{{ $user->prenom }} {{ $user->nom }}</div>
                                    <div class="user-email">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Rôle --}}
                        <td>
                            @php
                                $roleClass = match($user->role) {
                                    'admin'   => 'role-admin',
                                    'delegue' => 'role-delegue',
                                    'rbo'     => 'role-rbo',
                                    default   => 'role-default',
                                };
                                $roleIcon = match($user->role) {
                                    'admin'   => '<svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
                                    'delegue' => '<svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
                                    'rbo'     => '<svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>',
                                    default   => '',
                                };
                            @endphp
                            <span class="role-badge {{ $roleClass }}">
                                {!! $roleIcon !!}
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>

                        {{-- Ville --}}
                        <td>
                            @if($user->ville)
                                <div class="ville-cell">
                                    <span class="ville-dot"></span>
                                    {{ $user->ville->nom }}
                                </div>
                            @else
                                <span style="color:var(--text-hint);">—</span>
                            @endif
                        </td>

                        {{-- Statut --}}
                        <td>
                            @if($user->is_active)
                                <span class="status-badge status-actif">
                                    <span class="status-dot"></span>
                                    Actif
                                </span>
                            @else
                                <span class="status-badge status-inactif">
                                    <span class="status-dot"></span>
                                    Inactif
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div class="actions-cell">
                                <a href="{{ route('users.edit', $user) }}" class="btn-us btn-us-sm btn-us-warning">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                                    Modifier
                                </a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-us btn-us-sm btn-us-danger">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="us-empty">
                                <div class="us-empty-icon">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                </div>
                                <h3>Aucun utilisateur trouvé</h3>
                                <p>Commencez par créer votre premier utilisateur.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
        <div class="us-pagination">
            {{ $users->withQueryString()->links('vendor.pagination.custom') }}
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    const input = document.querySelector('.us-search-input');
    if (!input) return;
    input.addEventListener('keydown', e => {
        if (e.key === 'Escape') { input.value = ''; input.closest('form').submit(); }
    });
})();
</script>
@endpush
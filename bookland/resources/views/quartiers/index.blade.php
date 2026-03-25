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
.qt-page { padding: 2rem 2.5rem 3rem; animation: pageIn .4s var(--ease) both; }
@keyframes pageIn {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Breadcrumb ────────────────────────────────────── */
.qt-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.4rem; }
.qt-bc a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
.qt-bc a:hover { color: var(--blue); }
.qt-bc-sep { color: var(--text-hint); }
.qt-bc-cur { color: var(--text-secondary); }

/* ── Header ────────────────────────────────────────── */
.qt-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap; }
.qt-header-left h1 { font-size: 1.65rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.15; }
.qt-header-left p { font-size: .83rem; color: var(--text-muted); margin-top: .3rem; }

/* ── Buttons ───────────────────────────────────────── */
.btn-qt {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .56rem 1.1rem; border-radius: var(--r-sm);
    font-family: var(--font); font-size: .82rem; font-weight: 600;
    cursor: pointer; border: 1px solid transparent;
    transition: all var(--t); text-decoration: none;
    white-space: nowrap; letter-spacing: -.01em; line-height: 1;
}
.btn-qt svg { flex-shrink: 0; }
.btn-qt-primary { background: var(--blue); color: #fff; border-color: var(--blue); box-shadow: var(--shadow-blue); }
.btn-qt-primary:hover { background: var(--blue-dark); border-color: var(--blue-dark); color: #fff; text-decoration: none; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(91,141,238,.4); }
.btn-qt-ghost { background: var(--bg-card); color: var(--text-secondary); border-color: var(--border); box-shadow: var(--shadow-xs); }
.btn-qt-ghost:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--border-md); text-decoration: none; }
.btn-qt-danger-ghost { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.2); }
.btn-qt-danger-ghost:hover { background: #fddde2; color: var(--rose); text-decoration: none; }
.btn-qt-sm { padding: .38rem .72rem; font-size: .75rem; }
.btn-qt-warning { background: var(--amber-light); color: var(--amber); border-color: rgba(232,160,32,.2); }
.btn-qt-warning:hover { background: #ffefd4; color: var(--amber); text-decoration: none; }
.btn-qt-danger { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.18); }
.btn-qt-danger:hover { background: #fddde2; color: var(--rose); text-decoration: none; }

/* ── Stat Cards ────────────────────────────────────── */
.qt-stats { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
.qt-stat {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--r-lg); padding: 1.35rem 1.5rem;
    display: flex; align-items: center; gap: 1.1rem;
    box-shadow: var(--shadow-xs); transition: all var(--t);
    animation: pageIn .5s var(--ease) both;
    position: relative; overflow: hidden;
}
.qt-stat::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px; opacity: 0; transition: opacity var(--t);
    border-radius: var(--r-lg) var(--r-lg) 0 0;
}
.qt-stat:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); border-color: var(--border-md); }
.qt-stat:hover::before { opacity: 1; }
.qt-stat:nth-child(1) { animation-delay:.06s; } .qt-stat:nth-child(1)::before { background: var(--blue); }
.qt-stat:nth-child(2) { animation-delay:.11s; } .qt-stat:nth-child(2)::before { background: var(--teal); }
.qt-stat:nth-child(3) { animation-delay:.16s; } .qt-stat:nth-child(3)::before { background: var(--violet); }

.stat-ico { width: 44px; height: 44px; border-radius: var(--r-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.si-blue   { background: var(--blue-light);   color: var(--blue); }
.si-teal   { background: var(--teal-light);   color: var(--teal); }
.si-violet { background: var(--violet-light); color: var(--violet); }

.stat-label { font-size: .72rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .05em; }
.stat-value { font-size: 1.7rem; font-weight: 700; color: var(--text-primary); line-height: 1.1; letter-spacing: -.04em; margin-top: .06rem; }

/* ── Search ────────────────────────────────────────── */
.qt-search-bar { display: flex; align-items: center; gap: .6rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
.qt-search-wrap { position: relative; flex: 1; min-width: 220px; max-width: 380px; }
.qt-search-wrap svg { position: absolute; left: .85rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; }
.qt-search-input {
    width: 100%; padding: .56rem .9rem .56rem 2.35rem;
    border: 1px solid var(--border); border-radius: var(--r-sm);
    background: var(--bg-card); font-family: var(--font);
    font-size: .83rem; color: var(--text-primary);
    box-shadow: var(--shadow-xs); transition: all var(--t); outline: none;
}
.qt-search-input::placeholder { color: var(--text-muted); }
.qt-search-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); }
.search-pill {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .3rem .75rem; border-radius: 20px;
    background: var(--blue-light); color: var(--blue);
    border: 1px solid var(--blue-mid); font-size: .76rem; font-weight: 600;
}

/* ── Card ──────────────────────────────────────────── */
.qt-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-xl); box-shadow: var(--shadow-sm); overflow: hidden; }
.qt-card-header { padding: 1.1rem 1.6rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
.qt-card-title { font-size: .88rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: .55rem; letter-spacing: -.01em; }
.title-pip { width: 7px; height: 7px; border-radius: 50%; background: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); }
.qt-count { font-size: .76rem; color: var(--text-muted); font-weight: 500; }

/* ── Table ─────────────────────────────────────────── */
.qt-table { width: 100%; border-collapse: collapse; }
.qt-table thead tr { border-bottom: 1px solid var(--border); }
.qt-table th {
    padding: .85rem 1.2rem; font-size: .69rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: var(--text-hint); text-align: left;
    background: var(--bg-base); white-space: nowrap;
}
.qt-table td { padding: .95rem 1.2rem; font-size: .83rem; color: var(--text-secondary); border-bottom: 1px solid var(--border); vertical-align: middle; }
.qt-table tbody tr { transition: background var(--t); }
.qt-table tbody tr:hover { background: #f8f9fd; }
.qt-table tbody tr:last-child td { border-bottom: none; }

/* ID pill */
.id-pill { font-family: var(--font-mono); font-size: .75rem; color: var(--text-muted); font-weight: 500; background: var(--bg-subtle); border-radius: var(--r-xs); padding: .18rem .5rem; display: inline-block; }

/* Quartier name cell */
.qt-name-cell { display: flex; align-items: center; gap: .75rem; }
.qt-color-dot {
    width: 32px; height: 32px; border-radius: var(--r-sm);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-weight: 700; font-size: .72rem; color: #fff;
}
.qd-a { background: linear-gradient(135deg, #5b8dee, #6c63ff); }
.qd-b { background: linear-gradient(135deg, #0cb8b6, #00d4aa); }
.qd-c { background: linear-gradient(135deg, #7c6fcd, #b06ab3); }
.qd-d { background: linear-gradient(135deg, #e8a020, #f97316); }
.qd-e { background: linear-gradient(135deg, #e8506a, #ff6b9d); }
.qt-name-text { font-weight: 600; color: var(--text-primary); font-size: .84rem; letter-spacing: -.01em; }

/* Zone badge */
.zone-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .2rem .65rem; border-radius: 20px;
    font-size: .72rem; font-weight: 600;
    background: var(--violet-light); color: var(--violet);
    border: 1px solid rgba(124,111,205,.22);
}

/* Ville cell */
.ville-cell { display: flex; align-items: center; gap: .38rem; font-size: .81rem; color: var(--text-secondary); }
.ville-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--teal); flex-shrink: 0; }

/* Actions */
.actions-cell { display: flex; align-items: center; gap: .35rem; }

/* Empty */
.qt-empty { padding: 4rem 2rem; text-align: center; }
.qt-empty-icon { width: 52px; height: 52px; border-radius: var(--r-md); background: var(--bg-subtle); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--text-hint); }
.qt-empty h3 { font-size: .95rem; font-weight: 700; color: var(--text-secondary); }
.qt-empty p  { font-size: .82rem; color: var(--text-muted); margin-top: .3rem; }

/* Pagination */
/* ── Pagination fix ──────────────────────────────────── */

/* Responsive */
@media (max-width: 768px) {
    .qt-page { padding: 1.25rem 1rem 2rem; }
    .qt-header { flex-direction: column; gap: 1rem; }
    .qt-stats { grid-template-columns: 1fr 1fr; }
    .qt-table th, .qt-table td { padding: .75rem .9rem; }
}
@media (max-width: 480px) { .qt-stats { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="qt-page">

    {{-- Breadcrumb --}}
    <div class="qt-bc">
        <a href="{{ route('quartiers.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </a>
        <span class="qt-bc-sep">›</span>
        <a href="#">Géographie</a>
        <span class="qt-bc-sep">›</span>
        <span class="qt-bc-cur">Quartiers</span>
    </div>

    {{-- Header --}}
    <div class="qt-header">
        <div class="qt-header-left">
            <h1>Quartiers</h1>
            <p>Gérez les quartiers rattachés à vos zones et villes</p>
        </div>
        <a href="{{ route('quartiers.create') }}" class="btn-qt btn-qt-primary">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouveau quartier
        </a>
    </div>

    {{-- Stat Cards --}}
    @php
        $totalQuartiers = $quartiers->total();
        $zonesCount     = $quartiers->getCollection()->pluck('zone.name')->unique()->filter()->count();
        $villesCount    = $quartiers->getCollection()->pluck('zone.ville.nom')->unique()->filter()->count();
    @endphp
    <div class="qt-stats">
        <div class="qt-stat">
            <div class="stat-ico si-blue">
                <svg width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <div>
                <div class="stat-label">Quartiers</div>
                <div class="stat-value">{{ $totalQuartiers }}</div>
            </div>
        </div>
        <div class="qt-stat">
            <div class="stat-ico si-teal">
                <svg width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
            </div>
            <div>
                <div class="stat-label">Zones</div>
                <div class="stat-value">{{ $zonesCount }}</div>
            </div>
        </div>
        <div class="qt-stat">
            <div class="stat-ico si-violet">
                <svg width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div>
                <div class="stat-label">Villes</div>
                <div class="stat-value">{{ $villesCount }}</div>
            </div>
        </div>
    </div>

    {{-- Search --}}
    <div class="qt-search-bar">
        <form method="GET" action="{{ route('quartiers.index') }}" style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;">
            <div class="qt-search-wrap">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input
                    type="text"
                    name="search"
                    class="qt-search-input"
                    placeholder="Rechercher un quartier, zone, ville…"
                    value="{{ request('search') }}"
                    autocomplete="off"
                >
            </div>
            <button type="submit" class="btn-qt btn-qt-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Filtrer
            </button>
            @if(request('search'))
                <span class="search-pill">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    «&nbsp;{{ request('search') }}&nbsp;»
                </span>
                <a href="{{ route('quartiers.index') }}" class="btn-qt btn-qt-danger-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- Table Card --}}
    <div class="qt-card">
        <div class="qt-card-header">
            <div class="qt-card-title">
                <span class="title-pip"></span>
                Liste des quartiers
            </div>
            <span class="qt-count">{{ $quartiers->total() }} résultat{{ $quartiers->total() > 1 ? 's' : '' }}</span>
        </div>

        <div style="overflow-x: auto;">
            <table class="qt-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Quartier</th>
                        <th>Zone</th>
                        <th>Ville</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $dots = ['qd-a','qd-b','qd-c','qd-d','qd-e']; @endphp
                    @forelse($quartiers as $i => $quartier)
                    <tr>
                        <td><span class="id-pill">{{ $quartier->id }}</span></td>

                        <td>
                            <div class="qt-name-cell">
                                <div class="qt-color-dot {{ $dots[$i % count($dots)] }}">
                                    {{ strtoupper(substr($quartier->nom, 0, 2)) }}
                                </div>
                                <span class="qt-name-text">{{ $quartier->nom }}</span>
                            </div>
                        </td>

                        <td>
                            <span class="zone-badge">
                                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                                {{ $quartier->zone->name }}
                            </span>
                        </td>

                        <td>
                            <div class="ville-cell">
                                <span class="ville-dot"></span>
                                {{ $quartier->zone->ville->nom }}
                            </div>
                        </td>

                        <td>
                            <div class="actions-cell">
                                <a href="{{ route('quartiers.edit', $quartier) }}" class="btn-qt btn-qt-sm btn-qt-warning">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                                    Modifier
                                </a>
                                <form action="{{ route('quartiers.destroy', $quartier) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce quartier ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-qt btn-qt-sm btn-qt-danger">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="qt-empty">
                                <div class="qt-empty-icon">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                </div>
                                <h3>Aucun quartier trouvé</h3>
                                <p>{{ request('search') ? 'Aucun résultat pour «\u00a0'.request('search').'\u00a0». Essayez un autre terme.' : 'Commencez par créer votre premier quartier.' }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($quartiers->hasPages())
        <div class="qt-pagination">
            {{ $quartiers->withQueryString()->links('vendor.pagination.custom') }}
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    const input = document.querySelector('.qt-search-input');
    if (!input) return;
    input.addEventListener('keydown', e => {
        if (e.key === 'Escape') { input.value = ''; input.closest('form').submit(); }
    });
})();
</script>
@endpush
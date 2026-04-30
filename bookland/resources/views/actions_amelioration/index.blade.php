@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --bg:        #f5f6fa; --card:  #ffffff; --hover: #f8f9fd; --subtle: #f0f2f8;
    --border:    #e4e7f0; --border-2: #d0d5e8;
    --blue:      #5b8dee; --blue-d: #3d6fd6; --blue-l: #eef3fd; --blue-m: #dce8fb;
    --teal:      #0cb8b6; --teal-l: #e6faf9;
    --violet:    #7c6fcd; --violet-l: #f0eeff;
    --amber:     #e8a020; --amber-l: #fff8ec;
    --rose:      #e8506a; --rose-l: #fef0f2;
    --green:     #28c76f; --green-l: #e8fbf0;
    --t1: #1a1f36; --t2: #525f7f; --t3: #9ba8c5; --t4: #bcc5dc;
    --r-xs: 6px; --r-sm: 8px; --r-md: 12px; --r-lg: 16px; --r-xl: 20px;
    --s1: 0 1px 3px rgba(31,45,80,.06); --s2: 0 2px 8px rgba(31,45,80,.08); --s3: 0 8px 24px rgba(31,45,80,.10);
    --sb: 0 4px 14px rgba(91,141,238,.32);
    --font: 'DM Sans', sans-serif; --mono: 'DM Mono', monospace;
    --ease: cubic-bezier(.4,0,.2,1); --t: .17s var(--ease);
}

body { font-family: var(--font); background: var(--bg); color: var(--t1); -webkit-font-smoothing: antialiased; }

/* ── Page ── */
.aa-page { padding: 2rem 2.5rem 3rem; animation: rise .4s var(--ease) both; }
@keyframes rise { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

/* ── Breadcrumb ── */
.aa-bc { display: flex; align-items: center; gap: .4rem; font-size: .75rem; font-weight: 500; color: var(--t3); margin-bottom: 1.5rem; }
.aa-bc a { color: var(--t3); text-decoration: none; transition: color var(--t); }
.aa-bc a:hover { color: var(--blue); }
.aa-bc-sep { color: var(--t4); }

/* ── Header ── */
.aa-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap; }
.aa-header-left h1 { font-size: 1.6rem; font-weight: 800; letter-spacing: -.03em; color: var(--t1); }
.aa-header-left p  { font-size: .83rem; color: var(--t3); margin-top: .3rem; }

/* ── Buttons ── */
.btn-aa {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .56rem 1.1rem; border-radius: var(--r-sm);
    font-family: var(--font); font-size: .82rem; font-weight: 600;
    cursor: pointer; border: 1px solid transparent;
    transition: all var(--t); text-decoration: none;
    white-space: nowrap; letter-spacing: -.01em; line-height: 1;
}
.btn-aa-primary { background: var(--blue); color: #fff; border-color: var(--blue); box-shadow: var(--sb); }
.btn-aa-primary:hover { background: var(--blue-d); color: #fff; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(91,141,238,.4); text-decoration: none; }
.btn-aa-ghost { background: var(--card); color: var(--t2); border-color: var(--border); box-shadow: var(--s1); }
.btn-aa-ghost:hover { background: var(--hover); color: var(--t1); border-color: var(--border-2); text-decoration: none; }
.btn-aa-warning { background: var(--amber-l); color: var(--amber); border-color: rgba(232,160,32,.2); }
.btn-aa-warning:hover { background: #ffefd4; color: var(--amber); text-decoration: none; }
.btn-aa-danger { background: var(--rose-l); color: var(--rose); border-color: rgba(232,80,106,.18); }
.btn-aa-danger:hover { background: #fddde2; color: var(--rose); text-decoration: none; }
.btn-aa-info { background: var(--violet-l); color: var(--violet); border-color: rgba(124,111,205,.2); }
.btn-aa-info:hover { background: #e4deff; color: var(--violet); text-decoration: none; }
.btn-aa-sm { padding: .34rem .7rem; font-size: .74rem; }

/* ── Filter bar ── */
.aa-filters {
    background: var(--card); border: 1px solid var(--border);
    border-radius: var(--r-lg); padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem; box-shadow: var(--s1);
}
.aa-filter-row { display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end; }
.aa-filter-group { display: flex; flex-direction: column; gap: .32rem; min-width: 160px; }
.aa-filter-label { font-size: .68rem; font-weight: 700; color: var(--t3); text-transform: uppercase; letter-spacing: .06em; }
.aa-select {
    padding: .5rem .85rem; border: 1px solid var(--border);
    border-radius: var(--r-sm); background: var(--card);
    font-family: var(--font); font-size: .82rem; color: var(--t1);
    box-shadow: var(--s1); transition: all var(--t); outline: none; cursor: pointer;
}
.aa-select:focus { border-color: var(--blue); box-shadow: 0 0 0 3px var(--blue-m); }
.aa-filter-actions { display: flex; align-items: flex-end; gap: .5rem; flex-wrap: wrap; }

/* ── Main card ── */
.aa-card { background: var(--card); border: 1px solid var(--border); border-radius: var(--r-xl); box-shadow: var(--s2); overflow: hidden; }
.aa-card-hd {
    padding: 1rem 1.6rem; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    background: linear-gradient(to bottom, #fafbff, #fff);
}
.aa-card-title { font-size: .88rem; font-weight: 700; color: var(--t1); display: flex; align-items: center; gap: .5rem; }
.aa-pip { width: 7px; height: 7px; border-radius: 50%; background: var(--blue); box-shadow: 0 0 0 3px var(--blue-m); }
.aa-card-count { font-size: .75rem; color: var(--t3); font-weight: 500; }

/* ── Table ── */
.aa-table { width: 100%; border-collapse: collapse; }
.aa-table thead tr { border-bottom: 1px solid var(--border); }
.aa-table th {
    padding: .85rem 1.2rem; font-size: .67rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .09em;
    color: var(--t4); text-align: left;
    background: var(--bg); white-space: nowrap;
}
.aa-table td { padding: .92rem 1.2rem; font-size: .83rem; color: var(--t2); border-bottom: 1px solid var(--border); vertical-align: middle; }
.aa-table tbody tr { transition: background var(--t); }
.aa-table tbody tr:hover { background: var(--hover); }
.aa-table tbody tr:last-child td { border-bottom: none; }

/* Numero cell */
.aa-num { font-family: var(--mono); font-size: .78rem; color: var(--blue); font-weight: 600; background: var(--blue-l); padding: .18rem .6rem; border-radius: 20px; border: 1px solid var(--blue-m); white-space: nowrap; display: inline-block; }

/* Date cell */
.aa-date { font-family: var(--mono); font-size: .76rem; color: var(--t3); }

/* Status badges */
.aa-badge { display: inline-flex; align-items: center; gap: .25rem; padding: .2rem .65rem; border-radius: 20px; font-size: .69rem; font-weight: 600; border: 1px solid transparent; white-space: nowrap; }
.badge-ouvert    { background: var(--blue-l);   color: var(--blue);   border-color: var(--blue-m); }
.badge-en_cours  { background: var(--amber-l);  color: var(--amber);  border-color: rgba(232,160,32,.2); }
.badge-cloture   { background: var(--green-l);  color: #1aaa5e;       border-color: rgba(40,199,111,.2); }
.badge-annule    { background: var(--subtle);   color: var(--t3);     border-color: var(--border); }
.badge-default   { background: var(--subtle);   color: var(--t3);     border-color: var(--border); }

/* Actions cell */
.aa-actions { display: flex; align-items: center; gap: .35rem; flex-wrap: wrap; }

/* Empty */
.aa-empty { padding: 4rem 2rem; text-align: center; }
.aa-empty-icon { width: 52px; height: 52px; border-radius: var(--r-md); background: var(--subtle); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--t4); }
.aa-empty h3 { font-size: .95rem; font-weight: 700; color: var(--t2); }
.aa-empty p  { font-size: .82rem; color: var(--t3); margin-top: .3rem; }

/* Pagination */
.aa-pagination { padding: 1rem 1.5rem; border-top: 1px solid var(--border); background: var(--card); display: flex; justify-content: center; }

/* Responsive */
@media (max-width: 768px) {
    .aa-page { padding: 1.25rem 1rem 2rem; }
    .aa-header { flex-direction: column; gap: 1rem; }
    .aa-filter-row { flex-direction: column; align-items: stretch; }
    .aa-filter-actions { justify-content: flex-end; }
    .aa-table th, .aa-table td { padding: .7rem .9rem; }
    .aa-actions { flex-direction: column; align-items: flex-start; }
}
</style>
@endpush

@section('content')
<div class="aa-page">

    {{-- Breadcrumb --}}
    <div class="aa-bc">
        <a href="{{ route('actions-amelioration.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </a>
        <span class="aa-bc-sep">›</span>
        <span style="color:var(--t2);font-weight:600;">Actions d'amélioration</span>
    </div>

    {{-- Header --}}
    <div class="aa-header">
        <div class="aa-header-left">
            <h1>Actions d'amélioration</h1>
            <p>Suivi et gestion des actions correctives et préventives</p>
        </div>
        @if(auth()->user()->role !== 'rbo')
        <a href="{{ route('actions-amelioration.create') }}" class="btn-aa btn-aa-primary">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouvelle action
        </a>
        @endif
    </div>

    {{-- Filters --}}
    <div class="aa-filters">
        <form method="GET" action="{{ route('actions-amelioration.index') }}">
            <div class="aa-filter-row">
                <div class="aa-filter-group">
                    <label class="aa-filter-label">Statut</label>
                    <select name="statut" class="aa-select">
                        <option value="">Tous statuts</option>
                        @foreach($statuts as $s)
                            <option value="{{ $s }}" {{ request('statut') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="aa-filter-group">
                    <label class="aa-filter-label">Type</label>
                    <select name="type" class="aa-select">
                        <option value="">Tous types</option>
                        @foreach($types as $t)
                            <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="aa-filter-group">
                    <label class="aa-filter-label">Compte</label>
                    <select name="compte_id" class="aa-select">
                        <option value="">Tous comptes</option>
                        @foreach($comptes as $c)
                            <option value="{{ $c->id }}" {{ request('compte_id') == $c->id ? 'selected' : '' }}>{{ $c->etablissement }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="aa-filter-actions">
                    <button type="submit" class="btn-aa btn-aa-sm btn-aa-ghost">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Filtrer
                    </button>
                    <a href="{{ route('actions-amelioration.index') }}" class="btn-aa btn-aa-sm btn-aa-danger">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        Réinitialiser
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Table card --}}
    <div class="aa-card">
        <div class="aa-card-hd">
            <div class="aa-card-title">
                <span class="aa-pip"></span>
                Liste des actions
            </div>
            <span class="aa-card-count">{{ $actions->total() }} action{{ $actions->total() > 1 ? 's' : '' }}</span>
        </div>

        <div style="overflow-x:auto;">
            <table class="aa-table">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Compte</th>
                        <th>Type</th>
                        <th>Origine</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actions as $a)
                    @php
                        $badgeClass = match($a->statut) {
                            'ouvert'   => 'badge-ouvert',
                            'en_cours' => 'badge-en_cours',
                            'cloture'  => 'badge-cloture',
                            'annule'   => 'badge-annule',
                            default    => 'badge-default',
                        };
                    @endphp
                    <tr>
                        <td><span class="aa-num">{{ $a->numero }}</span></td>
                        <td style="font-weight:600;color:var(--t1);">{{ $a->compte->etablissement }}</td>
                        <td>{{ $a->type }}</td>
                        <td style="color:var(--t3);font-size:.8rem;">{{ $a->origine }}</td>
                        <td><span class="aa-date">{{ $a->dateAA->format('d/m/Y') }}</span></td>
                        <td><span class="aa-badge {{ $badgeClass }}">{{ ucfirst($a->statut) }}</span></td>
                        <td>
                            <div class="aa-actions">
                                <a href="{{ route('actions-amelioration.show', $a) }}" class="btn-aa btn-aa-sm btn-aa-info">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Voir
                                </a>
                                @if(auth()->user()->role !== 'rbo')
                                <a href="{{ route('actions-amelioration.edit', $a) }}" class="btn-aa btn-aa-sm btn-aa-warning">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                                    Modifier
                                </a>
                                @endif
                                @if(auth()->user()->role === 'admin')
                                <form method="POST" action="{{ route('actions-amelioration.destroy', $a) }}" style="display:inline;" onsubmit="return confirm('Supprimer cette action ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-aa btn-aa-sm btn-aa-danger">
                                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="aa-empty">
                                <div class="aa-empty-icon">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                </div>
                                <h3>Aucune action d'amélioration</h3>
                                <p>{{ request('statut') || request('type') || request('compte_id') ? 'Aucun résultat pour les filtres sélectionnés.' : 'Commencez par créer une nouvelle action.' }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($actions->hasPages())
        <div class="aa-pagination">
            {{ $actions->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
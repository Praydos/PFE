@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
:root {
    --bg:#f5f6fa; --card:#ffffff; --hover:#f8f9fd; --subtle:#f0f2f8;
    --border:#e4e7f0; --border-2:#d0d5e8;
    --blue:#5b8dee; --blue-d:#3d6fd6; --blue-l:#eef3fd; --blue-m:#dce8fb;
    --teal:#0cb8b6; --teal-l:#e6faf9;
    --violet:#7c6fcd; --violet-l:#f0eeff;
    --amber:#e8a020; --amber-l:#fff8ec;
    --rose:#e8506a; --rose-l:#fef0f2;
    --green:#28c76f; --green-l:#e8fbf0;
    --t1:#1a1f36; --t2:#525f7f; --t3:#9ba8c5; --t4:#bcc5dc;
    --r1:6px; --r2:8px; --r3:12px; --r4:16px; --r5:20px;
    --s1:0 1px 3px rgba(31,45,80,.06); --s2:0 2px 8px rgba(31,45,80,.08); --s3:0 8px 24px rgba(31,45,80,.10);
    --sb:0 4px 14px rgba(91,141,238,.32);
    --font:'DM Sans',sans-serif; --mono:'DM Mono',monospace;
    --ease:cubic-bezier(.4,0,.2,1); --t:.17s var(--ease);
}
body { font-family:var(--font); background:var(--bg); color:var(--t1); -webkit-font-smoothing:antialiased; }
.tk-page { padding:2rem 2.5rem 3rem; animation:rise .4s var(--ease) both; }
@keyframes rise { from{opacity:0;transform:translateY(12px);}to{opacity:1;transform:translateY(0);} }
.tk-bc { display:flex; align-items:center; gap:.4rem; font-size:.75rem; font-weight:500; color:var(--t3); margin-bottom:1.5rem; }
.tk-bc a { color:var(--t3); text-decoration:none; transition:color var(--t); }
.tk-bc a:hover { color:var(--blue); }
.tk-bc-s { color:var(--t4); }
.tk-header { display:flex; align-items:flex-start; justify-content:space-between; gap:1.5rem; margin-bottom:2rem; flex-wrap:wrap; }
.tk-header-left h1 { font-size:1.6rem; font-weight:800; letter-spacing:-.03em; color:var(--t1); }
.tk-header-left p  { font-size:.83rem; color:var(--t3); margin-top:.3rem; }
/* Buttons */
.btn-tk { display:inline-flex; align-items:center; gap:.4rem; padding:.54rem 1.1rem; border-radius:var(--r2); font-family:var(--font); font-size:.81rem; font-weight:600; cursor:pointer; border:1px solid transparent; transition:all var(--t); text-decoration:none; white-space:nowrap; letter-spacing:-.01em; line-height:1; }
.btn-tk svg { flex-shrink:0; }
.btn-tk-primary { background:var(--blue); color:#fff; border-color:var(--blue); box-shadow:var(--sb); }
.btn-tk-primary:hover { background:var(--blue-d); color:#fff; text-decoration:none; transform:translateY(-1px); }
.btn-tk-ghost { background:var(--card); color:var(--t2); border-color:var(--border); box-shadow:var(--s1); }
.btn-tk-ghost:hover { background:var(--hover); color:var(--t1); border-color:var(--border-2); text-decoration:none; }
.btn-tk-danger-ghost { background:var(--rose-l); color:var(--rose); border-color:rgba(232,80,106,.2); }
.btn-tk-danger-ghost:hover { background:#fddde2; color:var(--rose); text-decoration:none; }
.btn-tk-warning { background:var(--amber-l); color:var(--amber); border-color:rgba(232,160,32,.2); }
.btn-tk-warning:hover { background:#ffefd4; color:var(--amber); text-decoration:none; }
.btn-tk-danger { background:var(--rose-l); color:var(--rose); border-color:rgba(232,80,106,.18); }
.btn-tk-danger:hover { background:#fddde2; color:var(--rose); text-decoration:none; }
.btn-tk-success { background:var(--green-l); color:#1aaa5e; border-color:rgba(40,199,111,.22); }
.btn-tk-success:hover { background:#d0f5e1; color:#1aaa5e; text-decoration:none; }
.btn-tk-sm { padding:.33rem .7rem; font-size:.74rem; }
/* Stats */
.tk-stats { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:1rem; margin-bottom:2rem; }
.tk-stat { background:var(--card); border:1px solid var(--border); border-radius:var(--r4); padding:1.25rem 1.4rem; display:flex; align-items:center; gap:1rem; box-shadow:var(--s1); transition:all var(--t); animation:rise .5s var(--ease) both; position:relative; overflow:hidden; }
.tk-stat::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; opacity:0; transition:opacity var(--t); border-radius:var(--r4) var(--r4) 0 0; }
.tk-stat:hover { box-shadow:var(--s3); transform:translateY(-2px); border-color:var(--border-2); }
.tk-stat:hover::before { opacity:1; }
.tk-stat:nth-child(1){animation-delay:.06s;} .tk-stat:nth-child(1)::before{background:var(--blue);}
.tk-stat:nth-child(2){animation-delay:.11s;} .tk-stat:nth-child(2)::before{background:var(--amber);}
.tk-stat:nth-child(3){animation-delay:.16s;} .tk-stat:nth-child(3)::before{background:var(--green);}
.stat-ico { width:42px; height:42px; border-radius:var(--r3); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.si-blue  { background:var(--blue-l); color:var(--blue); }
.si-amber { background:var(--amber-l); color:var(--amber); }
.si-green { background:var(--green-l); color:var(--green); }
.stat-label { font-size:.7rem; font-weight:600; color:var(--t3); text-transform:uppercase; letter-spacing:.05em; }
.stat-value { font-size:1.6rem; font-weight:800; color:var(--t1); line-height:1.1; letter-spacing:-.04em; margin-top:.04rem; }
/* Filter bar */
.tk-filters { background:var(--card); border:1px solid var(--border); border-radius:var(--r4); box-shadow:var(--s1); padding:1rem 1.4rem; margin-bottom:1.25rem; display:flex; align-items:flex-end; gap:.85rem; flex-wrap:wrap; }
.tk-filter-grp { display:flex; flex-direction:column; gap:.35rem; min-width:160px; flex:1; }
.tk-filter-label { font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--t4); }
.tk-sel-wrap { position:relative; }
.tk-sel-wrap::after { content:''; position:absolute; right:.8rem; top:50%; transform:translateY(-50%); width:0; height:0; border-left:4px solid transparent; border-right:4px solid transparent; border-top:5px solid var(--t3); pointer-events:none; }
.tk-select { width:100%; padding:.52rem 2rem .52rem .85rem; border:1px solid var(--border); border-radius:var(--r2); background:var(--card); font-family:var(--font); font-size:.82rem; color:var(--t1); box-shadow:var(--s1); transition:all var(--t); outline:none; appearance:none; -webkit-appearance:none; cursor:pointer; }
.tk-select:focus { border-color:var(--blue); box-shadow:0 0 0 3px var(--blue-m); }
/* Card */
.tk-card { background:var(--card); border:1px solid var(--border); border-radius:var(--r5); box-shadow:var(--s2); overflow:hidden; }
.tk-card-hd { padding:1rem 1.6rem; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; gap:1rem; }
.tk-card-title { font-size:.87rem; font-weight:700; color:var(--t1); display:flex; align-items:center; gap:.5rem; }
.tk-pip { width:7px; height:7px; border-radius:50%; background:var(--blue); box-shadow:0 0 0 3px var(--blue-m); }
.tk-count { font-size:.76rem; color:var(--t3); font-weight:500; }
/* Table */
.tk-table { width:100%; border-collapse:collapse; }
.tk-table thead tr { border-bottom:1px solid var(--border); }
.tk-table th { padding:.8rem 1.2rem; font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.09em; color:var(--t4); text-align:left; background:var(--subtle); white-space:nowrap; }
.tk-table td { padding:.9rem 1.2rem; font-size:.83rem; color:var(--t2); border-bottom:1px solid var(--border); vertical-align:middle; }
.tk-table tbody tr { transition:background var(--t); }
.tk-table tbody tr:hover { background:var(--hover); }
.tk-table tbody tr:last-child td { border-bottom:none; }
/* Objet cell */
.objet-cell { font-weight:600; color:var(--t1); font-size:.84rem; letter-spacing:-.01em; }
/* Date cell */
.date-cell { font-family:var(--mono); font-size:.78rem; color:var(--t2); }
/* Status badges */
.st-badge { display:inline-flex; align-items:center; gap:.28rem; padding:.22rem .65rem; border-radius:20px; font-size:.7rem; font-weight:600; border:1px solid transparent; white-space:nowrap; }
.st-dot { width:5px; height:5px; border-radius:50%; flex-shrink:0; }
.st-pending  { background:var(--amber-l); color:var(--amber); border-color:rgba(232,160,32,.22); } .st-pending  .st-dot { background:var(--amber); }
.st-validated { background:var(--green-l); color:#1aaa5e; border-color:rgba(40,199,111,.22); }     .st-validated .st-dot { background:var(--green); }
/* Actions cell */
.actions-cell { display:flex; align-items:center; gap:.3rem; flex-wrap:wrap; }
/* Empty */
.tk-empty { padding:4rem 2rem; text-align:center; }
.tk-empty-icon { width:52px; height:52px; border-radius:var(--r3); background:var(--subtle); border:1px solid var(--border); display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; color:var(--t4); }
.tk-empty h3 { font-size:.95rem; font-weight:700; color:var(--t2); }
.tk-empty p  { font-size:.82rem; color:var(--t3); margin-top:.3rem; }
/* Pagination */
.tk-pagination { padding:1rem 1.6rem; border-top:1px solid var(--border); background:var(--bg); display:flex; align-items:center; justify-content:flex-end; }
/* Responsive */
@media(max-width:768px) {
    .tk-page { padding:1.25rem 1rem 2rem; }
    .tk-header { flex-direction:column; gap:1rem; }
    .tk-stats { grid-template-columns:1fr 1fr; }
    .tk-table th,.tk-table td { padding:.7rem .9rem; }
}
</style>
@endpush

@section('content')
<div class="tk-page">

    <div class="tk-bc">
        <a href="{{ route('taches.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </a>
        <span class="tk-bc-s">›</span>
        <span style="color:var(--t2);font-weight:600;">Tâches</span>
    </div>

    <div class="tk-header">
        <div class="tk-header-left">
            <h1>Tâches</h1>
            <p>Gérez vos tâches planifiées et leur validation</p>
        </div>
        @if(auth()->user()->role === 'delegue')
        <a href="{{ route('taches.create') }}" class="btn-tk btn-tk-primary">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouvelle tâche
        </a>
        @endif
    </div>

    

    <div class="tk-filters">
        <form method="GET" action="{{ route('taches.index') }}" style="display:contents;">
            <div class="tk-filter-grp">
                <label class="tk-filter-label">Statut</label>
                <div class="tk-sel-wrap">
                    <select name="statut" class="tk-select">
                        <option value="">Tous les statuts</option>
                        <option value="pending"    {{ request('statut')==='pending'    ? 'selected':'' }}>En attente</option>
                        <option value="validated"  {{ request('statut')==='validated'  ? 'selected':'' }}>Validées</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;gap:.5rem;align-items:flex-end;">
                <button type="submit" class="btn-tk btn-tk-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    Filtrer
                </button>
                @if(request('statut'))
                <a href="{{ route('taches.index') }}" class="btn-tk btn-tk-danger-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Réinitialiser
                </a>
                @endif
            </div>
        </form>
    </div>

    <div class="tk-card">
        <div class="tk-card-hd">
            <div class="tk-card-title"><span class="tk-pip"></span>Liste des tâches</div>
            <span class="tk-count">{{ $taches->total() }} tâche{{ $taches->total()>1?'s':'' }}</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="tk-table">
                <thead>
                    <tr>
                        <th>Objet</th>
                        <th>Date planification</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($taches as $t)
                    <tr>
                        <td><span class="objet-cell">{{ $t->objet }}</span></td>
                        <td><span class="date-cell">{{ $t->date_planification->format('d/m/Y') }}</span></td>
                        <td>
                            @if($t->is_validated)
                                <span class="st-badge st-validated"><span class="st-dot"></span>Validée</span>
                            @else
                                <span class="st-badge st-pending"><span class="st-dot"></span>En attente</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions-cell">
                                <a href="{{ route('taches.show', $t) }}" class="btn-tk btn-tk-sm btn-tk-ghost">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Voir
                                </a>
                                @if(!$t->is_validated && auth()->user()->role === 'delegue' && $t->delegue_id === auth()->id())
                                    <a href="{{ route('taches.edit', $t) }}" class="btn-tk btn-tk-sm btn-tk-warning">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('taches.destroy', $t) }}" style="display:inline;" onsubmit="return confirm('Supprimer cette tâche ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-tk btn-tk-sm btn-tk-danger">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                        </button>
                                    </form>
                                @endif
                                @if(!$t->is_validated && in_array(auth()->user()->role, ['admin','rbo']))
                                    <form method="POST" action="{{ route('taches.validate', $t) }}" style="display:inline;" onsubmit="return confirm('Valider cette tâche ?')">
                                        @csrf
                                        <button type="submit" class="btn-tk btn-tk-sm btn-tk-success">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                            Valider
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4">
                        <div class="tk-empty">
                            <div class="tk-empty-icon"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>
                            <h3>Aucune tâche trouvée</h3>
                            <p>{{ request('statut') ? 'Aucun résultat pour ce filtre.' : 'Commencez par créer une nouvelle tâche.' }}</p>
                        </div>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($taches->hasPages())
        <div class="tk-pagination">{{ $taches->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
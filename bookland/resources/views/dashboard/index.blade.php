@extends('layouts.app')

@push('styles')
<style>
.db-page { padding: 2rem 2.5rem 3rem; animation: dbRise .4s cubic-bezier(.4,0,.2,1) both; }
@keyframes dbRise { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:none} }

.db-bc { display:flex; align-items:center; gap:.4rem; font-size:.75rem; color:var(--text-muted); margin-bottom:1.25rem; }
.db-header { margin-bottom:1.75rem; }
.db-header h1 { font-size:1.55rem; font-weight:800; letter-spacing:-.03em; }
.db-header p { font-size:.84rem; color:var(--text-muted); margin-top:.25rem; }

.db-filter {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--r-lg); padding: 1.1rem 1.35rem; margin-bottom: 1.75rem;
    box-shadow: var(--shadow-xs);
}
.db-filter-row { display:flex; flex-wrap:wrap; gap:1rem; align-items:flex-end; }
.db-filter-grp { display:flex; flex-direction:column; gap:.35rem; min-width:200px; flex:1; }
.db-filter-grp label { font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:var(--text-hint); }
.db-filter-grp select {
    padding:.5rem .75rem; border:1px solid var(--border); border-radius:var(--r-sm);
    font-family:var(--font); font-size:.82rem; background:var(--bg-card); color:var(--text-primary);
}

.db-section { margin-bottom: 2.25rem; }
.db-section-title {
    font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.12em;
    color:var(--text-muted); margin-bottom:1rem; display:flex; align-items:center; gap:.5rem;
}
.db-section-title::after { content:''; flex:1; height:1px; background:var(--border); }

.db-stats { display:grid; grid-template-columns:repeat(auto-fill, minmax(170px, 1fr)); gap:1rem; margin-bottom:1.25rem; }
.db-stat {
    background:var(--bg-card); border:1px solid var(--border); border-radius:var(--r-md);
    padding:1.15rem 1.25rem; box-shadow:var(--shadow-xs); position:relative; overflow:hidden;
}
.db-stat::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; }
.db-stat.accent-blue::before { background:var(--blue); }
.db-stat.accent-teal::before { background:var(--teal); }
.db-stat.accent-green::before { background:var(--green); }
.db-stat.accent-amber::before { background:var(--amber); }
.db-stat.accent-violet::before { background:var(--violet); }
.db-stat.accent-rose::before { background:var(--rose); }
.db-stat-label { font-size:.68rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); }
.db-stat-value { font-size:1.55rem; font-weight:800; letter-spacing:-.03em; margin-top:.15rem; }
.db-stat-sub { font-size:.72rem; color:var(--text-muted); margin-top:.2rem; }

.db-charts { display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:1.25rem; }
.db-chart-card {
    background:var(--bg-card); border:1px solid var(--border); border-radius:var(--r-md);
    padding:1.25rem; box-shadow:var(--shadow-xs);
}
.db-chart-card h3 { font-size:.88rem; font-weight:700; margin-bottom:1rem; color:var(--text-secondary); }
.db-chart-wrap { height:260px; position:relative; }

.db-empty {
    text-align:center; padding:2.5rem; color:var(--text-muted); font-size:.9rem;
    background:var(--bg-card); border:1px dashed var(--border); border-radius:var(--r-md);
}
.db-pills { display:flex; flex-wrap:wrap; gap:.5rem; margin-top:.75rem; }
.db-pill {
    display:inline-flex; align-items:center; gap:.35rem; padding:.35rem .75rem;
    border-radius:999px; font-size:.78rem; font-weight:600; text-decoration:none;
    border:1px solid var(--border); color:var(--text-secondary); background:var(--bg-card);
    transition:all .15s ease;
}
.db-pill:hover, .db-pill.active { border-color:var(--blue); color:var(--blue); background:var(--blue-light); }
</style>
@endpush

@section('content')
@php
    $ds = $data['delegate_stats'] ?? null;
    $rbo = $data['rbo_stats'] ?? null;
    $admin = $data['admin_stats'] ?? null;
    $delegateName = $data['delegate']['name'] ?? null;
@endphp

<div class="db-page">
    <div class="db-bc">
        <span>Bookland CRM</span>
        <span>/</span>
        <span>Tableau de bord</span>
    </div>

    <div class="db-header">
        <h1>Tableau de bord</h1>
        <p>
            @if(auth()->user()->role === 'delegue')
                Vos indicateurs de performance
            @elseif(auth()->user()->role === 'rbo')
                @if($delegateName)
                    Statistiques de {{ $delegateName }}
                @else
                    Sélectionnez un délégué pour voir ses indicateurs
                @endif
            @else
                @if($delegateName)
                    Vue délégué — {{ $delegateName }}
                @elseif($selectedRboId && $rbo)
                    Vue RBO — équipe et validations
                @else
                    Vue exécutive et statistiques globales
                @endif
            @endif
        </p>
    </div>

    @if(in_array(auth()->user()->role, ['rbo', 'admin', 'abo']))
    <form method="GET" action="{{ route('dashboard.index') }}" class="db-filter" id="dashboard-filter-form">
        <div class="db-filter-row">
            @if(in_array(auth()->user()->role, ['admin', 'abo']) && $rboList->count())
            <div class="db-filter-grp">
                <label for="rbo_id">RBO</label>
                <select name="rbo_id" id="rbo_id" onchange="this.form.submit()">
                    <option value="">— Tous / vue globale —</option>
                    @foreach($rboList as $rboUser)
                        <option value="{{ $rboUser->id }}" @selected($selectedRboId == $rboUser->id)>
                            {{ $rboUser->prenom }} {{ $rboUser->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            @if(auth()->user()->role === 'rbo' || (in_array(auth()->user()->role, ['admin','abo']) && $selectedRboId))
            <div class="db-filter-grp">
                <label for="delegate_id">Délégué</label>
                <select name="delegate_id" id="delegate_id" onchange="this.form.submit()">
                    <option value="">— Sélectionner un délégué —</option>
                    @foreach($delegateList as $dlg)
                        <option value="{{ $dlg->id }}" @selected($selectedDelegateId == $dlg->id)>
                            {{ $dlg->prenom }} {{ $dlg->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>

        @if(auth()->user()->role === 'rbo' && $delegateList->isNotEmpty())
        <div class="db-pills">
            @foreach($delegateList as $dlg)
                <a href="{{ route('dashboard.index', array_filter(['delegate_id' => $dlg->id, 'rbo_id' => $selectedRboId])) }}"
                   class="db-pill {{ $selectedDelegateId == $dlg->id ? 'active' : '' }}">
                    {{ $dlg->prenom }} {{ $dlg->nom }}
                </a>
            @endforeach
        </div>
        @endif
    </form>
    @endif

    {{-- Admin executive KPIs --}}
    @if($admin && in_array(auth()->user()->role, ['admin', 'abo']))
    <div class="db-section">
        <div class="db-section-title">KPI exécutifs — CRM</div>
        <div class="db-stats">
            <div class="db-stat accent-blue"><div class="db-stat-label">Utilisateurs</div><div class="db-stat-value">{{ $admin['executive']['total_users'] }}</div></div>
            <div class="db-stat accent-teal"><div class="db-stat-label">Délégués</div><div class="db-stat-value">{{ $admin['executive']['total_delegates'] }}</div></div>
            <div class="db-stat accent-violet"><div class="db-stat-label">RBO</div><div class="db-stat-value">{{ $admin['executive']['total_rbo'] }}</div></div>
            <div class="db-stat accent-amber"><div class="db-stat-label">Écoles</div><div class="db-stat-value">{{ $admin['executive']['total_schools'] }}</div></div>
        </div>
        <div class="db-section-title" style="margin-top:1rem">Statistiques commerciales</div>
        <div class="db-stats">
            <div class="db-stat accent-blue"><div class="db-stat-label">Actions commerciales</div><div class="db-stat-value">{{ $admin['commercial']['total_actions'] }}</div></div>
            <div class="db-stat accent-green"><div class="db-stat-label">Formations</div><div class="db-stat-value">{{ $admin['commercial']['total_trainings'] }}</div></div>
            <div class="db-stat accent-teal"><div class="db-stat-label">Examens</div><div class="db-stat-value">{{ $admin['commercial']['total_exams'] }}</div></div>
            <div class="db-stat accent-violet"><div class="db-stat-label">Spécimens (BSS)</div><div class="db-stat-value">{{ $admin['commercial']['total_specimens'] }}</div></div>
            <div class="db-stat accent-amber"><div class="db-stat-label">Adoptions</div><div class="db-stat-value">{{ $admin['commercial']['total_adoptions'] }}</div></div>
        </div>
        <div class="db-section-title" style="margin-top:1rem">Qualité</div>
        <div class="db-stats">
            <div class="db-stat accent-rose"><div class="db-stat-label">Réclamations</div><div class="db-stat-value">{{ $admin['quality']['total_complaints'] }}</div></div>
            <div class="db-stat accent-amber"><div class="db-stat-label">Non-conformités</div><div class="db-stat-value">{{ $admin['quality']['total_non_conformities'] }}</div></div>
            <div class="db-stat accent-blue"><div class="db-stat-label">Actions d'amélioration</div><div class="db-stat-value">{{ $admin['quality']['total_improvement_actions'] }}</div></div>
            <div class="db-stat accent-green"><div class="db-stat-label">Taux adoption</div><div class="db-stat-value">{{ $admin['rates']['adoption_rate'] }}%</div></div>
            <div class="db-stat accent-rose"><div class="db-stat-label">Taux retour</div><div class="db-stat-value">{{ $admin['rates']['retour_rate'] }}%</div></div>
        </div>
        <div class="db-charts" style="margin-top:1rem">
            <div class="db-chart-card">
                <h3>Répartition des utilisateurs</h3>
                <div class="db-chart-wrap"><canvas id="chart-admin-roles"></canvas></div>
            </div>
            <div class="db-chart-card">
                <h3>Spécimens par statut</h3>
                <div class="db-chart-wrap"><canvas id="chart-admin-specimens"></canvas></div>
            </div>
        </div>
    </div>
    @endif

    {{-- RBO personal KPIs --}}
    @if($rbo && in_array(auth()->user()->role, ['rbo', 'admin', 'abo']))
    <div class="db-section">
        <div class="db-section-title">
            @if(auth()->user()->role === 'rbo')
                Gestion d'équipe & validations
            @else
                KPI RBO @if($selectedRboId) (sélectionné) @endif
            @endif
        </div>
        <div class="db-stats">
            <div class="db-stat accent-blue"><div class="db-stat-label">Délégués gérés</div><div class="db-stat-value">{{ $rbo['team']['managed_delegates'] }}</div></div>
            <div class="db-stat accent-teal"><div class="db-stat-label">Écoles gérées</div><div class="db-stat-value">{{ $rbo['team']['managed_schools'] }}</div></div>
            <div class="db-stat accent-green"><div class="db-stat-label">Délégués actifs</div><div class="db-stat-value">{{ $rbo['team']['active_delegates'] }}</div></div>
            <div class="db-stat accent-amber"><div class="db-stat-label">Validations en attente</div><div class="db-stat-value">{{ $rbo['validation']['pending'] }}</div></div>
            <div class="db-stat accent-green"><div class="db-stat-label">Demandes approuvées</div><div class="db-stat-value">{{ $rbo['validation']['approved'] }}</div></div>
            <div class="db-stat accent-rose"><div class="db-stat-label">Demandes rejetées</div><div class="db-stat-value">{{ $rbo['validation']['rejected'] }}</div></div>
        </div>
        <div class="db-charts">
            <div class="db-chart-card">
                <h3>Équipe</h3>
                <div class="db-chart-wrap"><canvas id="chart-rbo-team"></canvas></div>
            </div>
            <div class="db-chart-card">
                <h3>Validations (demandes spéciales)</h3>
                <div class="db-chart-wrap"><canvas id="chart-rbo-validation"></canvas></div>
            </div>
        </div>
    </div>
    @endif

    {{-- Delegate dashboard --}}
    @if($ds)
    <div class="db-section">
        <div class="db-section-title">
            @if($delegateName) {{ $delegateName }} — @endif Écoles
        </div>
        <div class="db-stats">
            <div class="db-stat accent-blue">
                <div class="db-stat-label">Comptes assignés</div>
                <div class="db-stat-value">{{ $ds['schools']['assigned_total'] }}</div>
            </div>
            <div class="db-stat accent-teal">
                <div class="db-stat-label">Visites ce mois</div>
                <div class="db-stat-value">{{ $ds['schools']['visited_this_month'] }}</div>
                <div class="db-stat-sub">{{ now()->translatedFormat('F Y') }}</div>
            </div>
        </div>
    </div>

    <div class="db-section">
        <div class="db-section-title">Actions commerciales</div>
        <div class="db-stats">
            <div class="db-stat accent-blue"><div class="db-stat-label">Créées</div><div class="db-stat-value">{{ $ds['actions']['created'] }}</div></div>
            <div class="db-stat accent-green"><div class="db-stat-label">Réalisées</div><div class="db-stat-value">{{ $ds['actions']['realised'] }}</div></div>
            <div class="db-stat accent-amber"><div class="db-stat-label">Non réalisées</div><div class="db-stat-value">{{ $ds['actions']['pending'] }}</div></div>
        </div>
        <div class="db-charts">
            <div class="db-chart-card" style="grid-column: span 2">
                <h3>Actions réalisées par mois</h3>
                <div class="db-chart-wrap"><canvas id="chart-actions-monthly" data-label="Réalisées"></canvas></div>
            </div>
            <div class="db-chart-card">
                <h3>Répartition par statut</h3>
                <div class="db-chart-wrap"><canvas id="chart-actions-status"></canvas></div>
            </div>
        </div>
    </div>

    <div class="db-section">
        <div class="db-section-title">Spécimens (BSS)</div>
        <div class="db-stats">
            <div class="db-stat accent-blue"><div class="db-stat-label">Créés</div><div class="db-stat-value">{{ $ds['specimens']['created'] }}</div></div>
            <div class="db-stat accent-teal"><div class="db-stat-label">Livrés</div><div class="db-stat-value">{{ $ds['specimens']['delivered'] }}</div></div>
            <div class="db-stat accent-green"><div class="db-stat-label">Adoptés</div><div class="db-stat-value">{{ $ds['specimens']['adopted'] }}</div></div>
            <div class="db-stat accent-rose"><div class="db-stat-label">Retours</div><div class="db-stat-value">{{ $ds['specimens']['retour'] }}</div></div>
            <div class="db-stat accent-violet"><div class="db-stat-label">Taux adoption</div><div class="db-stat-value">{{ $ds['specimens']['adoption_rate'] }}%</div></div>
            <div class="db-stat accent-amber"><div class="db-stat-label">Taux retour</div><div class="db-stat-value">{{ $ds['specimens']['retour_rate'] }}%</div></div>
        </div>
        <div class="db-charts">
            <div class="db-chart-card">
                <h3>Flux spécimens</h3>
                <div class="db-chart-wrap"><canvas id="chart-specimens-flow"></canvas></div>
            </div>
            <div class="db-chart-card">
                <h3>Par statut</h3>
                <div class="db-chart-wrap"><canvas id="chart-specimens-status"></canvas></div>
            </div>
        </div>
    </div>

    <div class="db-section">
        <div class="db-section-title">Autres activités</div>
        <div class="db-stats">
            @foreach(['examens' => 'Examens', 'formations' => 'Formations', 'events' => 'Événements', 'taches' => 'Tâches'] as $key => $label)
            <div class="db-stat accent-blue">
                <div class="db-stat-label">{{ $label }}</div>
                <div class="db-stat-value">{{ $ds['activities'][$key]['created'] }}</div>
                <div class="db-stat-sub">{{ $ds['activities'][$key]['completed'] }} terminé(s)</div>
            </div>
            @endforeach
        </div>
        <div class="db-charts">
            <div class="db-chart-card" style="grid-column: 1 / -1">
                <h3>Créés vs terminés</h3>
                <div class="db-chart-wrap"><canvas id="chart-activities"></canvas></div>
            </div>
        </div>
    </div>

    @elseif(in_array(auth()->user()->role, ['rbo', 'admin', 'abo']))
    <div class="db-empty">
        Sélectionnez un délégué pour afficher son tableau de bord détaillé.
    </div>
    @endif
</div>

<div id="dashboard-root" data-charts="{{ json_encode($data) }}" hidden></div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
<script src="{{ asset('js/dashboard-charts.js') }}"></script>
@endpush

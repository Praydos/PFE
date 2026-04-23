@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
<style>
    .dr-page { padding: 2rem 2.5rem 3rem; max-width: 1400px; margin: 0 auto; }
    .dr-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
    .dr-card { background: white; border-radius: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow: hidden; }
    .dr-table { width: 100%; border-collapse: collapse; }
    .dr-table th { text-align: left; padding: 0.8rem 1.5rem; background: #f8f9fc; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; color: #6b7280; border-bottom: 1px solid #e5e7eb; }
    .dr-table td { padding: 0.8rem 1.5rem; border-bottom: 1px solid #f0f2f5; vertical-align: middle; }
    .dr-badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.7rem; font-weight: 600; }
    .bd-planifie { background: #fff8e1; color: #ff8f00; }
    .bd-realise { background: #e3f2fd; color: #1976d2; }
    .bd-valide { background: #e8f5e9; color: #388e3c; }
    .bd-annule { background: #ffebee; color: #d32f2f; }
    .bd-reporte { background: #f3e5f5; color: #7b1fa2; }
    .actions-cell { display: flex; gap: 0.5rem; flex-wrap: wrap; }
    .btn-sm { padding: 0.25rem 0.6rem; font-size: 0.75rem; border-radius: 6px; }
    .btn-ghost { background: #f1f5f9; border: 1px solid #e2e8f0; }
    .btn-primary { background: #5b8dee; color: white; border: none; }
    .btn-danger { background: #dc3545; color: white; border: none; }
    .btn-success { background: #28a745; color: white; border: none; }
</style>
@endpush

@section('content')
<div class="dr-page">
    <div class="dr-header">
        <div><h1>Actions Commerciales</h1><p>Gestion des actions et tâches</p></div>
        @if(auth()->user()->role === 'delegue')
        <a href="{{ route('actions.create') }}" class="btn-primary btn-sm" style="padding:0.5rem 1rem;">Nouvelle action</a>
        @endif
    </div>

    <form method="GET" action="{{ route('actions.index') }}" class="filters mb-4">
        <div class="row g-2">
            <div class="col-md-3"><select name="statut" class="form-select"><option value="">Tous statuts</option>@foreach($statuts as $s)<option value="{{ $s }}" {{ request('statut')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
            <div class="col-md-3"><select name="type" class="form-select"><option value="">Tous types</option>@foreach($types as $t)<option value="{{ $t }}" {{ request('type')==$t?'selected':'' }}>{{ ucfirst($t) }}</option>@endforeach</select></div>
            <div class="col-md-3"><select name="compte_id" class="form-select"><option value="">Tous comptes</option>@foreach($comptes as $c)<option value="{{ $c->id }}" {{ request('compte_id')==$c->id?'selected':'' }}>{{ $c->etablissement }}</option>@endforeach</select></div>
            <div class="col-md-3"><button type="submit" class="btn-primary btn-sm">Filtrer</button><a href="{{ route('actions.index') }}" class="btn-ghost btn-sm ms-2">Réinitialiser</a></div>
        </div>
    </form>

    <div class="dr-card">
        <div class="dr-card-body p-0">
            <table class="dr-table">
                <thead>
                    <tr>
                        <th>Objet</th><th>Compte</th><th>Date</th><th>Heure</th><th>Statut</th><th>Type</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actions as $a)
                    <tr>
                        <td>{{ $a->objet }}</td>
                        <td>{{ $a->compte->etablissement }}</td>
                        <td>{{ $a->date_planification->format('d/m/Y') }}</td>
                        <td>{{ $a->heure ?? '-' }}</td>
                        <td><span class="dr-badge bd-{{ $a->statut }}">{{ ucfirst($a->statut) }}</span></td>
                        <td>{{ ucfirst($a->type) }}</td>
                        <td>
                            <div class="actions-cell">
                                <a href="{{ route('actions.show', $a) }}" class="btn-sm btn-ghost">Voir</a>
                                @if(in_array(auth()->user()->role, ['admin','rbo']) || (auth()->user()->role === 'delegue' && $a->delegue_id === auth()->id()))
                                    @if($a->statut === 'planifie')
                                        <a href="{{ route('actions.edit', $a) }}" class="btn-sm btn-ghost">Modifier</a>
                                        <form method="POST" action="{{ route('actions.realiser', $a) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn-sm btn-primary" onclick="return confirm('Marquer comme réalisée ?')">Réaliser</button>
                                        </form>
                                        <form method="POST" action="{{ route('actions.annuler', $a) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn-sm btn-danger" onclick="return confirm('Annuler cette action ?')">Annuler</button>
                                        </form>
                                    @elseif($a->statut === 'realise' && in_array(auth()->user()->role, ['admin','rbo']))
                                        <form method="POST" action="{{ route('actions.valider', $a) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn-sm btn-success" onclick="return confirm('Valider cette action ?')">Valider</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-5">Aucune action trouvée.‹/td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $actions->links() }}
    </div>
</div>
@endsection
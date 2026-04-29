@extends('layouts.app')

@push('styles')
<style>
    .dr-page { padding: 2rem 2.5rem 3rem; max-width: 1200px; margin: 0 auto; }
    .dr-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
    .dr-card { background: white; border-radius: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow: hidden; }
    .dr-table { width: 100%; border-collapse: collapse; }
    .dr-table th { text-align: left; padding: 0.8rem 1.5rem; background: #f8f9fc; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; color: #6b7280; border-bottom: 1px solid #e5e7eb; }
    .dr-table td { padding: 0.8rem 1.5rem; border-bottom: 1px solid #f0f2f5; vertical-align: middle; }
    .btn-primary { background: #5b8dee; color: white; border: none; padding: 0.5rem 1rem; border-radius: 8px; }
</style>
@endpush

@section('content')
<div class="dr-page">
    <div class="dr-header">
        <h1>Tâches</h1>
        @if(auth()->user()->role === 'delegue')
        <a href="{{ route('taches.create') }}" class="btn-primary">Nouvelle tâche</a>
        @endif
    </div>

    <form method="GET" action="{{ route('taches.index') }}" class="mb-3">
        <select name="statut">
            <option value="">Tous</option>
            <option value="pending" {{ request('statut')=='pending'?'selected':'' }}>En attente</option>
            <option value="validated" {{ request('statut')=='validated'?'selected':'' }}>Validées</option>
        </select>
        <button type="submit" class="btn-primary">Filtrer</button>
    </form>

    <div class="dr-card">
        <table class="dr-table">
            <thead>
                <tr>
                    <th>Objet</th>
                    <th>Date planification</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </td>
            </thead>
            <tbody>
                @foreach($taches as $t)
                <tr>
                    <td>{{ $t->objet }}</td>
                    <td>{{ $t->date_planification->format('d/m/Y') }}</td>
                    <td>{{ $t->is_validated ? 'Validée' : 'En attente' }}</td>
                    <td>
                        <a href="{{ route('taches.show', $t) }}">Voir</a>
                        @if(!$t->is_validated && (auth()->user()->role === 'delegue' && $t->delegue_id === auth()->id()))
                            <a href="{{ route('taches.edit', $t) }}">Modifier</a>
                            <form method="POST" action="{{ route('taches.destroy', $t) }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit">Supprimer</button>
                            </form>
                        @endif
                        @if(!$t->is_validated && in_array(auth()->user()->role, ['admin','rbo']))
                            <form method="POST" action="{{ route('taches.validate', $t) }}" style="display:inline;">
                                @csrf <button type="submit">Valider</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $taches->links() }}
    </div>
</div>
@endsection
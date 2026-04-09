@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
<style>
    .dr-page { padding: 2rem 2.5rem 3rem; }
    .dr-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
    .dr-card { background: white; border-radius: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow: hidden; }
    .dr-table { width: 100%; border-collapse: collapse; }
    .dr-table th { text-align: left; padding: 0.8rem 1.5rem; background: #f8f9fc; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; color: #6b7280; border-bottom: 1px solid #e5e7eb; }
    .dr-table td { padding: 0.8rem 1.5rem; border-bottom: 1px solid #f0f2f5; vertical-align: middle; }
    .dr-badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.7rem; font-weight: 600; }
    .bd-teal { background: #e0f2f1; color: #00897b; }
    .bd-blue { background: #e3f2fd; color: #1976d2; }
    .bd-green { background: #e8f5e9; color: #388e3c; }
    .bd-none { background: #f1f5f9; color: #64748b; }
    .btn-sm { padding: 0.25rem 0.6rem; font-size: 0.75rem; border-radius: 6px; }
    .btn-ghost { background: #f1f5f9; border: 1px solid #e2e8f0; }
    .btn-primary { background: #5b8dee; color: white; border: none; }
    .text-muted { color: #6c757d; }
</style>
@endpush

@section('content')
<div class="dr-page">
    <div class="dr-header">
        <div>
            <h1>Bons de retour</h1>
            <p class="text-muted">Historique des retours de spécimens</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="dr-search-bar" style="margin-bottom: 1.5rem;">
        <form method="GET" action="{{ route('retours.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
            <div class="dr-search-wrap" style="min-width: 200px;">
                <label style="font-size: 0.7rem;">Recherche</label>
                <input type="text" name="search" class="form-control" placeholder="N° retour ou n° BSS" value="{{ request('search') }}">
            </div>
            <div class="dr-search-wrap" style="min-width: 180px;">
                <label style="font-size: 0.7rem;">BSS</label>
                <select name="bss_id" class="form-select">
                    <option value="">Tous</option>
                    @foreach($bssList as $b)
                        <option value="{{ $b->id }}" {{ request('bss_id') == $b->id ? 'selected' : '' }}>{{ $b->numero }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-sm btn-ghost">Filtrer</button>
            <a href="{{ route('retours.index') }}" class="btn btn-sm btn-ghost">Réinitialiser</a>
        </form>
    </div>

    <div class="dr-card">
        <div style="overflow-x: auto;">
            <table class="dr-table">
                <thead>
                    <tr>
                        <th>N° retour</th>
                        <th>BSS associé</th>
                        <th>Date retour</th>
                        <th>Créé par</th>
                        <th>Motif</th>
                        <th>Produits retournés</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($retours as $retour)
                    <tr>
                        <td><strong>{{ $retour->numero }}</strong></td>
                        <td>
                            <a href="{{ route('bss.show', $retour->bss) }}" class="text-decoration-none">
                                {{ $retour->bss->numero }}
                            </a>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($retour->date_retour)->format('d/m/Y') }}
                        </td>
                        <td>{{ $retour->createdBy->prenom }} {{ $retour->createdBy->nom }}</td>
                        <td>{{ $retour->motif ?? '-' }}</td>
                        <td>
                            <ul class="mb-0" style="padding-left: 1rem;">
                                @foreach($retour->lignes as $ligne)
                                    <li>{{ $ligne->product->titre }} : {{ $ligne->pivot->quantite_retournee }} ex.</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <a href="{{ route('bss.show', $retour->bss) }}" class="btn btn-sm btn-ghost">Voir BSS</a>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Aucun bon de retour trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $retours->links() }}
        </div>
    </div>
</div>
@endsection
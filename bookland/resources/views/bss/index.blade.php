@extends('layouts.app')

@section('content')
<div class="dr-page">
    <div class="dr-header">
        <div class="dr-header-left">
            <h1>Bons de sortie spécimens (BSS)</h1>
            <p>Gérez vos demandes de spécimens</p>
        </div>
        @if(auth()->user()->role === 'delegue')
        <div class="dr-header-actions">
            <a href="{{ route('bss.create') }}" class="btn-dr btn-dr-primary">Nouveau BSS</a>
        </div>
        @endif
    </div>

    {{-- Filters --}}
    <div class="dr-search-bar">
        <form method="GET" action="{{ route('bss.index') }}" style="display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-end;">
            @if(auth()->user()->role !== 'delegue')
            <div class="dr-search-wrap" style="min-width:180px;">
                <label>Délégué</label>
                <select name="delegate_id" class="form-select">
                    <option value="">Tous</option>
                    @foreach($delegates as $del)
                        <option value="{{ $del->id }}" {{ request('delegate_id') == $del->id ? 'selected' : '' }}>{{ $del->prenom }} {{ $del->nom }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="dr-search-wrap" style="min-width:180px;">
                <label>Compte</label>
                <select name="compte_id" class="form-select">
                    <option value="">Tous</option>
                    @foreach($comptes as $c)
                        <option value="{{ $c->id }}" {{ request('compte_id') == $c->id ? 'selected' : '' }}>{{ $c->etablissement }}</option>
                    @endforeach
                </select>
            </div>
            <div class="dr-search-wrap" style="min-width:150px;">
                <label>Statut</label>
                <select name="statut" class="form-select">
                    <option value="">Tous</option>
                    <option value="brouillon" {{ request('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                    <option value="soumis" {{ request('statut') == 'soumis' ? 'selected' : '' }}>Soumis</option>
                    <option value="valide" {{ request('statut') == 'valide' ? 'selected' : '' }}>Validé</option>
                    <option value="livre" {{ request('statut') == 'livre' ? 'selected' : '' }}>Livré</option>
                    <option value="refuse" {{ request('statut') == 'refuse' ? 'selected' : '' }}>Refusé</option>
                </select>
            </div>
            <button type="submit" class="btn-dr btn-dr-ghost">Filtrer</button>
            <a href="{{ route('bss.index') }}" class="btn-dr btn-dr-danger-ghost">Réinitialiser</a>
        </form>
    </div>

    <div class="dr-card">
        <div class="dr-card-header"><div class="dr-card-title">Liste des BSS</div></div>
        <div style="overflow-x:auto;">
            <table class="dr-table">
                <thead>
                    <tr>
                        <th>N° BSS</th><th>Compte</th><th>Contact</th><th>Délégué</th><th>Date création</th><th>Statut</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bssList as $bss)
                    <tr>
                        <td>{{ $bss->numero }}</td>
                        <td>{{ $bss->compte->etablissement }}</td>
                        <td>{{ $bss->contact->prenom }} {{ $bss->contact->nom }}</td>
                        <td>{{ $bss->delegate->prenom }} {{ $bss->delegate->nom }}</td>
                        <td>{{ $bss->date_bss->format('d/m/Y') }}</td>
                        <td>
                            @if($bss->statut == 'soumis') <span class="dr-badge bd-blue">Soumis</span>
                            @elseif($bss->statut == 'valide') <span class="dr-badge bd-teal">Validé</span>
                            @elseif($bss->statut == 'livre') <span class="dr-badge bd-green">Livré</span>
                            @elseif($bss->statut == 'refuse') <span class="dr-badge bd-none">Refusé</span>
                            @else <span class="dr-badge bd-none">{{ $bss->statut }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions-cell">
                                <a href="{{ route('bss.show', $bss) }}" class="btn-dr btn-dr-sm btn-dr-info">Détails</a>
                                @if(auth()->user()->role === 'delegue' && $bss->delegate_id === auth()->id() && $bss->statut === 'valide' && !$bss->feedback)
                                    <a href="{{ route('bss.edit', $bss) }}" class="btn-dr btn-dr-sm btn-dr-warning">Feedback</a>
                                @endif
                                @if(in_array(auth()->user()->role, ['admin','rbo']) && $bss->statut === 'soumis')
                                    <form action="{{ route('bss.validate', $bss) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="btn-dr btn-dr-sm btn-dr-primary" onclick="return confirm('Valider ce BSS ?')">Valider</button>
                                    </form>
                                    <button class="btn-dr btn-dr-sm btn-dr-danger" onclick="openRefuseModal({{ $bss->id }})">Refuser</button>
                                @endif
                                @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('bss.destroy', $bss) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-dr btn-dr-sm btn-dr-danger">Supprimer</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7"><div class="dr-empty">Aucun BSS.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $bssList->links() }}
    </div>
</div>

{{-- Modal for refuse reason --}}
<div class="modal fade" id="refuseModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="refuseForm">
            @csrf
            <input type="hidden" name="action" value="refuse">
            <div class="modal-content">
                <div class="modal-header"><h5>Motif du refus</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <textarea name="motif_refus" class="form-control" rows="3" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Refuser</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openRefuseModal(bssId) {
    const form = document.getElementById('refuseForm');
    form.action = `/bss/${bssId}/validate`;
    new bootstrap.Modal(document.getElementById('refuseModal')).show();
}
</script>
@endpush
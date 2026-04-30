@extends('layouts.app')

@section('content')
<div class="dr-page">
    <div class="dr-header">
        <h1>Actions d'amélioration</h1>
        @if(auth()->user()->role !== 'rbo')
            <a href="{{ route('actions-amelioration.create') }}" class="btn-primary">Nouvelle action</a>
        @endif
    </div>

    <form method="GET" action="{{ route('actions-amelioration.index') }}" class="filters">
        <select name="statut"><option value="">Tous statuts</option>@foreach($statuts as $s)<option value="{{ $s }}" {{ request('statut')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select>
        <select name="type"><option value="">Tous types</option>@foreach($types as $t)<option value="{{ $t }}" {{ request('type')==$t?'selected':'' }}>{{ $t }}</option>@endforeach</select>
        <select name="compte_id"><option value="">Tous comptes</option>@foreach($comptes as $c)<option value="{{ $c->id }}" {{ request('compte_id')==$c->id?'selected':'' }}>{{ $c->etablissement }}</option>@endforeach</select>
        <button type="submit">Filtrer</button>
        <a href="{{ route('actions-amelioration.index') }}">Réinitialiser</a>
    </form>

    <div class="dr-card">
        <table class="dr-table">
            <thead><tr><th>N°</th><th>Compte</th><th>Type</th><th>Origine</th><th>Date</th><th>Statut</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($actions as $a)
                <tr>
                    <td>{{ $a->numero }}</td>
                    <td>{{ $a->compte->etablissement }}</td>
                    <td>{{ $a->type }}</td>
                    <td>{{ $a->origine }}</td>
                    <td>{{ $a->dateAA->format('d/m/Y') }}</td>
                    <td><span class="dr-badge">{{ ucfirst($a->statut) }}</span></td>
                    <td>
                        <a href="{{ route('actions-amelioration.show', $a) }}">Voir</a>
                        @if(auth()->user()->role !== 'rbo')
                            <a href="{{ route('actions-amelioration.edit', $a) }}">Modifier</a>
                        @endif
                        @if(auth()->user()->role === 'admin')
                            <form method="POST" action="{{ route('actions-amelioration.destroy', $a) }}" style="display:inline;">@csrf @method('DELETE')<button type="submit" onclick="return confirm('Supprimer ?')">Supprimer</button></form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $actions->links() }}
    </div>
</div>
@endsection
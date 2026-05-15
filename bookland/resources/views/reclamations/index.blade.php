@extends('layouts.app')

@section('content')
<div class="dr-page">
    <div class="dr-header">
        <h1>Réclamations</h1>
        @if(auth()->user()->role === 'delegue')
            <a href="{{ route('reclamations.create') }}" class="btn-primary">Nouvelle réclamation</a>
        @endif
    </div>

    <form method="GET" action="{{ route('reclamations.index') }}" class="filters">
        <select name="statut"><option value="">Tous statuts</option>@foreach($statuts as $s)<option value="{{ $s }}" {{ request('statut')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select>
        <select name="categorie"><option value="">Toutes catégories</option>@foreach($categories as $c)<option value="{{ $c }}" {{ request('categorie')==$c?'selected':'' }}>{{ $c }}</option>@endforeach</select>
        <select name="compte_id"><option value="">Tous comptes</option>@foreach($comptes as $c)<option value="{{ $c->id }}" {{ request('compte_id')==$c->id?'selected':'' }}>{{ $c->etablissement }}</option>@endforeach</select>
        <button type="submit">Filtrer</button>
        <a href="{{ route('reclamations.index') }}">Réinitialiser</a>
    </form>

    <div class="dr-card">
        <table class="dr-table">
            <thead>
                <tr>
                    <th>N°</th><th>Compte</th><th>Catégorie</th><th>Date</th><th>Statut</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reclamations as $r)
                <tr>
                    <td>{{ $r->reference }}</td>
                    <td>{{ $r->compte->etablissement }}</td>
                   <td>{{ $r->categorie }} @if($r->sous_categorie) ({{ $r->sous_categorie }}) @endif</td>
                    <td>{{ $r->date_reclamation->format('d/m/Y') }}</td>
                    <td><span class="dr-badge">{{ ucfirst($r->statut) }}</span></td>
                    <td>
                        <a href="{{ route('reclamations.show', $r) }}">Voir</a>
                        @if($r->statut === 'brouillon' && (auth()->user()->role === 'delegue' && $r->delegue_id === auth()->id()))
                            <a href="{{ route('reclamations.edit', $r) }}">Modifier</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $reclamations->links() }}
    </div>
</div>
@endsection
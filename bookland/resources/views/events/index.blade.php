@extends('layouts.app')

@section('content')
<div class="dr-page">
    <div class="dr-header">
        <div><h1>Événements</h1><p>Gestion des événements</p></div>
        @if(auth()->user()->role === 'delegue')
        <a href="{{ route('events.create') }}" class="btn-primary">Nouvel événement</a>
        @endif
    </div>

    <form method="GET" action="{{ route('events.index') }}" class="filters">
        <select name="type">
            <option value="">Tous types</option>
            @foreach($types as $t)
                <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
        <select name="editeur">
            <option value="">Tous éditeurs</option>
            @foreach($editeurs as $e)
                <option value="{{ $e }}" {{ request('editeur') == $e ? 'selected' : '' }}>{{ $e }}</option>
            @endforeach
        </select>
        <select name="ville_id">
            <option value="">Toutes villes</option>
            @foreach($villes as $v)
                <option value="{{ $v->id }}" {{ request('ville_id') == $v->id ? 'selected' : '' }}>{{ $v->nom }}</option>
            @endforeach
        </select>
        <button type="submit">Filtrer</button>
        <a href="{{ route('events.index') }}">Réinitialiser</a>
    </form>

    <div class="dr-card">
        <table class="dr-table">
            <thead>
                <tr><th>Type</th><th>Éditeur</th><th>Date</th><th>Ville</th><th>Statistiques</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($events as $e)
                <tr>
                    <td>{{ $e->type }}</td>
                    <td>{{ $e->editeur }}</td>
                    <td>{{ $e->date_event->format('d/m/Y') }}</td>
                    <td>{{ $e->ville->nom }}</td>
                    <td><a href="{{ route('events.statistics', $e) }}">Voir stats</a></td>
                    <td>
                        <a href="{{ route('events.show', $e) }}">Voir</a>
                        @if(in_array(auth()->user()->role, ['admin','rbo']) || (auth()->user()->role === 'delegue' && $e->delegue_id === auth()->id()))
                            <a href="{{ route('events.edit', $e) }}">Modifier</a>
                        @endif
                     </td>
                </tr>
                @empty
                <tr><td colspan="6">Aucun événement.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $events->links() }}
    </div>
</div>
@endsection
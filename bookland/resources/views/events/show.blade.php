@extends('layouts.app')

@section('content')
<div class="dr-page">
    <div class="dr-header">
        <h1>{{ $event->type }}</h1>
        <p>{{ $event->date_event->format('d/m/Y') }} - {{ $event->ville->nom }}</p>
    </div>

    <div class="dr-card">
        <div class="info-grid">
            <div class="info-item"><span class="info-label">Éditeur</span> {{ $event->editeur }}</div>
            <div class="info-item"><span class="info-label">Zone</span> {{ $event->zone->name }}</div>
            <div class="info-item"><span class="info-label">Délégué</span> {{ $event->delegate->prenom }} {{ $event->delegate->nom }}</div>
        </div>

        <h3>Contacts invités</h3>
        <table class="dr-table">
            <thead>
                <tr><th>Nom</th><th>Ville</th><th>Fonction</th><th>Statut</th><th>Action</th></tr>
            </thead>
            <tbody>
                @foreach($event->contacts as $contact)
                <tr>
                    <td>{{ $contact->prenom }} {{ $contact->nom }}</td>
                    <td>{{ $contact->ville->nom }}</td>
                    <td>{{ $contact->fonction ?? '-' }}</td>
                    <td><span class="dr-badge">{{ $statuts[$contact->pivot->statut] }}</span></td>
                    <td>
                        @if(auth()->user()->role === 'delegue' && $event->delegue_id === auth()->id())
                        <form method="POST" action="{{ route('events.update-status', [$event, $contact]) }}" style="display:inline;">
                            @csrf
                            <select name="statut" class="form-select w-auto" onchange="this.form.submit()">
                                @foreach($statuts as $key => $label)
                                    <option value="{{ $key }}" {{ $contact->pivot->statut == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </form>
                        @endif
                     </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            <a href="{{ route('events.index') }}">Retour</a>
            @if(auth()->user()->role === 'delegue' && $event->delegue_id === auth()->id())
                <a href="{{ route('events.invite', $event) }}">Inviter plus de contacts</a>
                <a href="{{ route('events.edit', $event) }}">Modifier</a>
                <a href="{{ route('events.statistics', $event) }}">Voir statistiques</a>
            @endif
        </div>
    </div>
</div>
@endsection
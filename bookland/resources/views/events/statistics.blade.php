@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Statistiques - {{ $event->type }}</h1>
    <div class="dr-card">
        <div class="info-grid">
            <div class="info-item"><span class="info-label">Total invités</span> {{ $stats['total_invites'] }}</div>
            <div class="info-item"><span class="info-label">Présents</span> {{ $stats['total_presents'] }}</div>
            <div class="info-item"><span class="info-label">Taux de participation</span> {{ $stats['participation_rate'] }}%</div>
        </div>
        <h3>Par ville</h3>
        <table class="dr-table">
            <thead><tr><th>Ville</th><th>Invités</th><th>Présents</th><th>Taux</th></tr></thead>
            <tbody>
                @foreach($stats['by_ville'] as $ville => $data)
                <tr><td>{{ $ville }}</td><td>{{ $data['total'] }}</td><td>{{ $data['presents'] }}</td><td>{{ $data['rate'] }}%</td></tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('events.show', $event) }}">Retour</a>
    </div>
</div>
@endsection
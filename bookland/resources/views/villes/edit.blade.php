@extends('layouts.app')

@section('content')
<h1>Modifier la ville : {{ $ville->nom }}</h1>

<form method="POST" action="{{ route('villes.update', $ville) }}">
    @csrf @method('PUT')
    @include('villes._form')
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="{{ route('villes.index') }}" class="btn btn-secondary">Annuler</a>
</form>

<hr class="my-4">

<h3>Zones assignées à cette ville</h3>
<div class="table-responsive mb-4">
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Nom</th>
                <th>RBO</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assignedZones as $zone)
             <tr>
                <td>{{ $zone->name }}</td>
                <td>{{ $zone->rbo->prenom ?? '' }} {{ $zone->rbo->nom ?? '-' }}</td>
                <td>
                    <a href="{{ route('zones.edit', $zone) }}" class="btn btn-sm btn-warning">Modifier</a>
                    <form action="{{ route('zones.destroy', $zone) }}" method="POST" style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette zone ?')">Supprimer</button>
                    </form>
                </td>
             </tr>
            @empty
             <tr>
                <td colspan="3" class="text-muted text-center">Aucune zone assignée à cette ville.</td>
             </tr>
            @endforelse
        </tbody>
    </table>
</div>

<h3>Assigner une zone existante à cette ville</h3>
<form method="POST" action="{{ route('villes.assignZone', $ville) }}" class="mb-4">
    @csrf
    <div class="row g-2">
        <div class="col-auto">
            <select name="zone_id" class="form-select" required>
                <option value="">Sélectionnez une zone</option>
                @foreach($availableZones as $zone)
                    <option value="{{ $zone->id }}">
                        {{ $zone->name }}
                        (actuellement dans {{ $zone->ville->nom ?? 'aucune ville' }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Assigner</button>
        </div>
    </div>
</form>

<a href="{{ route('zones.create', ['ville_id' => $ville->id]) }}" class="btn btn-success btn-sm">+ Créer une nouvelle zone</a>
@endsection
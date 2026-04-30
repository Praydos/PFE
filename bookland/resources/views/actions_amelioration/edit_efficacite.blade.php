@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Évaluation de l'efficacité – {{ $actions_amelioration->numero }}</h1>
    <form method="POST" action="{{ route('actions-amelioration.update-efficacite', $actions_amelioration) }}">
        @csrf @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3"><label>Date efficacité</label><input type="date" name="date_effecacite" class="form-control" value="{{ old('date_efficacite', $actions_amelioration->date_efficacite ? $actions_amelioration->date_efficacite->format('Y-m-d') : '') }}"></div>
            <div class="col-md-6 mb-3">
                <label>Responsable efficacité *</label>
                <select name="responsable_effecacite_id" class="form-select" required>
                    <option value="">-- Sélectionnez un contact --</option>
                    @foreach($contacts as $c)
                        <option value="{{ $c->id }}" {{ old('responsable_effecacite_id', $actions_amelioration->responsable_effecacite_id) == $c->id ? 'selected' : '' }}>
                            {{ $c->prenom }} {{ $c->nom }} ({{ $c->fonction ?? 'Contact' }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3"><label>Mode de contrôle</label><textarea name="mode_controle" class="form-control" rows="2">{{ old('mode_controle', $actions_amelioration->mode_controle) }}</textarea></div>
        <div class="mb-3"><label>Description du résultat</label><textarea name="description_resultat" class="form-control" rows="2">{{ old('description_resultat', $actions_amelioration->description_resultat) }}</textarea></div>
        <div class="row">
            <div class="col-md-4 mb-3"><label>Action efficace ?</label><select name="action_effecace" class="form-select"><option value="">-- Non spécifié --</option><option value="1" {{ old('action_effecace', $actions_amelioration->action_effecace) === '1' ? 'selected' : '' }}>Oui</option><option value="0" {{ old('action_effecace', $actions_amelioration->action_effecace) === '0' ? 'selected' : '' }}>Non</option></select></div>
            <div class="col-md-4 mb-3"><label>Besoin d'action d'amélioration ?</label><select name="besoin_action_amelioration" class="form-select"><option value="">-- Non spécifié --</option><option value="1" {{ old('besoin_action_amelioration', $actions_amelioration->besoin_action_amelioration) === '1' ? 'selected' : '' }}>Oui</option><option value="0" {{ old('besoin_action_amelioration', $actions_amelioration->besoin_action_amelioration) === '0' ? 'selected' : '' }}>Non</option></select></div>
            <div class="col-md-4 mb-3"><label>Statut *</label><select name="statut" class="form-select" required>@foreach(['brouillon','en_cours','termine','annule','en_attente'] as $s)<option value="{{ $s }}" {{ old('statut', $actions_amelioration->statut) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
        </div>
        <div class="mb-3"><label>Date clôture</label><input type="date" name="date_cloture" class="form-control" value="{{ old('date_cloture', $actions_amelioration->date_cloture ? $actions_amelioration->date_cloture->format('Y-m-d') : '') }}"></div>
        <button type="submit" class="btn-primary">Enregistrer l'évaluation</button>
        <a href="{{ route('actions-amelioration.show', $actions_amelioration) }}" class="btn-secondary">Annuler</a>
    </form>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Efficacité – NC {{ $non_conformite->numero }}</h1>
    <form method="POST" action="{{ route('non-conformites.update-efficacite', $non_conformite) }}">
        @csrf @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Mode de contrôle</label>
                <textarea name="mode_controle" class="form-control" rows="2">{{ old('mode_controle', $non_conformite->mode_controle) }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label>Description du résultat</label>
                <textarea name="description_resultat" class="form-control" rows="2">{{ old('description_resultat', $non_conformite->description_resultat) }}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Action efficace ?</label>
                <select name="action_efficace" class="form-select">
                    <option value="">-- Non spécifié --</option>
                    <option value="1" {{ old('action_efficace', $non_conformite->action_efficace) === '1' ? 'selected' : '' }}>Oui</option>
                    <option value="0" {{ old('action_efficace', $non_conformite->action_efficace) === '0' ? 'selected' : '' }}>Non</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Besoin d'action d'amélioration ?</label>
                <select name="besoin_action_amelioration" class="form-select">
                    <option value="">-- Non spécifié --</option>
                    <option value="1" {{ old('besoin_action_amelioration', $non_conformite->besoin_action_amelioration) === '1' ? 'selected' : '' }}>Oui</option>
                    <option value="0" {{ old('besoin_action_amelioration', $non_conformite->besoin_action_amelioration) === '0' ? 'selected' : '' }}>Non</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Statut *</label>
                <select name="statut" class="form-select" required>
                    @foreach(['brouillon','en_cours','termine','annule','mise_en_attente'] as $s)
                        <option value="{{ $s }}" {{ old('statut', $non_conformite->statut) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Responsable efficacité *</label>
                <select name="responsable_efficacite_id" class="form-select" required>
                    <option value="">-- Sélectionnez --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ old('responsable_efficacite_id', $non_conformite->responsable_efficacite_id) == $u->id ? 'selected' : '' }}>{{ $u->prenom }} {{ $u->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Date efficacité</label>
                <input type="date" name="date_efficacite" class="form-control" value="{{ old('date_efficacite', $non_conformite->date_efficacite ? $non_conformite->date_efficacite->format('Y-m-d') : '') }}">
            </div>
        </div>
        <div class="mb-3">
            <label>Date clôture</label>
            <input type="date" name="date_cloture" class="form-control" value="{{ old('date_cloture', $non_conformite->date_cloture ? $non_conformite->date_cloture->format('Y-m-d') : '') }}">
        </div>
        <button type="submit" class="btn-primary">Enregistrer l'efficacité</button>
        <a href="{{ route('non-conformites.show', $non_conformite) }}" class="btn-secondary">Annuler</a>
    </form>
</div>
@endsection
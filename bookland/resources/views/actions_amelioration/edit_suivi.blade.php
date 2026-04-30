@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Ajouter le suivi – {{ $actions_amelioration->numero }}</h1>
    <form method="POST" action="{{ route('actions-amelioration.update-suivi', $actions_amelioration) }}">
        @csrf @method('PUT')
        <div class="mb-3"><label>Vérification mise en œuvre</label><textarea name="verification_mise_en_oeuvre" class="form-control" rows="2">{{ old('verification_mise_en_oeuvre', $actions_amelioration->verification_mise_en_oeuvre) }}</textarea></div>
        <div class="mb-3">
            <label>Responsable suivi *</label>
            <select name="responsable_suivi_id" class="form-select" required>
                <option value="">-- Sélectionnez un contact --</option>
                @foreach($contacts as $c)
                    <option value="{{ $c->id }}" {{ old('responsable_suivi_id', $actions_amelioration->responsable_suivi_id) == $c->id ? 'selected' : '' }}>
                        {{ $c->prenom }} {{ $c->nom }} ({{ $c->fonction ?? 'Contact' }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3"><label>Date suivi</label><input type="date" name="date_suivi" class="form-control" value="{{ old('date_suivi', $actions_amelioration->date_suivi ? $actions_amelioration->date_suivi->format('Y-m-d') : '') }}"></div>
        <button type="submit" class="btn-primary">Enregistrer le suivi</button>
        <a href="{{ route('actions-amelioration.show', $actions_amelioration) }}" class="btn-secondary">Annuler</a>
    </form>
</div>
@endsection
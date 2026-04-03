@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Modifier BSS {{ $bss->numero }}</h1>
    <form method="POST" action="{{ route('bss.update', $bss) }}">
        @csrf @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="frm-group">
                    <label>Compte *</label>
                    <select name="compte_id" class="frm-select" required>
                        <option value="">-- Sélectionnez --</option>
                        @foreach($comptes as $c)
                            <option value="{{ $c->id }}" {{ $bss->compte_id == $c->id ? 'selected' : '' }}>{{ $c->etablissement }} ({{ $c->ville->nom }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="frm-group">
                    <label>Contact *</label>
                    <select name="contact_id" class="frm-select" required>
                        <option value="">-- Sélectionnez --</option>
                        @foreach($contacts as $c)
                            <option value="{{ $c->id }}" {{ $bss->contact_id == $c->id ? 'selected' : '' }}>{{ $c->prenom }} {{ $c->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="frm-group">
                    <label>Source</label>
                    <select name="source" class="frm-select">
                        <option value="consignation" {{ $bss->source == 'consignation' ? 'selected' : '' }}>Consignation</option>
                        <option value="magasin" {{ $bss->source == 'magasin' ? 'selected' : '' }}>Magasin</option>
                        <option value="transport" {{ $bss->source == 'transport' ? 'selected' : '' }}>Transport</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="frm-group">
                    <label>Date BSS *</label>
                    <input type="date" name="date_bss" class="frm-input" value="{{ $bss->date_bss->format('Y-m-d') }}" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="frm-group">
                    <label>Date livraison prévue</label>
                    <input type="date" name="date_livraison" class="frm-input" value="{{ $bss->date_livraison ? $bss->date_livraison->format('Y-m-d') : '' }}">
                </div>
            </div>
        </div>

        <div class="frm-group">
            <label>Observation</label>
            <textarea name="observation" class="frm-textarea" rows="2">{{ $bss->observation }}</textarea>
        </div>

        <div class="alert alert-info">
            <strong>Note :</strong> Les lignes produits ne peuvent pas être modifiées ici. Utilisez l'interface de retour pour ajuster les quantités.
        </div>

        <button type="submit" class="btn-dr btn-dr-primary">Mettre à jour</button>
        <a href="{{ route('bss.show', $bss) }}" class="btn-dr btn-dr-ghost">Annuler</a>
    </form>
</div>
@endsection
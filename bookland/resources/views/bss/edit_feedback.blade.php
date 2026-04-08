@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Feedback du BSS {{ $bss->numero }}</h1>
    <form method="POST" action="{{ route('bss.update', $bss) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Feedback (résultat de la livraison)</label>
            <textarea name="feedback" class="form-control" rows="3">{{ old('feedback', $bss->feedback) }}</textarea>
        </div>
        <div class="mb-3">
            <label>Contrôle document physique</label>
            <select name="controle_document" class="form-select">
                <option value="">-- Sélectionnez --</option>
                <option value="OK" {{ $bss->controle_document == 'OK' ? 'selected' : '' }}>OK</option>
                <option value="Absence signature" {{ $bss->controle_document == 'Absence signature' ? 'selected' : '' }}>Absence signature</option>
                <option value="Absence cachet" {{ $bss->controle_document == 'Absence cachet' ? 'selected' : '' }}>Absence cachet</option>
                <option value="Absence Document" {{ $bss->controle_document == 'Absence Document' ? 'selected' : '' }}>Absence Document</option>
            </select>
        </div>
        <button type="submit" class="btn-dr btn-dr-primary">Enregistrer</button>
        <a href="{{ route('bss.show', $bss) }}" class="btn-dr btn-dr-ghost">Annuler</a>
    </form>
</div>
@endsection
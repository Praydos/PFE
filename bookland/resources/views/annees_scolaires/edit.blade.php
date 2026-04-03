@extends('layouts.app')

@push('styles')
{{-- Same <link> and <style> as in create.blade.php – copy exactly the same @push block --}}
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* Paste the exact same style block from create view here */
    /* (I’m omitting the duplicate content for brevity – you can copy it from above) */
</style>
@endpush

@section('content')
<div class="form-page">
    <div class="fp-bc">
        <a href="{{ route('annees-scolaires.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="fp-bc-sep">›</span>
        <a href="{{ route('annees-scolaires.index') }}">Années scolaires</a>
        <span class="fp-bc-sep">›</span>
        <span class="fp-bc-cur">Modifier</span>
    </div>

    <div class="fp-header">
        <h1>Modifier l'année : {{ $annees_scolaire->libelle }}</h1>
        <p>Mettez à jour les informations de cette année scolaire.</p>
    </div>

    <div class="fp-card">
        <div class="fp-card-header">
            <span class="fp-card-pip"></span>
            <span class="fp-card-title">Informations de l'année</span>
        </div>

        <form method="POST" action="{{ route('annees-scolaires.update', $annees_scolaire) }}" novalidate>
            @csrf
            @method('PUT')
            <div class="fp-card-body">
                @include('annees_scolaires._form')
            </div>
            <div class="fp-footer">
                <a href="{{ route('annees-scolaires.index') }}" class="btn-fp btn-fp-ghost">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="btn-fp btn-fp-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
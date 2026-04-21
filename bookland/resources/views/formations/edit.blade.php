@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ===== FULL CSS FROM ZONES EXAMPLE ===== */
    /* Paste the complete CSS block provided earlier here */
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('formations.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('formations.index') }}">Formations</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Modifier</span>
    </div>

    <div class="zn-header">
        <h1>Modifier la demande de formation</h1>
        <p>Mettez à jour les informations</p>
    </div>

    <div class="zn-card">
        <div class="zn-card-header">
            <span class="title-pip"></span>
            <span class="zn-card-title">Formulaire</span>
        </div>
        <div class="zn-card-body">
            <form method="POST" action="{{ route('formations.update', $formation) }}">
                @csrf
                @method('PUT')
                @include('formations._form')
                <div class="card-footer" style="margin-top: 2rem; padding: 0; background: none; border: none; display: flex; justify-content: flex-end; gap: 0.6rem;">
                    <button type="submit" class="btn-zn btn-zn-primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Mettre à jour
                    </button>
                    <a href="{{ route('formations.show', $formation) }}" class="btn-zn btn-zn-ghost">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="19" y1="12" x2="5" y2="12"/>
                            <polyline points="12 19 5 12 12 5"/>
                        </svg>
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
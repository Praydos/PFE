@extends('layouts.app')
@push('styles')
    

{{-- ─────────────────────────────────────────────────────────────
     Google Fonts (Sora + DM Sans)
     Add this once in your layout <head> if not already present:
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
──────────────────────────────────────────────────────────────── --}}

<style>
/* ── Reset & base ── */
.tch-pg *  { box-sizing: border-box; margin: 0; padding: 0; }
.tch-pg    {
    font-family: 'DM Sans', sans-serif;
    background: var(--bg, #f5f6fa);
    min-height: 100vh;
    padding: 2rem 1.75rem;
    color: var(--text, #1a1d23);
}

/* ── Breadcrumb ── */
.tch-bc               { display: flex; align-items: center; gap: .4rem; font-size: .78rem; color: #6b7280; margin-bottom: 2rem; }
.tch-bc a             { color: #6b7280; text-decoration: none; display: flex; align-items: center; gap: .3rem; transition: color .15s; }
.tch-bc a:hover       { color: #111827; }
.tch-bc-sep           { opacity: .35; }
.tch-bc-cur           { font-family: 'Sora', sans-serif; font-weight: 600; font-size: .78rem; color: #111827; }

/* ── Page header ── */
.tch-hdr              { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.75rem; gap: 1rem; flex-wrap: wrap; }
.tch-hdr-l h1         { font-family: 'Sora', sans-serif; font-weight: 600; font-size: 1.55rem; letter-spacing: -.02em; line-height: 1.2; color: #111827; }
.tch-hdr-l p          { font-size: .82rem; color: #6b7280; margin-top: .3rem; }
.tch-hdr-actions      { display: flex; gap: .5rem; flex-wrap: wrap; }

/* ── Buttons ── */
.btn-tch              { display: inline-flex; align-items: center; gap: .4rem; padding: .5rem 1rem; border-radius: 8px; font-size: .8rem; font-weight: 500; font-family: 'DM Sans', sans-serif; cursor: pointer; border: none; transition: all .15s; text-decoration: none; }
.btn-tch-ghost        { background: #fff; border: 1px solid #e5e7eb; color: #374151; }
.btn-tch-ghost:hover  { background: #f9fafb; }
.btn-tch-primary      { background: #185FA5; color: #fff; }
.btn-tch-primary:hover{ background: #0c447c; }
.btn-tch-success      { background: #3B6D11; color: #fff; }
.btn-tch-success:hover{ background: #27500A; }
.btn-tch-danger       { background: #A32D2D; color: #fff; font-size: .78rem; padding: .42rem .9rem; }
.btn-tch-danger:hover { background: #791F1F; }

/* ── Main card ── */
.tch-card             { background: #fff; border: 1px solid #e9eaee; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.05); }

/* ── Card header ── */
.tch-card-hd          { padding: 1.1rem 1.5rem; border-bottom: 1px solid #f0f1f5; display: flex; align-items: center; justify-content: space-between; }
.tch-card-title       { display: flex; align-items: center; gap: .55rem; font-family: 'Sora', sans-serif; font-weight: 600; font-size: .88rem; color: #111827; }
.tch-pip              { width: 6px; height: 6px; border-radius: 50%; background: #185FA5; flex-shrink: 0; }

/* ── Info grid ── */
.tch-info-grid        { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
.tch-info-row         { padding: 1rem 1.5rem; border-bottom: 1px solid #f0f1f5; display: flex; flex-direction: column; gap: .3rem; }
.tch-info-row:nth-child(even) { background: #fafbfc; }
.tch-info-row.full    { grid-column: 1 / -1; }
.tch-info-lbl         { font-size: .72rem; font-weight: 500; text-transform: uppercase; letter-spacing: .07em; color: #9ca3af; }
.tch-info-val         { font-size: .9rem; color: #111827; font-weight: 400; display: flex; align-items: center; gap: .4rem; }
.tch-info-val.muted   { color: #9ca3af; font-style: italic; }

/* ── Badges ── */
.tch-badge            { display: inline-flex; align-items: center; gap: .3rem; padding: .25rem .65rem; border-radius: 999px; font-size: .75rem; font-weight: 500; }
.tch-badge-green      { background: #EAF3DE; color: #3B6D11; }
.tch-badge-amber      { background: #FAEEDA; color: #854F0B; }

/* ── Avatar ── */
.tch-avatar           { width: 30px; height: 30px; border-radius: 50%; background: #B5D4F4; display: inline-flex; align-items: center; justify-content: center; font-size: .7rem; font-weight: 600; color: #0C447C; flex-shrink: 0; }
.tch-avatar.sm        { width: 22px; height: 22px; font-size: .62rem; }

/* ── Contact chips ── */
.tch-chips            { display: flex; gap: .4rem; flex-wrap: wrap; margin-top: .1rem; }
.tch-chip             { display: inline-flex; align-items: center; gap: .3rem; background: #f3f4f6; border: 1px solid #e9eaee; border-radius: 999px; padding: .2rem .55rem .2rem .3rem; font-size: .78rem; color: #374151; }

/* ── Recurring section ── */
.tch-recur            { margin: 1.25rem 1.5rem; border: 1px solid #F0997B; border-radius: 12px; background: #FAECE7; padding: 1.1rem 1.25rem; }
.tch-recur-title      { display: flex; align-items: center; gap: .45rem; font-family: 'Sora', sans-serif; font-size: .8rem; font-weight: 600; color: #993C1D; margin-bottom: .85rem; }
.tch-radio-group      { display: flex; flex-direction: column; gap: .45rem; margin-bottom: 1rem; }
.tch-radio-lbl        { display: flex; align-items: center; gap: .5rem; font-size: .82rem; color: #712B13; cursor: pointer; }
.tch-radio-lbl input  { accent-color: #993C1D; }

/* ── Card footer ── */
.tch-card-ft          { padding: .9rem 1.5rem; border-top: 1px solid #f0f1f5; background: #fafbfc; display: flex; justify-content: flex-end; gap: .5rem; align-items: center; flex-wrap: wrap; }

/* ── SVG icons (inline helpers) ── */
.tch-icon             { display: inline-block; vertical-align: middle; flex-shrink: 0; }
</style>
@endpush
@section('content')
<div class="tch-pg">

    {{-- ── Breadcrumb ── --}}
    <nav class="tch-bc" aria-label="Fil d'Ariane">
        <a href="{{ route('taches.index') }}">
            <svg class="tch-icon" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="tch-bc-sep">›</span>
        <a href="{{ route('taches.index') }}">Tâches</a>
        <span class="tch-bc-sep">›</span>
        <span class="tch-bc-cur">{{ $tache->objet }}</span>
    </nav>

    {{-- ── Page header ── --}}
    <div class="tch-hdr">
        <div class="tch-hdr-l">
            <h1>{{ $tache->objet }}</h1>
            <p>Détail de la tâche · Créée le {{ $tache->created_at->format('d/m/Y') }}</p>
        </div>
        <div class="tch-hdr-actions">
            <a href="{{ route('taches.index') }}" class="btn-tch btn-tch-ghost">
                <svg class="tch-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour
            </a>

            @if(!$tache->is_validated && (auth()->user()->role === 'delegue' && $tache->delegue_id === auth()->id()))
                <a href="{{ route('taches.edit', $tache) }}" class="btn-tch btn-tch-primary">
                    <svg class="tch-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                    </svg>
                    Modifier
                </a>
            @endif

            @if(!$tache->is_validated && in_array(auth()->user()->role, ['admin','rbo']))
                <form method="POST" action="{{ route('taches.validate', $tache) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-tch btn-tch-success">
                        <svg class="tch-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Valider
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- ── Main card ── --}}
    <div class="tch-card">

        {{-- Card header --}}
        <div class="tch-card-hd">
            <div class="tch-card-title">
                <span class="tch-pip"></span>
                Informations de la tâche
            </div>
            <span class="tch-badge {{ $tache->is_validated ? 'tch-badge-green' : 'tch-badge-amber' }}">
                @if($tache->is_validated)
                    <svg class="tch-icon" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    Validée
                @else
                    <svg class="tch-icon" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    En attente
                @endif
            </span>
        </div>

        {{-- Info grid --}}
        <div class="tch-info-grid">

            <div class="tch-info-row">
                <span class="tch-info-lbl">Objet</span>
                <span class="tch-info-val" style="font-weight:500">{{ $tache->objet }}</span>
            </div>

            <div class="tch-info-row">
                <span class="tch-info-lbl">Description</span>
                @if($tache->description)
                    <span class="tch-info-val">{{ $tache->description }}</span>
                @else
                    <span class="tch-info-val muted">Aucune description</span>
                @endif
            </div>

            <div class="tch-info-row">
                <span class="tch-info-lbl">Date de planification</span>
                <span class="tch-info-val">
                    <svg class="tch-icon" width="15" height="15" fill="none" stroke="#185FA5" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    {{ $tache->date_planification->format('d/m/Y') }}
                </span>
            </div>

            <div class="tch-info-row">
                <span class="tch-info-lbl">Date de fin</span>
                <span class="tch-info-val">
                    @if($tache->date_fin)
                        <svg class="tch-icon" width="15" height="15" fill="none" stroke="#185FA5" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        {{ $tache->date_fin->format('d/m/Y') }}
                    @else
                        <span class="muted">—</span>
                    @endif
                </span>
            </div>

            <div class="tch-info-row">
                <span class="tch-info-lbl">Lieu</span>
                <span class="tch-info-val">
                    @if($tache->lieu)
                        <svg class="tch-icon" width="15" height="15" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $tache->lieu }}
                    @else
                        <span class="muted">—</span>
                    @endif
                </span>
            </div>

            <div class="tch-info-row">
                <span class="tch-info-lbl">Toute la journée</span>
                <span class="tch-info-val">{{ $tache->all_day ? 'Oui' : 'Non' }}</span>
            </div>

            <div class="tch-info-row">
                <span class="tch-info-lbl">Statut</span>
                <span class="tch-info-val">
                    <span class="tch-badge {{ $tache->is_validated ? 'tch-badge-green' : 'tch-badge-amber' }}">
                        {{ $tache->is_validated ? 'Validée' : 'En attente' }}
                    </span>
                </span>
            </div>

            <div class="tch-info-row">
                <span class="tch-info-lbl">Délégué</span>
                <span class="tch-info-val">
                    <div class="tch-avatar" aria-hidden="true">
                        {{ strtoupper(substr($tache->delegate->prenom, 0, 1)) }}{{ strtoupper(substr($tache->delegate->nom, 0, 1)) }}
                    </div>
                    {{ $tache->delegate->prenom }} {{ $tache->delegate->nom }}
                </span>
            </div>

            <div class="tch-info-row full">
                <span class="tch-info-lbl">Contacts</span>
                <div class="tch-chips">
                    @forelse($tache->contactsList as $contact)
                        <span class="tch-chip">
                            <div class="tch-avatar sm" aria-hidden="true">
                                {{ strtoupper(substr($contact->prenom, 0, 1)) }}{{ strtoupper(substr($contact->nom, 0, 1)) }}
                            </div>
                            {{ $contact->prenom }} {{ $contact->nom }}
                        </span>
                    @empty
                        <span class="tch-info-val muted">Aucun contact</span>
                    @endforelse
                </div>
            </div>

        </div>{{-- /tch-info-grid --}}

        {{-- ── Recurring section ── --}}
        @if($tache->recurrence_frequence || $tache->parent_tache_id)
        <div class="tch-recur">
            <div class="tch-recur-title">
                <svg class="tch-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <polyline points="23 4 23 10 17 10"/>
                    <polyline points="1 20 1 14 7 14"/>
                    <path d="M3.5 9a9 9 0 0114.5-3.5L23 10M1 14l5 4.5A9 9 0 0020.5 15"/>
                </svg>
                Tâche récurrente — Annuler la récurrence
            </div>

            <form method="POST" action="{{ route('taches.cancelRecurrence', $tache) }}"
                  onsubmit="return confirm('Confirmer la suppression des occurrences sélectionnées ?')">
                @csrf
                <div class="tch-radio-group">
                    <label class="tch-radio-lbl">
                        <input type="radio" name="scope" value="this_and_following" checked>
                        Supprimer <strong style="margin-left:.2rem">cette occurrence et les suivantes</strong>
                    </label>
                    <label class="tch-radio-lbl">
                        <input type="radio" name="scope" value="all">
                        Supprimer <strong style="margin-left:.2rem">toute la série</strong> (toutes les occurrences)
                    </label>
                </div>
                <button type="submit" class="btn-tch btn-tch-danger">
                    <svg class="tch-icon" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    Annuler la récurrence
                </button>
            </form>
        </div>
        @endif

        {{-- ── Card footer ── --}}
        <div class="tch-card-ft">
            <a href="{{ route('taches.index') }}" class="btn-tch btn-tch-ghost">
                <svg class="tch-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour
            </a>

            @if(!$tache->is_validated && (auth()->user()->role === 'delegue' && $tache->delegue_id === auth()->id()))
                <a href="{{ route('taches.edit', $tache) }}" class="btn-tch btn-tch-primary">
                    <svg class="tch-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                    </svg>
                    Modifier
                </a>
            @endif

            @if(!$tache->is_validated && in_array(auth()->user()->role, ['admin','rbo']))
                <form method="POST" action="{{ route('taches.validate', $tache) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-tch btn-tch-success">
                        <svg class="tch-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Valider
                    </button>
                </form>
            @endif
        </div>

    </div>{{-- /tch-card --}}

</div>{{-- /tch-pg --}}

@endsection

@push('scripts')
{{-- No specific script needed for show view --}}
@endpush
@extends('layouts.app')

@push('styles')
<style>
    /* ─────────────────────────────────────────────────────────────
   Google Fonts (Sora + DM Sans)
   Add this once in your layout <head> if not already present:
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
──────────────────────────────────────────────────────────────── */

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
.btn-tch-warning      { background: #F59E0B; color: #fff; }
.btn-tch-warning:hover{ background: #D97706; }
.btn-tch-info         { background: #EDE9FE; color: #6D28D9; border: 1px solid #E9D5FF; }
.btn-tch-info:hover   { background: #DDD6FE; }

/* ── Main card ── */
.tch-card             { background: #fff; border: 1px solid #e9eaee; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.05); }

/* ── Card header ── */
.tch-card-hd          { padding: 1.1rem 1.5rem; border-bottom: 1px solid #f0f1f5; display: flex; align-items: center; justify-content: space-between; }
.tch-card-title       { display: flex; align-items: center; gap: .55rem; font-family: 'Sora', sans-serif; font-weight: 600; font-size: .88rem; color: #111827; }
.tch-pip              { width: 6px; height: 6px; border-radius: 50%; background: #185FA5; flex-shrink: 0; }

/* ── Card body (used in forms and show views) ── */
.tch-card-body {
    padding: 1.5rem;
}

/* ── Info grid (show views: two columns, alternating rows) ── */
.tch-info-grid        { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
.tch-info-row         { padding: 1rem 1.5rem; border-bottom: 1px solid #f0f1f5; display: flex; flex-direction: column; gap: .3rem; }
.tch-info-row:nth-child(even) { background: #fafbfc; }
.tch-info-row.full    { grid-column: 1 / -1; }
.tch-info-lbl         { font-size: .72rem; font-weight: 500; text-transform: uppercase; letter-spacing: .07em; color: #9ca3af; }
.tch-info-val         { font-size: .9rem; color: #111827; font-weight: 400; display: flex; align-items: center; gap: .4rem; flex-wrap: wrap; word-break: break-word; overflow-wrap: break-word; }
.tch-info-val.muted   { color: #9ca3af; font-style: italic; }
.tch-info-val a       { color: #185FA5; text-decoration: none; }
.tch-info-val a:hover { text-decoration: underline; }

/* ── Form elements ── */
.frm-group {
    margin-bottom: 1.25rem;
}
.frm-label {
    display: block;
    font-size: 0.8rem;
    font-weight: 500;
    margin-bottom: 0.4rem;
    color: #1f2937;
}
.frm-label .req {
    color: #dc2626;
    margin-left: 0.2rem;
}
.frm-input-wrap,
.frm-select-wrap {
    position: relative;
}
.frm-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    pointer-events: none;
    display: flex;
    align-items: center;
}
.frm-input,
.frm-select {
    width: 100%;
    padding: 0.6rem 0.75rem;
    font-size: 0.85rem;
    font-family: 'DM Sans', sans-serif;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    background: #fff;
    transition: all 0.15s;
    color: #1a1d23;
}
.frm-input:focus,
.frm-select:focus {
    outline: none;
    border-color: #185FA5;
    box-shadow: 0 0 0 3px rgba(24, 95, 165, 0.1);
}
.frm-input.is-invalid,
.frm-select.is-invalid {
    border-color: #dc2626;
    background-color: #fef2f2;
}
.frm-input {
    padding-left: 2rem;
}
.frm-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' stroke='%236b7280' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1rem;
}
select[multiple] {
    background-image: none;
    padding: 0.5rem;
}
select[multiple] option {
    padding: 0.4rem 0.5rem;
    border-bottom: 1px solid #f0f1f5;
}
textarea.frm-input {
    padding-left: 0.75rem;
    resize: vertical;
}
.frm-error {
    display: flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.7rem;
    color: #dc2626;
    margin-top: 0.3rem;
}
.frm-hint {
    display: block;
    font-size: 0.7rem;
    color: #6b7280;
    margin-top: 0.3rem;
}
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1rem;
}

/* ── Table styles ── */
.tch-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
}
.tch-table th,
.tch-table td {
    padding: 0.75rem 1rem;
    text-align: left;
    border-bottom: 1px solid #f0f1f5;
}
.tch-table th {
    font-weight: 600;
    color: #374151;
    background: #fafbfc;
    font-family: 'Sora', sans-serif;
    font-size: 0.8rem;
}
.tch-table tbody tr:hover {
    background: #f9fafb;
}
.tch-table td:first-child,
.tch-table th:first-child {
    padding-left: 1rem;
}
.tch-table td:last-child,
.tch-table th:last-child {
    padding-right: 1rem;
}
.table-responsive {
    overflow-x: auto;
    margin: 1rem 0;
}

/* ── Badges (base + product + status variants) ── */
.tch-badge {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    padding: .25rem .65rem;
    border-radius: 999px;
    font-size: .75rem;
    font-weight: 500;
}
.tch-badge-green      { background: #EAF3DE; color: #3B6D11; }
.tch-badge-amber      { background: #FAEEDA; color: #854F0B; }
.tch-badge-blue       { background: #E0F2FE; color: #0369A1; }
.tch-badge-teal       { background: #CCFBF1; color: #0F766E; }
.tch-badge-violet     { background: #EDE9FE; color: #6D28D9; }
/* Action status badges */
.tch-badge-planifie   { background: #FEF3C7; color: #92400E; }
.tch-badge-realise    { background: #E0F2FE; color: #0369A1; }
.tch-badge-valide     { background: #DCFCE7; color: #166534; }
.tch-badge-annule     { background: #FEE2E2; color: #991B1B; }
/* Réclamation status badges */
.tch-badge-brouillon  { background: #F3F4F6; color: #374151; }
.tch-badge-en_cours   { background: #FEF3C7; color: #92400E; }
.tch-badge-traite     { background: #E0F2FE; color: #0369A1; }
.tch-badge-cloture    { background: #DCFCE7; color: #166534; }
/* Event contact status badges (invité, confirmé, refusé) – reuse existing */
/* tch-badge-blue for invité, tch-badge-green for confirmé, tch-badge-amber for refusé */

/* ── Chips (for tags / metadata) ── */
.tch-chips            { display: flex; gap: .4rem; flex-wrap: wrap; margin: .5rem 0 .25rem; }
.tch-chip             { display: inline-flex; align-items: center; gap: .3rem; background: #f3f4f6; border: 1px solid #e9eaee; border-radius: 999px; padding: .2rem .55rem .2rem .3rem; font-size: .78rem; color: #374151; }

/* ── Card footer ── */
.tch-card-ft          { padding: .9rem 1.5rem; border-top: 1px solid #f0f1f5; background: #fafbfc; display: flex; justify-content: flex-end; gap: .5rem; align-items: center; flex-wrap: wrap; }

/* ── Divider, subtitle, help text, alert, line card (action view) ── */
.tch-divider {
    margin: 1.5rem 0;
    border: none;
    border-top: 1px solid #e5e7eb;
}
.tch-subtitle {
    font-family: 'Sora', sans-serif;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #111827;
}
.tch-help-text {
    font-size: 0.82rem;
    color: #6b7280;
    margin-top: 0.75rem;
}
.tch-alert {
    padding: 0.85rem 1rem;
    border-radius: 10px;
    font-size: 0.82rem;
    margin: 1rem 0;
}
.tch-alert-error {
    background: #FEF2F2;
    border: 1px solid #FEE2E2;
    color: #991B1B;
}
.tch-alert-error ul {
    margin: 0.5rem 0 0 1rem;
}
.tch-line-card {
    background: #fafbfc;
    border: 1px solid #f0f1f5;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1rem;
}
.tch-line-card .tch-info-grid {
    margin-bottom: 0;
}

/* ── Modal styles ── */
.tch-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s;
}
.tch-modal-overlay.visible {
    opacity: 1;
    visibility: visible;
}
.tch-modal {
    background: #fff;
    border-radius: 20px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 20px 35px -10px rgba(0,0,0,0.2);
    overflow: hidden;
}
.tch-modal.tch-modal-lg {
    max-width: 650px;
}
.tch-modal-hd {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1.2rem 1.5rem;
    border-bottom: 1px solid #f0f1f5;
}
.tch-modal-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: #f3f4f6;
    border-radius: 999px;
    color: #185FA5;
}
.tch-modal-titles {
    flex: 1;
}
.tch-modal-titles h2 {
    font-family: 'Sora', sans-serif;
    font-size: 1rem;
    font-weight: 600;
    margin: 0;
    color: #111827;
}
.tch-modal-titles p {
    font-size: 0.75rem;
    color: #6b7280;
    margin: 0.2rem 0 0;
}
.tch-modal-close {
    background: none;
    border: none;
    cursor: pointer;
    color: #9ca3af;
    padding: 0.25rem;
    display: flex;
    align-items: center;
    transition: color 0.15s;
}
.tch-modal-close:hover {
    color: #111827;
}
.tch-modal-body {
    padding: 1.5rem;
}
.tch-modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.6rem;
    margin-top: 1.5rem;
}

/* ── SVG icons (inline helpers) ── */
.tch-icon             { display: inline-block; vertical-align: middle; flex-shrink: 0; }

/* ── Responsive adjustments ── */
@media (max-width: 480px) {
    .tch-pg { padding: 1rem; }
    .tch-info-row { padding-inline: 1rem; }
    .tch-card-body { padding: 1rem; }
    .tch-table th,
    .tch-table td { padding: 0.5rem; }
    .tch-modal { width: 95%; }
}

/* ── Focus styles for accessibility ── */
.btn-tch:focus-visible,
.tch-bc a:focus-visible,
.frm-input:focus-visible,
.frm-select:focus-visible,
.tch-modal-close:focus-visible,
.tch-info-val a:focus-visible {
    outline: 2px solid #185FA5;
    outline-offset: 2px;
}
</style>
@endpush

@section('content')
<div class="tch-pg">

    {{-- Breadcrumb --}}
    <nav class="tch-bc" aria-label="Fil d'Ariane">
        <a href="{{ route('events.index') }}">
            <svg class="tch-icon" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="tch-bc-sep">›</span>
        <span class="tch-bc-cur">{{ $event->type }}</span>
    </nav>

    {{-- Page header --}}
    <div class="tch-hdr">
        <div class="tch-hdr-l">
            <h1>{{ $event->type }}</h1>
            <p>{{ $event->date_event->format('d/m/Y') }} - {{ $event->ville->nom }}</p>
        </div>
    </div>

    {{-- Main card --}}
    <div class="tch-card">
        <div class="tch-card-hd">
            <div class="tch-card-title">
                <span class="tch-pip"></span>
                Détails de l'événement
            </div>
        </div>

        <div class="tch-card-body">
            {{-- Informations générales --}}
            <div class="tch-info-grid">
                <div class="tch-info-row">
                    <span class="tch-info-lbl">Éditeur</span>
                    <span class="tch-info-val">{{ $event->editeur }}</span>
                </div>
                <div class="tch-info-row">
                    <span class="tch-info-lbl">Zone</span>
                    <span class="tch-info-val">{{ $event->zone->name }}</span>
                </div>
                <div class="tch-info-row">
                    <span class="tch-info-lbl">Délégué</span>
                    <span class="tch-info-val">{{ $event->delegate->prenom }} {{ $event->delegate->nom }}</span>
                </div>
            </div>

            {{-- Contacts invités --}}
            <h3 class="tch-subtitle">Contacts invités</h3>
            <div class="table-responsive">
                <table class="tch-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Ville</th>
                            <th>Fonction</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($event->contacts as $contact)
                        <tr>
                            <td>{{ $contact->prenom }} {{ $contact->nom }}</td>
                            <td>{{ $contact->ville->nom }}</td>
                            <td>{{ $contact->fonction ?? '-' }}</td>
                            <td>
                                @php
                                    $statutClass = match($contact->pivot->statut) {
                                        'invite' => 'tch-badge-blue',
                                        'confirme' => 'tch-badge-green',
                                        'refuse' => 'tch-badge-amber',
                                        default => 'tch-badge'
                                    };
                                @endphp
                                <span class="tch-badge {{ $statutClass }}">{{ $statuts[$contact->pivot->statut] }}</span>
                            </td>
                            <td>
                                @if(auth()->user()->role === 'delegue' && $event->delegue_id === auth()->id())
                                    <form method="POST" action="{{ route('events.update-status', [$event, $contact]) }}" style="display:inline;">
                                        @csrf
                                        <div class="frm-select-wrap">
                                            <select name="statut" class="frm-select" onchange="this.form.submit()">
                                                @foreach($statuts as $key => $label)
                                                    <option value="{{ $key }}" {{ $contact->pivot->statut == $key ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </form>
                                @endif
                             </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer actions --}}
        @if(auth()->user()->role === 'delegue' && $event->delegue_id === auth()->id())
        <div class="tch-card-ft">
            <a href="{{ route('events.index') }}" class="btn-tch btn-tch-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour
            </a>
            <a href="{{ route('events.invite', $event) }}" class="btn-tch btn-tch-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Inviter plus de contacts
            </a>
            <a href="{{ route('events.edit', $event) }}" class="btn-tch btn-tch-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                </svg>
                Modifier
            </a>
            <a href="{{ route('events.statistics', $event) }}" class="btn-tch btn-tch-info">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 12a9 9 0 0 1-9 9m9-9a9 9 0 0 0-9-9m9 9H3m9 9a9 9 0 0 1-9-9m9 9c1.66 0 3-4 3-9s-1.34-9-3-9m0 18c-1.66 0-3-4-3-9s1.34-9 3-9"/>
                </svg>
                Statistiques
            </a>
        </div>
        @else
        <div class="tch-card-ft" style="justify-content: flex-end;">
            <a href="{{ route('events.index') }}" class="btn-tch btn-tch-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour
            </a>
            @if(in_array(auth()->user()->role, ['admin','rbo']))
                <a href="{{ route('events.edit', $event) }}" class="btn-tch btn-tch-ghost">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                    </svg>
                    Modifier
                </a>
                <a href="{{ route('events.statistics', $event) }}" class="btn-tch btn-tch-info">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 12a9 9 0 0 1-9 9m9-9a9 9 0 0 0-9-9m9 9H3m9 9a9 9 0 0 1-9-9m9 9c1.66 0 3-4 3-9s-1.34-9-3-9m0 18c-1.66 0-3-4-3-9s1.34-9 3-9"/>
                    </svg>
                    Statistiques
                </a>
            @endif
            @if(auth()->user()->role === 'admin')
                <form method="POST" action="{{ route('events.destroy', $event) }}" style="display:inline;" onsubmit="return confirm('Supprimer cet événement ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-tch btn-tch-danger" title="Supprimer">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        Supprimer
                    </button>
                </form>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
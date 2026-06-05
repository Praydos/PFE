@extends('layouts.app')

@push('styles')
{{-- Include the full tch CSS (provided in previous answers) --}}
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
        <a href="{{ route('reclamations.index') }}">
            <svg class="tch-icon" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="tch-bc-sep">›</span>
        <a href="{{ route('reclamations.index') }}">Réclamations</a>
        <span class="tch-bc-sep">›</span>
        <span class="tch-bc-cur">{{ $reclamation->reference }}</span>
    </nav>

    {{-- Page header --}}
    <div class="tch-hdr">
        <div class="tch-hdr-l">
            <h1>Réclamation {{ $reclamation->reference }}</h1>
            <p>{{ $reclamation->categorie }} – 
                <span class="tch-badge tch-badge-{{ $reclamation->statut }}">
                    {{ ucfirst($reclamation->statut) }}
                </span>
            </p>
        </div>
        <div class="tch-hdr-actions">
            <a href="{{ route('reclamations.pdf', $reclamation) }}" target="_blank" class="btn-tch btn-tch-ghost">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                    <polyline points="10 9 9 9 8 9"/>
                </svg>
                Exporter PDF
            </a>
        </div>
    </div>

    {{-- Main card --}}
    <div class="tch-card">
        <div class="tch-card-hd">
            <div class="tch-card-title">
                <span class="tch-pip"></span>
                Détails de la réclamation
            </div>
        </div>

        <div class="tch-card-body">
            {{-- Info grid --}}
            <div class="tch-info-grid">
                <div class="tch-info-row"><span class="tch-info-lbl">Compte</span><span class="tch-info-val">{{ $reclamation->compte->etablissement }}</span></div>
                <div class="tch-info-row"><span class="tch-info-lbl">Contact</span><span class="tch-info-val">{{ $reclamation->contact->prenom }} {{ $reclamation->contact->nom }}</span></div>
                <div class="tch-info-row"><span class="tch-info-lbl">Date réclamation</span><span class="tch-info-val">{{ $reclamation->date_reclamation->format('d/m/Y') }}</span></div>
                <div class="tch-info-row"><span class="tch-info-lbl">Type</span><span class="tch-info-val">{{ str_replace('_', ' ', $reclamation->type ?? '-') }}</span></div>
                <div class="tch-info-row"><span class="tch-info-lbl">Priorité</span><span class="tch-info-val">{{ ucfirst($reclamation->priorite) }}</span></div>
                <div class="tch-info-row"><span class="tch-info-lbl">Catégorie</span><span class="tch-info-val">{{ $reclamation->categorie }} @if($reclamation->sous_categorie) / {{ $reclamation->sous_categorie }} @endif</span></div>

                {{-- Linked module (if any) --}}
                @if(($reclamation->module_lie && $reclamation->module_id) || $reclamation->produit_id || $reclamation->specimen_id || $reclamation->mp_id)
                    @php $linked = $reclamation->linked_module; @endphp
                    @if($linked || $reclamation->produit_id || $reclamation->specimen_id || $reclamation->mp_id)
                        <div class="tch-info-row">
                            <span class="tch-info-lbl">
                                @switch($reclamation->module_lie)
                                    @case('examen') Examen lié @break
                                    @case('event') Événement lié @break
                                    @case('product') Produit lié @break
                                    @case('specimen') Spécimen lié @break
                                    @case('mp') Matériel pédagogique lié @break
                                    @default Élément lié
                                @endswitch
                            </span>
                            <span class="tch-info-val">
                                @if($reclamation->module_lie === 'examen')
                                    <a href="{{ route('examens.show', $reclamation->module_id) }}">{{ $linked->titre ?? 'Voir' }}</a>
                                @elseif($reclamation->module_lie === 'event')
                                    <a href="{{ route('events.show', $reclamation->module_id) }}">Voir l'événement</a>
                                @elseif($reclamation->module_lie === 'product')
                                    <a href="{{ route('products.show', $reclamation->produit_id) }}">Voir le produit</a>
                                @elseif($reclamation->module_lie === 'specimen')
                                    <a href="{{ route('bss.show', $reclamation->specimen_id) }}">Voir le spécimen</a>
                                @elseif($reclamation->module_lie === 'mp')
                                    <a href="{{ route('mp-products.index', $reclamation->mp_id) }}">Voir le matériel pédagogique</a>
                                @endif
                            </span>
                        </div>
                    @endif
                @endif

                {{-- Description --}}
                <div class="tch-info-row full"><span class="tch-info-lbl">Description</span><span class="tch-info-val">{{ $reclamation->description }}</span></div>

                @if($reclamation->analyse)
                <div class="tch-info-row full"><span class="tch-info-lbl">Analyse</span><span class="tch-info-val">{{ $reclamation->analyse }}</span></div>
                @endif
                @if($reclamation->reponse)
                <div class="tch-info-row full"><span class="tch-info-lbl">Réponse</span><span class="tch-info-val">{{ $reclamation->reponse }}</span></div>
                @endif
                @if($reclamation->date_reponse)
                <div class="tch-info-row"><span class="tch-info-lbl">Date réponse</span><span class="tch-info-val">{{ $reclamation->date_reponse->format('d/m/Y') }}</span></div>
                @endif
                @if($reclamation->responsable)
                <div class="tch-info-row"><span class="tch-info-lbl">Responsable</span><span class="tch-info-val">{{ $reclamation->responsable->prenom }} {{ $reclamation->responsable->nom }}</span></div>
                @endif
                <div class="tch-info-row"><span class="tch-info-lbl">Statut</span><span class="tch-info-val">
                    <span class="tch-badge tch-badge-{{ $reclamation->statut }}">{{ ucfirst($reclamation->statut) }}</span>
                </span></div>
                @if($reclamation->date_cloture)
                <div class="tch-info-row"><span class="tch-info-lbl">Date clôture</span><span class="tch-info-val">{{ $reclamation->date_cloture->format('d/m/Y') }}</span></div>
                @endif
                <div class="tch-info-row"><span class="tch-info-lbl">Est une non‑conformité ?</span><span class="tch-info-val">{{ $reclamation->est_non_conformite ? 'Oui' : 'Non' }}</span></div>
                <div class="tch-info-row"><span class="tch-info-lbl">Besoin d'action d'amélioration ?</span><span class="tch-info-val">{{ $reclamation->besoin_action_amelioration ? 'Oui' : 'Non' }}</span></div>
            </div>
        </div>

        {{-- Card footer (actions) --}}
        <div class="tch-card-ft">
            <a href="{{ route('reclamations.index') }}" class="btn-tch btn-tch-ghost">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour
            </a>

            @if($reclamation->statut === 'brouillon' && auth()->user()->role === 'delegue' && $reclamation->delegue_id === auth()->id())
                <a href="{{ route('reclamations.edit', $reclamation) }}" class="btn-tch btn-tch-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                    </svg>
                    Modifier
                </a>
            @endif

            @if(in_array(auth()->user()->role, ['admin','rbo']))
                <a href="{{ route('reclamations.edit', $reclamation) }}" class="btn-tch btn-tch-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                    </svg>
                    Traiter
                </a>
            @endif

            @if(auth()->user()->role === 'admin')
                <form method="POST" action="{{ route('reclamations.destroy', $reclamation) }}" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-tch btn-tch-danger" onclick="return confirm('Supprimer définitivement ?')">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6l-1 14H6L5 6"/>
                        </svg>
                        Supprimer
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
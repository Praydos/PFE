@extends('layouts.app')

@push('styles')
{{-- The full CSS from previous tasks should be included here or in your main layout --}}
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

/* ── Info grid (used in show views) ── */
.tch-info-grid        { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
.tch-info-row         { padding: 1rem 1.5rem; border-bottom: 1px solid #f0f1f5; display: flex; flex-direction: column; gap: .3rem; }
.tch-info-row:nth-child(even) { background: #fafbfc; }
.tch-info-row.full    { grid-column: 1 / -1; }
.tch-info-lbl         { font-size: .72rem; font-weight: 500; text-transform: uppercase; letter-spacing: .07em; color: #9ca3af; }
.tch-info-val         { font-size: .9rem; color: #111827; font-weight: 400; display: flex; align-items: center; gap: .4rem; flex-wrap: wrap; word-break: break-word; overflow-wrap: break-word; }
.tch-info-val.muted   { color: #9ca3af; font-style: italic; }
.tch-info-val a       { color: #185FA5; text-decoration: none; }
.tch-info-val a:hover { text-decoration: underline; }

/* ── Badges (optional, kept for consistency) ── */
.tch-badge            { display: inline-flex; align-items: center; gap: .3rem; padding: .25rem .65rem; border-radius: 999px; font-size: .75rem; font-weight: 500; }
.tch-badge-green      { background: #EAF3DE; color: #3B6D11; }
.tch-badge-amber      { background: #FAEEDA; color: #854F0B; }

/* ── Card footer ── */
.tch-card-ft          { padding: .9rem 1.5rem; border-top: 1px solid #f0f1f5; background: #fafbfc; display: flex; justify-content: flex-end; gap: .5rem; align-items: center; flex-wrap: wrap; }

/* ── SVG icons (inline helpers) ── */
.tch-icon             { display: inline-block; vertical-align: middle; flex-shrink: 0; }

/* ── Responsive adjustments ── */
@media (max-width: 480px) {
    .tch-pg { padding: 1rem; }
    .tch-info-row { padding-inline: 1rem; }
}

/* ── Focus styles for accessibility ── */
.btn-tch:focus-visible,
.tch-bc a:focus-visible,
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
        <a href="{{ route('adoptions.index') }}">
            <svg class="tch-icon" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="tch-bc-sep">›</span>
        <a href="{{ route('adoptions.index') }}">Adoptions</a>
        <span class="tch-bc-sep">›</span>
        <span class="tch-bc-cur">Détail #{{ $adoption->id }}</span>
    </nav>

    {{-- Page header --}}
    <div class="tch-hdr">
        <div class="tch-hdr-l">
            <h1>Adoption #{{ $adoption->id }}</h1>
            <p>Détail de l'adoption</p>
        </div>
        <div class="tch-hdr-actions">
            <a href="{{ route('adoptions.index') }}" class="btn-tch btn-tch-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour
            </a>
        </div>
    </div>

    {{-- Main card --}}
    <div class="tch-card">
        <div class="tch-card-hd">
            <div class="tch-card-title">
                <span class="tch-pip"></span>
                Informations générales
            </div>
        </div>

        {{-- Info grid (two columns with alternating rows) --}}
        <div class="tch-info-grid">
            <div class="tch-info-row">
                <span class="tch-info-lbl">Compte</span>
                <span class="tch-info-val">{{ $adoption->compte->etablissement }}</span>
            </div>
            <div class="tch-info-row">
                <span class="tch-info-lbl">Contact</span>
                <span class="tch-info-val">{{ $adoption->contact->prenom }} {{ $adoption->contact->nom }}</span>
            </div>
            <div class="tch-info-row">
                <span class="tch-info-lbl">Produit</span>
                <span class="tch-info-val">{{ $adoption->product->titre }} ({{ $adoption->product->isbn_13 ?? $adoption->product->isbn_10 }})</span>
            </div>
            <div class="tch-info-row">
                <span class="tch-info-lbl">Type adoption</span>
                <span class="tch-info-val">
                    @php
                        $typeMap = [
                            'BOOKLAND' => 'Bookland',
                            'ESPRIT_DU_LIVRE' => 'Esprit du livre',
                            'CONCURRENT' => 'Concurrent'
                        ];
                    @endphp
                    {{ $typeMap[$adoption->type_adoption] ?? $adoption->type_adoption }}
                </span>
            </div>
            <div class="tch-info-row">
                <span class="tch-info-lbl">ISBN</span>
                <span class="tch-info-val">{{ $adoption->isbn ?? '-' }}</span>
            </div>
            <div class="tch-info-row">
                <span class="tch-info-lbl">Sous-catégorie</span>
                <span class="tch-info-val">{{ $adoption->sous_categorie ?? '-' }}</span>
            </div>
            <div class="tch-info-row">
                <span class="tch-info-lbl">Méthode</span>
                <span class="tch-info-val">{{ $adoption->methode }}</span>
            </div>
            <div class="tch-info-row">
                <span class="tch-info-lbl">Année scolaire</span>
                <span class="tch-info-val">{{ $adoption->anneeScolaire->libelle }}</span>
            </div>
            <div class="tch-info-row">
                <span class="tch-info-lbl">Quantité</span>
                <span class="tch-info-val">{{ $adoption->quantity }}</span>
            </div>
            <div class="tch-info-row">
                <span class="tch-info-lbl">Date adoption</span>
                <span class="tch-info-val">{{ $adoption->date_adoption->format('d/m/Y') }}</span>
            </div>
            <div class="tch-info-row">
                <span class="tch-info-lbl">Délégué</span>
                <span class="tch-info-val">{{ $adoption->delegate->prenom }} {{ $adoption->delegate->nom }}</span>
            </div>
            <div class="tch-info-row">
                <span class="tch-info-lbl">Niveau scolaire</span>
                <span class="tch-info-val">{{ $adoption->niveau ?? '-' }}</span>
            </div>
            <div class="tch-info-row">
                <span class="tch-info-lbl">Cycle</span>
                <span class="tch-info-val">{{ $adoption->cycle ?? '-' }}</span>
            </div>
            @if($adoption->bssLigne)
            <div class="tch-info-row">
                <span class="tch-info-lbl">BSS source</span>
                <span class="tch-info-val">
                    <a href="{{ route('bss.show', $adoption->bssLigne->bss) }}" style="color: #185FA5; text-decoration: none;">
                        {{ $adoption->bssLigne->bss->numero }}
                    </a>
                </span>
            </div>
            @endif
        </div>{{-- /.tch-info-grid --}}

        {{-- Card footer (actions) --}}
        <div class="tch-card-ft">
            <a href="{{ route('adoptions.index') }}" class="btn-tch btn-tch-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour
            </a>
            @if(auth()->user()->role === 'delegue' && $adoption->delegate_id === auth()->id())
                <a href="{{ route('adoptions.edit', $adoption) }}" class="btn-tch btn-tch-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                    </svg>
                    Modifier
                </a>
            @endif
        </div>
    </div>{{-- /.tch-card --}}

</div>{{-- /.tch-pg --}}
@endsection
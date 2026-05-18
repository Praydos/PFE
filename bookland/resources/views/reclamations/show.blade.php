@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ===== FULL DESIGN SYSTEM CSS ===== */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --bg-base:       #f5f6fa;
        --bg-card:       #ffffff;
        --bg-hover:      #f8f9fd;
        --bg-subtle:     #f0f2f8;
        --border:        #e4e7f0;
        --border-md:     #d0d5e8;
        --blue:          #5b8dee;
        --blue-dark:     #3d6fd6;
        --blue-light:    #eef3fd;
        --blue-mid:      #dce8fb;
        --teal:          #0cb8b6;
        --teal-light:    #e6faf9;
        --violet:        #7c6fcd;
        --violet-light:  #f0eeff;
        --amber:         #e8a020;
        --amber-light:   #fff8ec;
        --rose:          #e8506a;
        --rose-light:    #fef0f2;
        --green:         #28c76f;
        --green-light:   #e8fbf0;
        --text-primary:   #1a1f36;
        --text-secondary: #525f7f;
        --text-muted:     #9ba8c5;
        --text-hint:      #bcc5dc;
        --r-xs: 6px; --r-sm: 8px; --r-md: 12px; --r-lg: 16px; --r-xl: 20px;
        --shadow-xs: 0 1px 3px rgba(31,45,80,.06), 0 1px 2px rgba(31,45,80,.04);
        --shadow-sm: 0 2px 8px rgba(31,45,80,.08), 0 1px 3px rgba(31,45,80,.05);
        --shadow-md: 0 8px 24px rgba(31,45,80,.10), 0 2px 8px rgba(31,45,80,.06);
        --shadow-blue: 0 4px 14px rgba(91,141,238,.35);
        --font: 'DM Sans', sans-serif;
        --font-mono: 'DM Mono', monospace;
        --ease: cubic-bezier(.4,0,.2,1);
        --t: .18s var(--ease);
    }

    body { font-family: var(--font); background: var(--bg-base); color: var(--text-primary); -webkit-font-smoothing: antialiased; }

    .zn-page { padding: 2rem 2.5rem 3rem; animation: pageIn .4s var(--ease) both; max-width: 1400px; margin: 0 auto; }
    @keyframes pageIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

    .zn-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.4rem; }
    .zn-bc a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
    .zn-bc a:hover { color: var(--blue); }
    .zn-bc-sep { color: var(--text-hint); }
    .zn-bc-cur { color: var(--text-secondary); }

    .zn-header { margin-bottom: 2rem; }
    .zn-header h1 { font-size: 1.5rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.15; }
    .zn-header p { font-size: .82rem; color: var(--text-muted); margin-top: .3rem; }

    .btn-zn {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .56rem 1.1rem; border-radius: var(--r-sm);
        font-family: var(--font); font-size: .82rem; font-weight: 600;
        cursor: pointer; border: 1px solid transparent;
        transition: all var(--t); text-decoration: none;
        white-space: nowrap; letter-spacing: -.01em; line-height: 1;
    }
    .btn-zn svg { flex-shrink: 0; }
    .btn-zn-primary { background: var(--blue); color: #fff; border-color: var(--blue); box-shadow: var(--shadow-blue); }
    .btn-zn-primary:hover { background: var(--blue-dark); color: #fff; transform: translateY(-1px); }
    .btn-zn-ghost { background: var(--bg-card); color: var(--text-secondary); border-color: var(--border); box-shadow: var(--shadow-xs); }
    .btn-zn-ghost:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--border-md); text-decoration: none; }
    .btn-zn-danger { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.18); }
    .btn-zn-danger:hover { background: #fddde2; color: var(--rose); text-decoration: none; }

    .fp-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--r-xl);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .fp-section {
        padding: 2rem 2rem 1.5rem;
        border-bottom: 1px solid var(--border);
    }
    .fp-section:last-of-type { border-bottom: none; }
    .fp-section-head {
        display: flex; align-items: center; gap: .75rem;
        margin-bottom: 1.6rem;
    }
    .fp-section-icon {
        width: 34px; height: 34px; flex-shrink: 0;
        border-radius: var(--r-md);
        display: flex; align-items: center; justify-content: center;
        background: var(--blue-light); color: var(--blue);
    }
    .fp-section-meta { flex: 1; }
    .fp-section-title { font-size: .9rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.02em; }
    .fp-section-sub   { font-size: .74rem; color: var(--text-muted); margin-top: .1rem; }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1rem;
    }
    .info-item {
        display: flex;
        flex-wrap: wrap;
        align-items: baseline;
        gap: 0.5rem;
        padding: 0.6rem 0;
        border-bottom: 1px solid var(--border);
    }
    .info-item.full {
        grid-column: 1 / -1;
    }
    .info-label {
        font-size: .68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--text-muted);
        min-width: 130px;
    }
    .info-value {
        font-size: .85rem;
        color: var(--text-primary);
        font-weight: 500;
    }
    .info-value a {
        color: var(--blue);
        text-decoration: none;
    }
    .info-value a:hover {
        text-decoration: underline;
    }

    .fp-footer {
        padding: 1.25rem 2rem;
        background: linear-gradient(to bottom, #fafbff, #fff);
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: .75rem;
        flex-wrap: wrap;
    }
    .fp-footer-spacer { flex: 1; }

    @media (max-width: 680px) {
        .zn-page { padding: 1.25rem 1rem 2rem; }
        .info-grid { grid-template-columns: 1fr; }
        .fp-footer { flex-direction: column; align-items: stretch; }
        .fp-footer-spacer { display: none; }
        .btn-zn { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('reclamations.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('reclamations.index') }}">Réclamations</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">{{ $reclamation->reference }}</span>
    </div>

    <div class="zn-header">
        <h1>Réclamation {{ $reclamation->reference }}</h1>
        <p>{{ $reclamation->categorie }} – {{ ucfirst($reclamation->statut) }}</p>
    </div>

    <div class="fp-card">
        <div class="fp-section">
            <div class="fp-section-head">
                <div class="fp-section-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                </div>
                <div class="fp-section-meta">
                    <div class="fp-section-title">Détails de la réclamation</div>
                    <div class="fp-section-sub">Informations client et traitement</div>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item"><span class="info-label">Compte</span><span class="info-value">{{ $reclamation->compte->etablissement }}</span></div>
                <div class="info-item"><span class="info-label">Contact</span><span class="info-value">{{ $reclamation->contact->prenom }} {{ $reclamation->contact->nom }}</span></div>
                <div class="info-item"><span class="info-label">Date réclamation</span><span class="info-value">{{ $reclamation->date_reclamation->format('d/m/Y') }}</span></div>
                <div class="info-item"><span class="info-label">Type</span><span class="info-value">{{ str_replace('_', ' ', $reclamation->type ?? '-') }}</span></div>
                <div class="info-item"><span class="info-label">Priorité</span><span class="info-value">{{ ucfirst($reclamation->priorite) }}</span></div>
                <div class="info-item"><span class="info-label">Catégorie</span><span class="info-value">{{ $reclamation->categorie }} @if($reclamation->sous_categorie) / {{ $reclamation->sous_categorie }} @endif</span></div>

                @if($reclamation->produit_id)
                <div class="info-item"><span class="info-label">Produit lié</span><span class="info-value"><a href="{{ route('products.show', $reclamation->produit_id) }}">{{ optional($reclamation->produit)->titre }}</a></span></div>
                @endif
                @if($reclamation->specimen_id)
                <div class="info-item"><span class="info-label">Spécimen lié</span><span class="info-value"><a href="{{ route('bss.show', $reclamation->specimen_id) }}">{{ optional($reclamation->specimen)->numero }}</a></span></div>
                @endif
                @if($reclamation->mp_id)
                <div class="info-item"><span class="info-label">MP lié</span><span class="info-value"><a href="{{ route('mp-products.show', $reclamation->mp_id) }}">{{ optional($reclamation->mp)->nom }}</a></span></div>
                @endif

                <div class="info-item full"><span class="info-label">Description</span><span class="info-value">{{ $reclamation->description }}</span></div>

                @if($reclamation->analyse)
                <div class="info-item full"><span class="info-label">Analyse</span><span class="info-value">{{ $reclamation->analyse }}</span></div>
                @endif
                @if($reclamation->reponse)
                <div class="info-item full"><span class="info-label">Réponse</span><span class="info-value">{{ $reclamation->reponse }}</span></div>
                @endif
                @if($reclamation->date_reponse)
                <div class="info-item"><span class="info-label">Date réponse</span><span class="info-value">{{ $reclamation->date_reponse->format('d/m/Y') }}</span></div>
                @endif
                @if($reclamation->responsable)
                <div class="info-item"><span class="info-label">Responsable</span><span class="info-value">{{ $reclamation->responsable->prenom }} {{ $reclamation->responsable->nom }}</span></div>
                @endif
                <div class="info-item"><span class="info-label">Statut</span><span class="info-value">{{ ucfirst($reclamation->statut) }}</span></div>
                @if($reclamation->date_cloture)
                <div class="info-item"><span class="info-label">Date clôture</span><span class="info-value">{{ $reclamation->date_cloture->format('d/m/Y') }}</span></div>
                @endif
                <div class="info-item"><span class="info-label">Est une non‑conformité ?</span><span class="info-value">{{ $reclamation->est_non_conformite ? 'Oui' : 'Non' }}</span></div>
                <div class="info-item"><span class="info-label">Besoin d'action d'amélioration ?</span><span class="info-value">{{ $reclamation->besoin_action_amelioration ? 'Oui' : 'Non' }}</span></div>
            </div>
        </div>

        <div class="fp-footer">
            <div class="fp-footer-spacer"></div>
            <a href="{{ route('reclamations.index') }}" class="btn-zn btn-zn-ghost">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour
            </a>
            @if($reclamation->statut === 'brouillon' && auth()->user()->role === 'delegue' && $reclamation->delegue_id === auth()->id())
                <a href="{{ route('reclamations.edit', $reclamation) }}" class="btn-zn btn-zn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                    </svg>
                    Modifier
                </a>
            @endif
            @if(in_array(auth()->user()->role, ['admin','rbo']))
                <a href="{{ route('reclamations.edit', $reclamation) }}" class="btn-zn btn-zn-primary">
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
                    <button type="submit" class="btn-zn btn-zn-danger" onclick="return confirm('Supprimer définitivement ?')">
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
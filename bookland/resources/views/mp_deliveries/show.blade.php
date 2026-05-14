@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ===== FULL CSS FROM THE DESIGN SYSTEM ===== */
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

    .zn-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1.5rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }
    .zn-header-left h1 { font-size: 1.5rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.15; margin: 0; }
    .zn-header-left p { font-size: .82rem; color: var(--text-muted); margin-top: .3rem; }

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

    .dr-badge {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .22rem .65rem; border-radius: 20px;
        font-size: .7rem; font-weight: 600; white-space: nowrap;
    }
    .bd-green { background: var(--green-light); color: var(--green); }
    .bd-amber { background: var(--amber-light); color: var(--amber); }

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

    .zn-alert-success {
        background: var(--green-light);
        border: 1px solid rgba(40,199,111,.2);
        color: #166534;
        padding: .85rem 1rem;
        border-radius: var(--r-lg);
        margin-bottom: 1.5rem;
        font-size: .82rem;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

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
        <a href="{{ route('mp-deliveries.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('mp-deliveries.index') }}">Livraisons MP</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">{{ $delivery->numero }}</span>
    </div>

    @if(session('success'))
        <div class="zn-alert-success">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Header with title and delete button --}}
    <div class="zn-header">
        <div class="zn-header-left">
            <h1>{{ $delivery->numero }}</h1>
            <p>Détail de la livraison MP</p>
        </div>
        @if(auth()->user()->role === 'admin')
            <form action="{{ route('mp-deliveries.destroy', $delivery) }}" method="POST" onsubmit="return confirm('Supprimer cette livraison ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-zn btn-zn-danger">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14H6L5 6"/>
                    </svg>
                    Supprimer
                </button>
            </form>
        @endif
    </div>

    {{-- Main card --}}
    <div class="fp-card">
        <div class="fp-section">
            <div class="fp-section-head">
                <div class="fp-section-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
                <div class="fp-section-meta">
                    <div class="fp-section-title">Informations de la livraison</div>
                    <div class="fp-section-sub">Détails de la commande et du suivi</div>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Compte</span>
                    <span class="info-value">{{ $delivery->compte?->etablissement ?? '—' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Contact</span>
                    <span class="info-value">{{ $delivery->contact ? trim($delivery->contact->prenom.' '.$delivery->contact->nom) : '—' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Délégué</span>
                    <span class="info-value">{{ $delivery->delegate ? trim($delivery->delegate->prenom.' '.$delivery->delegate->nom) : '—' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Article MP</span>
                    <span class="info-value">
                        {{ $delivery->mpProduct?->nom ?? '—' }}
                        @if($delivery->mpProduct?->code_article)
                            <span style="color:var(--text-muted); font-size:.7rem;">({{ $delivery->mpProduct->code_article }})</span>
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Éditeur</span>
                    <span class="info-value">{{ $delivery->mpProduct?->editeur ?? '—' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date de livraison</span>
                    <span class="info-value">{{ $delivery->date_delivery?->format('d/m/Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Année scolaire</span>
                    <span class="info-value">{{ $delivery->anneeScolaire?->libelle ?? '—' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Statut</span>
                    <span class="info-value">
                        @if($delivery->statut === 'livre')
                            <span class="dr-badge bd-green">Livré</span>
                        @else
                            <span class="dr-badge bd-amber">Planifié</span>
                        @endif
                    </span>
                </div>
                @if($delivery->linkedCommercialAction)
                <div class="info-item">
                    <span class="info-label">Action commerciale liée</span>
                    <span class="info-value">
                        <a href="{{ route('actions.show', $delivery->linkedCommercialAction) }}" style="color:var(--blue); font-weight:600; text-decoration:none;">
                            {{ $delivery->linkedCommercialAction->objet }}
                        </a>
                    </span>
                </div>
                @endif
            </div>
        </div>

        <div class="fp-footer">
            <div class="fp-footer-spacer"></div>
            <a href="{{ route('mp-deliveries.index') }}" class="btn-zn btn-zn-ghost">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour
            </a>
        </div>
    </div>

    <p style="margin-top: 1rem; font-size: .75rem; color: var(--text-muted); text-align: center;">
        Après enregistrement, la livraison est figée (pas de modification).
    </p>
</div>
@endsection
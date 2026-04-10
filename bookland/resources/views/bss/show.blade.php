@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ===== FULL CSS FROM ZONES EXAMPLE ===== */
    /* Copy the entire <style> block from the zones example here */
    /* We'll include the essential parts – you can replace with the complete block */
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

    .zn-page { padding: 2rem 2.5rem 3rem; animation: pageIn .4s var(--ease) both; max-width: 1200px; margin: 0 auto; }
    @keyframes pageIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Breadcrumb ──────────────────────────────────────── */
    .zn-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.4rem; }
    .zn-bc a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
    .zn-bc a:hover { color: var(--blue); }
    .zn-bc-sep { color: var(--text-hint); }
    .zn-bc-cur { color: var(--text-secondary); }

    /* ── Header ──────────────────────────────────────────── */
    .zn-header { margin-bottom: 2rem; }
    .zn-header h1 { font-size: 1.65rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.15; margin: 0; }
    .zn-header p { font-size: .83rem; color: var(--text-muted); margin-top: .3rem; }

    /* ── Buttons ─────────────────────────────────────────── */
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
    .btn-zn-primary:hover { background: var(--blue-dark); border-color: var(--blue-dark); color: #fff; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(91,141,238,.4); }
    .btn-zn-ghost { background: var(--bg-card); color: var(--text-secondary); border-color: var(--border); box-shadow: var(--shadow-xs); }
    .btn-zn-ghost:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--border-md); text-decoration: none; }
    .btn-zn-sm { padding: .38rem .72rem; font-size: .75rem; }

    /* ── Card ────────────────────────────────────────────── */
    .zn-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-xl); box-shadow: var(--shadow-sm); overflow: hidden; }
    .zn-card-header { padding: 1.1rem 1.6rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: .55rem; background: linear-gradient(to bottom, #fafbff, #fff); }
    .zn-card-pip { width: 7px; height: 7px; border-radius: 50%; background: var(--amber); box-shadow: 0 0 0 3px rgba(232,160,32,.2); }
    .zn-card-title { font-size: .88rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.01em; }
    .zn-card-body { padding: 1.5rem 1.6rem; }

    /* ── Info grid ───────────────────────────────────────── */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .info-item {
        font-size: 0.84rem;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border);
        padding-bottom: 0.5rem;
    }
    .info-label {
        font-weight: 600;
        color: var(--text-primary);
        margin-right: 0.5rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .info-value {
        color: var(--text-secondary);
        font-weight: 500;
    }
    hr { border: none; border-top: 1px solid var(--border); margin: 1rem 0; }

    /* ── Table ───────────────────────────────────────────── */
    .zn-table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
    .zn-table thead tr { border-bottom: 1px solid var(--border); }
    .zn-table th {
        padding: .85rem 1.2rem; font-size: .69rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .08em;
        color: var(--text-hint); text-align: left;
        background: var(--bg-base); white-space: nowrap;
    }
    .zn-table td { padding: .95rem 1.2rem; font-size: .83rem; color: var(--text-secondary); border-bottom: 1px solid var(--border); vertical-align: middle; }
    .zn-table tbody tr:hover { background: #f8f9fd; }

    /* Badges */
    .dr-badge {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .22rem .65rem; border-radius: 20px;
        font-size: .7rem; font-weight: 600; white-space: nowrap;
    }
    .bd-teal { background: var(--teal-light); color: var(--teal); }
    .bd-blue { background: var(--blue-light); color: var(--blue); }
    .bd-green { background: var(--green-light); color: var(--green); }
    .bd-none { background: var(--bg-subtle); color: var(--text-muted); }

    /* Footer actions */
    .card-actions {
        padding: 1rem 1.6rem 1.6rem;
        display: flex;
        gap: 0.8rem;
        flex-wrap: wrap;
        border-top: 1px solid var(--border);
        margin-top: 1rem;
    }

    @media (max-width: 768px) {
        .zn-page { padding: 1.25rem 1rem 2rem; }
        .info-grid { grid-template-columns: 1fr; }
        .zn-table th, .zn-table td { padding: .75rem .9rem; }
        .card-actions { flex-direction: column-reverse; }
        .btn-zn { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('bss.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('bss.index') }}">BSS</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">{{ $bss->numero }}</span>
    </div>

    {{-- Header --}}
    <div class="zn-header">
        <h1>BSS {{ $bss->numero }}</h1>
        <p>Détail du bon de sortie spécimens</p>
    </div>

    {{-- Main card --}}
    <div class="zn-card">
        <div class="zn-card-header">
            <span class="zn-card-pip"></span>
            <span class="zn-card-title">Informations générales</span>
        </div>
        <div class="zn-card-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Compte</span>
                    <span class="info-value">{{ $bss->compte->etablissement }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Contact</span>
                    <span class="info-value">{{ $bss->contact->prenom }} {{ $bss->contact->nom }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Délégué</span>
                    <span class="info-value">{{ $bss->delegate->prenom }} {{ $bss->delegate->nom }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date création</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($bss->date_bss)->format('d/m/Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date livraison prévue</span>
                    <span class="info-value">{{ $bss->date_livraison_prevue ? \Carbon\Carbon::parse($bss->date_livraison_prevue)->format('d/m/Y') : '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Moyen contact</span>
                    <span class="info-value">{{ $bss->moyen_contact ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Récupéré par</span>
                    <span class="info-value">{{ $bss->recupere_par_nom }} ({{ $bss->recupere_par_type }})</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Statut</span>
                    <span class="info-value">
                        @if($bss->statut == 'soumis') <span class="dr-badge bd-blue">Soumis</span>
                        @elseif($bss->statut == 'valide') <span class="dr-badge bd-teal">Validé</span>
                        @elseif($bss->statut == 'livre') <span class="dr-badge bd-green">Livré</span>
                        @elseif($bss->statut == 'refuse') <span class="dr-badge bd-none">Refusé</span>
                        @else <span class="dr-badge bd-none">{{ $bss->statut }}</span>
                        @endif
                    </span>
                </div>
                @if($bss->motif_refus)
                <div class="info-item">
                    <span class="info-label">Motif refus</span>
                    <span class="info-value">{{ $bss->motif_refus }}</span>
                </div>
                @endif
                @if($bss->feedback)
                <div class="info-item">
                    <span class="info-label">Feedback</span>
                    <span class="info-value">{{ $bss->feedback }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date feedback</span>
                    <span class="info-value">{{ $bss->date_feedback ? \Carbon\Carbon::parse($bss->date_feedback)->format('d/m/Y') : '-' }}</span>
                </div>
                @endif
                @if($bss->controle_document)
                <div class="info-item">
                    <span class="info-label">Contrôle document</span>
                    <span class="info-value">{{ $bss->controle_document }}</span>
                </div>
                @endif
            </div>

            <hr>

            <h3 style="font-size: 0.9rem; font-weight: 600; margin-bottom: 1rem;">Produits</h3>
            <div style="overflow-x: auto;">
                <table class="zn-table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Quantité</th>
                            <th>Source</th>
                            <th>Statut ligne</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bss->lignes as $ligne)
                        <tr>
                            <td><strong>{{ $ligne->product->titre }}</strong></td>
                            <td>{{ $ligne->quantity }}</td>
                            <td>{{ $ligne->source ?? '-' }}</td>
                            <td>{{ $ligne->statut_ligne ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-actions">
            <a href="{{ route('bss.index') }}" class="btn-zn btn-zn-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour
            </a>
            @if(auth()->user()->role === 'delegue' && $bss->delegate_id === auth()->id() && $bss->statut === 'valide' && !$bss->feedback)
                <a href="{{ route('bss.edit', $bss) }}" class="btn-zn btn-zn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                    </svg>
                    Ajouter feedback
                </a>
            @endif
            @if($bss->statut === 'livre' && auth()->user()->role === 'delegue' && $bss->delegate_id === auth()->id())
                <a href="{{ route('retours.create', $bss) }}" class="btn-zn btn-zn-primary">Créer un retour</a>
            @endif



            @if($ligne->statut_ligne === 'livree' && !$ligne->adoption)
                <a href="{{ route('adoptions.convert', $ligne) }}" class="btn-zn btn-zn-sm btn-zn-primary">Convertir</a>
            @elseif($ligne->adoption)
                <span class="dr-badge bd-green">Adopté</span>
            @endif
        </div>
    </div>
</div>
@endsection
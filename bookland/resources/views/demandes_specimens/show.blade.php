@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
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

    .zn-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap; }
    .zn-header-left h1 { font-size: 1.65rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.15; margin: 0; }
    .zn-header-left p { font-size: .83rem; color: var(--text-muted); margin-top: .3rem; }

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
    .btn-zn-danger { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.18); }
    .btn-zn-danger:hover { background: #fddde2; color: var(--rose); }
    .btn-zn-success { background: var(--green-light); color: var(--green); border-color: rgba(40,199,111,.2); }
    .btn-zn-success:hover { background: #d4f5e2; color: var(--green); }
    .btn-zn-info { background: var(--violet-light); color: var(--violet); border-color: rgba(124,111,205,.2); }
    .btn-zn-info:hover { background: #e8e5ff; color: var(--violet); }
    .btn-zn-sm { padding: .38rem .72rem; font-size: .75rem; }
    .btn-zn-warning { background: var(--amber-light); color: var(--amber); border-color: rgba(232,160,32,.2); }
    .btn-zn-warning:hover { background: #ffefd4; color: var(--amber); }

    .zn-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-xl); box-shadow: var(--shadow-sm); overflow: hidden; margin-bottom: 1.5rem; }
    .zn-card-header { padding: 1.1rem 1.6rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: .55rem; background: linear-gradient(to bottom, #fafbff, #fff); }
    .title-pip { width: 7px; height: 7px; border-radius: 50%; background: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); }
    .zn-card-title { font-size: .88rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.01em; }
    .zn-card-body { padding: 1.5rem 1.6rem; }

    .locked-banner {
        background: linear-gradient(135deg, #fef0f2 0%, #fff4e6 100%);
        border: 1px solid rgba(232,80,106,.2);
        border-radius: var(--r-lg);
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex; align-items: center; gap: .75rem;
    }
    .locked-banner-icon { width: 32px; height: 32px; border-radius: var(--r-md); background: rgba(232,80,106,.1); color: var(--rose); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .locked-banner-text { font-size: .82rem; color: var(--text-primary); }
    .locked-banner-text strong { color: var(--rose); }

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

    .dr-badge {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .22rem .65rem; border-radius: 20px;
        font-size: .7rem; font-weight: 600; white-space: nowrap;
    }
    .bd-demande { background: #fff4e6; color: #d97706; }
    .bd-valide { background: var(--green-light); color: var(--green); }
    .bd-decline { background: var(--rose-light); color: var(--rose); }
    .bd-teal { background: var(--teal-light); color: var(--teal); }
    .bd-blue { background: var(--blue-light); color: var(--blue); }

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

    hr { border: none; border-top: 1px solid var(--border); margin: 1rem 0; }

    .card-footer {
        padding: 1.1rem 1.6rem;
        border-top: 1px solid var(--border);
        background: var(--bg-base);
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: .6rem;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .zn-page { padding: 1.25rem 1rem 2rem; }
        .info-grid { grid-template-columns: 1fr; }
        .zn-table th, .zn-table td { padding: .75rem .9rem; font-size: .75rem; }
        .card-footer { flex-direction: column-reverse; }
        .btn-zn { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('demandes-specimens.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('demandes-specimens.index') }}">Demandes spéciales</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">#{{ $demandes_specimen->id }}</span>
    </div>

    {{-- Header --}}
    <div class="zn-header">
        <div class="zn-header-left">
            <h1>Demande spéciale #{{ $demandes_specimen->id }}</h1>
            <p>{{ ucfirst($demandes_specimen->type) }} – {{ $demandes_specimen->date_demande->format('d/m/Y') }}</p>
        </div>
    </div>

    {{-- Locked banner (if validated) --}}
    @if($demandes_specimen->statut === 'valide')
    <div class="locked-banner">
        <div class="locked-banner-icon">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 1c-6.338 0-12 4.226-12 10.007 0 2.05.738 4.063 2.047 5.625.055 3.215 2.902 5.368 9.953 5.368 1.668 0 4.281-.105 5.953-.747.827 1.536 2.456 2.754 4.047 2.754.828 0 1.625-.371 2.25-1.057l1.75-1.944.75 1.944c.55.828 1.522 1.057 2.25 1.057 1.591 0 3.22-1.218 4.047-2.754 1.672.642 4.285.747 5.953.747 7.051 0 9.898-2.153 9.953-5.368 1.309-1.562 2.047-3.575 2.047-5.625 0-5.781-5.662-10.007-12-10.007zm.623 17.769c-3.905.504-8.721.504-12.623 0-.964-1.116-1.5-2.401-1.5-3.769 0-3.314 3.134-6 7-6s7 2.686 7 6c0 1.368-.536 2.653-1.5 3.769z" transform="scale(.8)"/>
            </svg>
        </div>
        <div class="locked-banner-text">
            <strong>Demande validée et verrouillée</strong><br>
            Cette demande a été approuvée. Les informations ne peuvent plus être modifiées.
        </div>
    </div>
    @endif

    {{-- Main card --}}
    <div class="zn-card">
        <div class="zn-card-header">
            <span class="title-pip"></span>
            <span class="zn-card-title">Détails de la demande</span>
        </div>
        <div class="zn-card-body">
            {{-- Info grid --}}
            <div class="info-grid">
                <div class="info-item"><span class="info-label">Type</span>
                    <span class="dr-badge bd-blue">{{ ucfirst($demandes_specimen->type) }}</span>
                </div>
                <div class="info-item"><span class="info-label">Compte</span> {{ $demandes_specimen->compte->etablissement ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Contact</span> {{ optional($demandes_specimen->contact)->prenom ?? '' }} {{ optional($demandes_specimen->contact)->nom ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Délégué</span> {{ $demandes_specimen->delegate->prenom }} {{ $demandes_specimen->delegate->nom }}</div>
                <div class="info-item"><span class="info-label">Ville</span> {{ $demandes_specimen->ville->nom ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Zone</span> {{ $demandes_specimen->zone->name ?? '-' }}</div>
                <div class="info-item">
                    <span class="info-label">Statut</span>
                    <span class="dr-badge bd-{{ $demandes_specimen->statut }}">{{ ucfirst($demandes_specimen->statut) }}</span>
                </div>
                <div class="info-item"><span class="info-label">Date demande</span> {{ $demandes_specimen->date_demande->format('d/m/Y') }}</div>
                @if($demandes_specimen->originalBss)
                <div class="info-item"><span class="info-label">Ancien BSS</span>
                    <a href="{{ route('bss.show', $demandes_specimen->originalBss) }}" class="btn-zn btn-zn-sm btn-zn-info">{{ $demandes_specimen->originalBss->numero }}</a>
                </div>
                @endif
                @if($demandes_specimen->valide_par)
                <div class="info-item"><span class="info-label">Validé par</span> {{ $demandes_specimen->validePar->prenom }} {{ $demandes_specimen->validePar->nom }} le {{ $demandes_specimen->date_validation->format('d/m/Y H:i') }}</div>
                @endif
                @if($demandes_specimen->description)
                <div class="info-item"><span class="info-label">Description</span> {{ $demandes_specimen->description }}</div>
                @endif
            </div>

            <hr>

            <h3 style="font-size: 0.9rem; font-weight: 600; margin-bottom: 1rem;">Produits demandés</h3>
            <div style="overflow-x: auto;">
                <table class="zn-table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>ISBN</th>
                            <th>Quantité</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($demandes_specimen->lignes as $ligne)
                        <tr>
                            <td><strong>{{ $ligne->product->titre }}</strong></td>
                            <td><span style="font-family: var(--font-mono); font-size: .75rem; color: var(--text-muted);">{{ $ligne->product->isbn_13 ?? $ligne->product->isbn_10 ?? '-' }}</span></td>
                            <td><span class="dr-badge bd-teal">{{ $ligne->quantity }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer actions --}}
        <div class="card-footer">
            <a href="{{ route('demandes-specimens.index') }}" class="btn-zn btn-zn-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour
            </a>

            @if($demandes_specimen->statut === 'demande' && auth()->user()->role === 'delegue' && $demandes_specimen->delegue_id === auth()->id())
                <a href="{{ route('demandes-specimens.edit', $demandes_specimen) }}" class="btn-zn btn-zn-warning">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                    </svg>
                    Modifier
                </a>
            @endif

            @if($demandes_specimen->statut === 'demande' && in_array(auth()->user()->role, ['admin','rbo']))
                <form method="POST" action="{{ route('demandes-specimens.validate', $demandes_specimen) }}" style="display:inline;">
                    @csrf
                    <button type="submit" name="action" value="approve" class="btn-zn btn-zn-success" onclick="return confirm('Approuver cette demande et créer un BSS spécial ?')">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Approuver (générer BSS)
                    </button>
                </form>
                <form method="POST" action="{{ route('demandes-specimens.validate', $demandes_specimen) }}" style="display:inline;">
                    @csrf
                    <button type="submit" name="action" value="decline" class="btn-zn btn-zn-danger" onclick="return confirm('Refuser cette demande ?')">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                        Refuser
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
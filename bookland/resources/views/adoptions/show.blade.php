@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
<style>
    /* ===== FULL CSS FROM ZONES EXAMPLE ===== */
    /* Paste the same CSS as in bss/show.blade.php */
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

    .zn-page { padding: 2rem 2.5rem 3rem; animation: pageIn .4s var(--ease) both; max-width: 1000px; margin: 0 auto; }
    @keyframes pageIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .zn-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.4rem; }
    .zn-bc a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
    .zn-bc a:hover { color: var(--blue); }
    .zn-bc-sep { color: var(--text-hint); }
    .zn-bc-cur { color: var(--text-secondary); }

    .zn-header { margin-bottom: 2rem; }
    .zn-header h1 { font-size: 1.65rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.15; margin: 0; }
    .zn-header p { font-size: .83rem; color: var(--text-muted); margin-top: .3rem; }

    .zn-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-xl); box-shadow: var(--shadow-sm); overflow: hidden; }
    .zn-card-header { padding: 1.1rem 1.6rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: .55rem; background: linear-gradient(to bottom, #fafbff, #fff); }
    .zn-card-pip { width: 7px; height: 7px; border-radius: 50%; background: var(--amber); box-shadow: 0 0 0 3px rgba(232,160,32,.2); }
    .zn-card-title { font-size: .88rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.01em; }
    .zn-card-body { padding: 1.75rem 1.6rem; }

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

    .card-footer {
        padding: 1.1rem 1.6rem;
        border-top: 1px solid var(--border);
        background: var(--bg-base);
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: .6rem;
        margin-top: 1.5rem;
    }

    .btn-zn {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .56rem 1.1rem; border-radius: var(--r-sm);
        font-family: var(--font); font-size: .82rem; font-weight: 600;
        cursor: pointer; border: 1px solid transparent;
        transition: all var(--t); text-decoration: none;
        white-space: nowrap; letter-spacing: -.01em; line-height: 1;
    }
    .btn-zn-primary { background: var(--blue); color: #fff; border-color: var(--blue); box-shadow: var(--shadow-blue); }
    .btn-zn-primary:hover { background: var(--blue-dark); color: #fff; transform: translateY(-1px); }
    .btn-zn-ghost { background: var(--bg-card); color: var(--text-secondary); border-color: var(--border); box-shadow: var(--shadow-xs); }
    .btn-zn-ghost:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--border-md); }

    @media (max-width: 768px) {
        .zn-page { padding: 1.25rem 1rem 2rem; }
        .info-grid { grid-template-columns: 1fr; }
        .card-footer { flex-direction: column-reverse; }
        .btn-zn { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('adoptions.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('adoptions.index') }}">Adoptions</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Détail</span>
    </div>

    <div class="zn-header">
        <h1>Adoption #{{ $adoption->id }}</h1>
        <p>Détail de l'adoption</p>
    </div>

    <div class="zn-card">
        <div class="zn-card-header">
            <span class="zn-card-pip"></span>
            <span class="zn-card-title">Informations générales</span>
        </div>
        <div class="zn-card-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Compte</span>
                    <span class="info-value">{{ $adoption->compte->etablissement }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Contact</span>
                    <span class="info-value">{{ $adoption->contact->prenom }} {{ $adoption->contact->nom }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Produit</span>
                    <span class="info-value">{{ $adoption->product->titre }} ({{ $adoption->product->isbn_13 ?? $adoption->product->isbn_10 }})</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Méthode</span>
                    <span class="info-value">{{ $adoption->methode }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Année scolaire</span>
                    <span class="info-value">{{ $adoption->anneeScolaire->libelle }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Quantité</span>
                    <span class="info-value">{{ $adoption->quantity }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date adoption</span>
                    <span class="info-value">{{ $adoption->date_adoption->format('d/m/Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Délégué</span>
                    <span class="info-value">{{ $adoption->delegate->prenom }} {{ $adoption->delegate->nom }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Niveau scolaire</span>
                    <span class="info-value">{{ $adoption->niveau_scolaire ?? '-' }}</span>
                </div>
                @if($adoption->bssLigne)
                <div class="info-item">
                    <span class="info-label">BSS source</span>
                    <span class="info-value">
                        <a href="{{ route('bss.show', $adoption->bssLigne->bss) }}" class="text-decoration-none">
                            {{ $adoption->bssLigne->bss->numero }}
                        </a>
                    </span>
                </div>
                @endif
            </div>
        </div>

        <div class="card-footer">
            <a href="{{ route('adoptions.index') }}" class="btn-zn btn-zn-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour
            </a>
            @if(auth()->user()->role === 'delegue' && $adoption->delegate_id === auth()->id())
                <a href="{{ route('adoptions.edit', $adoption) }}" class="btn-zn btn-zn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                    </svg>
                    Modifier
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
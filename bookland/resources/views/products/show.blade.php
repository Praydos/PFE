@extends('layouts.app')

@section('content')

@push('styles')
<style>
/* ══════════════════════════════════════
   PRODUCT SHOW  –  inherits CRM tokens
══════════════════════════════════════ */

.ps {
    padding: 2rem 2.5rem 3rem;
}

/* ── Breadcrumb ── */
.ps-breadcrumb {
    display: flex;
    align-items: center;
    gap: .4rem;
    font-size: .75rem;
    color: var(--text-muted);
    margin-bottom: 1.5rem;
}
.ps-breadcrumb a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
.ps-breadcrumb a:hover { color: var(--blue); }
.ps-breadcrumb-sep { opacity: .4; }

/* ── Hero section ── */
.ps-hero {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 2rem;
    align-items: start;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--r-xl);
    padding: 2rem 2.25rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow-xs);
}

.ps-hero-meta {
    display: flex;
    align-items: center;
    gap: .55rem;
    flex-wrap: wrap;
    margin-bottom: .9rem;
}

.ps-type-label {
    font-size: .72rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: .07em;
}

/* ── Badges ── */
.ps-badge {
    display: inline-flex;
    align-items: center;
    font-size: .7rem;
    font-weight: 700;
    padding: .2rem .7rem;
    border-radius: 20px;
    letter-spacing: .03em;
}
.ps-badge-blue   { background: var(--blue-light);   color: var(--blue);   border: 1px solid var(--blue-mid); }
.ps-badge-teal   { background: var(--teal-light);   color: var(--teal); }
.ps-badge-violet { background: var(--violet-light); color: var(--violet); }
.ps-badge-amber  { background: var(--amber-light);  color: var(--amber); }

.ps-hero h1 {
    font-size: 1.65rem;
    font-weight: 700;
    color: var(--text-primary);
    letter-spacing: -.025em;
    line-height: 1.25;
    margin-bottom: .35rem;
}
.ps-hero-subtitle {
    font-size: .9rem;
    color: var(--text-muted);
    font-style: italic;
    margin-bottom: 1.25rem;
}

/* ── Key info chips ── */
.ps-hero-chips {
    display: flex;
    align-items: center;
    gap: .55rem;
    flex-wrap: wrap;
}
.ps-chip {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .32rem .8rem;
    background: var(--bg-subtle);
    border: 1px solid var(--border);
    border-radius: var(--r-sm);
    font-size: .78rem;
    font-weight: 500;
    color: var(--text-secondary);
}
.ps-chip svg { color: var(--text-muted); flex-shrink: 0; }
.ps-chip-price {
    background: var(--green-light);
    border-color: rgba(40,199,111,.2);
    color: #14532d;
    font-weight: 700;
}
.ps-chip-price svg { color: #28c76f; }

/* ── Cover / placeholder ── */
.ps-cover-area { flex-shrink: 0; }
.ps-cover-img {
    width: 130px;
    border-radius: var(--r-md);
    box-shadow: var(--shadow-md);
    display: block;
}
.ps-cover-placeholder {
    width: 130px;
    height: 176px;
    border-radius: var(--r-md);
    background: var(--bg-subtle);
    border: 1px dashed var(--border-md);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    color: var(--text-hint);
}
.ps-cover-placeholder span {
    font-size: .7rem;
    font-weight: 500;
    text-align: center;
}

/* ── Info grid ── */
.ps-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}
@media (max-width: 860px) { .ps-grid { grid-template-columns: 1fr; } }

.ps-section {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    overflow: hidden;
    box-shadow: var(--shadow-xs);
}
.ps-section.full { grid-column: 1 / -1; }

.ps-section-head {
    display: flex;
    align-items: center;
    gap: .6rem;
    padding: .85rem 1.35rem;
    border-bottom: 1px solid var(--border);
    background: var(--bg-subtle);
}
.ps-section-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    flex-shrink: 0;
}
.dot-blue   { background: var(--blue); }
.dot-teal   { background: var(--teal); }
.dot-violet { background: var(--violet); }
.dot-amber  { background: var(--amber); }
.dot-green  { background: var(--green); }

.ps-section-title {
    font-size: .73rem;
    font-weight: 700;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: var(--text-secondary);
}

.ps-section-body {
    padding: 0 1.35rem;
}

/* ── Field rows ── */
.ps-field {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: .72rem 0;
    border-bottom: 1px solid var(--border);
}
.ps-field:last-child { border-bottom: none; }

.ps-field-label {
    font-size: .77rem;
    color: var(--text-muted);
    font-weight: 500;
    white-space: nowrap;
    flex-shrink: 0;
    min-width: 110px;
}
.ps-field-value {
    font-size: .82rem;
    color: var(--text-primary);
    font-weight: 500;
    text-align: right;
    word-break: break-word;
}
.ps-field-value.mono {
    font-family: var(--font-mono);
    font-size: .78rem;
    letter-spacing: .03em;
    color: var(--text-secondary);
}
.ps-field-empty {
    color: var(--text-hint);
    font-weight: 400;
}

/* ── Description body ── */
.ps-desc-body {
    padding: 1.1rem 1.35rem;
    font-size: .85rem;
    color: var(--text-secondary);
    line-height: 1.7;
}

/* ── Footer actions ── */
.ps-footer {
    display: flex;
    align-items: center;
    gap: .75rem;
    flex-wrap: wrap;
}

.btn-ps-back {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    height: 40px;
    padding: 0 1.2rem;
    background: var(--bg-subtle);
    border: 1px solid var(--border-md);
    border-radius: var(--r-md);
    font-family: var(--font);
    font-size: .82rem;
    font-weight: 600;
    color: var(--text-secondary);
    text-decoration: none;
    transition: all var(--t);
    cursor: pointer;
}
.btn-ps-back:hover {
    background: var(--bg-hover);
    border-color: var(--blue-mid);
    color: var(--blue);
    text-decoration: none;
}

.btn-ps-edit {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    height: 40px;
    padding: 0 1.35rem;
    background: var(--blue);
    color: #fff;
    font-family: var(--font);
    font-size: .82rem;
    font-weight: 600;
    border-radius: var(--r-md);
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: background var(--t), transform var(--t), box-shadow var(--t);
    box-shadow: 0 2px 8px rgba(91,141,238,.3);
}
.btn-ps-edit:hover {
    background: var(--blue-dark);
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(91,141,238,.4);
    color: #fff;
    text-decoration: none;
}
</style>
@endpush

<div class="ps">

    {{-- Breadcrumb --}}
    <nav class="ps-breadcrumb">
        <a href="{{ route('products.index') }}">Produits</a>
        <span class="ps-breadcrumb-sep">›</span>
        <span>{{ Str::limit($product->titre, 60) }}</span>
    </nav>

    {{-- Hero --}}
    <div class="ps-hero">
        <div>
            <div class="ps-hero-meta">
                <span class="ps-badge {{ $product->source == 'bookland' ? 'ps-badge-blue' : 'ps-badge-teal' }}">
                    {{ ucfirst(str_replace('_', ' ', $product->source)) }}
                </span>
                @if($product->type)
                    <span class="ps-badge ps-badge-violet">{{ $product->type }}</span>
                @endif
                @if($product->niveau)
                    <span class="ps-badge ps-badge-amber">{{ $product->niveau }}</span>
                @endif
            </div>

            <h1>{{ $product->titre }}</h1>

            @if($product->sous_titre)
                <p class="ps-hero-subtitle">{{ $product->sous_titre }}</p>
            @endif

            <div class="ps-hero-chips">
                @if($product->prix)
                <span class="ps-chip ps-chip-price">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 1 0 0 7h5a3.5 3.5 0 1 1 0 7H6"/></svg>
                    {{ number_format($product->prix, 2) }} €
                </span>
                @endif
                @if($product->auteur)
                <span class="ps-chip">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    {{ $product->auteur }}
                </span>
                @endif
                @if($product->editeur)
                <span class="ps-chip">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
                    {{ $product->editeur }}
                </span>
                @endif
                @if($product->langue)
                <span class="ps-chip">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    {{ $product->langue }}
                </span>
                @endif
                @if($product->date_parution)
                <span class="ps-chip">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    {{ \Carbon\Carbon::parse($product->date_parution)->format('d/m/Y') }}
                </span>
                @endif
                @if($product->nbr_pages)
                <span class="ps-chip">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    {{ $product->nbr_pages }} pages
                </span>
                @endif
            </div>
        </div>

        <div class="ps-cover-area">
            @if($product->image)
                <img src="{{ $product->image }}" alt="{{ $product->titre }}" class="ps-cover-img">
            @else
                <div class="ps-cover-placeholder">
                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    <span>Pas d'image</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Info sections --}}
    <div class="ps-grid">

        {{-- Identification --}}
        <div class="ps-section">
            <div class="ps-section-head">
                <span class="ps-section-dot dot-blue"></span>
                <span class="ps-section-title">Identification</span>
            </div>
            <div class="ps-section-body">
                <div class="ps-field">
                    <span class="ps-field-label">ISBN‑13</span>
                    <span class="ps-field-value mono {{ !$product->isbn_13 ? 'ps-field-empty' : '' }}">{{ $product->isbn_13 ?? '—' }}</span>
                </div>
                <div class="ps-field">
                    <span class="ps-field-label">ISBN‑10</span>
                    <span class="ps-field-value mono {{ !$product->isbn_10 ? 'ps-field-empty' : '' }}">{{ $product->isbn_10 ?? '—' }}</span>
                </div>
                <div class="ps-field">
                    <span class="ps-field-label">Réf. interne</span>
                    <span class="ps-field-value mono {{ !$product->reference_interne ? 'ps-field-empty' : '' }}">{{ $product->reference_interne ?? '—' }}</span>
                </div>
                <div class="ps-field">
                    <span class="ps-field-label">Édition</span>
                    <span class="ps-field-value {{ !$product->edition ? 'ps-field-empty' : '' }}">{{ $product->edition ?? '—' }}</span>
                </div>
                <div class="ps-field">
                    <span class="ps-field-label">Support</span>
                    <span class="ps-field-value {{ !$product->support ? 'ps-field-empty' : '' }}">{{ $product->support ?? '—' }}</span>
                </div>
            </div>
        </div>

        {{-- Classification --}}
        <div class="ps-section">
            <div class="ps-section-head">
                <span class="ps-section-dot dot-violet"></span>
                <span class="ps-section-title">Classification</span>
            </div>
            <div class="ps-section-body">
                <div class="ps-field">
                    <span class="ps-field-label">Rayon</span>
                    <span class="ps-field-value {{ !$product->rayon ? 'ps-field-empty' : '' }}">{{ $product->rayon ?? '—' }}</span>
                </div>
                <div class="ps-field">
                    <span class="ps-field-label">Sous‑rayon</span>
                    <span class="ps-field-value {{ !$product->sous_rayon ? 'ps-field-empty' : '' }}">{{ $product->sous_rayon ?? '—' }}</span>
                </div>
                <div class="ps-field">
                    <span class="ps-field-label">Catégorie</span>
                    <span class="ps-field-value {{ !$product->categorie ? 'ps-field-empty' : '' }}">{{ $product->categorie ?? '—' }}</span>
                </div>
                <div class="ps-field">
                    <span class="ps-field-label">Sous-catégorie</span>
                    <span class="ps-field-value {{ !$product->sous_categorie ? 'ps-field-empty' : '' }}">{{ $product->sous_categorie ?? '—' }}</span>
                </div>
                <div class="ps-field">
                    <span class="ps-field-label">Niveau</span>
                    <span class="ps-field-value {{ !$product->niveau ? 'ps-field-empty' : '' }}">{{ $product->niveau ?? '—' }}</span>
                </div>
            </div>
        </div>

        {{-- Edition & Publication --}}
        <div class="ps-section">
            <div class="ps-section-head">
                <span class="ps-section-dot dot-teal"></span>
                <span class="ps-section-title">Édition &amp; Publication</span>
            </div>
            <div class="ps-section-body">
                <div class="ps-field">
                    <span class="ps-field-label">Éditeur</span>
                    <span class="ps-field-value {{ !$product->editeur ? 'ps-field-empty' : '' }}">{{ $product->editeur ?? '—' }}</span>
                </div>
                <div class="ps-field">
                    <span class="ps-field-label">Collection</span>
                    <span class="ps-field-value {{ !$product->collection ? 'ps-field-empty' : '' }}">{{ $product->collection ?? '—' }}</span>
                </div>
                <div class="ps-field">
                    <span class="ps-field-label">Auteur(s)</span>
                    <span class="ps-field-value {{ !$product->auteur ? 'ps-field-empty' : '' }}">{{ $product->auteur ?? '—' }}</span>
                </div>
                <div class="ps-field">
                    <span class="ps-field-label">Date de parution</span>
                    <span class="ps-field-value {{ !$product->date_parution ? 'ps-field-empty' : '' }}">
                        {{ $product->date_parution ? \Carbon\Carbon::parse($product->date_parution)->format('d/m/Y') : '—' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Détails physiques --}}
        <div class="ps-section">
            <div class="ps-section-head">
                <span class="ps-section-dot dot-amber"></span>
                <span class="ps-section-title">Détails physiques</span>
            </div>
            <div class="ps-section-body">
                <div class="ps-field">
                    <span class="ps-field-label">Nombre de pages</span>
                    <span class="ps-field-value {{ !$product->nbr_pages ? 'ps-field-empty' : '' }}">{{ $product->nbr_pages ?: '—' }}</span>
                </div>
                <div class="ps-field">
                    <span class="ps-field-label">Langue</span>
                    <span class="ps-field-value {{ !$product->langue ? 'ps-field-empty' : '' }}">{{ $product->langue ?? '—' }}</span>
                </div>
                <div class="ps-field">
                    <span class="ps-field-label">Prix</span>
                    <span class="ps-field-value {{ !$product->prix ? 'ps-field-empty' : '' }}">
                        {{ $product->prix ? number_format($product->prix, 2).' €' : '—' }}
                    </span>
                </div>
                <div class="ps-field">
                    <span class="ps-field-label">Source</span>
                    <span class="ps-field-value">
                        <span class="ps-badge {{ $product->source == 'bookland' ? 'ps-badge-blue' : 'ps-badge-teal' }}">
                            {{ ucfirst(str_replace('_', ' ', $product->source)) }}
                        </span>
                    </span>
                </div>
            </div>
        </div>

        {{-- Description (full width) --}}
        @if($product->description)
        <div class="ps-section full">
            <div class="ps-section-head">
                <span class="ps-section-dot dot-green"></span>
                <span class="ps-section-title">Description</span>
            </div>
            <div class="ps-desc-body">{{ $product->description }}</div>
        </div>
        @endif

    </div>

    {{-- Footer actions --}}
    <div class="ps-footer">
        <a href="{{ route('products.index') }}" class="btn-ps-back">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Retour aux produits
        </a>
        @can('update', $product)
            <a href="{{ route('products.edit', $product) }}" class="btn-ps-edit">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                Modifier le produit
            </a>
        @endcan
    </div>

</div>

@endsection
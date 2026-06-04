@extends('layouts.app')

@push('styles')
{{-- Include the complete tch CSS (base + forms + tables) --}}
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

/* ── Main card ── */
.tch-card             { background: #fff; border: 1px solid #e9eaee; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.05); }

/* ── Card header ── */
.tch-card-hd          { padding: 1.1rem 1.5rem; border-bottom: 1px solid #f0f1f5; display: flex; align-items: center; justify-content: space-between; }
.tch-card-title       { display: flex; align-items: center; gap: .55rem; font-family: 'Sora', sans-serif; font-weight: 600; font-size: .88rem; color: #111827; }
.tch-pip              { width: 6px; height: 6px; border-radius: 50%; background: #185FA5; flex-shrink: 0; }

/* ── Card body (form container) ── */
.tch-card-body {
    padding: 1.5rem;
}

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

.frm-input {
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

.frm-input:focus {
    outline: none;
    border-color: #185FA5;
    box-shadow: 0 0 0 3px rgba(24, 95, 165, 0.1);
}

.frm-input.is-invalid {
    border-color: #dc2626;
    background-color: #fef2f2;
}

textarea.frm-input {
    resize: vertical;
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

/* Responsive table wrapper */
.table-responsive {
    overflow-x: auto;
    margin: 1rem 0;
}

/* ── Card footer ── */
.tch-card-ft          { padding: .9rem 1.5rem; border-top: 1px solid #f0f1f5; background: #fafbfc; display: flex; justify-content: flex-end; gap: .5rem; align-items: center; flex-wrap: wrap; }

/* ── SVG icons ── */
.tch-icon             { display: inline-block; vertical-align: middle; flex-shrink: 0; }

/* ── Responsive adjustments ── */
@media (max-width: 480px) {
    .tch-pg { padding: 1rem; }
    .tch-card-body { padding: 1rem; }
    .tch-table th,
    .tch-table td { padding: 0.5rem; }
}

/* ── Focus styles for accessibility ── */
.btn-tch:focus-visible,
.tch-bc a:focus-visible,
.frm-input:focus-visible,
.tch-table input:focus-visible {
    outline: 2px solid #185FA5;
    outline-offset: 2px;
}
</style>
@endpush

@section('content')
<div class="tch-pg">

    {{-- Breadcrumb --}}
    <nav class="tch-bc" aria-label="Fil d'Ariane">
        <a href="{{ route('bss.index') }}">
            <svg class="tch-icon" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="tch-bc-sep">›</span>
        <a href="{{ route('bss.show', $bss) }}">BSS {{ $bss->numero }}</a>
        <span class="tch-bc-sep">›</span>
        <span class="tch-bc-cur">Bon de retour</span>
    </nav>

    {{-- Page header --}}
    <div class="tch-hdr">
        <div class="tch-hdr-l">
            <h1>Bon de retour – BSS {{ $bss->numero }}</h1>
            <p>Sélectionnez les articles à retourner</p>
        </div>
    </div>

    {{-- Form card --}}
    <div class="tch-card">
        <div class="tch-card-hd">
            <div class="tch-card-title">
                <span class="tch-pip"></span>
                Informations du retour
            </div>
        </div>

        <form method="POST" action="{{ route('retours.store', $bss) }}" id="retourForm">
            @csrf
            <input type="hidden" name="numero" value="{{ $numero }}">

            <div class="tch-card-body">

                {{-- Date de retour --}}
                <div class="frm-group">
                    <label class="frm-label" for="date_retour">Date de retour <span class="req">*</span></label>
                    <input type="date" name="date_retour" id="date_retour" class="frm-input" value="{{ now()->format('Y-m-d') }}" required>
                </div>

                {{-- Motif --}}
                <div class="frm-group">
                    <label class="frm-label" for="motif">Motif du retour</label>
                    <textarea name="motif" id="motif" class="frm-input" rows="3" placeholder="Expliquez la raison du retour...">{{ old('motif') }}</textarea>
                </div>

                <hr style="margin: 1.5rem 0; border: none; border-top: 1px solid #e5e7eb;">

                <h3 style="font-family: 'Sora', sans-serif; font-size: 0.9rem; font-weight: 600; margin-bottom: 1rem; color: #111827;">Articles à retourner</h3>

                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="tch-table">
                        <thead>
                            <tr>
                                <th>Sélection</th>
                                <th>Produit</th>
                                <th>Quantité livrée</th>
                                <th>Quantité à retourner</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($returnableLines as $index => $ligne)
                            <tr>
                                <td>
                                    <input type="checkbox" name="lignes[{{ $index }}][selected]" class="line-checkbox" data-index="{{ $index }}">
                                    <input type="hidden" name="lignes[{{ $index }}][id]" value="{{ $ligne->id }}">
                                 </td>
                                <td>{{ $ligne->product->titre }} ({{ $ligne->product->isbn_13 ?? $ligne->product->isbn_10 }})</td>
                                <td>{{ $ligne->quantity }}</td>
                                <td>
                                    <input type="number" name="lignes[{{ $index }}][quantite]" class="frm-input quantite-input" data-max="{{ $ligne->quantity }}" style="width:100px;" value="1" disabled>
                                 </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tch-card-ft">
                <a href="{{ route('bss.show', $bss) }}" class="btn-tch btn-tch-ghost">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="btn-tch btn-tch-primary" onclick="confirmRetour(event)">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Créer le bon de retour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmRetour(event) {
    if (!confirm('Voulez-vous exécuter le retour des articles sélectionnés ? Cette action est irréversible.')) {
        event.preventDefault();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.line-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const index = this.dataset.index;
            const quantiteInput = document.querySelector(`input[name="lignes[${index}][quantite]"]`);
            quantiteInput.disabled = !this.checked;
            if (!this.checked) {
                quantiteInput.value = '';
            } else {
                quantiteInput.value = 1;
            }
        });
    });

    document.querySelectorAll('.quantite-input').forEach(input => {
        input.addEventListener('change', function() {
            let max = parseInt(this.dataset.max);
            let val = parseInt(this.value);
            if (isNaN(val)) val = 0;
            if (val > max) {
                alert(`La quantité retournée ne peut pas dépasser ${max}.`);
                this.value = max;
            }
            if (val < 0) this.value = 0;
        });
    });
});
</script>
@endpush
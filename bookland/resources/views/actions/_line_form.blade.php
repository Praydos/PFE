@php
    $lineIndex = $lineIndex ?? 0;
    $line = $line ?? null;
    $categories = $categories ?? [];
    $lineProducts = $line ? $line->products->pluck('id')->toArray() : [];
    $lineExamens = $line ? $line->examens->pluck('id')->toArray() : [];
@endphp

<div class="line-item card p-3 mb-3" data-line-index="{{ $lineIndex }}">
    <div class="row g-2">
        <div class="col-md-3">
            <label>Catégorie *</label>
            <select name="lines[{{ $lineIndex }}][categorie]" class="form-select categorie-select" required>
                <option value="">-- Sélectionnez --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ old("lines.{$lineIndex}.categorie", $line->categorie ?? '') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label>Type d'action *</label>
            <select name="lines[{{ $lineIndex }}][action_type]" class="form-select action-type-select" required>
                <option value="">-- D'abord choisir catégorie --</option>
            </select>
        </div>
        <div class="col-md-2">
            <label>Moyen</label>
            <select name="lines[{{ $lineIndex }}][moyen]" class="form-select moyen-select">
                <option value="">-- Sélectionnez --</option>
            </select>
        </div>
        <div class="col-md-4">
            <label>Description</label>
            <input type="text" name="lines[{{ $lineIndex }}][description]" class="form-control" value="{{ old("lines.{$lineIndex}.description", $line->description ?? '') }}">
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-4">
            <label>Contacts</label>
            <select name="lines[{{ $lineIndex }}][contact_ids][]" class="form-select" multiple size="3">
                {{-- Will be populated via AJAX based on compte selection --}}
            </select>
        </div>
       

        <div class="row mt-2">
            <div class="col-md-4">
                <label>Produits</label>
                <select name="lines[{{ $lineIndex }}][product_ids][]" class="form-select" multiple size="3">
                    @foreach($products ?? [] as $p)
                        <option value="{{ $p->id }}" {{ in_array($p->id, old("lines.{$lineIndex}.product_ids", $lineProducts)) ? 'selected' : '' }}>{{ $p->titre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Examens</label>
                <select name="lines[{{ $lineIndex }}][examen_ids][]" class="form-select" multiple size="3">
                    @foreach($examens ?? [] as $e)
                        <option value="{{ $e->id }}" {{ in_array($e->id, old("lines.{$lineIndex}.examen_ids", $lineExamens)) ? 'selected' : '' }}>{{ $e->titre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>BSS (spécimen)</label>
                <select name="lines[{{ $lineIndex }}][bss_id]" class="form-select bss-select" style="display:none;">
                    <option value="">-- Sélectionnez un BSS --</option>
                    @foreach($bssList ?? [] as $b)
                        <option value="{{ $b->id }}" {{ old("lines.{$lineIndex}.bss_id", $line->bss_id ?? '') == $b->id ? 'selected' : '' }}>{{ $b->numero }} - {{ $b->compte->etablissement }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Bon de retour</label>
                <select name="lines[{{ $lineIndex }}][retour_id]" class="form-select retour-select" style="display:none;">
                    <option value="">-- Sélectionnez un retour --</option>
                    @foreach($retoursList ?? [] as $r)
                        <option value="{{ $r->id }}" {{ old("lines.{$lineIndex}.retour_id", $line->retour_id ?? '') == $r->id ? 'selected' : '' }}>{{ $r->numero }} - {{ $r->bss->compte->etablissement }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <button type="button" class="btn-danger btn-sm mt-2 remove-line">Supprimer cette ligne</button>
</div>
@php
    $lineIndex = $lineIndex ?? 0;
    $line = $line ?? null;
    $categories = $categories ?? [];
    $lineProducts = $line ? $line->products->pluck('id')->toArray() : [];
    $lineExamens = $line ? $line->examens->pluck('id')->toArray() : [];
@endphp

<div class="line-item card p-3 mb-3" data-line-index="{{ $lineIndex }}" style="background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-lg);">
    {{-- Catégorie, Type d'action, Moyen, Description --}}
    <div class="form-row" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem;">
        <div class="frm-group" style="flex: 2;">
            <label class="frm-label">Catégorie <span class="req">*</span></label>
            <div class="frm-select-wrap">
                <select name="lines[{{ $lineIndex }}][categorie]" class="frm-select categorie-select" required>
                    <option value="">— Sélectionnez —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ old("lines.{$lineIndex}.categorie", $line->categorie ?? '') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="frm-group" style="flex: 2;">
            <label class="frm-label">Type d'action <span class="req">*</span></label>
            <div class="frm-select-wrap">
                <select name="lines[{{ $lineIndex }}][action_type]" class="frm-select action-type-select" required>
                    <option value="">— D'abord choisir catégorie —</option>
                </select>
            </div>
        </div>
        <div class="frm-group" style="flex: 1.5;">
            <label class="frm-label">Moyen</label>
            <div class="frm-select-wrap">
                <select name="lines[{{ $lineIndex }}][moyen]" class="frm-select moyen-select">
                    <option value="">— Sélectionnez —</option>
                </select>
            </div>
        </div>
        <div class="frm-group" style="flex: 3;">
            <label class="frm-label">Description</label>
            <input type="text" name="lines[{{ $lineIndex }}][description]" class="frm-input" value="{{ old("lines.{$lineIndex}.description", $line->description ?? '') }}">
        </div>
    </div>

    {{-- Contacts, Produits, Examens --}}
    <div class="form-row" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem;">
        <div class="frm-group" style="flex: 1;">
            <label class="frm-label">Contacts</label>
            <select name="lines[{{ $lineIndex }}][contact_ids][]" class="frm-select" multiple size="3" style="height: auto;">
                {{-- Populated via AJAX based on compte --}}
            </select>
        </div>
        <div class="frm-group" style="flex: 1;">
            <label class="frm-label">Produits</label>
            <select name="lines[{{ $lineIndex }}][product_ids][]" class="frm-select" multiple size="3" style="height: auto;">
                @foreach($products ?? [] as $p)
                    <option value="{{ $p->id }}" {{ in_array($p->id, old("lines.{$lineIndex}.product_ids", $lineProducts)) ? 'selected' : '' }}>{{ $p->titre }}</option>
                @endforeach
            </select>
        </div>
        <div class="frm-group" style="flex: 1;">
            <label class="frm-label">Examens</label>
            <select name="lines[{{ $lineIndex }}][examen_ids][]" class="frm-select" multiple size="3" style="height: auto;">
                @foreach($examens ?? [] as $e)
                    <option value="{{ $e->id }}" {{ in_array($e->id, old("lines.{$lineIndex}.examen_ids", $lineExamens)) ? 'selected' : '' }}>{{ $e->titre }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- BSS and Retour (conditional) --}}
    <div class="form-row" style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <div class="frm-group" style="flex: 1;" id="bss-group-{{ $lineIndex }}" style="display: none;">
            <label class="frm-label">BSS (spécimen)</label>
            <div class="frm-select-wrap">
                <select name="lines[{{ $lineIndex }}][bss_id]" class="frm-select bss-select">
                    <option value="">— Sélectionnez un BSS —</option>
                    @foreach($bssList ?? [] as $b)
                        <option value="{{ $b->id }}" {{ old("lines.{$lineIndex}.bss_id", $line->bss_id ?? '') == $b->id ? 'selected' : '' }}>{{ $b->numero }} - {{ $b->compte->etablissement }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="frm-group" style="flex: 1;" id="retour-group-{{ $lineIndex }}" style="display: none;">
            <label class="frm-label">Bon de retour</label>
            <div class="frm-select-wrap">
                <select name="lines[{{ $lineIndex }}][retour_id]" class="frm-select retour-select">
                    <option value="">— Sélectionnez un retour —</option>
                    @foreach($retoursList ?? [] as $r)
                        <option value="{{ $r->id }}" {{ old("lines.{$lineIndex}.retour_id", $line->retour_id ?? '') == $r->id ? 'selected' : '' }}>{{ $r->numero }} - {{ $r->bss->compte->etablissement }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="mt-2">
        <button type="button" class="btn-zn btn-zn-danger btn-zn-sm remove-line">Supprimer cette ligne</button>
    </div>
</div>
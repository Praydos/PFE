@php
    $lineIndex   = $lineIndex ?? 0;
    $line        = $line ?? null;
    $categories  = $categories ?? [];
    $lineProducts= $line ? $line->products->pluck('id')->toArray() : [];
    $lineExamens = $line ? $line->examens->pluck('id')->toArray() : [];
@endphp

{{-- ── Line header ─────────────────────────────────── --}}
<div class="line-item-hd">
    <div class="line-item-num">
        <span class="line-num-badge">{{ $lineIndex + 1 }}</span>
        <span class="line-num-label">Ligne {{ $lineIndex + 1 }}</span>
    </div>
    <button type="button" class="btn-ac btn-ac-sm btn-ac-danger remove-line" title="Supprimer cette ligne">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
        Supprimer
    </button>
</div>

<div class="line-item-body">

    {{-- ── Row 1 : Catégorie / Type / Moyen / Description ── --}}
    <div class="ac-row ac-row-2">

        <div class="ac-group">
            <label class="ac-label">Catégorie <span class="req">*</span></label>
            <div class="ac-sel-wrap">
                <select name="lines[{{ $lineIndex }}][categorie]"
                        class="ac-select categorie-select" required>
                    <option value="">— Sélectionnez —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}"
                            {{ old("lines.{$lineIndex}.categorie", $line->categorie ?? '') == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="ac-group">
            <label class="ac-label">Type d'action <span class="req">*</span></label>
            <div class="ac-sel-wrap">
                <select name="lines[{{ $lineIndex }}][action_type]"
                        class="ac-select action-type-select" required>
                    <option value="">— Choisir catégorie d'abord —</option>
                </select>
            </div>
        </div>

    </div>

    <div class="ac-row ac-row-2">

        <div class="ac-group">
            <label class="ac-label">Moyen <span class="opt">(optionnel)</span></label>
            <div class="ac-sel-wrap">
                <select name="lines[{{ $lineIndex }}][moyen]"
                        class="ac-select moyen-select">
                    <option value="">— Sélectionnez —</option>
                </select>
            </div>
        </div>

        <div class="ac-group">
            <label class="ac-label">Description <span class="opt">(optionnel)</span></label>
            <input type="text"
                   name="lines[{{ $lineIndex }}][description]"
                   class="ac-input"
                   value="{{ old("lines.{$lineIndex}.description", $line->description ?? '') }}"
                   placeholder="Précisions sur cette ligne…">
        </div>

    </div>

    {{-- ── Row 2 : Contacts / Produits / Examens ────────── --}}
    <div style="border-top:1px solid var(--border);padding-top:.9rem;">
        <div style="font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--t4);margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;">
            Associations
            <span style="flex:1;height:1px;background:var(--border);display:block;"></span>
        </div>
        <div class="ac-row ac-row-3">

            {{-- Contacts --}}
            <div class="ac-group">
                <label class="ac-label">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Contacts
                </label>
                <select name="lines[{{ $lineIndex }}][contact_ids][]"
                        class="ac-multiselect contact-multiselect"
                        multiple size="4">
                    {{-- Populated via AJAX --}}
                </select>
                <span class="ac-hint">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Chargé depuis le compte sélectionné
                </span>
            </div>

            {{-- Produits --}}
            <div class="ac-group">
                <label class="ac-label">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    Produits
                </label>
                <select name="lines[{{ $lineIndex }}][product_ids][]"
                        class="ac-multiselect"
                        multiple size="4">
                    @foreach($products ?? [] as $p)
                        <option value="{{ $p->id }}"
                            {{ in_array($p->id, old("lines.{$lineIndex}.product_ids", $lineProducts)) ? 'selected' : '' }}>
                            {{ $p->titre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Examens --}}
            <div class="ac-group">
                <label class="ac-label">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Examens
                </label>
                <select name="lines[{{ $lineIndex }}][examen_ids][]"
                        class="ac-multiselect"
                        multiple size="4">
                    @foreach($examens ?? [] as $e)
                        <option value="{{ $e->id }}"
                            {{ in_array($e->id, old("lines.{$lineIndex}.examen_ids", $lineExamens)) ? 'selected' : '' }}>
                            {{ $e->titre }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>
    </div>

    {{-- ── Conditional : BSS & Retour ───────────────────── --}}
    <div class="ac-row ac-row-2">

        <div class="ac-group cond-field" id="bss-group-{{ $lineIndex }}">
            <label class="ac-label">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                BSS (spécimen)
            </label>
            <div class="ac-sel-wrap">
                <select name="lines[{{ $lineIndex }}][bss_id]"
                        class="ac-select bss-select">
                    <option value="">— Sélectionnez un BSS —</option>
                    @foreach($bssList ?? [] as $b)
                        <option value="{{ $b->id }}"
                            {{ old("lines.{$lineIndex}.bss_id", $line->bss_id ?? '') == $b->id ? 'selected' : '' }}>
                            {{ $b->numero }} – {{ $b->compte->etablissement }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="ac-group cond-field" id="retour-group-{{ $lineIndex }}">
            <label class="ac-label">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.51"/></svg>
                Bon de retour
            </label>
            <div class="ac-sel-wrap">
                <select name="lines[{{ $lineIndex }}][retour_id]"
                        class="ac-select retour-select">
                    <option value="">— Sélectionnez un retour —</option>
                    @foreach($retoursList ?? [] as $r)
                        <option value="{{ $r->id }}"
                            {{ old("lines.{$lineIndex}.retour_id", $line->retour_id ?? '') == $r->id ? 'selected' : '' }}>
                            {{ $r->numero }} – {{ $r->bss->compte->etablissement }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

</div>
@php
    $isEdit = isset($adoption) && $adoption;
    $defaultCompteId = $defaultCompteId ?? ($isEdit ? $adoption->compte_id : old('compte_id'));
    $defaultProductId = $defaultProductId ?? ($isEdit ? $adoption->product_id : old('product_id'));
    $defaultYearId = $defaultYearId ?? ($isEdit ? $adoption->annee_scolaire_id : old('annee_scolaire_id'));
    $defaultQuantity = $defaultQuantity ?? ($isEdit ? $adoption->quantity : old('quantity'));
    $defaultDate = $defaultDate ?? ($isEdit && $adoption->date_adoption ? $adoption->date_adoption->format('Y-m-d') : old('date_adoption', now()->format('Y-m-d')));
    $defaultNiveau = $defaultNiveau ?? ($isEdit ? $adoption->niveau_scolaire : old('niveau_scolaire'));
@endphp

<div class="form-row" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.25rem;">
    {{-- Compte --}}
    <div class="frm-group" style="flex: 1; min-width: 200px;">
        <label class="frm-label" for="compte_id">
            Compte <span class="req">*</span>
        </label>
        <div class="frm-select-wrap">
            <select name="compte_id" id="compte_id" class="frm-select" required>
                @foreach($comptes as $c)
                    <option value="{{ $c->id }}" {{ $defaultCompteId == $c->id ? 'selected' : '' }}>
                        {{ $c->etablissement }} ({{ $c->ville->nom }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Produit --}}
    <div class="frm-group" style="flex: 1; min-width: 200px;">
        <label class="frm-label" for="product_id">
            Produit <span class="req">*</span>
        </label>
        <div class="frm-select-wrap">
            <select name="product_id" id="product_id" class="frm-select" required>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" {{ $defaultProductId == $p->id ? 'selected' : '' }}>
                        {{ $p->titre }} ({{ $p->isbn_13 ?? $p->isbn_10 }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="form-row" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.25rem;">
    {{-- Année scolaire --}}
    <div class="frm-group" style="flex: 1; min-width: 180px;">
        <label class="frm-label" for="annee_scolaire_id">
            Année scolaire <span class="req">*</span>
        </label>
        <div class="frm-select-wrap">
            <select name="annee_scolaire_id" id="annee_scolaire_id" class="frm-select" required>
                @foreach($years as $y)
                    <option value="{{ $y->id }}" {{ $defaultYearId == $y->id ? 'selected' : '' }}>
                        {{ $y->libelle }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Quantité --}}
    <div class="frm-group" style="flex: 1; min-width: 120px;">
        <label class="frm-label" for="quantity">
            Quantité <span class="req">*</span>
        </label>
        <input type="number" name="quantity" id="quantity" class="frm-input"
               value="{{ $defaultQuantity }}" required>
    </div>

    {{-- Date adoption --}}
    <div class="frm-group" style="flex: 1; min-width: 150px;">
        <label class="frm-label" for="date_adoption">
            Date adoption <span class="req">*</span>
        </label>
        <input type="date" name="date_adoption" id="date_adoption" class="frm-input"
               value="{{ $defaultDate }}" required>
    </div>

    {{-- Niveau scolaire --}}
    <div class="frm-group" style="flex: 1; min-width: 150px;">
        <label class="frm-label" for="niveau_scolaire">
            Niveau scolaire
        </label>
        <input type="text" name="niveau_scolaire" id="niveau_scolaire" class="frm-input"
               value="{{ $defaultNiveau }}" placeholder="Ex: 1ère année, Bac…">
    </div>
</div>
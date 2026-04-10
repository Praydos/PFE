<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Compte *</label>
        <select name="compte_id" class="form-select" required>
            <option value="">-- Sélectionnez un compte --</option>
            @foreach($comptes as $c)
                <option value="{{ $c->id }}" {{ old('compte_id', $defaultCompteId ?? $adoption->compte_id ?? '') == $c->id ? 'selected' : '' }}>
                    {{ $c->etablissement }} ({{ $c->ville->nom }})
                </option>
            @endforeach
        </select>
        @error('compte_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Produit *</label>
        <select name="product_id" class="form-select" required>
            <option value="">-- Sélectionnez un produit --</option>
            @foreach($products as $p)
                <option value="{{ $p->id }}" {{ old('product_id', $defaultProductId ?? $adoption->product_id ?? '') == $p->id ? 'selected' : '' }}>
                    {{ $p->titre }} ({{ $p->isbn_13 ?? $p->isbn_10 }})
                </option>
            @endforeach
        </select>
        @error('product_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Année scolaire *</label>
        <select name="annee_scolaire_id" class="form-select" required>
            @foreach($years as $y)
                <option value="{{ $y->id }}" {{ old('annee_scolaire_id', $currentYear->id ?? $adoption->annee_scolaire_id ?? '') == $y->id ? 'selected' : '' }}>
                    {{ $y->libelle }}
                </option>
            @endforeach
        </select>
        @error('annee_scolaire_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-2 mb-3">
        <label class="form-label">Quantité *</label>
        <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $defaultQuantity ?? $adoption->quantity ?? '') }}" required min="1">
        @error('quantity') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label">Date adoption *</label>
        <input type="date" name="date_adoption" class="form-control" value="{{ old('date_adoption', $defaultDate ?? ($adoption->date_adoption->format('Y-m-d') ?? now()->format('Y-m-d'))) }}" required>
        @error('date_adoption') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label">Niveau scolaire</label>
        <input type="text" name="niveau_scolaire" class="form-control" value="{{ old('niveau_scolaire', $defaultNiveau ?? $adoption->niveau_scolaire ?? '') }}" placeholder="Ex: CE1, 6ème...">
        @error('niveau_scolaire') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
</div>
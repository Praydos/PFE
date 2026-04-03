{{-- 
    Consignation form partial – follows compte form styling
    Uses frm-group, frm-select-wrap / frm-input-wrap with icons, and full error handling.
--}}

<div class="form-grid">
    <div class="grid-row grid-cols-3">
        {{-- Délégué (select) --}}
        <div class="frm-group">
            <label class="frm-label">Délégué <span class="req">*</span></label>
            <div class="frm-select-wrap">
                <select name="delegate_id" class="frm-select @error('delegate_id') is-invalid @enderror" required>
                    <option value="">-- Sélectionnez --</option>
                    @foreach($delegates as $del)
                        <option value="{{ $del->id }}" {{ old('delegate_id', $consignation->delegate_id ?? '') == $del->id ? 'selected' : '' }}>
                            {{ $del->prenom }} {{ $del->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error('delegate_id')
                <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
            @enderror
        </div>

        {{-- Produit (select) --}}
        <div class="frm-group">
            <label class="frm-label">Produit <span class="req">*</span></label>
            <div class="frm-select-wrap">
                <select name="product_id" class="frm-select @error('product_id') is-invalid @enderror" required>
                    <option value="">-- Sélectionnez --</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}" {{ old('product_id', $consignation->product_id ?? '') == $prod->id ? 'selected' : '' }}>
                            {{ $prod->titre }} ({{ $prod->isbn_13 ?? $prod->isbn_10 }})
                        </option>
                    @endforeach
                </select>
            </div>
            @error('product_id')
                <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
            @enderror
        </div>

        {{-- Année scolaire (select) --}}
        <div class="frm-group">
            <label class="frm-label">Année scolaire <span class="req">*</span></label>
            <div class="frm-select-wrap">
                <select name="annee_scolaire_id" class="frm-select @error('annee_scolaire_id') is-invalid @enderror" required>
                    <option value="">-- Sélectionnez --</option>
                    @foreach($years as $year)
                        <option value="{{ $year->id }}" {{ old('annee_scolaire_id', $consignation->annee_scolaire_id ?? '') == $year->id ? 'selected' : '' }}>
                            {{ $year->libelle }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error('annee_scolaire_id')
                <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="grid-row grid-cols-1">
        {{-- Quantité (number) --}}
        <div class="frm-group">
            <label class="frm-label">Quantité <span class="req">*</span></label>
            <div class="frm-input-wrap">
                <span class="frm-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                        <line x1="16" y1="21" x2="16" y2="15"/>
                        <line x1="8" y1="21" x2="8" y2="15"/>
                        <line x1="2" y1="11" x2="22" y2="11"/>
                    </svg>
                </span>
                <input type="number" name="quantity" class="frm-input @error('quantity') is-invalid @enderror"
                       value="{{ old('quantity', $consignation->quantity ?? 0) }}" min="0" required>
            </div>
            @error('quantity')
                <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
{{-- 
    AnneeScolaire form partial – follows compte form styling
    Uses frm-group, frm-input-wrap with icons, and full error handling.
--}}

<div class="form-grid">
    <div class="grid-row grid-cols-2">
        {{-- Libellé --}}
        <div class="frm-group">
            <label class="frm-label">Libellé <span class="req">*</span></label>
            <div class="frm-input-wrap">
                <span class="frm-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 4h16v16H4z"/><line x1="8" y1="9" x2="16" y2="9"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="12" y2="17"/>
                    </svg>
                </span>
                <input type="text" name="libelle" class="frm-input @error('libelle') is-invalid @enderror"
                       value="{{ old('libelle', $annees_scolaire->libelle ?? '') }}" required>
            </div>
            @error('libelle')
                <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
            @enderror
        </div>

        {{-- Date début --}}
        <div class="frm-group">
            <label class="frm-label">Date début <span class="req">*</span></label>
            <div class="frm-input-wrap">
                <span class="frm-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </span>
                <input type="date" name="date_debut" class="frm-input @error('date_debut') is-invalid @enderror"
                       value="{{ old('date_debut', $annees_scolaire->date_debut ?? '') }}" required>
            </div>
            @error('date_debut')
                <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
            @enderror
        </div>

        {{-- Date fin --}}
        <div class="frm-group">
            <label class="frm-label">Date fin <span class="req">*</span></label>
            <div class="frm-input-wrap">
                <span class="frm-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </span>
                <input type="date" name="date_fin" class="frm-input @error('date_fin') is-invalid @enderror"
                       value="{{ old('date_fin', $annees_scolaire->date_fin ?? '') }}" required>
            </div>
            @error('date_fin')
                <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
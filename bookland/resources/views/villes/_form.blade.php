<div class="frm-group">
    <label class="frm-label" for="nom">
        Nom de la ville <span class="req">*</span>
    </label>
    <div class="frm-input-wrap">
        <span class="frm-icon">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 2a7 7 0 0 0-7 7c0 4 7 13 7 13s7-9 7-13a7 7 0 0 0-7-7z"/>
                <circle cx="12" cy="9" r="3"/>
            </svg>
        </span>
        <input type="text" id="nom" name="nom"
               class="frm-input {{ $errors->has('nom') ? 'is-invalid' : '' }}"
               value="{{ old('nom', $ville->nom ?? '') }}"
               placeholder="Ex : Casablanca, Rabat…"
               required autocomplete="off">
    </div>
    @error('nom')
        <span class="frm-error">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            {{ $message }}
        </span>
    @enderror
</div>
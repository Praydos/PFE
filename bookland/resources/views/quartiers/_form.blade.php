<style>
/* ── Form Fields ───────────────────────────────────────── */
.frm-group {
    display: flex;
    flex-direction: column;
    gap: .45rem;
    margin-bottom: 1.25rem;
}

.frm-label {
    font-size: .8rem;
    font-weight: 600;
    color: var(--text-secondary);
    letter-spacing: -.01em;
}

.frm-label span.req {
    color: var(--rose);
    margin-left: .2rem;
}

.frm-input,
.frm-select {
    width: 100%;
    padding: .62rem .9rem;
    border: 1px solid var(--border);
    border-radius: var(--r-sm);
    background: var(--bg-card);
    font-family: var(--font);
    font-size: .84rem;
    color: var(--text-primary);
    box-shadow: var(--shadow-xs);
    transition: border-color var(--t), box-shadow var(--t);
    outline: none;
    appearance: none;
    -webkit-appearance: none;
}

.frm-input::placeholder { color: var(--text-hint); }

.frm-input:focus,
.frm-select:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px var(--blue-mid);
}

.frm-input.is-invalid,
.frm-select.is-invalid {
    border-color: var(--rose);
    box-shadow: 0 0 0 3px rgba(232,80,106,.12);
}

.frm-error {
    font-size: .76rem;
    color: var(--rose);
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: .3rem;
}

/* Select arrow */
.frm-select-wrap {
    position: relative;
}
.frm-select-wrap::after {
    content: '';
    position: absolute;
    right: .9rem;
    top: 50%;
    transform: translateY(-50%);
    width: 0; height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-top: 5px solid var(--text-muted);
    pointer-events: none;
}
.frm-select { padding-right: 2.2rem; cursor: pointer; }

/* Input with icon prefix */
.frm-input-wrap {
    position: relative;
}
.frm-input-wrap .frm-icon {
    position: absolute;
    left: .85rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    pointer-events: none;
}
.frm-input-wrap .frm-input { padding-left: 2.35rem; }
</style>

<div class="frm-group">
    <label class="frm-label" for="nom">
        Nom du quartier <span class="req">*</span>
    </label>
    <div class="frm-input-wrap">
        <span class="frm-icon">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </span>
        <input
            type="text"
            id="nom"
            name="nom"
            class="frm-input {{ $errors->has('nom') ? 'is-invalid' : '' }}"
            value="{{ old('nom', $quartier->nom ?? '') }}"
            placeholder="Ex : Hay Riad, Centre-ville…"
            required
            autocomplete="off"
        >
    </div>
    @error('nom')
        <span class="frm-error">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ $message }}
        </span>
    @enderror
</div>

<div class="frm-group">
    <label class="frm-label" for="zone_id">
        Zone <span class="req">*</span>
    </label>
    <div class="frm-select-wrap">
        <select
            id="zone_id"
            name="zone_id"
            class="frm-select {{ $errors->has('zone_id') ? 'is-invalid' : '' }}"
            required
        >
            <option value="">— Sélectionnez une zone —</option>
            @foreach($zones as $zone)
                <option
                    value="{{ $zone->id }}"
                    {{ (old('zone_id', $quartier->zone_id ?? '') == $zone->id) ? 'selected' : '' }}
                >
                    {{ $zone->name }} — {{ $zone->ville->nom }}
                </option>
            @endforeach
        </select>
    </div>
    @error('zone_id')
        <span class="frm-error">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ $message }}
        </span>
    @enderror
</div>
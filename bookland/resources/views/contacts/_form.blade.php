{{-- Nom and Prénom in a row --}}
<div style="display: flex; gap: 1rem; margin-bottom: 1.25rem;">
    <div style="flex: 1;">
        <div class="frm-group">
            <label class="frm-label" for="nom">
                Nom <span class="req">*</span>
            </label>
            <div class="frm-input-wrap">
                <span class="frm-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </span>
                <input type="text" id="nom" name="nom"
                       class="frm-input @error('nom') is-invalid @enderror"
                       value="{{ old('nom', $contact->nom ?? '') }}"
                       placeholder="Ex : Dupont"
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
    </div>
    <div style="flex: 1;">
        <div class="frm-group">
            <label class="frm-label" for="prenom">
                Prénom
            </label>
            <div class="frm-input-wrap">
                <span class="frm-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </span>
                <input type="text" id="prenom" name="prenom"
                       class="frm-input @error('prenom') is-invalid @enderror"
                       value="{{ old('prenom', $contact->prenom ?? '') }}"
                       placeholder="Ex : Jean"
                       autocomplete="off">
            </div>
            @error('prenom')
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
    </div>
</div>

{{-- Email and Telephone --}}
<div style="display: flex; gap: 1rem; margin-bottom: 1.25rem;">
    <div style="flex: 1;">
        <div class="frm-group">
            <label class="frm-label" for="email">
                Email
            </label>
            <div class="frm-input-wrap">
                <span class="frm-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                </span>
                <input type="email" id="email" name="email"
                       class="frm-input @error('email') is-invalid @enderror"
                       value="{{ old('email', $contact->email ?? '') }}"
                       placeholder="contact@exemple.com"
                       autocomplete="off">
            </div>
            @error('email')
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
    </div>
    <div style="flex: 1;">
        <div class="frm-group">
            <label class="frm-label" for="telephone">
                Téléphone
            </label>
            <div class="frm-input-wrap">
                <span class="frm-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                </span>
                <input type="text" id="telephone" name="telephone"
                       class="frm-input @error('telephone') is-invalid @enderror"
                       value="{{ old('telephone', $contact->telephone ?? '') }}"
                       placeholder="05 37 68 12 34"
                       autocomplete="off">
            </div>
            @error('telephone')
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
    </div>
</div>

{{-- Ville and Civilité --}}
<div style="display: flex; gap: 1rem; margin-bottom: 1.25rem;">
    <div style="flex: 1;">
        <div class="frm-group">
            <label class="frm-label" for="ville_id">
                Ville <span class="req">*</span>
            </label>
            <div class="frm-select-wrap">
                <select id="ville_id" name="ville_id"
                        class="frm-select @error('ville_id') is-invalid @enderror"
                        required>
                    <option value="">— Sélectionnez une ville —</option>
                    @foreach($villes as $ville)
                        <option value="{{ $ville->id }}" {{ (old('ville_id', $contact->ville_id ?? '') == $ville->id) ? 'selected' : '' }}>
                            {{ $ville->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error('ville_id')
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
    </div>
    <div style="flex: 1;">
        <div class="frm-group">
            <label class="frm-label" for="civilite">
                Civilité
            </label>
            <div class="frm-select-wrap">
                <select id="civilite" name="civilite"
                        class="frm-select @error('civilite') is-invalid @enderror">
                    <option value="">— Sélectionnez —</option>
                    @foreach(['M.', 'Mme', 'Mlle'] as $civ)
                        <option value="{{ $civ }}" {{ (old('civilite', $contact->civilite ?? '') == $civ) ? 'selected' : '' }}>
                            {{ $civ }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error('civilite')
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
    </div>
</div>

{{-- Fonction and Catégories (flex row) --}}
<div style="display: flex; gap: 1rem; margin-bottom: 1.25rem;">
    <div style="flex: 1;">
        <div class="frm-group">
            <label class="frm-label" for="fonction">
                Fonction
            </label>
            <div class="frm-select-wrap">
                <select id="fonction" name="fonction"
                        class="frm-select @error('fonction') is-invalid @enderror">
                    <option value="">— Sélectionnez —</option>
                    @foreach(['Directeur', 'Responsable', 'Enseignant', 'Autre'] as $func)
                        <option value="{{ $func }}" {{ (old('fonction', $contact->fonction ?? '') == $func) ? 'selected' : '' }}>
                            {{ $func }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error('fonction')
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
    </div>
    <div style="flex: 1;">
        <div class="frm-group">
            <label class="frm-label">Catégories</label>
            <div style="margin-top: 0.5rem;">
                @foreach($categoriesOptions as $cat)
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <input type="checkbox" name="categories[]" value="{{ $cat }}" id="cat_{{ $loop->index }}"
                               class="frm-checkbox"
                               style="width: 1rem; height: 1rem; cursor: pointer;"
                               {{ in_array($cat, old('categories', $contact->categories ?? [])) ? 'checked' : '' }}>
                        <label for="cat_{{ $loop->index }}" class="frm-label" style="margin: 0; font-weight: normal;">
                            {{ $cat }}
                        </label>
                    </div>
                @endforeach
            </div>
            @error('categories')
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
    </div>
</div>

{{-- Cycles --}}
<div class="frm-group">
    <label class="frm-label">Cycles</label>
    <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-top: 0.5rem;">
        @foreach($cyclesOptions as $cycle)
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="cycles[]" value="{{ $cycle }}" id="cycle_{{ $loop->index }}"
                       class="frm-checkbox"
                       style="width: 1rem; height: 1rem; cursor: pointer;"
                       {{ in_array($cycle, old('cycles', $contact->cycles ?? [])) ? 'checked' : '' }}>
                <label for="cycle_{{ $loop->index }}" class="frm-label" style="margin: 0; font-weight: normal;">
                    {{ $cycle }}
                </label>
            </div>
        @endforeach
    </div>
    @error('cycles')
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

<style>
    .frm-checkbox {
        accent-color: var(--blue);
    }
</style>
{{-- Nom and Prénom (flex row) --}}
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
                       value="{{ old('nom', $user->nom ?? '') }}"
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
                Prénom <span class="req">*</span>
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
                       value="{{ old('prenom', $user->prenom ?? '') }}"
                       placeholder="Ex : Jean"
                       required autocomplete="off">
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

{{-- Email --}}
<div class="frm-group">
    <label class="frm-label" for="email">
        Email <span class="req">*</span>
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
               value="{{ old('email', $user->email ?? '') }}"
               placeholder="utilisateur@exemple.com"
               required autocomplete="off">
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

{{-- Password --}}
<div class="frm-group">
    <label class="frm-label" for="password">
        Mot de passe @if(!isset($user)) <span class="req">*</span> @endif
    </label>
    <div class="frm-input-wrap">
        <span class="frm-icon">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
        </span>
        <input type="password" id="password" name="password"
               class="frm-input @error('password') is-invalid @enderror"
               placeholder="{{ isset($user) ? 'Laisser vide pour ne pas changer' : 'Minimum 6 caractères' }}"
               autocomplete="new-password">
    </div>
    @error('password')
        <span class="frm-error">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            {{ $message }}
        </span>
    @enderror
    @isset($user)
        <small class="frm-hint">Laisser vide pour conserver le mot de passe actuel</small>
    @endisset
</div>

{{-- Password Confirmation --}}
<div class="frm-group">
    <label class="frm-label" for="password_confirmation">
        Confirmation
    </label>
    <div class="frm-input-wrap">
        <span class="frm-icon">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
        </span>
        <input type="password" id="password_confirmation" name="password_confirmation"
               class="frm-input"
               placeholder="Confirmez le mot de passe">
    </div>
</div>

{{-- Role and Ville (optional) in a row --}}
<div style="display: flex; gap: 1rem; margin-bottom: 1.25rem;">
    <div style="flex: 1;">
        <div class="frm-group">
            <label class="frm-label" for="role">
                Rôle <span class="req">*</span>
            </label>
            <div class="frm-select-wrap">
                <select id="role" name="role"
                        class="frm-select @error('role') is-invalid @enderror"
                        required>
                    @foreach($roles as $roleOption)
                        <option value="{{ $roleOption }}" {{ (old('role', $user->role ?? $selectedRole ?? 'delegue') == $roleOption) ? 'selected' : '' }}>
                            {{ ucfirst($roleOption) }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error('role')
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
            <label class="frm-label" for="ville_id">
                Ville (optionnelle)
            </label>
            <div class="frm-select-wrap">
                <select id="ville_id" name="ville_id"
                        class="frm-select @error('ville_id') is-invalid @enderror">
                    <option value="">-- Aucune --</option>
                    @foreach($villes as $ville)
                        <option value="{{ $ville->id }}" {{ (old('ville_id', $user->ville_id ?? '') == $ville->id) ? 'selected' : '' }}>
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
</div>

{{-- RBO villes container (multiple select) --}}
<div id="rbo-villes-container" class="frm-group" style="{{ (old('role', $user->role ?? $selectedRole ?? 'delegue') == 'rbo') ? '' : 'display:none;' }}">
    <label class="frm-label">Villes assignées (pour RBO)</label>
    <select name="ville_ids[]" id="ville_ids" class="frm-select" multiple>
        @foreach($villes as $ville)
            <option value="{{ $ville->id }}" {{ in_array($ville->id, $assignedVilles ?? []) ? 'selected' : '' }}>
                {{ $ville->nom }}
            </option>
        @endforeach
    </select>
    <small class="frm-hint">Sélectionnez les villes que ce RBO supervisera.</small>
</div>

{{-- Active checkbox --}}
<div class="frm-group">
    <label class="frm-label" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
        <input type="checkbox" id="is_active" name="is_active" value="1"
               class="frm-checkbox"
               style="width: 1rem; height: 1rem; cursor: pointer;"
               {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
        <span>Compte actif</span>
    </label>
</div>

<style>
    .frm-hint {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.3rem;
    }
    .frm-checkbox {
        accent-color: var(--blue);
    }
</style>

{{-- Script for toggling RBO villes container --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const rboContainer = document.getElementById('rbo-villes-container');
    function toggleRboVilles() {
        if (roleSelect.value === 'rbo') {
            rboContainer.style.display = 'block';
        } else {
            rboContainer.style.display = 'none';
        }
    }
    roleSelect.addEventListener('change', toggleRboVilles);
    toggleRboVilles(); // initial state
});
</script>
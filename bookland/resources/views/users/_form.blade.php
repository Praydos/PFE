<div class="row">
    <div class="col-md-6 mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom', $user->nom ?? '') }}" required>
        @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="prenom" class="form-label">Prénom</label>
        <input type="text" class="form-control @error('prenom') is-invalid @enderror" id="prenom" name="prenom" value="{{ old('prenom', $user->prenom ?? '') }}" required>
        @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="password" class="form-label">Mot de passe</label>
    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" {{ isset($user) ? '' : 'required' }}>
    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    @isset($user) <small class="form-text text-muted">Laisser vide pour ne pas changer</small> @endisset
</div>

<div class="mb-3">
    <label for="password_confirmation" class="form-label">Confirmation</label>
    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="role" class="form-label">Rôle</label>
        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
            @foreach($roles as $roleOption)
                <option value="{{ $roleOption }}" {{ (old('role', $user->role ?? $selectedRole ?? 'delegue') == $roleOption) ? 'selected' : '' }}>
                    {{ ucfirst($roleOption) }}
                </option>
            @endforeach
        </select>
        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="ville_id" class="form-label">Ville (optionnelle)</label>
        <select class="form-select @error('ville_id') is-invalid @enderror" id="ville_id" name="ville_id">
            <option value="">-- Aucune --</option>
            @foreach($villes as $ville)
                <option value="{{ $ville->id }}" {{ (old('ville_id', $user->ville_id ?? '') == $ville->id) ? 'selected' : '' }}>
                    {{ $ville->nom }}
                </option>
            @endforeach
        </select>
        @error('ville_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Compte actif</label>
</div>
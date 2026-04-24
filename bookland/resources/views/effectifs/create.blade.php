@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --bg:       #f5f6fa;
    --card:     #ffffff;
    --hover:    #f8f9fd;
    --subtle:   #f0f2f8;
    --border:   #e4e7f0;
    --border-2: #d0d5e8;
    --blue:     #5b8dee;
    --blue-d:   #3d6fd6;
    --blue-l:   #eef3fd;
    --blue-m:   #dce8fb;
    --teal:     #0cb8b6;
    --teal-l:   #e6faf9;
    --violet:   #7c6fcd;
    --violet-l: #f0eeff;
    --amber:    #e8a020;
    --amber-l:  #fff8ec;
    --rose:     #e8506a;
    --rose-l:   #fef0f2;
    --green:    #28c76f;
    --green-l:  #e8fbf0;
    --t1: #1a1f36; --t2: #525f7f; --t3: #9ba8c5; --t4: #bcc5dc;
    --r1:6px; --r2:8px; --r3:12px; --r4:16px; --r5:20px;
    --s1: 0 1px 3px rgba(31,45,80,.06);
    --s2: 0 2px 8px rgba(31,45,80,.08);
    --s3: 0 8px 24px rgba(31,45,80,.10);
    --sb: 0 4px 14px rgba(91,141,238,.32);
    --font:'DM Sans',sans-serif;
    --mono:'DM Mono',monospace;
    --ease:cubic-bezier(.4,0,.2,1);
    --t:.17s var(--ease);
}

body { font-family:var(--font); background:var(--bg); color:var(--t1); -webkit-font-smoothing:antialiased; }

/* ── Page ──────────────────────────────────────────── */
.ef-page { padding:2rem 2.5rem 3rem; animation:rise .4s var(--ease) both; }
@keyframes rise { from{opacity:0;transform:translateY(12px);} to{opacity:1;transform:translateY(0);} }

/* ── Breadcrumb ────────────────────────────────────── */
.ef-bc { display:flex; align-items:center; gap:.4rem; font-size:.75rem; font-weight:500; color:var(--t3); margin-bottom:1.5rem; }
.ef-bc a { color:var(--t3); text-decoration:none; transition:color var(--t); }
.ef-bc a:hover { color:var(--blue); }
.ef-bc-s { color:var(--t4); }

/* ── Header ────────────────────────────────────────── */
.ef-header { margin-bottom:2rem; }
.ef-header h1 { font-size:1.6rem; font-weight:800; letter-spacing:-.03em; color:var(--t1); line-height:1.15; }
.ef-header p  { font-size:.83rem; color:var(--t3); margin-top:.3rem; }

/* ── Buttons ───────────────────────────────────────── */
.btn-ef {
    display:inline-flex; align-items:center; gap:.4rem;
    padding:.56rem 1.2rem; border-radius:var(--r2);
    font-family:var(--font); font-size:.82rem; font-weight:600;
    cursor:pointer; border:1px solid transparent;
    transition:all var(--t); text-decoration:none;
    white-space:nowrap; letter-spacing:-.01em; line-height:1;
}
.btn-ef svg { flex-shrink:0; }
.btn-ef-primary { background:var(--blue); color:#fff; border-color:var(--blue); box-shadow:var(--sb); }
.btn-ef-primary:hover { background:var(--blue-d); color:#fff; text-decoration:none; transform:translateY(-1px); box-shadow:0 6px 20px rgba(91,141,238,.4); }
.btn-ef-ghost { background:var(--card); color:var(--t2); border-color:var(--border); box-shadow:var(--s1); }
.btn-ef-ghost:hover { background:var(--hover); color:var(--t1); border-color:var(--border-2); text-decoration:none; }

/* ── Card ──────────────────────────────────────────── */
.ef-card {
    background:var(--card);
    border:1px solid var(--border);
    border-radius:var(--r5);
    box-shadow:var(--s2);
    overflow:hidden;
    max-width:900px;
}
.ef-card-hd {
    padding:1rem 1.6rem;
    border-bottom:1px solid var(--border);
    display:flex; align-items:center; gap:.5rem;
    background:linear-gradient(to bottom,#fafbff,#fff);
}
.ef-card-pip { width:7px; height:7px; border-radius:50%; background:var(--amber); box-shadow:0 0 0 3px rgba(232,160,32,.2); flex-shrink:0; }
.ef-card-title { font-size:.87rem; font-weight:700; color:var(--t1); letter-spacing:-.01em; }

.ef-card-body { padding:1.75rem 1.6rem; display:flex; flex-direction:column; gap:1.5rem; }

/* ── Section label ─────────────────────────────────── */
.ef-section-label {
    font-size:.67rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.11em; color:var(--t4);
    display:flex; align-items:center; gap:.5rem;
    margin-bottom:1rem;
}
.ef-section-label::after { content:''; flex:1; height:1px; background:var(--border); }

/* ── Form grid ─────────────────────────────────────── */
.ef-row  { display:grid; gap:1rem; }
.ef-row-2 { grid-template-columns:1fr 1fr; }
.ef-row-3 { grid-template-columns:1fr 1fr 1fr; }

/* ── Form group ────────────────────────────────────── */
.ef-group { display:flex; flex-direction:column; gap:.42rem; }

.ef-label {
    font-size:.78rem; font-weight:600; color:var(--t2);
    letter-spacing:-.01em;
    display:flex; align-items:center; gap:.3rem;
}
.ef-label .req { color:var(--rose); font-size:.75rem; }
.ef-label .opt { font-size:.7rem; font-weight:400; color:var(--t4); }

.ef-input,
.ef-select {
    width:100%; padding:.62rem .9rem;
    border:1px solid var(--border); border-radius:var(--r2);
    background:var(--card); font-family:var(--font);
    font-size:.84rem; color:var(--t1);
    box-shadow:var(--s1);
    transition:border-color var(--t), box-shadow var(--t);
    outline:none; appearance:none; -webkit-appearance:none;
}
.ef-input::placeholder { color:var(--t4); }
.ef-input:focus,
.ef-select:focus { border-color:var(--blue); box-shadow:0 0 0 3px var(--blue-m); }
.ef-input:disabled { background:var(--subtle); color:var(--t3); cursor:not-allowed; }

/* Custom select arrow */
.ef-select-wrap { position:relative; }
.ef-select-wrap::after {
    content:'';
    position:absolute; right:.9rem; top:50%; transform:translateY(-50%);
    width:0; height:0;
    border-left:4px solid transparent;
    border-right:4px solid transparent;
    border-top:5px solid var(--t3);
    pointer-events:none;
}
.ef-select { padding-right:2.2rem; cursor:pointer; }

/* Number input — hide arrows for cleaner look */
.ef-input[type="number"] { -moz-appearance:textfield; font-family:var(--mono); font-size:.82rem; }
.ef-input[type="number"]::-webkit-inner-spin-button,
.ef-input[type="number"]::-webkit-outer-spin-button { -webkit-appearance:none; }

/* ── Source block ──────────────────────────────────── */
.ef-source-card {
    background:var(--subtle);
    border:1px solid var(--border);
    border-radius:var(--r3);
    padding:1.1rem 1.2rem;
    display:flex; flex-direction:column; gap:.75rem;
    transition:border-color var(--t);
}
.ef-source-card:hover { border-color:var(--border-2); }

.ef-source-header {
    display:flex; align-items:center; gap:.5rem;
    font-size:.74rem; font-weight:700; color:var(--t3);
    text-transform:uppercase; letter-spacing:.07em;
}
.ef-source-num {
    width:22px; height:22px; border-radius:50%;
    background:var(--blue-l); border:1px solid var(--blue-m);
    color:var(--blue); font-size:.68rem; font-weight:800;
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0;
}

/* ── Hint text ─────────────────────────────────────── */
.ef-hint { font-size:.73rem; color:var(--t3); margin-top:.25rem; display:flex; align-items:center; gap:.3rem; }

/* ── Validation badge (admin/rbo section) ──────────── */
.ef-admin-section {
    background:var(--violet-l);
    border:1px solid rgba(124,111,205,.2);
    border-radius:var(--r3);
    padding:1.1rem 1.3rem;
}
.ef-admin-badge {
    display:inline-flex; align-items:center; gap:.35rem;
    padding:.2rem .65rem; border-radius:20px;
    background:var(--violet-l); color:var(--violet);
    border:1px solid rgba(124,111,205,.25);
    font-size:.7rem; font-weight:700; letter-spacing:.04em;
    margin-bottom:.85rem;
}

/* ── Footer ────────────────────────────────────────── */
.ef-footer {
    padding:1.1rem 1.6rem;
    border-top:1px solid var(--border);
    background:var(--bg);
    display:flex; align-items:center; justify-content:flex-end; gap:.6rem;
}

/* ── Error ─────────────────────────────────────────── */
.ef-error {
    font-size:.75rem; color:var(--rose); font-weight:500;
    display:flex; align-items:center; gap:.3rem; margin-top:.2rem;
}
.ef-input.err, .ef-select.err { border-color:var(--rose); box-shadow:0 0 0 3px rgba(232,80,106,.1); }

/* ── Responsive ────────────────────────────────────── */
@media(max-width:768px) {
    .ef-page { padding:1.25rem 1rem 2rem; }
    .ef-row-2, .ef-row-3 { grid-template-columns:1fr; }
    .ef-footer { flex-direction:column-reverse; }
    .btn-ef { width:100%; justify-content:center; }
}
</style>
@endpush

@section('content')
<div class="ef-page">

    {{-- Breadcrumb --}}
    <div class="ef-bc">
        <a href="{{ route('effectifs.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </a>
        <span class="ef-bc-s">›</span>
        <a href="{{ route('effectifs.index') }}">Effectifs</a>
        <span class="ef-bc-s">›</span>
        <span style="color:var(--t2);font-weight:600;">Nouvel effectif</span>
    </div>

    {{-- Header --}}
    <div class="ef-header">
        <h1>Nouvel effectif</h1>
        <p>Enregistrez un nouvel effectif scolaire pour un compte</p>
    </div>

    {{-- Form card --}}
    <div class="ef-card">
        <div class="ef-card-hd">
            <span class="ef-card-pip"></span>
            <span class="ef-card-title">Informations de l'effectif</span>
        </div>

        <form method="POST" action="{{ route('effectifs.store') }}" novalidate>
            @csrf

            <div class="ef-card-body">

                {{-- ── Section 1 : Compte & Année ───────────── --}}
                <div>
                    <div class="ef-section-label">Compte & Période</div>
                    <div class="ef-row ef-row-2">

                        <div class="ef-group">
                            <label class="ef-label" for="compte_id">
                                Compte
                                <span class="req">*</span>
                            </label>
                            <div class="ef-select-wrap">
                                <select name="compte_id" id="compte_id"
                                        class="ef-select {{ $errors->has('compte_id') ? 'err' : '' }}"
                                        required>
                                    <option value="">— Sélectionnez un compte —</option>
                                    @foreach($comptes as $c)
                                        <option value="{{ $c->id }}"
                                            {{ old('compte_id', $effectif->compte_id ?? '') == $c->id ? 'selected' : '' }}>
                                            {{ $c->etablissement }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('compte_id')
                                <span class="ef-error">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="ef-group">
                            <label class="ef-label" for="annee_scolaire_id">
                                Année scolaire
                                <span class="req">*</span>
                            </label>
                            <div class="ef-select-wrap">
                                <select name="annee_scolaire_id" id="annee_scolaire_id"
                                        class="ef-select {{ $errors->has('annee_scolaire_id') ? 'err' : '' }}"
                                        required>
                                    <option value="">— Sélectionnez une année —</option>
                                    @foreach($years as $y)
                                        <option value="{{ $y->id }}"
                                            {{ old('annee_scolaire_id', $effectif->annee_scolaire_id ?? '') == $y->id ? 'selected' : '' }}>
                                            {{ $y->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('annee_scolaire_id')
                                <span class="ef-error">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- ── Section 2 : Niveau & Cycle & Massar ─── --}}
                <div>
                    <div class="ef-section-label">Niveau scolaire</div>
                    <div class="ef-row ef-row-3">

                        <div class="ef-group">
                            <label class="ef-label" for="niveau">
                                Niveau
                                <span class="req">*</span>
                            </label>
                            <div class="ef-select-wrap">
                                <select name="niveau" id="niveau"
                                        class="ef-select {{ $errors->has('niveau') ? 'err' : '' }}"
                                        required>
                                    <option value="">— Niveau —</option>
                                    @foreach($niveaux as $n)
                                        <option value="{{ $n }}"
                                            {{ old('niveau', $effectif->niveau ?? '') == $n ? 'selected' : '' }}>
                                            {{ $n }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('niveau')
                                <span class="ef-error"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="ef-group">
                            <label class="ef-label" for="cycle">
                                Cycle
                                <span class="opt">(facultatif)</span>
                            </label>
                            <div class="ef-select-wrap">
                                <select name="cycle" id="cycle"
                                        class="ef-select {{ $errors->has('cycle') ? 'err' : '' }}">
                                    <option value="">— Cycle —</option>
                                    @foreach($cycleOptions as $opt)
                                        <option value="{{ $opt }}"
                                            {{ old('cycle', $effectif->cycle ?? '') == $opt ? 'selected' : '' }}>
                                            {{ $opt }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="ef-group">
                            <label class="ef-label" for="massar">
                                Massar
                                <span class="opt">(officiel)</span>
                            </label>
                            <input type="number" name="massar" id="massar"
                                   class="ef-input {{ $errors->has('massar') ? 'err' : '' }}"
                                   value="{{ old('massar', $effectif->massar ?? '') }}"
                                   placeholder="Ex: 340"
                                   min="0">
                            @error('massar')
                                <span class="ef-error"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- ── Section 3 : Sources ──────────────────── --}}
                <div>
                    <div class="ef-section-label">Sources de contact</div>
                    <div class="ef-row ef-row-3">

                        @foreach([1,2,3] as $idx)
                        <div class="ef-source-card">
                            <div class="ef-source-header">
                                <span class="ef-source-num">{{ $idx }}</span>
                                Source {{ $idx }}
                            </div>

                            <div class="ef-group">
                                <label class="ef-label" for="source_{{ $idx }}">Contact</label>
                                <div class="ef-select-wrap">
                                    <select name="source_{{ $idx }}"
                                            id="source_{{ $idx }}"
                                            class="ef-select contact-select"
                                            data-target="nombre_classes_{{ $idx }}">
                                        <option value="">— Contact —</option>
                                    </select>
                                </div>
                            </div>

                            <div class="ef-group">
                                <label class="ef-label" for="nombre_classes_{{ $idx }}">Nb. classes</label>
                                <input type="number"
                                       name="nombre_classes_{{ $idx }}"
                                       id="nombre_classes_{{ $idx }}"
                                       class="ef-input"
                                       placeholder="0"
                                       min="0"
                                       value="{{ old('nombre_classes_'.$idx, $effectif->{'nombre_classes_'.$idx} ?? '') }}">
                            </div>
                        </div>
                        @endforeach

                    </div>
                    <p class="ef-hint" style="margin-top:.75rem;">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Sélectionnez d'abord un compte pour charger les contacts disponibles.
                    </p>
                </div>

                {{-- ── Section 4 : Validation (admin/rbo) ───── --}}
                @if(in_array(auth()->user()->role, ['admin', 'rbo']))
                <div class="ef-admin-section">
                    <div class="ef-admin-badge">
                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        RBO / Admin
                    </div>
                    <div class="ef-group" style="max-width:320px;">
                        <label class="ef-label" for="effectif_valide">Effectif validé</label>
                        <input type="number" name="effectif_valide" id="effectif_valide"
                               class="ef-input {{ $errors->has('effectif_valide') ? 'err' : '' }}"
                               value="{{ old('effectif_valide', $effectif->effectif_valide ?? '') }}"
                               placeholder="Laissez vide si non validé"
                               min="0">
                        <span class="ef-hint">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            Laissez vide si l'effectif n'est pas encore validé.
                        </span>
                        @error('effectif_valide')
                            <span class="ef-error"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                @endif

            </div>{{-- /card-body --}}

            <div class="ef-footer">
                <a href="{{ route('effectifs.index') }}" class="btn-ef btn-ef-ghost">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Annuler
                </a>
                <button type="submit" class="btn-ef btn-ef-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Enregistrer
                </button>
            </div>

        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    const compteSelect = document.getElementById('compte_id');

    function loadContacts(compteId, preselect) {
        const selects = document.querySelectorAll('.contact-select');
        if (!compteId) {
            selects.forEach(s => s.innerHTML = '<option value="">— Contact —</option>');
            return;
        }
        fetch(`/api/comptes/${compteId}/contacts`)
            .then(r => r.json())
            .then(data => {
                const base = '<option value="">— Contact —</option>';
                const opts = data.map(c =>
                    `<option value="${c.id}">${c.prenom} ${c.nom}${c.fonction ? ' · '+c.fonction : ''}</option>`
                ).join('');
                selects.forEach(s => { s.innerHTML = base + opts; });

                // Re-select existing values when editing
                @isset($effectif)
                    @if($effectif->source_1 ?? false) document.querySelector('select[name="source_1"]').value = {{ $effectif->source_1 }}; @endif
                    @if($effectif->source_2 ?? false) document.querySelector('select[name="source_2"]').value = {{ $effectif->source_2 }}; @endif
                    @if($effectif->source_3 ?? false) document.querySelector('select[name="source_3"]').value = {{ $effectif->source_3 }}; @endif
                @endisset
            })
            .catch(() => {
                document.querySelectorAll('.contact-select').forEach(s => {
                    s.innerHTML = '<option value="">Erreur de chargement</option>';
                });
            });
    }

    compteSelect.addEventListener('change', function () { loadContacts(this.value); });

    // On page load — reload contacts if compte already selected (edit mode)
    if (compteSelect.value) loadContacts(compteSelect.value);
})();
</script>
@endpush
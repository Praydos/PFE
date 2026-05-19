@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
/* ===== FULL DESIGN SYSTEM CSS (same as actions, non-conformites) ===== */
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
    --font: 'DM Sans', sans-serif;
    --mono: 'DM Mono', monospace;
    --ease: cubic-bezier(.4,0,.2,1);
    --t: .17s var(--ease);
}

body { font-family: var(--font); background: var(--bg); color: var(--t1); -webkit-font-smoothing: antialiased; }

.ac-page { padding: 2rem 2.5rem 3rem; animation: rise .4s var(--ease) both; }
@keyframes rise { from{opacity:0;transform:translateY(12px);} to{opacity:1;transform:translateY(0);} }

.ac-bc { display:flex; align-items:center; gap:.4rem; font-size:.75rem; font-weight:500; color:var(--t3); margin-bottom:1.5rem; }
.ac-bc a { color:var(--t3); text-decoration:none; transition:color var(--t); }
.ac-bc a:hover { color:var(--blue); }
.ac-bc-s { color:var(--t4); }

.ac-header { margin-bottom:2rem; }
.ac-header h1 { font-size:1.6rem; font-weight:800; letter-spacing:-.03em; color:var(--t1); }
.ac-header p  { font-size:.83rem; color:var(--t3); margin-top:.3rem; }

.btn-ac { display:inline-flex; align-items:center; gap:.4rem; padding:.56rem 1.2rem; border-radius:var(--r2); font-family:var(--font); font-size:.82rem; font-weight:600; cursor:pointer; border:1px solid transparent; transition:all var(--t); text-decoration:none; white-space:nowrap; letter-spacing:-.01em; line-height:1; }
.btn-ac svg { flex-shrink:0; }
.btn-ac-primary { background:var(--blue); color:#fff; border-color:var(--blue); box-shadow:var(--sb); }
.btn-ac-primary:hover { background:var(--blue-d); color:#fff; text-decoration:none; transform:translateY(-1px); }
.btn-ac-ghost { background:var(--card); color:var(--t2); border-color:var(--border); box-shadow:var(--s1); }
.btn-ac-ghost:hover { background:var(--hover); color:var(--t1); border-color:var(--border-2); text-decoration:none; }
.btn-ac-danger { background:var(--rose-l); color:var(--rose); border-color:rgba(232,80,106,.2); }
.btn-ac-danger:hover { background:#fddde2; color:var(--rose); text-decoration:none; }
.btn-ac-sm { padding:.34rem .7rem; font-size:.75rem; }

.ac-card { background:var(--card); border:1px solid var(--border); border-radius:var(--r5); box-shadow:var(--s2); overflow:hidden; }
.ac-card-hd { padding:1rem 1.6rem; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:.5rem; background:linear-gradient(to bottom,#fafbff,#fff); }
.ac-card-pip { width:7px; height:7px; border-radius:50%; background:var(--blue); box-shadow:0 0 0 3px var(--blue-m); flex-shrink:0; }
.ac-card-title { font-size:.87rem; font-weight:700; color:var(--t1); letter-spacing:-.01em; }

.ac-card-body { padding:1.75rem 1.6rem; }

.ac-sec { font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.11em; color:var(--t4); display:flex; align-items:center; gap:.5rem; margin-bottom:1rem; }
.ac-sec::after { content:''; flex:1; height:1px; background:var(--border); }

.ac-row   { display:grid; gap:1rem; margin-bottom:1.1rem; }
.ac-row-2 { grid-template-columns:1fr 1fr; }
.ac-row-3 { grid-template-columns:1fr 1fr 1fr; }
.ac-row-4 { grid-template-columns:repeat(4, 1fr); }

.ac-group { display:flex; flex-direction:column; gap:.42rem; }
.ac-label { font-size:.78rem; font-weight:600; color:var(--t2); letter-spacing:-.01em; display:flex; align-items:center; gap:.3rem; }
.ac-label .req { color:var(--rose); }
.ac-label .opt { font-size:.7rem; font-weight:400; color:var(--t4); }

.ac-input, .ac-select, .ac-textarea {
    width:100%; padding:.62rem .9rem;
    border:1px solid var(--border); border-radius:var(--r2);
    background:var(--card); font-family:var(--font);
    font-size:.84rem; color:var(--t1);
    box-shadow:var(--s1);
    transition:border-color var(--t), box-shadow var(--t);
    outline:none; appearance:none; -webkit-appearance:none;
}
.ac-input::placeholder, .ac-textarea::placeholder { color:var(--t4); }
.ac-input:focus, .ac-select:focus, .ac-textarea:focus { border-color:var(--blue); box-shadow:0 0 0 3px var(--blue-m); }
.ac-textarea { resize:vertical; min-height:80px; }
.ac-input.err, .ac-select.err { border-color:var(--rose); box-shadow:0 0 0 3px rgba(232,80,106,.1); }
.ac-input[type="date"] { font-family:var(--mono); font-size:.8rem; }

.ac-sel-wrap { position:relative; }
.ac-sel-wrap::after { content:''; position:absolute; right:.9rem; top:50%; transform:translateY(-50%); width:0; height:0; border-left:4px solid transparent; border-right:4px solid transparent; border-top:5px solid var(--t3); pointer-events:none; }
.ac-select { padding-right:2.2rem; cursor:pointer; }
.ac-select[multiple] { padding-right:.9rem; }

.ac-hint  { font-size:.72rem; color:var(--t3); margin-top:.2rem; display:flex; align-items:center; gap:.3rem; }
.ac-error { font-size:.75rem; color:var(--rose); font-weight:500; display:flex; align-items:center; gap:.3rem; margin-top:.2rem; }

.ac-check-row { display:flex; align-items:center; gap:.55rem; cursor:pointer; }
.ac-check-row input[type="checkbox"] { width:15px; height:15px; accent-color:var(--blue); cursor:pointer; flex-shrink:0; }
.ac-check-row span { font-size:.82rem; font-weight:600; color:var(--t2); }

.ac-footer { padding:1.1rem 1.6rem; border-top:1px solid var(--border); background:var(--bg); display:flex; align-items:center; justify-content:flex-end; gap:.6rem; }

@media(max-width:768px) {
    .ac-page { padding:1.25rem 1rem 2rem; }
    .ac-row-2, .ac-row-3, .ac-row-4 { grid-template-columns:1fr; }
    .ac-footer { flex-direction:column-reverse; }
    .btn-ac { width:100%; justify-content:center; }
}
</style>
@endpush

@section('content')
<div class="ac-page">

    {{-- Breadcrumb --}}
    <div class="ac-bc">
        <a href="{{ route('reclamations.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="ac-bc-s">›</span>
        <a href="{{ route('reclamations.index') }}">Réclamations</a>
        <span class="ac-bc-s">›</span>
        <span style="color:var(--t2);font-weight:600;">{{ isset($reclamation) ? 'Modifier' : 'Nouvelle' }}</span>
    </div>

    <div class="ac-header">
        <h1>{{ isset($reclamation) ? 'Modifier la réclamation' : 'Nouvelle réclamation' }}</h1>
        <p>{{ isset($reclamation) ? 'Mettez à jour les informations de la réclamation.' : 'Enregistrez une nouvelle réclamation client.' }}</p>
    </div>

    @if($errors->any())
    <div style="display:flex; align-items:flex-start; gap:.75rem; padding:1rem 1.25rem; border-radius:var(--r3); background:var(--rose-l); border:1px solid rgba(232,80,106,.25); margin-bottom:1.5rem;">
        <svg width="16" height="16" fill="none" stroke="var(--rose)" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div><strong style="display:block;margin-bottom:.3rem;">Veuillez corriger les erreurs suivantes&nbsp;:</strong><ul style="padding-left:1.2rem;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    </div>
    @endif

    @php
        $isEdit = isset($reclamation);
        $action = $isEdit ? route('reclamations.update', $reclamation) : route('reclamations.store');
        $method = $isEdit ? 'PUT' : 'POST';
    @endphp

    <div class="ac-card">
        <div class="ac-card-hd">
            <span class="ac-card-pip"></span>
            <span class="ac-card-title">Formulaire de réclamation</span>
        </div>

        <form method="POST" action="{{ $action }}">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="ac-card-body">
                {{-- Section 1 : Informations générales --}}
                <div class="ac-sec">Informations générales</div>
                <div class="ac-row ac-row-2">
                    <div class="ac-group">
                        <label class="ac-label" for="compte_id">Compte <span class="req">*</span></label>
                        <div class="ac-sel-wrap">
                            <select name="compte_id" id="compte_id" class="ac-select @error('compte_id') err @enderror" required>
                                <option value="">-- Sélectionnez --</option>
                                @foreach($comptes as $c)
                                    <option value="{{ $c->id }}" {{ old('compte_id', $isEdit ? $reclamation->compte_id : '') == $c->id ? 'selected' : '' }}>{{ $c->etablissement }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('compte_id')<span class="ac-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="ac-group">
                        <label class="ac-label" for="contact_id">Contact <span class="req">*</span></label>
                        <div class="ac-sel-wrap">
                            <select name="contact_id" id="contact_id" class="ac-select @error('contact_id') err @enderror" required>
                                <option value="">-- Sélectionnez un contact --</option>
                            </select>
                        </div>
                        @error('contact_id')<span class="ac-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="ac-row ac-row-3">
                    <div class="ac-group">
                        <label class="ac-label" for="date_reclamation">Date réclamation <span class="req">*</span></label>
                        <input type="date" name="date_reclamation" id="date_reclamation" class="ac-input @error('date_reclamation') err @enderror" value="{{ old('date_reclamation', $isEdit ? $reclamation->date_reclamation->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
                        @error('date_reclamation')<span class="ac-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="ac-group">
                        <label class="ac-label" for="type">Type</label>
                        <div class="ac-sel-wrap">
                            <select name="type" id="type" class="ac-select @error('type') err @enderror">
                                <option value="">-- Sélectionnez --</option>
                                @foreach($types as $t)
                                    <option value="{{ $t }}" {{ old('type', $isEdit ? $reclamation->type : '') == $t ? 'selected' : '' }}>{{ str_replace('_', ' ', $t) }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('type')<span class="ac-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="ac-group">
                        <label class="ac-label" for="priorite">Priorité</label>
                        <div class="ac-sel-wrap">
                            <select name="priorite" id="priorite" class="ac-select @error('priorite') err @enderror">
                                <option value="basse" {{ old('priorite', $isEdit ? $reclamation->priorite : '') == 'basse' ? 'selected' : '' }}>Basse</option>
                                <option value="moyenne" {{ old('priorite', $isEdit ? $reclamation->priorite : '') == 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                                <option value="haute" {{ old('priorite', $isEdit ? $reclamation->priorite : '') == 'haute' ? 'selected' : '' }}>Haute</option>
                            </select>
                        </div>
                        @error('priorite')<span class="ac-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="ac-row ac-row-2">
                    <div class="ac-group">
                        <label class="ac-label" for="categorie">Catégorie <span class="req">*</span></label>
                        <div class="ac-sel-wrap">
                            <select name="categorie" id="categorie" class="ac-select @error('categorie') err @enderror" required>
                                <option value="">-- Sélectionnez --</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c }}" {{ old('categorie', $isEdit ? $reclamation->categorie : '') == $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('categorie')<span class="ac-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="ac-group">
                        <label class="ac-label" for="sous_categorie">Sous‑catégorie</label>
                        <div class="ac-sel-wrap">
                            <select name="sous_categorie" id="sous_categorie" class="ac-select @error('sous_categorie') err @enderror">
                                <option value="">-- Sélectionnez --</option>
                            </select>
                        </div>
                        @error('sous_categorie')<span class="ac-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Linked items containers (produit, specimen, mp) --}}
                <div id="linked_product_container" class="ac-group" style="display:none;">
                    <label class="ac-label" for="produit_id">Produit lié</label>
                    <div class="ac-sel-wrap">
                        <select name="produit_id" id="produit_id" class="ac-select">
                            <option value="">-- Sélectionnez --</option>
                            @foreach($produits as $p)
                                <option value="{{ $p->id }}" {{ old('produit_id', $isEdit ? $reclamation->produit_id : '') == $p->id ? 'selected' : '' }}>{{ $p->titre }} ({{ $p->isbn_13 ?? $p->isbn_10 }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="linked_specimen_container" class="ac-group" style="display:none;">
                    <label class="ac-label" for="specimen_id">Spécimen lié (BSS)</label>
                    <div class="ac-sel-wrap">
                        <select name="specimen_id" id="specimen_id" class="ac-select">
                            <option value="">-- Sélectionnez --</option>
                            @foreach($specimens as $s)
                                <option value="{{ $s->id }}" {{ old('specimen_id', $isEdit ? $reclamation->specimen_id : '') == $s->id ? 'selected' : '' }}>{{ $s->numero }} - {{ $s->compte->etablissement }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="linked_mp_container" class="ac-group" style="display:none;">
                    <label class="ac-label" for="mp_id">Matériel pédagogique lié</label>
                    <div class="ac-sel-wrap">
                        <select name="mp_id" id="mp_id" class="ac-select">
                            <option value="">-- Sélectionnez --</option>
                            @foreach($mps as $mp)
                                <option value="{{ $mp->id }}" {{ old('mp_id', $isEdit ? $reclamation->mp_id : '') == $mp->id ? 'selected' : '' }}>{{ $mp->nom }} ({{ $mp->code_article }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- Linked Exam --}}
<div id="linked_exam_container" class="ac-group" style="display:none;">
    <label class="ac-label" for="examen_id">Examen lié</label>
    <div class="ac-sel-wrap">
        <select name="module_id" id="examen_id" class="ac-select">
            <option value="">-- Sélectionnez un examen --</option>
            @foreach($examens as $examen)
                <option value="{{ $examen->id }}" {{ old('module_id', ($isEdit && $reclamation->module_lie == 'examen') ? $reclamation->module_id : '') == $examen->id ? 'selected' : '' }}>
                    {{ $examen->titre }} ({{ $examen->date_examen?->format('d/m/Y') }})
                </option>
            @endforeach
        </select>
    </div>
</div>

{{-- Linked Event --}}
<div id="linked_event_container" class="ac-group" style="display:none;">
    <label class="ac-label" for="event_id">Événement lié</label>
    <div class="ac-sel-wrap">
        <select name="module_id" id="event_id" class="ac-select">
            <option value="">-- Sélectionnez un événement --</option>
            @foreach($events as $event)
                <option value="{{ $event->id }}" {{ old('module_id', ($isEdit && $reclamation->module_lie == 'event') ? $reclamation->module_id : '') == $event->id ? 'selected' : '' }}>
                    {{ $event->nom }} ({{ $event->date_event?->format('d/m/Y') }})
                </option>
            @endforeach
        </select>
    </div>
</div>

<!-- Also add a hidden input to store the type of linked module -->
<input type="hidden" name="module_lie" id="module_lie" value="{{ old('module_lie', $isEdit ? $reclamation->module_lie : '') }}">

                <div class="ac-group">
                    <label class="ac-label" for="description">Description <span class="req">*</span></label>
                    <textarea name="description" id="description" rows="3" class="ac-textarea @error('description') err @enderror" required>{{ old('description', $isEdit ? $reclamation->description : '') }}</textarea>
                    @error('description')<span class="ac-error">{{ $message }}</span>@enderror
                </div>

                {{-- Section 2 : Analyse & réponse (only for edit mode) --}}
                @if($isEdit)
                <div class="ac-sec" style="margin-top:1.5rem;">Traitement</div>
                <div class="ac-row ac-row-2">
                    <div class="ac-group">
                        <label class="ac-label" for="analyse">Analyse</label>
                        <textarea name="analyse" id="analyse" rows="2" class="ac-textarea">{{ old('analyse', $reclamation->analyse) }}</textarea>
                    </div>
                    <div class="ac-group">
                        <label class="ac-label" for="reponse">Réponse</label>
                        <textarea name="reponse" id="reponse" rows="2" class="ac-textarea">{{ old('reponse', $reclamation->reponse) }}</textarea>
                    </div>
                </div>

                <div class="ac-row ac-row-3">
                    <div class="ac-group">
                        <label class="ac-label" for="date_reponse">Date réponse</label>
                        <input type="date" name="date_reponse" id="date_reponse" class="ac-input" value="{{ old('date_reponse', $reclamation->date_reponse ? $reclamation->date_reponse->format('Y-m-d') : '') }}">
                    </div>
                    <div class="ac-group">
                        <label class="ac-label" for="responsable_id">Responsable</label>
                        <div class="ac-sel-wrap">
                            <select name="responsable_id" id="responsable_id" class="ac-select">
                                <option value="">-- Sélectionnez --</option>
                                @foreach(\App\Models\User::whereIn('role', ['admin','rbo'])->get() as $u)
                                    <option value="{{ $u->id }}" {{ old('responsable_id', $reclamation->responsable_id) == $u->id ? 'selected' : '' }}>{{ $u->prenom }} {{ $u->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="ac-group">
                        <label class="ac-label" for="statut">Statut <span class="req">*</span></label>
                        <div class="ac-sel-wrap">
                            <select name="statut" id="statut" class="ac-select @error('statut') err @enderror" required>
                                @foreach($statuts as $s)
                                    <option value="{{ $s }}" {{ old('statut', $reclamation->statut) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('statut')<span class="ac-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="ac-row ac-row-4">
                    <div class="ac-group">
                        <label class="ac-label" for="date_cloture">Date clôture</label>
                        <input type="date" name="date_cloture" id="date_cloture" class="ac-input" value="{{ old('date_cloture', $reclamation->date_cloture ? $reclamation->date_cloture->format('Y-m-d') : '') }}">
                    </div>
                    <div class="ac-group">
                        <label class="ac-check-row" style="padding:.7rem 1rem;background:var(--subtle);border:1px solid var(--border);border-radius:var(--r2);">
                            <input type="checkbox" name="est_non_conformite" value="1" id="est_non_conformite" {{ old('est_non_conformite', $reclamation->est_non_conformite) ? 'checked' : '' }}>
                            <span>Non‑conformité</span>
                        </label>
                    </div>
                    <div class="ac-group">
                        <label class="ac-check-row" style="padding:.7rem 1rem;background:var(--subtle);border:1px solid var(--border);border-radius:var(--r2);">
                            <input type="checkbox" name="besoin_action_amelioration" value="1" id="besoin_action" {{ old('besoin_action_amelioration', $reclamation->besoin_action_amelioration) ? 'checked' : '' }}>
                            <span>Action d'amélioration</span>
                        </label>
                    </div>
                </div>
                @endif
            </div>

            <div class="ac-footer">
                <a href="{{ route('reclamations.index') }}" class="btn-ac btn-ac-ghost">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Annuler
                </a>
                <button type="submit" class="btn-ac btn-ac-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ $isEdit ? 'Mettre à jour' : 'Créer' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // ── 1. Load contacts when compte changes ──
    const compteSelect = document.getElementById('compte_id');
    const contactSelect = document.getElementById('contact_id');
    const currentContactId = @json(old('contact_id', $isEdit ? $reclamation->contact_id : ''));

    function loadContacts() {
        const compteId = compteSelect.value;
        if (!compteId) {
            contactSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord un compte --</option>';
            return;
        }
        fetch(`/api/comptes/${compteId}/contacts`)
            .then(r => r.json())
            .then(data => {
                let options = '<option value="">-- Sélectionnez un contact --</option>';
                data.forEach(c => options += `<option value="${c.id}">${c.prenom} ${c.nom} (${c.fonction || ''})</option>`);
                contactSelect.innerHTML = options;
                if (currentContactId) contactSelect.value = currentContactId;
            });
    }
    compteSelect.addEventListener('change', loadContacts);
    if (compteSelect.value) loadContacts();

    // ── 2. Load sous‑catégories based on main category ──
    const sousCategoriesMap = @json($sousCategoriesMap);
    const categorieSelect = document.getElementById('categorie');
    const sousSelect = document.getElementById('sous_categorie');

    function updateSousCategories() {
        const cat = categorieSelect.value;
        let options = '<option value="">-- Sélectionnez --</option>';
        if (sousCategoriesMap[cat]) {
            sousCategoriesMap[cat].forEach(sub => options += `<option value="${sub}">${sub}</option>`);
        }
        sousSelect.innerHTML = options;
    }
    categorieSelect.addEventListener('change', updateSousCategories);
    updateSousCategories();

    // ── 3. Linked item selector based on category (categorie) ──
    const productDiv = document.getElementById('linked_product_container');
    const specimenDiv = document.getElementById('linked_specimen_container');
    const mpDiv = document.getElementById('linked_mp_container');

    function updateLinkedSelectorByCategory() {
    const cat = categorieSelect.value;
    // hide all containers
    document.getElementById('linked_product_container').style.display = 'none';
    document.getElementById('linked_specimen_container').style.display = 'none';
    document.getElementById('linked_mp_container').style.display = 'none';
    document.getElementById('linked_exam_container').style.display = 'none';
    document.getElementById('linked_event_container').style.display = 'none';

    // Also set the hidden module_lie value
    const moduleLieInput = document.getElementById('module_lie');
    
    if (cat === 'Produit') {
        document.getElementById('linked_product_container').style.display = 'block';
        moduleLieInput.value = 'product';
    } else if (cat === 'Spécimen') {
        document.getElementById('linked_specimen_container').style.display = 'block';
        moduleLieInput.value = 'specimen';
    } else if (cat === 'Matériel pédagogique') {
        document.getElementById('linked_mp_container').style.display = 'block';
        moduleLieInput.value = 'mp';
    } else if (cat === 'Examen') {
        document.getElementById('linked_exam_container').style.display = 'block';
        moduleLieInput.value = 'examen';
    } else if (cat === 'Événement') {
        document.getElementById('linked_event_container').style.display = 'block';
        moduleLieInput.value = 'event';
    } else {
        moduleLieInput.value = '';
    }
}
    categorieSelect.addEventListener('change', updateLinkedSelectorByCategory);
    updateLinkedSelectorByCategory();
</script>
@endsection
<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\ActionLine;
use App\Models\Compte;
use App\Models\Bss;
use App\Support\YearLock;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Examen;
use App\Models\Retour;
use App\Models\AnneeScolaire;
use App\Models\User;
use App\Models\MpDelivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ActionController extends Controller
{
    private function getDelegateScope($query)
    {
        $user = Auth::user();
        if ($user->role === 'delegue') {
            $query->where('delegue_id', $user->id);
        }
        elseif ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            $query->whereIn('delegue_id', $delegateIds);
        }
        return $query;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Action::with(['compte', 'delegate']);

        if ($user->role !== 'admin') {
            $query = $this->getDelegateScope($query);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('compte_id')) {
            $query->where('compte_id', $request->compte_id);
        }

        $actions = $query->orderBy('date_planification', 'desc')->paginate(15);
        $comptes = Compte::orderBy('etablissement')->get();
        $statuts = ['planifie', 'realise', 'valide', 'annule', 'reporte'];
        $types = ['commercial', 'tache'];

        return view('actions.index', compact('actions', 'comptes', 'statuts', 'types'));
    }

    // Add these properties at the top of the class    
    private $requiresProduct = [
        'Alimentation bibliothèque', 'Livraison Matériel promotionnel', 'Cadeaux personnalisés',
        'Prix / Lots', 'Livres offerts', 'Visite de Prospection – Présentation Produits'    ];    private $requiresBss = [
        'Livraison Spécimens', 'Retour Spécimens', 'Livraison Spécimens – Requêtes Spéciales', 'Livraison MP'    ];    private $requiresRetour = [
        'Retour MP'    ];    private $requiresExamen = [
        'Visite de Prospection – Présentation Examens'    ];
    // In the create method:    
    public function create(Request $request)    {
        $user = Auth::user();
        if ($user->role !== 'delegue')
            abort(403);

        $comptes = Compte::where('delegue_id', $user->id)->with('ville')->get();
        $categories = $this->getCategories();
        $products = Product::orderBy('titre')->get();
        $examens = Examen::orderBy('titre')->get();
        $bssList = Bss::where('delegue_id', $user->id)
            ->whereIn('statut', ['valide', 'livre'])
            ->with('compte')
            ->get();
        $retoursList = Retour::whereHas('bss', fn($q) => $q->where('delegue_id', $user->id))
            ->with('bss.compte')
            ->get();

        $selectedCompteId = request('compte_id');
        if ($selectedCompteId && $comptes->contains('id', $selectedCompteId)) {
            $selectedCompte = $comptes->find($selectedCompteId);
        }

        $requiresProduct = $this->requiresProduct;
        $requiresBss = $this->requiresBss;
        $requiresRetour = $this->requiresRetour;
        $requiresExamen = $this->requiresExamen;


        $defaultDate = $request->get('date_planification', now()->toDateString());
        $prefilledDate = $request->get(
            'date_planification',
            now()->toDateString()
        );

        return view('actions.create', compact(
            'comptes', 'categories', 'products', 'examens', 'bssList', 'retoursList',
            'requiresProduct', 'requiresBss', 'requiresRetour', 'requiresExamen', 'selectedCompteId', 'defaultDate', 'prefilledDate'
        ));
    }

    /**
     * RBO / Admin: show the action creation form for a specific delegate.
     */
    public function createForDelegate(Request $request, User $delegate)
    {
        $this->authorizeForDelegate($delegate);

        $comptes       = Compte::where('delegue_id', $delegate->id)->with('ville')->get();
        $categories    = $this->getCategories();
        $products      = Product::orderBy('titre')->get();
        $examens       = Examen::orderBy('titre')->get();
        $bssList       = Bss::where('delegue_id', $delegate->id)
                            ->whereIn('statut', ['valide', 'livre'])
                            ->with('compte')->get();
        $retoursList   = Retour::whereHas('bss', fn($q) => $q->where('delegue_id', $delegate->id))
                            ->with('bss.compte')->get();

        $requiresProduct = $this->requiresProduct;
        $requiresBss     = $this->requiresBss;
        $requiresRetour  = $this->requiresRetour;
        $requiresExamen  = $this->requiresExamen;

        $selectedCompteId = $request->get('compte_id');
        $prefilledDate    = $request->get('date_planification', now()->toDateString());
        $defaultDate      = $prefilledDate;
        $targetDelegate   = $delegate;

        return view('actions.create', compact(
            'comptes', 'categories', 'products', 'examens', 'bssList', 'retoursList',
            'requiresProduct', 'requiresBss', 'requiresRetour', 'requiresExamen',
            'selectedCompteId', 'defaultDate', 'prefilledDate', 'targetDelegate'
        ));
    }

    /**
     * RBO / Admin: store an action for a specific delegate.
     */
    public function storeForDelegate(Request $request, User $delegate)
    {
        $this->authorizeForDelegate($delegate);

        $rules = [
            'objet'                  => 'required|string|max:255',
            'compte_id'              => 'required|exists:comptes,id',
            'date_planification'     => 'required|date',
            'heure'                  => 'nullable|date_format:H:i',
            'duree'                  => 'nullable|integer|min:0',
            'lieu'                   => 'nullable|string|max:255',
            'rappel'                 => 'nullable|boolean',
            'rappel_avant'           => 'nullable|integer|min:1',
            'recurrence_frequence'   => 'nullable|in:daily,weekly,monthly,yearly',
            'recurrence_intervalle'  => 'nullable|integer|min:1',
            'recurrence_fin'         => 'nullable|date|after_or_equal:date_planification',
            'lines'                  => 'nullable|array',
            'lines.*.categorie'      => 'required_with:lines|string',
            'lines.*.action_type'    => 'required_with:lines|string',
            'lines.*.moyen'          => 'nullable|string',
            'lines.*.description'    => 'nullable|string',
            'lines.*.contact_ids'    => 'nullable|array',
            'lines.*.contact_ids.*'  => 'exists:contacts,id',
            'lines.*.product_ids'    => 'nullable|array',
            'lines.*.product_ids.*'  => 'exists:products,id',
            'lines.*.examen_ids'     => 'nullable|array',
            'lines.*.examen_ids.*'   => 'exists:examens,id',
            'lines.*.bss_id'         => 'nullable|exists:bsses,id',
            'lines.*.retour_id'      => 'nullable|exists:retours,id',
        ];

        $lines = $request->input('lines', []);
        foreach ($lines as $idx => $line) {
            $actionType = $line['action_type'] ?? '';
            if (in_array($actionType, $this->requiresProduct))    $rules["lines.{$idx}.product_ids"] = 'required|array|min:1';
            elseif (in_array($actionType, $this->requiresBss))    $rules["lines.{$idx}.bss_id"]      = 'required|exists:bsses,id';
            elseif (in_array($actionType, $this->requiresRetour)) $rules["lines.{$idx}.retour_id"]   = 'required|exists:retours,id';
            elseif (in_array($actionType, $this->requiresExamen)) $rules["lines.{$idx}.examen_ids"]  = 'required|array|min:1';
        }

        $validated = $request->validate($rules);

        $validated['delegue_id'] = $delegate->id;
        $validated['type']       = $request->type ?? 'commercial';
        $validated['rappel']     = $request->has('rappel');
        $validated['statut']     = 'planifie';

        $actionsToCreate = $this->generateRecurrence($validated);
        $createdIds = [];

        DB::transaction(function () use ($actionsToCreate, &$createdIds) {
            foreach ($actionsToCreate as $actionData) {
                $action = Action::create($actionData);
                $createdIds[] = $action->id;
                if (!empty($actionData['lines'])) {
                    foreach ($actionData['lines'] as $lineData) {
                        $line = $action->lignes()->create([
                            'categorie'   => $lineData['categorie'],
                            'action_type' => $lineData['action_type'],
                            'moyen'       => $lineData['moyen'],
                            'description' => $lineData['description'],
                            'bss_id'      => $lineData['bss_id'] ?? null,
                            'retour_id'   => $lineData['retour_id'] ?? null,
                        ]);
                        if (!empty($lineData['contact_ids'])) $line->contacts()->sync($lineData['contact_ids']);
                        if (!empty($lineData['product_ids'])) $line->products()->sync($lineData['product_ids']);
                        if (!empty($lineData['examen_ids']))  $line->examens()->sync($lineData['examen_ids']);
                    }
                }
            }
        });

        return redirect()->route('actions.index')
            ->with('success', count($createdIds) . ' action(s) créée(s) pour ' . $delegate->prenom . ' ' . $delegate->nom . '.');
    }

    /**
     * Ensure the authenticated user can act on behalf of $delegate.
     * Admin: always. RBO: only for their supervised delegates.
     */
    private function authorizeForDelegate(User $delegate): void
    {
        $user = Auth::user();
        if ($user->role === 'admin') return;
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($delegate->id)) return;
        }
        abort(403, 'Vous n\'êtes pas autorisé à créer des actions pour ce délégué.');
    }

    public function store(Request $request)    {
        $user = Auth::user();
        if ($user->role !== 'delegue')
            abort(403);

        // Base validation rules
        $rules = [
            'objet' => 'required|string|max:255',
            'compte_id' => 'required|exists:comptes,id',
            'date_planification' => 'required|date',
            'heure' => 'nullable|date_format:H:i',
            'duree' => 'nullable|integer|min:0',
            'lieu' => 'nullable|string|max:255',
            'rappel' => 'nullable|boolean',
            'rappel_avant' => 'nullable|integer|min:1',
            'recurrence_frequence' => 'nullable|in:daily,weekly,monthly,yearly',
            'recurrence_intervalle' => 'nullable|integer|min:1',
            'recurrence_fin' => 'nullable|date|after_or_equal:date_planification',
            'lines' => 'nullable|array',
            'lines.*.categorie' => 'required_with:lines|string',
            'lines.*.action_type' => 'required_with:lines|string',
            'lines.*.moyen' => 'nullable|string',
            'lines.*.description' => 'nullable|string',
            'lines.*.contact_ids' => 'nullable|array',
            'lines.*.contact_ids.*' => 'exists:contacts,id',
            'lines.*.product_ids' => 'nullable|array',
            'lines.*.product_ids.*' => 'exists:products,id',
            'lines.*.examen_ids' => 'nullable|array',
            'lines.*.examen_ids.*' => 'exists:examens,id',
            'lines.*.bss_id' => 'nullable|exists:bsses,id',
            'lines.*.retour_id' => 'nullable|exists:retours,id',
        ];

        // Add conditional requirements per line based on action type
        $lines = $request->input('lines', []);
        foreach ($lines as $idx => $line) {
            $actionType = $line['action_type'] ?? '';
            if (in_array($actionType, $this->requiresProduct)) {
                $rules["lines.{$idx}.product_ids"] = 'required|array|min:1';
            }
            elseif (in_array($actionType, $this->requiresBss)) {
                $rules["lines.{$idx}.bss_id"] = 'required|exists:bsses,id';
            }
            elseif (in_array($actionType, $this->requiresRetour)) {
                $rules["lines.{$idx}.retour_id"] = 'required|exists:retours,id';
            }
            elseif (in_array($actionType, $this->requiresExamen)) {
                $rules["lines.{$idx}.examen_ids"] = 'required|array|min:1';
            }
        }

        $validated = $request->validate($rules);

        $validated['delegue_id'] = $user->id;
        $validated['type'] = $request->type ?? 'commercial';
        $validated['rappel'] = $request->has('rappel');
        $validated['statut'] = 'planifie';

        // Handle recurrence
        $actionsToCreate = $this->generateRecurrence($validated);
        $createdIds = [];

        DB::transaction(function () use ($actionsToCreate, &$createdIds) {
            foreach ($actionsToCreate as $actionData) {
                $action = Action::create($actionData);
                $createdIds[] = $action->id;
                if (!empty($actionData['lines'])) {
                    foreach ($actionData['lines'] as $lineData) {
                        $line = $action->lignes()->create([
                            'categorie' => $lineData['categorie'],
                            'action_type' => $lineData['action_type'],
                            'moyen' => $lineData['moyen'],
                            'description' => $lineData['description'],
                            'bss_id' => $lineData['bss_id'] ?? null,
                            'retour_id' => $lineData['retour_id'] ?? null,
                        ]);
                        if (!empty($lineData['contact_ids'])) {
                            $line->contacts()->sync($lineData['contact_ids']);
                        }
                        if (!empty($lineData['product_ids'])) {
                            $line->products()->sync($lineData['product_ids']);
                        }
                        if (!empty($lineData['examen_ids'])) {
                            $line->examens()->sync($lineData['examen_ids']);
                        }
                    }
                }
            }
        });

        return redirect()->route('actions.index')->with('success', count($createdIds) . ' action(s) créée(s).');    }

    private function generateRecurrence($data)    {
        $actions = [];
        $start = Carbon::parse($data['date_planification']);
        $end = $data['recurrence_fin'] ?Carbon::parse($data['recurrence_fin']) : $start;
        $freq = $data['recurrence_frequence'] ?? null;
        $interval = $data['recurrence_intervalle'] ?? 1;

        if (!$freq) {
            // Single occurrence
            $actions[] = $this->buildActionData($data);
            return $actions;
        }

        // Map frequency to Carbon unit
        $unitMap = [
            'daily' => 'days',
            'weekly' => 'weeks',
            'monthly' => 'months',
            'yearly' => 'years',
        ];
        $unit = $unitMap[$freq] ?? 'days';

        // Generate occurrences
        $current = $start->copy();
        while ($current <= $end) {
            $actionData = $this->buildActionData($data);
            $actionData['date_planification'] = $current->toDateString();
            $actions[] = $actionData;

            $current->add($interval, $unit);
        }

        return $actions;    }

    private function buildActionData($data)
    {
        return [
            'objet' => $data['objet'],
            'compte_id' => $data['compte_id'],
            'delegue_id' => $data['delegue_id'],
            'date_planification' => $data['date_planification'],
            'heure' => $data['heure'] ?? null,
            'duree' => $data['duree'] ?? null,
            'lieu' => $data['lieu'] ?? null,
            'rappel' => $data['rappel'] ?? false,
            'rappel_avant' => $data['rappel_avant'] ?? null,
            'recurrence_frequence' => $data['recurrence_frequence'] ?? null,
            'recurrence_intervalle' => $data['recurrence_intervalle'] ?? null,
            'recurrence_fin' => $data['recurrence_fin'] ?? null,
            'parent_action_id' => null,
            'statut' => 'planifie',
            'type' => $data['type'] ?? 'commercial',
            'module_lie' => $data['module_lie'] ?? null,
            'module_id' => $data['module_id'] ?? null,
            'lines' => $data['lines'] ?? [],
        ];
    }

    public function show(Action $action)
    {
        $this->authorizeView($action);
        $action->load('lignes.contacts', 'lignes.products', 'lignes.examens', 'lignes.bss', 'lignes.retour', 'compte', 'delegate');

        $mpDelivery = null;
        if ($action->module_lie === 'mp_delivery' && $action->module_id) {
            $mpDelivery = MpDelivery::with(['mpProduct', 'anneeScolaire'])->find($action->module_id);
        }

        $user = Auth::user();
        $delegateIds = $user->role === 'rbo'
            ? $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique()
            : collect();

        $canValiderLegacy = $action->statut === 'realise'
            && in_array($user->role, ['admin', 'rbo'], true)
            && ($user->role === 'admin' || $delegateIds->contains($action->delegue_id));

        $canDevalider = $action->statut === 'valide'
            && in_array($user->role, ['admin', 'rbo'], true)
            && ($user->role === 'admin' || $delegateIds->contains($action->delegue_id));

        return view('actions.show', compact('action', 'canValiderLegacy', 'canDevalider', 'mpDelivery'));
    }

    public function edit(Action $action)
    {

        $this->authorizeEdit($action);
        $user = Auth::user();
        $comptes = Compte::where('delegue_id', $user->id)->with('ville')->get();
        $categories = $this->getCategories();
        $action->load('lignes.contacts', 'lignes.products', 'lignes.examens');

        $products = Product::orderBy('titre')->get();
        $examens = Examen::orderBy('titre')->get();
        $bssOptions = Bss::where('delegue_id', $user->id)
            ->whereIn('statut', ['valide', 'livre'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($b) => ['id' => $b->id, 'label' => $b->numero . ' - ' . $b->compte->etablissement]);
        return view('actions.edit', compact('action', 'comptes', 'categories', 'products', 'examens', 'bssOptions'));
    }

    public function update(Request $request, Action $action)
    {
        YearLock::check($action);
        $this->authorizeEdit($action);
        // Similar validation as store, but without recurrence generation.
        // We'll update the action header and lines (replace lines).
        $validated = $request->validate([
            'objet' => 'required|string|max:255',
            'compte_id' => 'required|exists:comptes,id',
            'date_planification' => 'required|date',
            'heure' => 'nullable|date_format:H:i',
            'duree' => 'nullable|integer|min:0',
            'lieu' => 'nullable|string|max:255',
            'rappel' => 'nullable|boolean',
            'rappel_avant' => 'nullable|integer|min:1',
            'lines' => 'nullable|array',
            // ... same line validation
        ]);

        $action->update($validated);
        // Update lines: delete old and create new
        $action->lignes()->delete();
        if (!empty($validated['lines'])) {
            foreach ($validated['lines'] as $lineData) {
                $line = $action->lignes()->create([
                    'categorie' => $lineData['categorie'],
                    'action_type' => $lineData['action_type'],
                    'moyen' => $lineData['moyen'],
                    'description' => $lineData['description'],
                ]);
                if (!empty($lineData['contact_ids'])) {
                    $line->contacts()->sync($lineData['contact_ids']);
                }
                if (!empty($lineData['product_ids'])) {
                    $line->products()->sync($lineData['product_ids']);
                }
                if (!empty($lineData['examen_ids'])) {
                    $line->examens()->sync($lineData['examen_ids']);
                }
            }
        }

        return redirect()->route('actions.show', $action)->with('success', 'Action mise à jour.');
    }

    public function destroy(Action $action)
    {
        YearLock::check($action);
        $this->authorizeEdit($action);
        $action->delete();
        return redirect()->route('actions.index')->with('success', 'Action supprimée.');
    }

    // Status workflow — only the owning délégué can complete (with mandatory report → validated).
    public function realiser(Request $request, Action $action)
    {
        $user = Auth::user();
        if ($user->role !== 'delegue' || (int)$action->delegue_id !== (int)$user->id) {
            abort(403);
        }
        YearLock::check($action);
        $this->authorizeEdit($action);
        if ($action->statut !== 'planifie') {
            return redirect()->back()->with('error', 'Seules les actions planifiées peuvent être clôturées avec un rapport.');
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'rapport_titre' => 'required|string|max:255',
            'rapport_description' => 'required|string|max:10000',
            'rapport_date' => 'required|date',
        ]);
        if ($validator->fails()) {
            return redirect()->to(route('actions.show', $action) . '?realiser=1')
                ->withErrors($validator)
                ->withInput();
        }
        $validated = $validator->validated();

        $mp = null;
        if ($action->module_lie === 'mp_delivery' && $action->module_id) {
            $mp = MpDelivery::with('anneeScolaire')->find($action->module_id);
            if (! $mp) {
                return redirect()->back()->with('error', 'Livraison MP introuvable.');
            }
            YearLock::check($mp);
            if ($mp->statut !== 'planifie') {
                return redirect()->back()->with('error', 'Cette livraison MP est déjà marquée comme livrée.');
            }
        }

        DB::transaction(function () use ($action, $validated, $user, $mp) {
            if ($mp) {
                $mp->update(['statut' => 'livre']);
            }
            $action->update([
                'statut' => 'valide',
                'rapport_titre' => $validated['rapport_titre'],
                'rapport_description' => $validated['rapport_description'],
                'rapport_date' => $validated['rapport_date'],
                'date_realisation' => now(),
                'date_validation' => now(),
                'valide_par' => $user->id,
            ]);
        });

        $msg = $mp
            ? 'Rapport enregistré : action validée et la livraison MP est marquée comme livrée.'
            : 'Rapport enregistré : action réalisée et validée.';

        return redirect()->route('actions.show', $action)->with('success', $msg);
    }

    public function valider(Action $action)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'rbo'])) {
            abort(403);
        }
        $this->authorizeRboOrAdminForDelegateAction($action);
        if ($action->statut !== 'realise') {
            return redirect()->back()->with('error', 'Seules les actions réalisées (ancien flux) peuvent être validées.');
        }
        $action->update([
            'statut' => 'valide',
            'date_validation' => now(),
            'valide_par' => $user->id,
        ]);

        return redirect()->route('actions.show', $action)->with('success', 'Action validée.');
    }

    /**
     * Remet une action validée en planification pour permettre au délégué de la modifier,
     * après saisie d’un nouveau rapport via « Réaliser ».
     */
    public function devalider(Action $action)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'rbo'])) {
            abort(403);
        }
        $this->authorizeRboOrAdminForDelegateAction($action);
        if ($action->statut !== 'valide') {
            return redirect()->back()->with('error', 'Seules les actions validées peuvent être dévalidées.');
        }

        $mp = null;
        if ($action->module_lie === 'mp_delivery' && $action->module_id) {
            $mp = MpDelivery::with('anneeScolaire')->find($action->module_id);
            if ($mp) {
                YearLock::check($mp);
            }
        }

        DB::transaction(function () use ($action, $mp) {
            if ($mp && $mp->statut === 'livre') {
                $mp->update(['statut' => 'planifie']);
            }
            $action->update([
                'statut' => 'planifie',
                'rapport_titre' => null,
                'rapport_description' => null,
                'rapport_date' => null,
                'date_realisation' => null,
                'date_validation' => null,
                'valide_par' => null,
            ]);
        });

        return redirect()->route('actions.show', $action)->with('success', 'Action dévalidée : le délégué peut à nouveau la modifier et soumettre un rapport.');
    }

    public function annuler(Action $action)
    {
        $isMp = $action->module_lie === 'mp_delivery';
        $this->authorizeEdit($action);

        if (! in_array($action->statut, ['planifie', 'realise'])) {
            return redirect()->back()->with('error', 'Action non annulable.');
        }
        if ($isMp && $action->module_id) {
            $mp = MpDelivery::find($action->module_id);
            if ($mp && $mp->statut === 'livre') {
                return redirect()->back()->with('error', 'Impossible d\'annuler : la livraison MP est déjà marquée comme livrée.');
            }
        }

        $action->update([
            'statut' => 'annule',
            'date_realisation' => null,
            'date_validation' => null,
            'valide_par' => null,
            'rapport_titre' => null,
            'rapport_description' => null,
            'rapport_date' => null,
        ]);

        return redirect()->route('actions.show', $action)->with('success', 'Action annulée.');
    }

    public function reporter(Request $request, Action $action)
    {
        $this->authorizeEdit($action);
        $request->validate([
            'nouvelle_date' => 'required|date|after:today',
            'nouvelle_heure' => 'nullable|date_format:H:i',
        ]);
        // Create a new action with later date, linked as child
        $newAction = $action->replicate();
        $newAction->date_planification = $request->nouvelle_date;
        $newAction->heure = $request->nouvelle_heure;
        $newAction->parent_action_id = $action->id;
        $newAction->statut = 'planifie';
        $newAction->date_realisation = null;
        $newAction->date_validation = null;
        $newAction->valide_par = null;
        $newAction->rapport_titre = null;
        $newAction->rapport_description = null;
        $newAction->rapport_date = null;
        $newAction->save();
        // Copy lines
        foreach ($action->lignes as $line) {
            $newLine = $line->replicate();
            $newLine->action_id = $newAction->id;
            $newLine->save();
            $newLine->contacts()->sync($line->contacts->pluck('id'));
            $newLine->products()->sync($line->products->pluck('id'));
            $newLine->examens()->sync($line->examens->pluck('id'));
        }
        // Mark original as reporte
        $action->update(['statut' => 'reporte']);
        return redirect()->route('actions.show', $newAction)->with('success', 'Action reportée à une nouvelle date.');
    }

    // API for cascading dropdowns
    public function getActionTypesByCategorie(Request $request)
    {
        $categorie = $request->categorie;
        $actionTypes = $this->getActionTypesForCategory($categorie);
        return response()->json($actionTypes);
    }

    public function getMoyensByActionType(Request $request)
    {
        $actionType = $request->action_type;
        $moyens = $this->getMoyensForActionType($actionType);
        return response()->json($moyens);
    }

    // Helpers
    private function getCategories()
    {
        return ['Visite', 'Action Marketing', 'Correspondance', 'Action Promotion'];
    }

    private function getActionTypesForCategory($category)
    {
        $map = [
            'Visite' => [
                'Visite de courtoisie',
                'Visite de Prospection – Renseignements & Prise de contact',
                'Visite de Prospection – Présentation Produits',
                'Visite de Prospection – Présentation Examens',
                'Livraison Spécimens',
                'Retour Spécimens',
                'Livraison Spécimens – Requêtes Spéciales',
                'Livraison MP',
                'Retour MP',
                'Livraison Commande',
                'Retour Commande',
                'Recouvrement',
            ],
            'Action Marketing' => [
                'Livraison Matériel promotionnel',
                'Livres offerts',
                'Prix / Lots',
                'Cadeaux personnalisés',
            ],
            'Correspondance' => [
                'Appel téléphonique',
                'Courrier',
                'E-mail',
                'SMS',
                'Fax',
            ],
            'Action Promotion' => [
                'Visite de Prospection – Renseignements & Prise de contact',
                'Visite de Prospection – Présentation Produits',
                'Visite de Prospection – Présentation Examens',
            ],
        ];
        return $map[$category] ?? [];
    }

    private function getMoyensForActionType($actionType)
    {
        $map = [
            'Visite de courtoisie' => ['Visite'],
            'Livraison Matériel promotionnel' => ['Visite'],
            'Livres offerts' => ['Visite'],
            'Prix / Lots' => ['Visite'],
            'Cadeaux personnalisés' => ['Visite'],
            'Visite de Prospection – Renseignements & Prise de contact' => ['Visite', 'Appel téléphonique', 'Courrier', 'E-mail', 'SMS', 'Fax'],
            'Visite de Prospection – Présentation Produits' => ['Visite', 'Appel téléphonique', 'Courrier', 'E-mail', 'SMS', 'Fax'],
            'Visite de Prospection – Présentation Examens' => ['Visite', 'Appel téléphonique', 'Courrier', 'E-mail', 'SMS', 'Fax'],
            'Livraison Spécimens' => ['Visite'],
            'Retour Spécimens' => ['Visite'],
            'Livraison Spécimens – Requêtes Spéciales' => ['Visite'],
            'Livraison MP' => ['Visite'],
            'Retour MP' => ['Visite'],
            'Livraison Commande' => ['Visite'],
            'Retour Commande' => ['Visite'],
            'Recouvrement' => ['Visite'],
            'Appel téléphonique' => ['Téléphone'],
            'Courrier' => ['Courrier'],
            'E-mail' => ['Email'],
            'SMS' => ['SMS'],
            'Fax' => ['Fax'],
        ];
        return $map[$actionType] ?? ['Visite'];
    }

    private function authorizeView(Action $action)
    {
        $user = Auth::user();
        if ($user->role === 'admin')
            return;
        if ($user->role === 'delegue' && $action->delegue_id === $user->id)
            return;
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($action->delegue_id))
                return;
        }
        abort(403);
    }

    private function authorizeEdit(Action $action)
    {
        $user = Auth::user();
        if ($action->statut === 'valide') {
            abort(403, 'Cette action est validée. Un administrateur ou un RBO doit d’abord la dévalider.');
        }
        if ($user->role === 'admin') {
            return;
        }
        if ($user->role === 'delegue' && $action->delegue_id === $user->id && in_array($action->statut, ['planifie', 'reporte'])) {
            return;
        }
        abort(403);
    }

    private function authorizeRboOrAdminForDelegateAction(Action $action): void
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return;
        }
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id')->unique();
            if ($delegateIds->contains($action->delegue_id)) {
                return;
            }
        }
        abort(403);
    }












}
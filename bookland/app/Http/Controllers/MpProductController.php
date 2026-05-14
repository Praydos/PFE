<?php

namespace App\Http\Controllers;

use App\Models\MpProduct;
use Illuminate\Http\Request;

class MpProductController extends Controller
{
    public function index(Request $request)
    {
        $query = MpProduct::query()->withCount('deliveries')->orderBy('nom');

        if ($request->filled('search')) {
            $s = $request->get('search');
            $query->where(function ($q) use ($s) {
                $q->where('nom', 'like', '%'.$s.'%')
                    ->orWhere('code_article', 'like', '%'.$s.'%')
                    ->orWhere('editeur', 'like', '%'.$s.'%');
            });
        }

        $products = $query->paginate(20)->withQueryString();

        return view('mp_products.index', compact('products'));
    }

    public function create()
    {
        return view('mp_products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_article' => 'required|string|max:100|unique:mp_products,code_article',
            'editeur' => 'required|in:Bookland,Esprit du livre',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        MpProduct::create($validated);

        return redirect()->route('mp-products.index')
            ->with('success', 'Article MP créé.');
    }

    public function edit(MpProduct $mp_product)
    {
        return view('mp_products.edit', ['product' => $mp_product]);
    }

    public function update(Request $request, MpProduct $mp_product)
    {
        $validated = $request->validate([
            'code_article' => 'required|string|max:100|unique:mp_products,code_article,'.$mp_product->id,
            'editeur' => 'required|in:Bookland,Esprit du livre',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $mp_product->update($validated);

        return redirect()->route('mp-products.index')
            ->with('success', 'Article MP mis à jour.');
    }

    public function destroy(MpProduct $mp_product)
    {
        if ($mp_product->deliveries()->exists()) {
            return redirect()->route('mp-products.index')
                ->with('error', 'Impossible de supprimer cet article : des livraisons MP y sont liées.');
        }

        $mp_product->delete();

        return redirect()->route('mp-products.index')
            ->with('success', 'Article MP supprimé.');
    }
}

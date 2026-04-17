<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('isbn_13', 'like', "%{$search}%")
                  ->orWhere('isbn_10', 'like', "%{$search}%")
                  ->orWhere('auteur', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('titre')->paginate(15);
        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'source' => 'required|in:bookland,esprit_du_livre',
            'isbn_13' => 'nullable|string|unique:products,isbn_13',
            'isbn_10' => 'nullable|string|unique:products,isbn_10',
            'reference_interne' => 'nullable|string',
            'titre' => 'required|string|max:255',
            'sous_titre' => 'nullable|string|max:255',
            'niveau' => 'nullable|string|max:255',
            'type' => 'required|string|max:255',
            'edition' => 'nullable|string|max:255',
            'auteur' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'langue' => 'nullable|string|max:255',
            'rayon' => 'nullable|string|max:255',
            'sous_rayon' => 'nullable|string|max:255',
            'categorie' => 'nullable|string|max:255',
            'sous_categorie' => 'nullable|string|max:255',
            'editeur' => 'nullable|string|max:255',
            'collection' => 'nullable|string|max:255',
            'support' => 'nullable|string|max:255',
            'nbr_pages' => 'nullable|integer|min:0',
            'prix' => 'nullable|numeric|min:0',
            'date_parution' => 'nullable|date',
            'image' => 'nullable|string|max:255',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'source' => 'required|in:bookland,esprit_du_livre',
            'isbn_13' => 'nullable|string|unique:products,isbn_13,' . $product->id,
            'isbn_10' => 'nullable|string|unique:products,isbn_10,' . $product->id,
            'reference_interne' => 'nullable|string',
            'titre' => 'required|string|max:255',
            'sous_titre' => 'nullable|string|max:255',
            'niveau' => 'nullable|string|max:255',
            'type' => 'required|string|max:255',
            'edition' => 'nullable|string|max:255',
            'auteur' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'langue' => 'nullable|string|max:255',
            'rayon' => 'nullable|string|max:255',
            'sous_rayon' => 'nullable|string|max:255',
            'categorie' => 'nullable|string|max:255',
            'sous_categorie' => 'nullable|string|max:255',
            'editeur' => 'nullable|string|max:255',
            'collection' => 'nullable|string|max:255',
            'support' => 'nullable|string|max:255',
            'nbr_pages' => 'nullable|integer|min:0',
            'prix' => 'nullable|numeric|min:0',
            'date_parution' => 'nullable|date',
            'image' => 'nullable|string|max:255',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $product)
    {
        // Later: check if product is used in specimens or adoptions before deletion
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produit supprimé.');
    }
}
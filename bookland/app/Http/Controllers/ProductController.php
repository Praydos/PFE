<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            if (empty($rows) || count($rows) < 2) {
                return back()->with('error', 'Le fichier est vide ou ne contient pas de données.');
            }

            $headers = array_shift($rows);
            $headers = array_map('strtolower', $headers);
            $headers = array_map('trim', $headers);

            $importedCount = 0;

            foreach ($rows as $row) {
                if (empty(array_filter($row))) continue; // Skip empty rows

                $data = [];
                foreach ($headers as $index => $header) {
                    $val = trim($row[$index] ?? '');
                    $data[$header] = $val === '' ? null : $val;
                }

                $productData = [
                    'source' => $data['source'] ?? 'bookland',
                    'isbn_13' => $data['isbn_13'] ?? null,
                    'isbn_10' => $data['isbn_10'] ?? null,
                    'reference_interne' => $data['reference_interne'] ?? null,
                    'titre' => $data['titre'] ?? 'Sans Titre',
                    'sous_titre' => $data['sous_titre'] ?? null,
                    'niveau' => $data['niveau'] ?? null,
                    'type' => $data['type'] ?? 'Livre',
                    'edition' => $data['edition'] ?? null,
                    'auteur' => $data['auteur'] ?? null,
                    'description' => $data['description'] ?? null,
                    'langue' => $data['langue'] ?? null,
                    'rayon' => $data['rayon'] ?? null,
                    'sous_rayon' => $data['sous_rayon'] ?? null,
                    'categorie' => $data['categorie'] ?? null,
                    'sous_categorie' => $data['sous_categorie'] ?? null,
                    'editeur' => $data['editeur'] ?? null,
                    'collection' => $data['collection'] ?? null,
                    'support' => $data['support'] ?? null,
                    'nbr_pages' => is_numeric($data['nbr_pages'] ?? null) ? (int)$data['nbr_pages'] : null,
                    'prix' => is_numeric($data['prix'] ?? null) ? (float)$data['prix'] : null,
                    'date_parution' => $data['date_parution'] ?? null,
                    'image' => $data['image'] ?? null,
                ];

                if (!empty($productData['isbn_13'])) {
                    Product::updateOrCreate(
                        ['isbn_13' => $productData['isbn_13']],
                        $productData
                    );
                    $importedCount++;
                } elseif (!empty($productData['reference_interne'])) {
                    Product::updateOrCreate(
                        ['reference_interne' => $productData['reference_interne']],
                        $productData
                    );
                    $importedCount++;
                } elseif (!empty($productData['titre'])) {
                     Product::updateOrCreate(
                        ['titre' => $productData['titre'], 'auteur' => $productData['auteur'] ?? null],
                        $productData
                    );
                    $importedCount++;
                }
            }

            return back()->with('success', "$importedCount produits ont été importés avec succès.");

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'importation: ' . $e->getMessage());
        }
    }
}
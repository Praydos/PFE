<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        if ($request->filled('search')) {
            $query->where('titre', 'like', '%' . $request->search . '%');
        }
        return response()->json($query->latest()->paginate(50));
    }

    public function show(Product $product)
    {
        return response()->json($product);
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:50',
            'auteur' => 'nullable|string|max:255',
            'niveau' => 'nullable|string|max:100',
            'prix' => 'nullable|numeric',
            'matiere' => 'nullable|string|max:100',
            'categorie' => 'nullable|string|max:100',
        ]);
        $product = Product::create($data);
        return response()->json($product, 201);
    }

    public function update(Request $request, Product $product)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);
        $data = $request->validate([
            'titre' => 'sometimes|string|max:255',
            'isbn' => 'nullable|string|max:50',
            'auteur' => 'nullable|string|max:255',
            'niveau' => 'nullable|string|max:100',
            'prix' => 'nullable|numeric',
            'matiere' => 'nullable|string|max:100',
            'categorie' => 'nullable|string|max:100',
        ]);
        $product->update($data);
        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);
        $product->delete();
        return response()->json(['message' => 'Deleted.']);
    }
}

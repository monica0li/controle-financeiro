<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    public function index()
    {
        // MODIFICADO: Buscar apenas do usuário atual
        $categories = Category::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();
            
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                // ADICIONADO: Validação única por usuário
                Rule::unique('categories')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })
            ],
            'active' => 'boolean'
        ]);

        // ADICIONADO: Associar ao usuário atual
        $validated['user_id'] = auth()->id();

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(Category $category)
    {
        // ADICIONADO: Verificar se pertence ao usuário
        if ($category->user_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para editar esta categoria.');
        }
        
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        // ADICIONADO: Verificar se pertence ao usuário
        if ($category->user_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para editar esta categoria.');
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                // ADICIONADO: Validação única por usuário, ignorando o próprio registro
                Rule::unique('categories')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })->ignore($category->id)
            ],
            'active' => 'boolean'
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(Category $category)
    {
        // ADICIONADO: Verificar se pertence ao usuário
        if ($category->user_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para excluir esta categoria.');
        }

        // Em vez de excluir, apenas desativa
        $category->update(['active' => false]);

        return redirect()->route('categories.index')
            ->with('success', 'Categoria desativada com sucesso!');
    }

    public function activate(Category $category)
    {
        // ADICIONADO: Verificar se pertence ao usuário
        if ($category->user_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para reativar esta categoria.');
        }

        $category->update(['active' => true]);

        return redirect()->route('categories.index')
            ->with('success', 'Categoria reativada com sucesso!');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\InvestmentAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestmentAccountController extends Controller
{
    public function index()
    {
        $accounts = InvestmentAccount::where('user_id', Auth::id())
            ->orderBy('name')
            ->get();
            
        return view('investments.index', compact('accounts'));
    }
    
    public function create()
    {
        return view('investments.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'target_amount' => 'nullable|numeric|min:0',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
        ]);
        
        InvestmentAccount::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'target_amount' => $request->target_amount,
            'color' => $request->color ?? '#3b82f6',
            'icon' => $request->icon ?? 'piggy-bank',
            'current_balance' => 0,
        ]);
        
        return redirect()->route('investments.index')
            ->with('success', 'Conta de investimento criada com sucesso!');
    }
    
    // Outros métodos: show, edit, update, destroy
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\PaymentMethod;

class TransactionController extends Controller
{
    public function index(Request $request)
{
    $query = Transaction::where('user_id', Auth::id())
        ->with('category', 'paymentMethod');

    // Filtros
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    if ($request->filled('start_date')) {
        $query->whereDate('date', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('date', '<=', $request->end_date);
    }

    if ($request->filled('search')) {
        $query->where('description', 'like', '%' . $request->search . '%');
    }

    $transactions = $query
        ->orderBy('date', 'desc')
        ->paginate(10)
        ->withQueryString(); // mantém filtros ao paginar

    $totalEntradas = Transaction::where('user_id', Auth::id())
        ->where('type', 'entrada')
        ->sum('amount');

    $totalSaidas = Transaction::where('user_id', Auth::id())
        ->where('type', 'saida')
        ->sum('amount');

    $saldo = $totalEntradas - $totalSaidas;

    $categories = Category::orderBy('name')->get();

    $paymentMethods = PaymentMethod::orderBy('name')->get();


    return view('transactions.index', compact(
        'transactions',
        'totalEntradas',
        'totalSaidas',
        'saldo',
        'categories',
        'paymentMethods'
    ));
}


    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $paymentMethods = PaymentMethod::where('active', true)->orderBy('name')->get();
    
        return view('transactions.create', compact(
            'categories',
            'paymentMethods'
        ));
    }

    public function store(Request $request)
    {
        $amount = str_replace(['R$', '.', ' '], '', $request->amount);
        $amount = str_replace(',', '.', $amount);

        $request->merge([
            'amount' => $amount
        ]);

        $request->validate([
            'type' => 'required|in:entrada,saida',
            'category_id' => 'required|exists:categories,id|integer',
            'amount' => 'required|numeric|min:0.01|max:1000000',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        Transaction::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        return redirect()->route('transactions.index');
    }

}

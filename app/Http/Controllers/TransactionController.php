<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::where('user_id', Auth::id())
            ->with('category', 'paymentMethod');

        // Filtros
        // Depois de todos os outros filtros existentes (categoria, data, busca, etc.)
        // MAS ANTES do ->paginate()
            
        // Filtro por tipo (maneira mais simples)
        if ($request->filled('type')) {
            if ($request->type === 'investimento') {
                $query->where('is_investment', true);
            } else {
                $query->where('type', $request->type)
                      ->where('is_investment', false);
            }
        } else {
            // Por padrão, mostrar tudo (incluindo investimentos)
            // Ou se quiser não mostrar investimentos por padrão:
            // $query->where('is_investment', false);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('payment_method_id')) {
            $query->where('payment_method_id', $request->payment_method_id);
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
            ->withQueryString();

        $totalEntradas = Transaction::where('user_id', Auth::id())
            ->where('type', 'entrada')
            ->sum('amount');

        $totalSaidas = Transaction::where('user_id', Auth::id())
            ->where('type', 'saida')
            ->sum('amount');

        $totalInvestimentos = Transaction::where('user_id', Auth::id())
            ->where('is_investment', true)
            ->sum('amount');

        // Saídas normais (excluindo investimentos)
        $totalSaidasNormais = Transaction::where('user_id', Auth::id())
            ->where('type', 'saida')
            ->where('is_investment', false)
            ->sum('amount');

        $saldo = $totalEntradas - ($totalSaidasNormais + $totalInvestimentos);

        $categories = Category::orderBy('name')->get();
        $paymentMethods = PaymentMethod::orderBy('name')->get();

        return view('transactions.index', compact(
            'transactions',
            'totalEntradas',
            'totalSaidas', // Mantém o total de todas as saídas (incluindo investimentos)
            'totalSaidasNormais', // Saídas que não são investimentos
            'totalInvestimentos', // ← NOVO
            'saldo',
            'categories',
            'paymentMethods'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $paymentMethods = PaymentMethod::where('active', true)->orderBy('name')->get();
    
        return view('transactions.create', compact(
            'categories',
            'paymentMethods'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Formatar valor de forma segura
        $amount = $this->formatAmount($request->amount);
        
        // Validações básicas
        $validated = $request->validate([
            'type' => 'required|in:entrada,saida,investimento',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'installments' => 'nullable|integer|min:1|max:24',
            'recurring_frequency' => 'nullable|required_if:is_recurring,1|in:mensal,semanal,quinzenal,anual',
            'recurring_until' => 'nullable|date|after_or_equal:date',
        ], [
            'type.required' => 'O tipo é obrigatório',
            'type.in' => 'O tipo deve ser entrada ou saída',
            'category_id.required' => 'A categoria é obrigatória',
            'category_id.exists' => 'Categoria inválida',
            'amount.required' => 'O valor é obrigatório',
            'date.required' => 'A data é obrigatória',
            'date.date' => 'Data inválida',
            'description.max' => 'A descrição não pode ter mais de 255 caracteres',
            'payment_method_id.exists' => 'Forma de pagamento inválida',
            'installments.integer' => 'O número de parcelas deve ser um número inteiro',
            'installments.min' => 'O número mínimo de parcelas é 1',
            'installments.max' => 'O número máximo de parcelas é 24',
            'recurring_frequency.required_if' => 'A frequência é obrigatória para transações recorrentes',
            'recurring_frequency.in' => 'Frequência inválida',
            'recurring_until.date' => 'Data final inválida',
            'recurring_until.after_or_equal' => 'A data final deve ser igual ou posterior à data inicial',
        ]);
        
        // Validar amount manualmente após formatação
        if ($amount <= 0 || $amount > 1000000) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['amount' => 'O valor deve estar entre R$ 0,01 e R$ 1.000.000,00']);
        }

        // Número de parcelas (padrão: 1)
        $installments = $request->installments ?? 1;
        
        // Se for saída parcelada, criar múltiplas transações
        if ($request->type === 'saida' && $installments > 1) {
            $this->createInstallments($request, $installments, $amount);
            $message = "Transação parcelada criada com sucesso! ({$installments} parcelas)";
        } else {
            // Verificar se é investimento
            $isInvestment = $request->type === 'investimento';

            // Para investimentos, o type no banco é 'saida' mas marcamos como investimento
            $type = $isInvestment ? 'saida' : $request->type;

            // Criar transação única
            Transaction::create([
                'user_id' => Auth::id(),
                'category_id' => $request->category_id,
                'payment_method_id' => $request->type === 'saida' || $isInvestment ? $request->payment_method_id : null,
                'type' => $type,
                'amount' => $amount,
                'date' => $request->date,
                'description' => $request->description,
                'installments' => $installments,
                'current_installment' => 1,
                'is_recurring' => $request->has('is_recurring') ? 1 : 0,
                'recurring_frequency' => $request->recurring_frequency,
                'recurring_until' => $request->recurring_until,
                'is_investment' => $isInvestment, // ← NOVO CAMPO
            ]);
            $message = 'Movimentação criada com sucesso!';
        }

        return redirect()->route('transactions.index')
            ->with('success', $message);
    }

    /**
     * Formatar valor monetário
     */
    private function formatAmount($amount)
    {
        // Remover R$ e espaços
        $amount = str_replace(['R$', ' '], '', $amount);
        
        // Se tiver ponto como separador de milhar, remover
        $amount = str_replace('.', '', $amount);
        
        // Substituir vírgula por ponto para decimal
        $amount = str_replace(',', '.', $amount);
        
        // Converter para float
        return floatval($amount);
    }

    /**
     * Criar transações parceladas
     */
    private function createInstallments($request, $installments, $totalAmount)
    {
        $installmentGroupId = Transaction::generateInstallmentGroupId();
        $installmentAmount = $totalAmount / $installments;

        for ($i = 1; $i <= $installments; $i++) {
            $installmentDate = \Carbon\Carbon::parse($request->date)->addMonths($i - 1);

            Transaction::create([
                'user_id' => Auth::id(),
                'category_id' => $request->category_id,
                'payment_method_id' => $request->payment_method_id,
                'type' => 'saida',
                'amount' => $installmentAmount,
                'date' => $installmentDate->format('Y-m-d'),
                'description' => $request->description . " ({$i}/{$installments})",
                'installments' => $installments,
                'current_installment' => $i,
                'installment_group_id' => $installmentGroupId,
                'is_recurring' => $request->has('is_recurring') ? 1 : 0,
                'recurring_frequency' => $request->recurring_frequency,
                'recurring_until' => $request->recurring_until,
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
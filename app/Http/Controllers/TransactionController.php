<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Obter dados do dashboard mensal
        $monthlyData = $this->getMonthlyDashboardData($request);
        
        // Query BASE (sem paginação) para os totais e contagens GERAIS
        $baseQuery = Transaction::where('user_id', Auth::id());

        // Aplicar os mesmos filtros na query base
        if ($request->filled('type')) {
            if ($request->type === 'investimento') {
                $baseQuery->where('is_investment', true);
            } else {
                $baseQuery->where('type', $request->type)
                          ->where('is_investment', false);
            }
        }

        if ($request->filled('category_id')) {
            $baseQuery->where('category_id', $request->category_id);
        }

        if ($request->filled('payment_method_id')) {
            $baseQuery->where('payment_method_id', $request->payment_method_id);
        }

        if ($request->filled('start_date')) {
            $baseQuery->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $baseQuery->whereDate('date', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $baseQuery->where('description', 'like', '%' . $request->search . '%');
        }

        // Query SEPARADA para paginação (com with())
        $paginationQuery = clone $baseQuery;
        
        $transactions = $paginationQuery
            ->with('category', 'paymentMethod')
            ->orderBy('date', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Calcular os totais usando a query BASE (sem paginação) - DADOS GERAIS
        $allTransactions = $baseQuery->get();

        // Calcular totais gerais
        $totalEntradasGeral = $allTransactions->where('type', 'entrada')->sum('amount');
        $totalSaidasGeral = $allTransactions->where('type', 'saida')->sum('amount');
        $totalInvestimentosGeral = $allTransactions->where('is_investment', true)->sum('amount');
        $totalSaidasNormaisGeral = $allTransactions->where('type', 'saida')
                                                   ->where('is_investment', false)
                                                   ->sum('amount');
        
        // Calcular transações parceladas gerais
        $saidasParceladasGeral = $allTransactions->where('type', 'saida')
                                                 ->where('is_investment', false)
                                                 ->where('installments', '>', 1);
        
        $totalSaidasParceladasGeral = $saidasParceladasGeral->sum('amount');
        $saidasParceladasCountGeral = $saidasParceladasGeral->count();

        $saldoGeral = $totalEntradasGeral - ($totalSaidasNormaisGeral + $totalInvestimentosGeral + $totalSaidasParceladasGeral);

        // Contagens gerais
        $entradasCountGeral = $allTransactions->where('type', 'entrada')->count();
        $saidasNormaisCountGeral = $allTransactions->where('type', 'saida')
                                                   ->where('is_investment', false)
                                                   ->count();
        $investCountGeral = $allTransactions->where('is_investment', true)->count();

        $categories = Category::orderBy('name')->get();
        $paymentMethods = PaymentMethod::orderBy('name')->get();

        // Combinar todos os dados para a view
        return view('transactions.index', array_merge(
            $monthlyData, // Dados mensais
            [
                'transactions' => $transactions,
                'totalEntradasGeral' => $totalEntradasGeral,
                'totalSaidasGeral' => $totalSaidasGeral,
                'totalSaidasNormaisGeral' => $totalSaidasNormaisGeral,
                'totalSaidasParceladasGeral' => $totalSaidasParceladasGeral,
                'saidasParceladasCountGeral' => $saidasParceladasCountGeral,
                'totalInvestimentosGeral' => $totalInvestimentosGeral,
                'saldoGeral' => $saldoGeral,
                'categories' => $categories,
                'paymentMethods' => $paymentMethods,
                'entradasCountGeral' => $entradasCountGeral,
                'saidasNormaisCountGeral' => $saidasNormaisCountGeral,
                'investCountGeral' => $investCountGeral,
                'allTransactions' => $allTransactions,
            ]
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('active', true)->orderBy('name')->get();
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
        try {
            DB::beginTransaction();

            // Determinar o tipo baseado nos inputs
            $isInvestment = $request->has('is_investment') && $request->is_investment == '1';

            // IMPORTANTE: A view envia 'type' com valor 'entrada', 'saida' ou 'investimento'
            $originalType = $request->type;

            // Se for investimento, tipo no banco é 'saida', senão mantém o tipo original
            $databaseType = $originalType === 'investimento' ? 'saida' : $originalType;

            // Validações base para todos os tipos
            $validationRules = [
                'type' => 'required|in:entrada,saida,investimento',
                'amount' => 'required',
                'date' => 'required|date',
                'description' => 'nullable|string|max:255',
            ];

            // Validações específicas por tipo
            if ($originalType === 'saida' && !$isInvestment) {
                // Apenas para SAÍDAS NORMAIS (não investimentos) - categoria obrigatória
                $validationRules['category_id'] = 'required|exists:categories,id';
                $validationRules['payment_method_id'] = 'nullable|exists:payment_methods,id';
                $validationRules['installments'] = 'nullable|integer|min:1|max:24';
                $validationRules['is_recurring'] = 'sometimes|boolean';

                // Validações condicionais para recorrente
                if ($request->has('is_recurring') && $request->is_recurring) {
                    $validationRules['recurring_frequency'] = 'required|in:mensal,semanal,quinzenal,anual';
                    $validationRules['recurring_until'] = 'nullable|date|after_or_equal:date';
                }
            } else {
                // Para ENTRADAS e INVESTIMENTOS, categoria e forma de pagamento são opcionais
                $validationRules['category_id'] = 'nullable|exists:categories,id';
                $validationRules['payment_method_id'] = 'nullable|exists:payment_methods,id';
            }

            // Validar os dados
            $validated = $request->validate($validationRules, [
                'type.required' => 'O tipo é obrigatório',
                'type.in' => 'O tipo deve ser entrada, saída ou investimento',
                'category_id.required' => 'A categoria é obrigatória para saídas',
                'category_id.exists' => 'Categoria inválida',
                'amount.required' => 'O valor é obrigatório',
                'date.required' => 'A data é obrigatória',
                'date.date' => 'Data inválida',
                'description.max' => 'A descrição não pode ter mais de 255 caracteres',
                'payment_method_id.exists' => 'Forma de pagamento inválida',
                'installments.integer' => 'O número de parcelas deve ser um número inteiro',
                'installments.min' => 'O número mínimo de parcelas é 1',
                'installments.max' => 'O número máximo de parcelas é 24',
                'recurring_frequency.required' => 'A frequência é obrigatória para transações recorrentes',
                'recurring_frequency.in' => 'Frequência inválida',
                'recurring_until.date' => 'Data final inválida',
                'recurring_until.after_or_equal' => 'A data final deve ser igual ou posterior à data inicial',
            ]);

            // Formatar o valor
            $amount = $this->formatAmount($validated['amount']);

            // Validar valor numérico
            if ($amount <= 0) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['amount' => 'O valor deve ser maior que R$ 0,00']);
            }

            if ($amount > 1000000) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['amount' => 'O valor máximo é R$ 1.000.000,00']);
            }

            // Preparar dados base da transação
            $transactionData = [
                'user_id' => Auth::id(),
                'type' => $databaseType, // Usar o tipo do banco
                'amount' => $amount,
                'date' => $validated['date'],
                'description' => $validated['description'] ?? null,
                'is_investment' => $isInvestment, // true para investimentos, false para outros
            ];

            // Adicionar categoria (pode ser null para entradas/investimentos)
            if ($originalType === 'saida' && !$isInvestment && isset($validated['category_id'])) {
                // Apenas para saídas normais, usar a categoria fornecida
                $transactionData['category_id'] = $validated['category_id'];
            } else {
                // Para entradas e investimentos, definir como null
                $transactionData['category_id'] = null;
            }

            // Adicionar forma de pagamento (pode ser null)
            $transactionData['payment_method_id'] = $validated['payment_method_id'] ?? null;

            // Definir valores padrão para outros campos
            $transactionData['installments'] = $validated['installments'] ?? 1;
            $transactionData['current_installment'] = 1;
            $transactionData['is_recurring'] = 0;
            $transactionData['recurring_frequency'] = null;
            $transactionData['recurring_until'] = null;
            $transactionData['installment_group_id'] = null;

            // Se for saída normal (não investimento), pode ter campos adicionais
            if ($originalType === 'saida' && !$isInvestment) {
                $transactionData['is_recurring'] = $request->has('is_recurring') && $request->is_recurring ? 1 : 0;

                if ($transactionData['is_recurring']) {
                    $transactionData['recurring_frequency'] = $validated['recurring_frequency'] ?? null;
                    $transactionData['recurring_until'] = $validated['recurring_until'] ?? null;
                }
            }

            // Lidar com parcelamento (apenas para saídas normais, não investimentos)
            $installments = $transactionData['installments'];
            $isInstallment = $installments > 1 && $originalType === 'saida' && !$isInvestment;

            if ($isInstallment) {
                // Gerar ID único para o grupo de parcelas
                $group_id = 'INST_' . time() . '_' . Str::random(8);
                $installmentAmount = $amount / $installments;

                for ($i = 1; $i <= $installments; $i++) {
                    $installmentData = $transactionData;
                    $installmentData['amount'] = $installmentAmount;
                    $installmentData['current_installment'] = $i;
                    $installmentData['installment_group_id'] = $group_id;

                    // Calcular data da parcela - manter o dia exato
                    $installmentDate = Carbon::parse($transactionData['date']);
                    if ($i > 1) {
                        $installmentDate->addMonths($i - 1);
                    }
                    $installmentData['date'] = $installmentDate->format('Y-m-d');

                    Transaction::create($installmentData);
                }

                DB::commit();

                return redirect()->route('transactions.index')
                    ->with('success', "Transação parcelada em {$installments}x criada com sucesso!");
            } else {
                // Transação única
                Transaction::create($transactionData);

                DB::commit();

                $message = 'Transação criada com sucesso!';
                if ($isInvestment) {
                    $message = 'Investimento registrado com sucesso!';
                } elseif ($originalType === 'entrada') {
                    $message = 'Entrada registrada com sucesso!';
                } elseif ($transactionData['is_recurring']) {
                    $message = 'Despesa recorrente criada com sucesso!';
                }

                return redirect()->route('transactions.index')
                    ->with('success', $message);
            }

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao criar transação: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
                'user_id' => Auth::id(),
            ]);

            return back()->withInput()
                ->with('error', 'Erro ao criar transação. Por favor, tente novamente.')
                ->withErrors(['system' => $e->getMessage()]);
        }
    }

    /**
     * Formatar valor monetário
     */
    private function formatAmount($amount)
    {
        if (is_numeric($amount)) {
            return floatval($amount);
        }
        
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = Transaction::with('category', 'paymentMethod')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transaction = Transaction::where('user_id', Auth::id())
            ->findOrFail($id);
            
        $categories = Category::where('active', true)->orderBy('name')->get();
        $paymentMethods = PaymentMethod::where('active', true)->orderBy('name')->get();
        
        return view('transactions.edit', compact('transaction', 'categories', 'paymentMethods'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $transaction = Transaction::where('user_id', Auth::id())
            ->findOrFail($id);
            
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:entrada,saida',
            'amount' => 'required|numeric|min:0.01|max:1000000',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
        ]);
        
        $transaction->update($validated);
        
        return redirect()->route('transactions.index')
            ->with('success', 'Transação atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaction = Transaction::where('user_id', Auth::id())
            ->findOrFail($id);
            
        // Se for uma transação parcelada, deletar todas as parcelas do grupo
        if ($transaction->installment_group_id) {
            Transaction::where('installment_group_id', $transaction->installment_group_id)
                ->where('user_id', Auth::id())
                ->delete();
        } else {
            $transaction->delete();
        }
        
        return redirect()->route('transactions.index')
            ->with('success', 'Transação excluída com sucesso!');
    }

    /**
     * Obter dados para o dashboard mensal
     */
    private function getMonthlyDashboardData($request, $month = null, $year = null)
    {
        // Determinar o mês a ser mostrado
        if ($month && $year) {
            $currentMonth = Carbon::create($year, $month, 1)->startOfMonth();
        } else {
            $currentMonth = Carbon::now()->startOfMonth();
        }
        
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        // Calcular meses anterior e próximo
        $previousMonth = $currentMonth->copy()->subMonth();
        $nextMonth = $currentMonth->copy()->addMonth();

        // Query base para o mês selecionado
        $monthlyQuery = Transaction::where('user_id', Auth::id())
            ->whereBetween('date', [$currentMonth, $endOfMonth]);

        // Aplicar filtros se existirem
        if ($request->filled('type')) {
            if ($request->type === 'investimento') {
                $monthlyQuery->where('is_investment', true);
            } else {
                $monthlyQuery->where('type', $request->type)
                    ->where('is_investment', false);
            }
        }

        // Obter todas as transações do mês
        $monthlyTransactions = $monthlyQuery->with('category', 'paymentMethod')->get();

        // Separar as transações
        $entradas = $monthlyTransactions->where('type', 'entrada');
        $saidasNormais = $monthlyTransactions->where('type', 'saida')
            ->where('is_investment', false)
            ->where('installments', 1); // Apenas não parceladas
        $saidasParceladas = $monthlyTransactions->where('type', 'saida')
            ->where('is_investment', false)
            ->where('installments', '>', 1);
        $investimentos = $monthlyTransactions->where('is_investment', true);

        // Calcular totais
        $totalEntradas = $entradas->sum('amount');
        $totalSaidasNormais = $saidasNormais->sum('amount');
        $totalSaidasParceladas = $saidasParceladas->sum('amount');
        $totalInvestimentos = $investimentos->sum('amount');

        // Total de saídas (normais + parceladas)
        $totalSaidas = $totalSaidasNormais + $totalSaidasParceladas;

        // Agrupar gastos por cartão
        $gastosPorCartao = $monthlyTransactions
            ->where('type', 'saida')
            ->where('is_investment', false)
            ->filter(function ($transaction) {
                return $transaction->paymentMethod !== null;
            })
            ->groupBy('payment_method_id')
            ->map(function ($transactions, $paymentMethodId) {
                $first = $transactions->first();
                $paymentMethod = $first->paymentMethod;
                
                return [
                    'name' => $paymentMethod->name ?? 'Desconhecido',
                    'total' => $transactions->sum('amount'),
                    'count' => $transactions->count(),
                    'is_card' => $paymentMethod ? ($paymentMethod->is_card ?? false) : false,
                    'card_type' => $paymentMethod ? ($paymentMethod->card_type ?? null) : null
                ];
            })
            ->sortByDesc('total')
            ->values();

        // Agrupar gastos por categoria
        $gastosPorCategoria = $monthlyTransactions
            ->where('type', 'saida')
            ->where('is_investment', false)
            ->filter(function ($transaction) {
                return $transaction->category !== null;
            })
            ->groupBy('category_id')
            ->map(function ($transactions, $categoryId) {
                $first = $transactions->first();
                $category = $first->category;
                
                return [
                    'name' => $category->name ?? 'Sem Categoria',
                    'total' => $transactions->sum('amount'),
                    'count' => $transactions->count(),
                    'color' => '#' . substr(md5($category->name ?? 'default'), 0, 6)
                ];
            })
            ->sortByDesc('total')
            ->values()
            ->take(10); // Limitar a 10 categorias

        // Agrupar gastos por forma de pagamento
        $gastosPorFormaPagamento = $monthlyTransactions
            ->where('type', 'saida')
            ->where('is_investment', false)
            ->filter(function ($transaction) {
                return $transaction->paymentMethod !== null;
            })
            ->groupBy('payment_method_id')
            ->map(function ($transactions, $paymentMethodId) {
                $first = $transactions->first();
                $paymentMethod = $first->paymentMethod;
                
                // Verificar se temos um paymentMethod válido
                if ($paymentMethod) {
                    $icon = $paymentMethod->is_card ? 'credit-card' : 'currency-dollar';
                    $displayName = $paymentMethod->display_name ?? $paymentMethod->name;
                } else {
                    $icon = 'currency-dollar';
                    $displayName = 'Desconhecido';
                }
                
                return [
                    'name' => $displayName,
                    'total' => $transactions->sum('amount'),
                    'count' => $transactions->count(),
                    'icon' => $icon
                ];
            })
            ->sortByDesc('total')
            ->values();

        return [
            'entradas' => $entradas,
            'saidasNormais' => $saidasNormais,
            'saidasParceladas' => $saidasParceladas,
            'investimentos' => $investimentos,
            'totalEntradas' => $totalEntradas,
            'totalSaidasNormais' => $totalSaidasNormais,
            'totalSaidasParceladas' => $totalSaidasParceladas,
            'totalInvestimentos' => $totalInvestimentos,
            'totalSaidas' => $totalSaidas,
            'saldoMensal' => $totalEntradas - $totalSaidas - $totalInvestimentos,
            'gastosPorCartao' => $gastosPorCartao,
            'gastosPorCategoria' => $gastosPorCategoria,
            'gastosPorFormaPagamento' => $gastosPorFormaPagamento,
            'currentMonth' => $currentMonth,
            'monthName' => $currentMonth->translatedFormat('F Y'),
            'entradasCount' => $entradas->count(),
            'saidasNormaisCount' => $saidasNormais->count(),
            'saidasParceladasCount' => $saidasParceladas->count(),
            'investimentosCount' => $investimentos->count(),
            'selectedMonth' => $currentMonth->month,
            'selectedYear' => $currentMonth->year,
            'previousMonth' => [
                'month' => $previousMonth->month,
                'year' => $previousMonth->year,
                'name' => $previousMonth->translatedFormat('F Y'),
            ],
            'nextMonth' => [
                'month' => $nextMonth->month,
                'year' => $nextMonth->year,
                'name' => $nextMonth->translatedFormat('F Y'),
            ],
        ];
    }
}
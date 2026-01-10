<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200">
                    Dashboard Financeiro
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Gerencie suas finanças mensais e transações
                </p>
            </div>
            <div>
                <a href="{{ route('transactions.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 border border-transparent rounded-lg font-semibold text-white shadow-lg hover:shadow-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nova Transação
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Tabs -->
            <div class="mb-8">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8">
                        <button onclick="switchTab('monthly')" id="tab-monthly"
                            class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200"
                            data-active-classes="border-emerald-500 text-emerald-600 dark:text-emerald-400"
                            data-inactive-classes="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Dashboard Mensal
                        </button>
                        <button onclick="switchTab('transactions')" id="tab-transactions"
                            class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200"
                            data-active-classes="border-emerald-500 text-emerald-600 dark:text-emerald-400"
                            data-inactive-classes="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Todas as Transações
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Conteúdo da Tab Mensal -->
            <div id="tab-monthly-content" class="tab-content">
                <!-- Navegação entre Meses -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg mb-6 border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center justify-between">
                        <!-- Botão Mês Anterior -->
                        <a href="{{ route('transactions.index', ['month' => $previousMonth['month'], 'year' => $previousMonth['year']]) }}"
                            class="flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-900 hover:bg-gray-200 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg font-medium transition-colors duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            {{ $previousMonth['name'] }}
                        </a>
                        
                        <!-- Mês Atual -->
                        <div class="text-center">
                            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                                {{ $monthName }}
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Dashboard Financeiro
                            </p>
                        </div>
                        
                        <!-- Botão Próximo Mês -->
                        <a href="{{ route('transactions.index', ['month' => $nextMonth['month'], 'year' => $nextMonth['year']]) }}"
                            class="flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-900 hover:bg-gray-200 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg font-medium transition-colors duration-300">
                            {{ $nextMonth['name'] }}
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    
                </div>

                <!-- Cards de Resumo Mensal -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Saldo Mensal - SVG muda conforme saldo -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Saldo do Mês</h3>
                            @if($saldoMensal >= 0)
                                <svg class="w-8 h-8 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @else
                                <svg class="w-8 h-8 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </div>
                        <p class="text-3xl font-bold {{ $saldoMensal >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                            R$ {{ number_format($saldoMensal, 2, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                            {{ $saldoMensal >= 0 ? 'Positivo' : 'Negativo' }} este mês
                        </p>
                    </div>

                    <!-- Entradas Mensais -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Entradas</h3>
                            <svg class="w-8 h-8 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                            R$ {{ number_format($totalEntradas, 2, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                            {{ $entradasCount }} {{ $entradasCount == 1 ? 'entrada' : 'entradas' }}
                        </p>
                    </div>

                    <!-- Saídas Totais (Normais + Parcelamentos) -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Saídas Totais</h3>
                            <svg class="w-8 h-8 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-red-600 dark:text-red-400">
                            R$ {{ number_format($totalSaidas, 2, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                            @php
                                $totalSaidasCount = $saidasNormaisCount + $saidasParceladasCount;
                            @endphp
                            {{ $totalSaidasCount }} {{ $totalSaidasCount == 1 ? 'saída' : 'saídas' }}
                        </p>
                    </div>

                    <!-- Parcelamentos -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Parcelamentos</h3>
                            <svg class="w-8 h-8 text-purple-500 dark:text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                            R$ {{ number_format($totalSaidasParceladas, 2, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                            {{ $saidasParceladasCount }} {{ $saidasParceladasCount == 1 ? 'parcela' : 'parcelas' }}
                        </p>
                    </div>
                </div>

                <!-- Cards Detalhados -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Card Entradas -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                    </svg>
                                    Entradas do Mês
                                </h3>
                                <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                                    R$ {{ number_format($totalEntradas, 2, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($entradas->count() > 0)
                                <div class="space-y-4">
                                    @foreach($entradas->take(5) as $entrada)
                                        <div class="flex items-center justify-between p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                                            <div>
                                                <p class="font-medium text-gray-800 dark:text-gray-200">
                                                    {{ $entrada->description }}
                                                </p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($entrada->date)->format('d/m') }}
                                                    @if($entrada->category)
                                                        • {{ $entrada->category->name }}
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                                                + R$ {{ number_format($entrada->amount, 2, ',', '.') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-400">
                                    <p>Nenhuma entrada este mês</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card Saídas Normais -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                    </svg>
                                    Saídas do Mês
                                </h3>
                                <span class="text-lg font-bold text-red-600 dark:text-red-400">
                                    R$ {{ number_format($totalSaidasNormais, 2, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($saidasNormais->count() > 0)
                                <div class="space-y-4">
                                    @foreach($saidasNormais->take(5) as $saida)
                                        <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                            <div>
                                                <p class="font-medium text-gray-800 dark:text-gray-200">
                                                    {{ $saida->description }}
                                                </p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($saida->date)->format('d/m') }}
                                                    • {{ $saida->category->name ?? 'Sem categoria' }}
                                                    @if($saida->paymentMethod)
                                                        • {{ $saida->paymentMethod->name }}
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="text-lg font-bold text-red-600 dark:text-red-400">
                                                - R$ {{ number_format($saida->amount, 2, ',', '.') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-400">
                                    <p>Nenhuma saída este mês</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card Parcelamentos -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 lg:col-span-2">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Parcelamentos do Mês
                                </h3>
                                <span class="text-lg font-bold text-purple-600 dark:text-purple-400">
                                    R$ {{ number_format($totalSaidasParceladas, 2, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($saidasParceladas->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr class="text-left text-xs text-gray-500 dark:text-gray-400 uppercase">
                                                <th class="px-4 py-2">Descrição</th>
                                                <th class="px-4 py-2">Data</th>
                                                <th class="px-4 py-2">Categoria</th>
                                                <th class="px-4 py-2">Cartão</th>
                                                <th class="px-4 py-2">Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($saidasParceladas as $parcela)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                                    <td class="px-4 py-3">
                                                        <ddiv class="flex items-center gap-2">
                                                            <span class="font-medium">{{ $parcela->description }}</span>
                                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                                {{ $parcela->current_installment }}/{{ $parcela->installments }}
                                                            </span>
                                                        </ddiv>
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap">{{ \Carbon\Carbon::parse($parcela->date)->format('d/m/Y') }}</td>
                                                    <td class="px-4 py-3">{{ $parcela->category->name ?? '-' }}</td>
                                                    <td class="px-4 py-3">{{ $parcela->paymentMethod->name ?? '-' }}</td>
                                                    <td class="px-4 py-3 font-bold text-red-600 dark:text-red-400">
                                                        - R$ {{ number_format($parcela->amount, 2, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-400">
                                    <p>Nenhum parcelamento este mês</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Resumos de Gastos -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Gastos por Cartão -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Gastos por Cartão</h3>
                                @php
                                    $totalGastosCartao = $gastosPorCartao->sum('total');
                                @endphp
                                <span class="text-lg font-bold text-red-600 dark:text-red-400">
                                    R$ {{ number_format($totalGastosCartao, 2, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($gastosPorCartao->count() > 0)
                                <div class="space-y-4 mb-4">
                                    @foreach($gastosPorCartao as $cartao)
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 rounded-full mr-3 {{ ($cartao['is_card'] ?? false) ? 'bg-blue-500' : 'bg-gray-500' }}"></div>
                                                <div>
                                                    <p class="font-medium">{{ $cartao['name'] }}</p>
                                                    <p class="text-sm text-gray-500">{{ $cartao['count'] }} {{ $cartao['count'] == 1 ? 'transação' : 'transações' }}</p>
                                                </div>
                                            </div>
                                            <div class="font-bold text-red-600">
                                                R$ {{ number_format($cartao['total'], 2, ',', '.') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-400">
                                    <p>Nenhum gasto em cartão este mês</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Gastos por Categoria -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Gastos por Categoria</h3>
                                @php
                                    $totalGastosCategoria = $gastosPorCategoria->sum('total');
                                @endphp
                                <span class="text-lg font-bold text-red-600 dark:text-red-400">
                                    R$ {{ number_format($totalGastosCategoria, 2, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($gastosPorCategoria->count() > 0)
                                <div class="space-y-4 mb-4">
                                    @foreach($gastosPorCategoria as $categoria)
                                        <div>
                                            <div class="flex justify-between mb-1">
                                                <span class="text-sm font-medium">{{ $categoria['name'] }}</span>
                                                <span class="text-sm font-bold text-red-600">
                                                    R$ {{ number_format($categoria['total'], 2, ',', '.') }}
                                                </span>
                                            </div>
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                @php
                                                    $maxValue = $gastosPorCategoria->max('total');
                                                    $percentage = $maxValue > 0 ? ($categoria['total'] / $maxValue) * 100 : 0;
                                                @endphp
                                                <div class="bg-red-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-400">
                                    <p>Nenhum gasto por categoria este mês</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Gastos por Forma de Pagamento -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Gastos por</h3>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Forma de Pagamento</h3>
                                </div>

                                @php
                                    $totalGastosFormaPagamento = $gastosPorFormaPagamento->sum('total');
                                @endphp
                                <span class="text-lg font-bold text-red-600 dark:text-red-400">
                                    R$ {{ number_format($totalGastosFormaPagamento, 2, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($gastosPorFormaPagamento->count() > 0)
                                <div class="space-y-6 mb-4">
                                    @foreach($gastosPorFormaPagamento as $forma)
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="p-2 bg-gray-100 dark:bg-gray-900 rounded-lg mr-3">
                                                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        @if(isset($forma['icon']) && $forma['icon'] == 'credit-card')
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                        @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        @endif
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="font-medium">{{ $forma['name'] }}</p>
                                                    <p class="text-sm text-gray-500">{{ $forma['count'] }} {{ $forma['count'] == 1 ? 'transação' : 'transações' }}</p>
                                                </div>
                                            </div>
                                            <div class="font-bold text-red-600">
                                                R$ {{ number_format($forma['total'], 2, ',', '.') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-400">
                                    <p>Nenhum gasto registrado este mês</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conteúdo da Tab Transações (Já Existente) -->
            <div id="tab-transactions-content" class="tab-content hidden">
                <!-- Cards de Resumo Geral -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Saldo Geral - SVG muda conforme saldo -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Saldo Geral</h3>
                            @if($saldoGeral >= 0)
                                <svg class="w-8 h-8 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @else
                                <svg class="w-8 h-8 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </div>
                        <p class="text-3xl font-bold {{ $saldoGeral >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                            R$ {{ number_format($saldoGeral, 2, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                            {{ $saldoGeral >= 0 ? 'Positivo' : 'Negativo' }}
                        </p>
                    </div>

                    <!-- Total Entradas Geral -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Entradas</h3>
                            <svg class="w-8 h-8 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                            R$ {{ number_format($totalEntradasGeral, 2, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                            {{ $entradasCountGeral }} {{ $entradasCountGeral == 1 ? 'transação' : 'transações' }}
                        </p>
                    </div>

                    <!-- Total Saídas Geral -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Saídas</h3>
                            <svg class="w-8 h-8 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-red-600 dark:text-red-400">
                            R$ {{ number_format($totalSaidasGeral, 2, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                            {{ $saidasNormaisCountGeral + $allTransactions->where('type', 'saida')->where('installments', '>', 1)->count() }} 
                            {{ ($saidasNormaisCountGeral + $allTransactions->where('type', 'saida')->where('installments', '>', 1)->count()) == 1 ? 'transação' : 'transações' }}
                        </p>
                    </div>

                    <!-- Total Investimentos Geral -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Investimentos</h3>
                            <svg class="w-8 h-8 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                            R$ {{ number_format($totalInvestimentosGeral, 2, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                            {{ $investCountGeral }} {{ $investCountGeral == 1 ? 'transação' : 'transações' }}
                        </p>
                    </div>
                </div>

                <!-- Filtros (MANTIDO IGUAL) -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg mb-8 border border-gray-200 dark:border-gray-700">
                    <form method="GET" action="{{ route('transactions.index') }}" class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5">
                            <!-- Tipo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo</label>
                                <select name="type" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:ring-emerald-400 text-gray-900 dark:text-gray-200">
                                    <option value="">Todos</option>
                                    <option value="entrada" {{ request('type') == 'entrada' ? 'selected' : '' }}>Entradas</option>
                                    <option value="saida" {{ request('type') == 'saida' ? 'selected' : '' }}>Saídas</option>
                                    <option value="investimento" {{ request('type') == 'investimento' ? 'selected' : '' }}>Investimentos</option>
                                </select>
                            </div>

                            <!-- Categoria -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categoria</label>
                                <select name="category_id" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:ring-emerald-400 text-gray-900 dark:text-gray-200">
                                    <option value="">Todas</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Forma de Pagamento -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Forma de Pagamento</label>
                                <select name="payment_method_id" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:ring-emerald-400 text-gray-900 dark:text-gray-200">
                                    <option value="">Todas</option>
                                    @foreach($paymentMethods as $method)
                                        <option value="{{ $method->id }}" {{ request('payment_method_id') == $method->id ? 'selected' : '' }}>
                                            {{ $method->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Período -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">De:</label>
                                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                                        class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:ring-emerald-400 text-gray-900 dark:text-gray-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Até:</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}"
                                        class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:ring-emerald-400 text-gray-900 dark:text-gray-200">
                            </div>
                            <!-- Busca -->
                            <div class="md:col-span-2 lg:col-span-5">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar</label>
                                <div class="flex gap-2">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Busque por descrição..."
                                        class="flex-1 px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:ring-emerald-400 text-gray-900 dark:text-gray-200">
                                    <button type="submit"
                                        class="px-6 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-medium transition-colors duration-300">
                                        Filtrar
                                    </button>
                                    <a href="{{ route('transactions.index') }}"
                                        class="px-6 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium transition-colors duration-300">
                                        Limpar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Tabela de Transações (MANTIDO IGUAL) -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            Transações
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                ({{ $transactions->total() }} registros)
                            </span>
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Descrição
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Data
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Categoria
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Forma de Pagamento
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Recorrente
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Valor
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($transactions as $transaction)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors duration-200">
                                        <!-- Descrição -->
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-gray-200">
                                                <div class="flex items-center gap-2">
                                                    <span>{{ $transaction->description }}</span>
                                                    @if($transaction->installments > 1)
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                        {{ $transaction->current_installment }}/{{ $transaction->installments }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>                                        
                                        </td>

                                        <!-- Data -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-200 font-medium">
                                                {{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}
                                            </div>
                                        </td>

                                        <!-- Categoria -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-200">
                                                @if($transaction->is_investment)
                                                    <span>Investimento</span>
                                                @else
                                                    {{ $transaction->category->name ?? '-' }}
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Forma de Pagamento -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-200">
                                                    {{ $transaction->paymentMethod->name ?? '—' }}
                                            </div>
                                        </td>

                                        <!-- Recorrente -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($transaction->is_recurring && !$transaction->is_investment)
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-blue-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                    <span class="text-xs text-blue-600 dark:text-blue-400">
                                                        @switch($transaction->recurring_frequency)
                                                            @case('mensal')
                                                                Mensal
                                                                @break
                                                            @case('semanal')
                                                                Semanal
                                                                @break
                                                            @case('quinzenal')
                                                                Quinzenal
                                                                @break
                                                            @case('anual')
                                                                Anual
                                                                @break
                                                            @default
                                                                Recorrente
                                                        @endswitch
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>

                                        <!-- Valor -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($transaction->is_investment)
                                                <div class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                                    - R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                                </div>
                                            @else
                                                <div class="text-sm font-medium {{ $transaction->type === 'entrada' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                                    {{ $transaction->type === 'entrada' ? '+' : '-' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-8 text-center">
                                            <div class="flex flex-col items-center justify-center text-gray-400">
                                                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <p class="text-lg font-medium text-gray-500 dark:text-gray-400 mb-2">
                                                    Nenhuma transação encontrada
                                                </p>
                                                <p class="text-sm text-gray-400">
                                                    Comece cadastrando sua primeira transação
                                                </p>
                                                <a href="{{ route('transactions.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-medium transition-colors duration-300">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    Nova Transação
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    @if($transactions->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $transactions->withQueryString()->links('vendor.pagination.tailwind') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

<script>
    // Função para alternar entre as tabs
    function switchTab(tabName) {
        // Esconder todos os conteúdos
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Mostrar o conteúdo da tab selecionada
        document.getElementById(`tab-${tabName}-content`).classList.remove('hidden');
        
        // Atualizar as tabs
        document.querySelectorAll('.tab-button').forEach(button => {
            const activeClasses = button.dataset.activeClasses.split(' ');
            const inactiveClasses = button.dataset.inactiveClasses.split(' ');
            
            if (button.id === `tab-${tabName}`) {
                // Ativar tab
                button.classList.remove(...inactiveClasses);
                button.classList.add(...activeClasses);
                button.setAttribute('aria-current', 'page');
            } else {
                // Desativar tab
                button.classList.remove(...activeClasses);
                button.classList.add(...inactiveClasses);
                button.removeAttribute('aria-current');
            }
        });
        
        // Salvar a tab ativa no localStorage
        localStorage.setItem('activeTab', tabName);
        
        // Se mudou para transações, limpar parâmetros month/year da URL (mas não recarregar)
        if (tabName === 'transactions') {
            const url = new URL(window.location);
            url.searchParams.delete('month');
            url.searchParams.delete('year');
            window.history.replaceState({}, '', url.toString());
        }
    }
    
    // Preservar parâmetros da URL ao alternar entre tabs
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar se há parâmetros de mês/ano na URL
        const urlParams = new URLSearchParams(window.location.search);
        const month = urlParams.get('month');
        const year = urlParams.get('year');
        const page = urlParams.get('page');
        
        // Lógica para determinar qual tab mostrar
        let activeTab = localStorage.getItem('activeTab') || 'monthly';
        
        // Se estiver na tab mensal OU tiver parâmetros month/year (e não for paginação)
        if ((month || year) && !page) {
            activeTab = 'monthly';
        } else if (page) {
            // Se estiver paginando, mostrar tab de transações
            activeTab = 'transactions';
        }
        
        switchTab(activeTab);
        
        // Limpar parâmetros month/year dos links de paginação
        function cleanPaginationLinks() {
            document.querySelectorAll('.pagination a, [href*="page="]').forEach(link => {
                if (link.href) {
                    const url = new URL(link.href);
                    const params = new URLSearchParams(url.search);
                    
                    // Remover month e year dos links de paginação
                    if (params.has('page')) {
                        params.delete('month');
                        params.delete('year');
                    }
                    
                    // Atualizar o href
                    url.search = params.toString();
                    link.href = url.toString();
                }
            });
        }
        
        // Executar limpeza inicial
        cleanPaginationLinks();
        
        // Observar mudanças no DOM para links carregados dinamicamente
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length) {
                    setTimeout(cleanPaginationLinks, 100);
                }
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
</script>

    <style>
        .tab-button {
            transition: all 0.3s ease;
        }
        
        .tab-button:hover {
            transform: translateY(-2px);
        }
        
        .tab-content {
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</x-app-layout>
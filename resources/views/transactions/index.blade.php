<x-app-layout>
<x-slot name="header">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200">
                Movimentações Financeiras
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Gerencie suas entradas e saídas
            </p>
        </div>

        <!-- Botão desktop -->
        <button onclick="window.location='{{ route('transactions.create') }}'"
            class="hidden sm:inline-flex items-center justify-center px-5 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 border border-transparent rounded-xl font-semibold text-sm text-white tracking-wider shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-3 focus:ring-emerald-400/50 group">
            <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
            </svg>
            Nova Movimentação
        </button>
    </div>
</x-slot>

<!-- Botão flutuante para mobile -->
<div class="fixed bottom-6 right-6 sm:hidden z-50">
    <button onclick="window.location='{{ route('transactions.create') }}'"
        class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-700 hover:from-emerald-600 hover:via-emerald-700 hover:to-teal-800 text-white rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 focus:outline-none focus:ring-3 focus:ring-emerald-400/50 active:scale-95">
        
        <!-- Ícone com efeito de pulsação sutil -->
        <div class="relative">
            <svg class="w-6 h-6 relative z-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
            </svg>
            <!-- Efeito de brilho -->
            <div class="absolute inset-0 bg-white/20 blur-sm rounded-full animate-ping opacity-30"></div>
        </div>
        
        <span class="sr-only">Nova Movimentação</span>
    </button>
</div>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Cards Resumo -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-emerald-900/20 dark:to-emerald-900/10 rounded-2xl p-6 shadow-lg border border-green-100 dark:border-emerald-800/30">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-700 dark:text-emerald-300 mb-1">Entradas</p>
                            <h3 class="text-3xl font-bold text-green-900 dark:text-emerald-100">
                                R$ {{ number_format($totalEntradas, 2, ',', '.') }}
                            </h3>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-emerald-800/40 rounded-xl">
                            <svg class="w-6 h-6 text-green-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-green-200 dark:border-emerald-800/30">
                        <p class="text-xs text-green-600 dark:text-emerald-400">
                            Receitas do período
                        </p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-50 to-rose-50 dark:from-rose-900/20 dark:to-rose-900/10 rounded-2xl p-6 shadow-lg border border-red-100 dark:border-rose-800/30">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-red-700 dark:text-rose-300 mb-1">Saídas</p>
                            <h3 class="text-3xl font-bold text-red-900 dark:text-rose-100">
                                R$ {{ number_format($totalSaidas, 2, ',', '.') }}
                            </h3>
                        </div>
                        <div class="p-3 bg-red-100 dark:bg-rose-800/40 rounded-xl">
                            <svg class="w-6 h-6 text-red-600 dark:text-rose-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-red-200 dark:border-rose-800/30">
                        <p class="text-xs text-red-600 dark:text-rose-400">
                            Despesas do período
                        </p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/10 rounded-2xl p-6 shadow-lg border border-blue-100 dark:border-blue-800/30">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-700 dark:text-blue-300 mb-1">Saldo</p>
                            <h3 class="text-3xl font-bold {{ $saldo >= 0 ? 'text-blue-900 dark:text-blue-100' : 'text-red-900 dark:text-rose-100' }}">
                                R$ {{ number_format($saldo, 2, ',', '.') }}
                            </h3>
                        </div>
                        <div class="p-3 {{ $saldo >= 0 ? 'bg-blue-100 dark:bg-blue-800/40' : 'bg-red-100 dark:bg-rose-800/40' }} rounded-xl">
                            <svg class="w-6 h-6 {{ $saldo >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-rose-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-blue-200 dark:border-blue-800/30">
                        <p class="text-xs {{ $saldo >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-rose-400' }}">
                            {{ $saldo >= 0 ? 'Positivo' : 'Negativo' }} no período
                        </p>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-8 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        Filtros
                    </h3>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $transactions->total() }} registros encontrados
                    </span>
                </div>

                <form method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar descrição..." class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                            <select name="type" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-gray-200 transition-all duration-200 appearance-none cursor-pointer">
                                <option value="" class="py-2">Todos os tipos</option>
                                <option value="entrada" @selected(request('type') === 'entrada') class="py-2">Entrada</option>
                                <option value="saida" @selected(request('type') === 'saida') class="py-2">Saída</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoria</label>
                            <select name="category_id" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-gray-200 transition-all duration-200 appearance-none cursor-pointer">
                                <option value="" class="py-2">Todas categorias</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id) class="py-2">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Inicial</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-gray-200 transition-all duration-200">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Final</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-gray-200 transition-all duration-200">
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex gap-3">
                            <button type="submit" class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 border border-transparent rounded-xl font-medium text-white shadow-md hover:shadow-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                Filtrar
                            </button>

                            <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-5 py-3 bg-gray-100 dark:bg-gray-900 hover:bg-gray-200 dark:hover:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl font-medium text-gray-700 dark:text-gray-300 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Limpar
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabela -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                @if($transactions->isEmpty())
                    <div class="p-12 text-center">
                        <div class="mx-auto w-24 h-24 text-gray-300 dark:text-gray-600 mb-6">
                            <svg class="w-full h-full" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Nenhuma movimentação encontrada
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-8">
                            Comece adicionando sua primeira movimentação financeira clicando no botão "Nova Movimentação".
                        </p>
                        <button onclick="window.location='{{ route('transactions.create') }}'"
                            class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 border border-transparent rounded-xl font-medium text-white shadow-md hover:shadow-lg transition-all duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Criar Primeira Movimentação
                        </button>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>

                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Categoria</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Valor</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">F. Pagamento</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Data</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Descrição</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($transactions as $transaction)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-200">
                                                {{ $transaction->category->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold {{ $transaction->type === 'entrada' ? 'text-green-600 dark:text-emerald-400' : 'text-red-600 dark:text-rose-400' }}">
                                                R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-200">
                                                @if($transaction->paymentMethod)
                                                    {{ $transaction->paymentMethod->name }}
                                                @else
                                                    <span class="text-gray-400">Não informado</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $transaction->type === 'entrada' ? 'bg-green-100 text-green-800 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-red-100 text-red-800 dark:bg-rose-900/40 dark:text-rose-300' }}">
                                                @if($transaction->type === 'entrada')
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                                    </svg>
                                                @endif
                                                {{ ucfirst($transaction->type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-200">
                                                {{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="max-w-xs truncate text-sm text-gray-900 dark:text-gray-200 group relative" title="{{ $transaction->description }}">
                                                {{ $transaction->description ?? '-' }}
                                                @if(strlen($transaction->description ?? '') > 50)
                                                    <div class="absolute invisible group-hover:visible z-10 left-0 bottom-full mb-2 w-64 p-3 text-sm bg-gray-900 dark:bg-gray-800 text-white rounded-lg shadow-xl opacity-0 group-hover:opacity-100 transition-all duration-200">
                                                        {{ $transaction->description }}
                                                        <div class="absolute top-full left-4 w-0 h-0 border-l-4 border-r-4 border-t-4 border-l-transparent border-r-transparent border-t-gray-900 dark:border-t-gray-800"></div>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
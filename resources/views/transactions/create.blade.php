<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200">
                    Nova Movimentação
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Adicione uma nova entrada ou saída financeira
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                
                <!-- Cabeçalho do formulário -->
                <div class="px-6 py-5 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        Detalhes da Movimentação
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Preencha os dados abaixo para registrar a transação
                    </p>
                </div>

                <form method="POST" action="{{ route('transactions.store') }}" class="p-6" id="transaction-form">
                    @csrf

                    <!-- Exibir erros de validação -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                            <h4 class="text-sm font-semibold text-red-800 mb-2">Corrija os seguintes erros:</h4>
                            <ul class="text-sm text-red-600 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-6">
                        <!-- Tipo -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Tipo de Movimentação
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-3 gap-4">
                                <button type="button" id="entrada-btn"
                                    class="flex items-center justify-center px-4 py-3 border-2 rounded-xl font-medium transition-all duration-300"
                                    onclick="selectType('entrada')">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                    </svg>
                                    Entrada
                                </button>
                                <button type="button" id="saida-btn"
                                    class="flex items-center justify-center px-4 py-3 border-2 rounded-xl font-medium transition-all duration-300"
                                    onclick="selectType('saida')">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                    </svg>
                                    Saída
                                </button>
                                <button type="button" id="investimento-btn"
                                    class="flex items-center justify-center px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl font-medium transition-all duration-300"
                                    onclick="selectType('investimento')">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Investimento
                                </button>
                            </div>
                            <input type="hidden" name="type" id="type-input" value="saida" required>
                            <input type="hidden" name="is_investment" id="is-investment-input" value="0">
                        </div>

                        <!-- Descrição (REORDENADO - agora vem primeiro) -->
                        <div id="description-section">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Descrição
                            </label>
                            <textarea id="description" maxlength="255" 
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:ring-emerald-400 dark:focus:border-emerald-400 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 transition-all duration-200 resize-none min-h-[100px] hover:border-gray-300 dark:hover:border-gray-600"
                                name="description" 
                                placeholder="Descreva a movimentação...">{{ old('description') }}</textarea>
                            <div class="flex justify-between items-center mt-3">
                                <div id="counter" class="text-sm font-medium px-3 py-1 bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 rounded-lg">
                                    {{ strlen(old('description', '')) }}/255 caracteres
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    Máximo 255 caracteres
                                </span>
                            </div>
                        </div>

                        <!-- Valor (REORDENADO - segundo) -->
                        <div id="amount-section">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Valor
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-700 dark:text-gray-300 font-bold">R$</span>
                                </div>
                                <input type="text" name="amount" 
                                    class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:ring-emerald-400 dark:focus:border-emerald-400 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 transition-all duration-200 font-medium"
                                    required value="{{ old('amount') }}" 
                                    id="amount" 
                                    placeholder="0,00"
                                    autocomplete="off">
                            </div>
                            <div id="amount-error" class="text-sm text-red-500 dark:text-red-400 mt-2 font-medium"></div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                Valor máximo: R$ 1.000.000,00
                            </p>
                        </div>

                        <!-- Forma de Pagamento (REORDENADO - terceiro) -->
                        <div id="payment-method-section" class="hidden">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Forma de Pagamento
                            </label>
                            <select name="payment_method_id" 
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:ring-emerald-400 dark:focus:border-emerald-400 text-gray-900 dark:text-gray-200 transition-all duration-200 appearance-none cursor-pointer hover:border-gray-300 dark:hover:border-gray-600">
                                <option value="" class="py-2 text-gray-400">Selecione uma forma de pagamento</option>
                                @foreach($paymentMethods as $paymentMethod)
                                    <option value="{{ $paymentMethod->id }}" {{ old('payment_method_id') == $paymentMethod->id ? 'selected' : '' }} class="py-2">{{ $paymentMethod->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Categoria (REORDENADO - quarto) -->
                        <div id="category-section">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Categoria
                                <span class="text-red-500 required-star">*</span>
                            </label>
                            <select name="category_id" id="category-select"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:ring-emerald-400 dark:focus:border-emerald-400 text-gray-900 dark:text-gray-200 transition-all duration-200 appearance-none cursor-pointer hover:border-gray-300 dark:hover:border-gray-600"
                                required>
                                <option value="" class="py-2 text-gray-400">Selecione uma categoria</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }} class="py-2">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Data (REORDENADO - quinto) -->
                        <div id="date-section">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Data
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="date" name="date" 
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:ring-emerald-400 dark:focus:border-emerald-400 text-gray-900 dark:text-gray-200 transition-all duration-200 cursor-pointer hover:border-gray-300 dark:hover:border-gray-600"
                                    required
                                    value="{{ old('date', now()->setTimezone('America/Sao_Paulo')->format('Y-m-d')) }}"
                                    min="1900-01-01" 
                                    max="2099-12-31"
                                    id="date-input">
                            </div>
                        </div>

                        <!-- Parcelamento (apenas para saídas) -->
                        <div id="installment-section" class="hidden">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Quantidade de Parcelas
                                    </label>
                                    <select name="installments" id="installments-select"
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:ring-emerald-400 dark:focus:border-emerald-400 text-gray-900 dark:text-gray-200 transition-all duration-200 appearance-none cursor-pointer hover:border-gray-300 dark:hover:border-gray-600">
                                        @for($i = 1; $i <= 24; $i++)
                                            <option value="{{ $i }}" {{ old('installments') == $i ? 'selected' : '' }}>{{ $i }}x</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Valor por Parcela
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 font-bold">R$</span>
                                        </div>
                                        <input type="text" 
                                            class="w-full pl-12 pr-4 py-3 bg-gray-100 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 dark:text-gray-400 placeholder-gray-400 dark:placeholder-gray-500 font-medium"
                                            id="installment-amount" 
                                            placeholder="0,00"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div id="installment-dates" class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg hidden">
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Datas das Parcelas:</h4>
                                <div id="installment-dates-list" class="text-sm text-gray-600 dark:text-gray-400 space-y-1"></div>
                            </div>
                        </div>

                        <!-- Recorrente/Fixo -->
                        <div id="recurring-section" class="hidden">
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border-2 border-gray-200 dark:border-gray-700">
                                <div class="flex items-center">
                                    <div class="relative inline-block w-12 mr-3 align-middle select-none">
                                        <input type="checkbox" name="is_recurring" id="is-recurring" 
                                            class="sr-only toggle-checkbox" 
                                            onchange="toggleRecurringOptions()"
                                            value="1"
                                            {{ old('is_recurring') ? 'checked' : '' }}>
                                        <label for="is-recurring" class="block h-6 w-12 cursor-pointer bg-gray-300 dark:bg-gray-700 rounded-full transition-all duration-300"></label>
                                        <span class="absolute left-1 top-1 bg-white dark:bg-gray-300 w-4 h-4 rounded-full transition-transform duration-300"></span>
                                    </div>
                                    <div>
                                        <label for="is-recurring" class="text-sm font-semibold text-gray-700 dark:text-gray-300 cursor-pointer">
                                            Esta é uma despesa fixa/recorrente
                                        </label>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Ex: Aluguel, internet, assinaturas
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Opções de recorrencia (escondidas inicialmente) -->
                            <div id="recurring-options" class="hidden space-y-4 mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            Frequência
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <select name="recurring_frequency" 
                                            class="w-full px-4 py-3 bg-white dark:bg-gray-800 border-2 border-blue-200 dark:border-blue-800 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 text-gray-900 dark:text-gray-200 transition-all duration-200">
                                            <option value="">Selecione</option>
                                            <option value="mensal" {{ old('recurring_frequency') == 'mensal' ? 'selected' : '' }}>Mensal</option>
                                            <option value="semanal" {{ old('recurring_frequency') == 'semanal' ? 'selected' : '' }}>Semanal</option>
                                            <option value="quinzenal" {{ old('recurring_frequency') == 'quinzenal' ? 'selected' : '' }}>Quinzenal</option>
                                            <option value="anual" {{ old('recurring_frequency') == 'anual' ? 'selected' : '' }}>Anual</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            Até (opcional)
                                        </label>
                                        <input type="date" name="recurring_until" 
                                            class="w-full px-4 py-3 bg-white dark:bg-gray-800 border-2 border-blue-200 dark:border-blue-800 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 text-gray-900 dark:text-gray-200 transition-all duration-200"
                                            value="{{ old('recurring_until') }}">
                                    </div>
                                </div>
                                <p class="text-xs text-blue-600 dark:text-blue-400">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Esta transação será considerada no planejamento mensal como despesa fixa.
                                </p>
                            </div>
                        </div>

                        <!-- Botões de ação -->
                        <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button type="submit"
                                    class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 border border-transparent rounded-xl font-semibold text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-3 focus:ring-emerald-400/50 focus:ring-offset-2 flex-1">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Salvar Movimentação
                                </button>
                                
                                <a href="{{ route('transactions.index') }}"
                                    class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 dark:bg-gray-900 hover:bg-gray-200 dark:hover:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl font-medium text-gray-700 dark:text-gray-300 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-gray-500 flex-1">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancelar
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
// Seleção de tipo (entrada/saída/investimento)
function selectType(type) {
    const entradaBtn = document.getElementById('entrada-btn');
    const saidaBtn = document.getElementById('saida-btn');
    const investimentoBtn = document.getElementById('investimento-btn');
    const typeInput = document.getElementById('type-input');
    const isInvestmentInput = document.getElementById('is-investment-input');
    const categorySelect = document.getElementById('category-select');
    const paymentMethodSelect = document.querySelector('select[name="payment_method_id"]');
    const requiredStar = document.querySelector('.required-star');
    
    // Resetar todos os botões para o estado inativo
    [entradaBtn, saidaBtn, investimentoBtn].forEach(btn => {
        btn.classList.remove(
            'border-emerald-500', 'bg-emerald-50', 'text-emerald-700',
            'dark:bg-emerald-900/30', 'dark:text-emerald-300', 'dark:border-emerald-500',
            'border-red-500', 'bg-red-50', 'text-red-700',
            'dark:bg-red-900/30', 'dark:text-red-300', 'dark:border-red-500',
            'border-blue-500', 'bg-blue-50', 'text-blue-700',
            'dark:bg-blue-900/30', 'dark:text-blue-300', 'dark:border-blue-500'
        );
        btn.classList.add('border-gray-200', 'dark:border-gray-700');
    });
    
    // Configurar inputs baseados no tipo
    if (type === 'entrada') {
        typeInput.value = 'entrada';
        isInvestmentInput.value = '0';
        
        entradaBtn.classList.add('border-emerald-500', 'bg-emerald-50', 'text-emerald-700', 'dark:bg-emerald-900/30', 'dark:text-emerald-300', 'dark:border-emerald-500');
        entradaBtn.classList.remove('border-gray-200', 'dark:border-gray-700');
        
        // Para entradas: mostrar apenas valor, data e descrição
        document.getElementById('payment-method-section').classList.add('hidden');
        document.getElementById('category-section').classList.add('hidden');
        document.getElementById('installment-section').classList.add('hidden');
        document.getElementById('recurring-section').classList.add('hidden');
        
        // Manter obrigatórios visíveis: valor, data, descrição
        document.getElementById('description-section').classList.remove('hidden');
        document.getElementById('amount-section').classList.remove('hidden');
        document.getElementById('date-section').classList.remove('hidden');

        // Remover required da categoria e forma de pagamento
        if (categorySelect) {
            categorySelect.required = false;
            categorySelect.removeAttribute('required');
        }
        if (paymentMethodSelect) {
            paymentMethodSelect.required = false;
            paymentMethodSelect.removeAttribute('required');
        }
        
        // Ocultar asterisco vermelho
        if (requiredStar) {
            requiredStar.classList.add('hidden');
        }

    } else if (type === 'saida') {
        typeInput.value = 'saida';
        isInvestmentInput.value = '0';
        
        saidaBtn.classList.add('border-red-500', 'bg-red-50', 'text-red-700', 'dark:bg-red-900/30', 'dark:text-red-300', 'dark:border-red-500');
        saidaBtn.classList.remove('border-gray-200', 'dark:border-gray-700');
        
        // Para saídas: mostrar todos os campos
        document.getElementById('payment-method-section').classList.remove('hidden');
        document.getElementById('category-section').classList.remove('hidden');
        document.getElementById('installment-section').classList.remove('hidden');
        document.getElementById('recurring-section').classList.remove('hidden');
        document.getElementById('description-section').classList.remove('hidden');
        document.getElementById('amount-section').classList.remove('hidden');
        document.getElementById('date-section').classList.remove('hidden');
        
        // Adicionar required apenas para a categoria (forma de pagamento é opcional)
        if (categorySelect) {
            categorySelect.required = true;
            categorySelect.setAttribute('required', 'required');
        }
        if (paymentMethodSelect) {
            paymentMethodSelect.required = false;
            paymentMethodSelect.removeAttribute('required');
        }
        
        // Mostrar asterisco vermelho
        if (requiredStar) {
            requiredStar.classList.remove('hidden');
        }
        
        // Calcular parcelas
        calculateInstallments();
        
    } else if (type === 'investimento') {
        typeInput.value = 'saida'; // Investimentos são registrados como saídas
        isInvestmentInput.value = '1';
        
        investimentoBtn.classList.add('border-blue-500', 'bg-blue-50', 'text-blue-700', 'dark:bg-blue-900/30', 'dark:text-blue-300', 'dark:border-blue-500');
        investimentoBtn.classList.remove('border-gray-200', 'dark:border-gray-700');
        
        // Para investimentos: mostrar apenas valor, data e descrição
        document.getElementById('payment-method-section').classList.add('hidden');
        document.getElementById('category-section').classList.add('hidden');
        document.getElementById('installment-section').classList.add('hidden');
        document.getElementById('recurring-section').classList.add('hidden');
        
        // Manter obrigatórios visíveis: valor, data, descrição
        document.getElementById('description-section').classList.remove('hidden');
        document.getElementById('amount-section').classList.remove('hidden');
        document.getElementById('date-section').classList.remove('hidden');
        
        // Remover required da categoria e forma de pagamento
        if (categorySelect) {
            categorySelect.required = false;
            categorySelect.removeAttribute('required');
        }
        if (paymentMethodSelect) {
            paymentMethodSelect.required = false;
            paymentMethodSelect.removeAttribute('required');
        }
        
        // Ocultar asterisco vermelho
        if (requiredStar) {
            requiredStar.classList.add('hidden');
        }
    }
}

// Função para criar uma data corretamente (resolver problema do fuso horário)
function parseDateWithoutTimezone(dateString) {
    // Divide a string "YYYY-MM-DD" em partes
    const parts = dateString.split('-');
    if (parts.length !== 3) {
        return new Date(dateString);
    }
    
    const year = parseInt(parts[0], 10);
    const month = parseInt(parts[1], 10) - 1; // JavaScript months are 0-indexed
    const day = parseInt(parts[2], 10);
    
    // Cria a data sem considerar fuso horário
    return new Date(year, month, day);
}

// Calcular parcelas CORRIGIDA (data um dia a menos)
function calculateInstallments() {
    const amountInput = document.getElementById('amount');
    const installmentsSelect = document.getElementById('installments-select');
    const installmentAmount = document.getElementById('installment-amount');
    const installmentDatesList = document.getElementById('installment-dates-list');
    const installmentDates = document.getElementById('installment-dates');
    const dateInput = document.getElementById('date-input');
    
    if (!amountInput || !installmentsSelect || !installmentAmount || !dateInput) return;
    
    const amountValue = parseFloat(amountInput.value.replace(/[^\d,]/g, '').replace(',', '.'));
    const installments = parseInt(installmentsSelect.value);
    
    if (isNaN(amountValue) || amountValue <= 0) {
        installmentAmount.value = '0,00';
        installmentDates.classList.add('hidden');
        return;
    }
    
    const installmentValue = amountValue / installments;
    installmentAmount.value = installmentValue.toFixed(2).replace('.', ',');
    
    // Gerar datas das parcelas CORRIGIDO
    if (installments > 1) {
        // Usar a função corrigida para evitar problemas de fuso horário
        const startDate = parseDateWithoutTimezone(dateInput.value);
        const dates = [];
        
        for (let i = 0; i < installments; i++) {
            const installmentDate = new Date(startDate);
            
            // Adicionar meses mantendo o dia original
            installmentDate.setMonth(startDate.getMonth() + i);
            
            // Garantir que não mudemos o dia (problema com meses com dias diferentes)
            // Se o dia original for 31 e o próximo mês tiver apenas 30, ajustar para 30
            const originalDay = startDate.getDate();
            const maxDay = new Date(installmentDate.getFullYear(), installmentDate.getMonth() + 1, 0).getDate();
            installmentDate.setDate(Math.min(originalDay, maxDay));
            
            // Formatar data no padrão brasileiro
            const day = String(installmentDate.getDate()).padStart(2, '0');
            const month = String(installmentDate.getMonth() + 1).padStart(2, '0');
            const year = installmentDate.getFullYear();
            const formattedDate = `${day}/${month}/${year}`;
            
            dates.push(`<div class="flex items-center">
                <span class="inline-block w-6 h-6 bg-gray-200 dark:bg-gray-700 rounded-full text-xs flex items-center justify-center mr-2">${i + 1}</span>
                ${formattedDate}
            </div>`);
        }
        
        installmentDatesList.innerHTML = dates.join('');
        installmentDates.classList.remove('hidden');
    } else {
        installmentDates.classList.add('hidden');
    }
}

// Alternar opções de recorrencia
function toggleRecurringOptions() {
    const recurringCheckbox = document.getElementById('is-recurring');
    const recurringOptions = document.getElementById('recurring-options');
    const frequencySelect = document.querySelector('select[name="recurring_frequency"]');
    
    if (recurringCheckbox.checked) {
        recurringOptions.classList.remove('hidden');
        if (frequencySelect) frequencySelect.required = true;
    } else {
        recurringOptions.classList.add('hidden');
        if (frequencySelect) frequencySelect.required = false;
    }
}

// Inicializar com saída selecionada
document.addEventListener('DOMContentLoaded', function() {
    // Verificar se há valor antigo para type
    const oldType = "{{ old('type', 'saida') }}";
    const oldIsInvestment = "{{ old('is_investment', 0) }}";
    
    if (oldIsInvestment == 1) {
        selectType('investimento');
    } else if (oldType === 'entrada') {
        selectType('entrada');
    } else {
        selectType('saida');
    }
    
    // Adicionar listeners para cálculos de parcelas
    const amountInput = document.getElementById('amount');
    const installmentsSelect = document.getElementById('installments-select');
    const dateInput = document.getElementById('date-input');
    
    if (amountInput && installmentsSelect) {
        amountInput.addEventListener('input', calculateInstallments);
        installmentsSelect.addEventListener('change', calculateInstallments);
    }
    
    if (dateInput && installmentsSelect) {
        dateInput.addEventListener('change', calculateInstallments);
    }
    
    // Verificar se recorrente estava marcado e mostrar opções
    const recurringCheckbox = document.getElementById('is-recurring');
    if (recurringCheckbox && recurringCheckbox.checked) {
        toggleRecurringOptions();
    }
    
    // Verificar se a data está vazia e definir para hoje
    if (dateInput && !dateInput.value) {
        const today = new Date();
        const formattedToday = today.toISOString().split('T')[0];
        dateInput.value = formattedToday;
    }
});

// Contador de caracteres
const textarea = document.getElementById('description');
const counter = document.getElementById('counter');
if (textarea && counter) {
    textarea.addEventListener('input', () => {
        const length = textarea.value.length;
        counter.textContent = `${length}/255 caracteres`;
        
        if (length > 200) {
            counter.classList.add('text-red-500', 'dark:text-red-400');
            counter.classList.remove('text-gray-700', 'dark:text-gray-300');
        } else {
            counter.classList.remove('text-red-500', 'dark:text-red-400');
            counter.classList.add('text-gray-700', 'dark:text-gray-300');
        }
    });
}

// Formatação do valor monetário
const amountInput = document.getElementById('amount');
const maxAmount = 1000000;

if (amountInput) {
    amountInput.addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, '');
        
        if (parseInt(value) > maxAmount * 100) {
            value = (maxAmount * 100).toString();
        }

        if (value) {
            value = (parseInt(value) / 100).toFixed(2);
            this.value = value.replace('.', ',');
        } else {
            this.value = '';
        }

        const amountError = document.getElementById('amount-error');
        if (amountError) amountError.textContent = '';
        calculateInstallments(); // Calcular parcelas ao digitar
    });

    // Validação do valor
    amountInput.addEventListener('blur', function() {
        let value = this.value.replace(/\D/g, '');
        const amountError = document.getElementById('amount-error');
        if (value && parseInt(value) < 1) {
            if (amountError) amountError.textContent = 'O valor deve ser maior que R$ 0,01';
            this.classList.add('border-red-500', 'dark:border-red-500');
        } else {
            this.classList.remove('border-red-500', 'dark:border-red-500');
        }
    });
}

// Focar no primeiro campo
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        if (amountInput) amountInput.focus();
    }, 100);
});
</script>

<style>
.toggle-checkbox:checked + label {
    background-color: #3b82f6;
}
.toggle-checkbox:checked + label + span {
    transform: translateX(1.5rem);
}
</style>
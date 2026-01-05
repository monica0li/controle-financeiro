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

                <form method="POST" action="{{ route('transactions.store') }}" class="p-6">
                    @csrf

                    <div class="space-y-6">
                        <!-- Tipo -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Tipo de Movimentação
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-4">
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
                            </div>
                            <input type="hidden" name="type" id="type-input" value="saida" required>
                        </div>

                        <!-- Categoria -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Categoria
                                <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" 
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:ring-emerald-400 dark:focus:border-emerald-400 text-gray-900 dark:text-gray-200 transition-all duration-200 appearance-none cursor-pointer hover:border-gray-300 dark:hover:border-gray-600" required>
                                <option value="" class="py-2 text-gray-400">Selecione uma categoria</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" class="py-2">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Valor -->
                        <div>
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

                        <!-- Categoria -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Forma de Pagamento
                                <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" 
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:ring-emerald-400 dark:focus:border-emerald-400 text-gray-900 dark:text-gray-200 transition-all duration-200 appearance-none cursor-pointer hover:border-gray-300 dark:hover:border-gray-600" required>
                                <option value="" class="py-2 text-gray-400">Selecione uma forma de pagamento</option>
                                @foreach($paymentMethods as $paymentMethod)
                                    <option value="{{ $paymentMethod->id }}" class="py-2">{{ $paymentMethod->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Data -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Data
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="date" name="date" 
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:ring-emerald-400 dark:focus:border-emerald-400 text-gray-900 dark:text-gray-200 transition-all duration-200 cursor-pointer hover:border-gray-300 dark:hover:border-gray-600"
                                    required
                                    value="{{ date('Y-m-d') }}"
                                    min="1900-01-01" 
                                    max="2099-12-31">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">

                                </div>
                            </div>
                        </div>

                        <!-- Descrição -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Descrição
                            </label>
                            <textarea id="description" maxlength="255" 
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:ring-emerald-400 dark:focus:border-emerald-400 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 transition-all duration-200 resize-none min-h-[100px] hover:border-gray-300 dark:hover:border-gray-600"
                                name="description" 
                                placeholder="Descreva a movimentação..."></textarea>
                            <div class="flex justify-between items-center mt-3">
                                <div id="counter" class="text-sm font-medium px-3 py-1 bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 rounded-lg">
                                    0/255 caracteres
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    Máximo 255 caracteres
                                </span>
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
// Seleção de tipo (entrada/saída)
function selectType(type) {
    const entradaBtn = document.getElementById('entrada-btn');
    const saidaBtn = document.getElementById('saida-btn');
    const typeInput = document.getElementById('type-input');
    
    typeInput.value = type;
    
    if (type === 'entrada') {
        entradaBtn.classList.add('border-emerald-500', 'bg-emerald-50', 'text-emerald-700', 'dark:bg-emerald-900/30', 'dark:text-emerald-300', 'dark:border-emerald-500');
        entradaBtn.classList.remove('border-gray-200', 'dark:border-gray-700');
        
        saidaBtn.classList.add('border-gray-200', 'dark:border-gray-700');
        saidaBtn.classList.remove('border-red-500', 'bg-red-50', 'text-red-700', 'dark:bg-red-900/30', 'dark:text-red-300', 'dark:border-red-500');
    } else {
        saidaBtn.classList.add('border-red-500', 'bg-red-50', 'text-red-700', 'dark:bg-red-900/30', 'dark:text-red-300', 'dark:border-red-500');
        saidaBtn.classList.remove('border-gray-200', 'dark:border-gray-700');
        
        entradaBtn.classList.add('border-gray-200', 'dark:border-gray-700');
        entradaBtn.classList.remove('border-emerald-500', 'bg-emerald-50', 'text-emerald-700', 'dark:bg-emerald-900/30', 'dark:text-emerald-300', 'dark:border-emerald-500');
    }
}

// Inicializar com saída selecionada
document.addEventListener('DOMContentLoaded', function() {
    selectType('saida');
});

// Contador de caracteres
const textarea = document.getElementById('description');
const counter = document.getElementById('counter');
textarea.addEventListener('input', () => {
    const length = textarea.value.length;
    counter.textContent = `${length}/255 caracteres`;
    
    // Mudar cor quando estiver perto do limite
    if (length > 200) {
        counter.classList.add('text-red-500', 'dark:text-red-400');
        counter.classList.remove('text-gray-700', 'dark:text-gray-300');
    } else {
        counter.classList.remove('text-red-500', 'dark:text-red-400');
        counter.classList.add('text-gray-700', 'dark:text-gray-300');
    }
});

// Formatação do valor monetário
const amountInput = document.getElementById('amount');
const maxAmount = 1000000;

amountInput.addEventListener('input', function(e) {
    let value = this.value.replace(/\D/g, '');
    
    // Limitar ao máximo
    if (parseInt(value) > maxAmount * 100) {
        value = (maxAmount * 100).toString();
    }

    // Formatar em reais (só se tiver valor)
    if (value) {
        value = (parseInt(value) / 100).toFixed(2);
        this.value = value.replace('.', ',');
        
        // Adicionar R$ apenas na exibição, mas mantém no formato numérico para o backend
        this.value = this.value;
    } else {
        this.value = '';
    }

    document.getElementById('amount-error').textContent = '';
});

// Focar no primeiro campo
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        amountInput.focus();
    }, 100);
});

// Validação do valor
amountInput.addEventListener('blur', function() {
    let value = this.value.replace(/\D/g, '');
    if (value && parseInt(value) < 1) {
        document.getElementById('amount-error').textContent = 'O valor deve ser maior que R$ 0,01';
        this.classList.add('border-red-500', 'dark:border-red-500');
    } else {
        this.classList.remove('border-red-500', 'dark:border-red-500');
    }
});
</script>
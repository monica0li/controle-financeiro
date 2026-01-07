{{-- resources/views/payment-methods/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200">
                    Nova Forma de Pagamento
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Adicione uma nova forma de pagamento
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                
                <div class="px-6 py-5 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        Detalhes da Forma de Pagamento
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Preencha os dados abaixo para criar uma nova forma de pagamento
                    </p>
                </div>

                <form method="POST" action="{{ route('payment-methods.store') }}" class="p-6">
                    @csrf

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
                        <!-- Nome -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Nome
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" 
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:ring-emerald-400 dark:focus:border-emerald-400 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 transition-all duration-200"
                                required
                                value="{{ old('name') }}"
                                placeholder="Ex: Dinheiro, PIX, Itaú, Nubank..."
                                autocomplete="off"
                                autofocus>
                        </div>

                        <!-- É cartão? -->
                        <div>
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border-2 border-gray-200 dark:border-gray-700">
                                <div class="flex items-center">
                                    <div class="relative inline-block w-12 mr-3 align-middle select-none">
                                        <input type="checkbox" name="is_card" id="is_card" 
                                            class="sr-only toggle-checkbox" 
                                            onchange="toggleCardType()"
                                            value="1"
                                            {{ old('is_card') ? 'checked' : '' }}>
                                        <label for="is_card" class="block h-6 w-12 cursor-pointer bg-gray-300 dark:bg-gray-700 rounded-full transition-all duration-300"></label>
                                        <span class="absolute left-1 top-1 bg-white dark:bg-gray-300 w-4 h-4 rounded-full transition-transform duration-300"></span>
                                    </div>
                                    <div>
                                        <label for="is_card" class="text-sm font-semibold text-gray-700 dark:text-gray-300 cursor-pointer">
                                            Esta forma de pagamento é um cartão
                                        </label>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Marque se for cartão de crédito ou débito
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tipo de Cartão (apenas se for cartão) -->
                        <div id="card-type-section" class="{{ old('is_card') ? '' : 'hidden' }}">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Tipo do Cartão
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="flex items-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer hover:border-blue-500 dark:hover:border-blue-500 transition-all duration-200 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20 dark:has-[:checked]:border-blue-500">
                                    <input type="radio" name="card_type" value="credit" 
                                        class="sr-only"
                                        {{ old('card_type') == 'credit' ? 'checked' : '' }}
                                        id="card_type_credit">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900 mr-3">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-gray-200">Crédito</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Cartão de crédito</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="flex items-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer hover:border-green-500 dark:hover:border-green-500 transition-all duration-200 has-[:checked]:border-green-500 has-[:checked]:bg-green-50 dark:has-[:checked]:bg-green-900/20 dark:has-[:checked]:border-green-500">
                                    <input type="radio" name="card_type" value="debit" 
                                        class="sr-only"
                                        {{ old('card_type') == 'debit' ? 'checked' : '' }}
                                        id="card_type_debit">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-100 dark:bg-green-900 mr-3">
                                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-gray-200">Débito</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Cartão de débito</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Status (opcional - criado já como ativo por padrão) -->
                        {{-- <div>
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border-2 border-gray-200 dark:border-gray-700">
                                <div class="flex items-center">
                                    <div class="relative inline-block w-12 mr-3 align-middle select-none">
                                        <input type="checkbox" name="active" id="active" 
                                            class="sr-only toggle-checkbox" 
                                            value="1"
                                            {{ old('active', true) ? 'checked' : '' }}>
                                        <label for="active" class="block h-6 w-12 cursor-pointer bg-gray-300 dark:bg-gray-700 rounded-full transition-all duration-300"></label>
                                        <span class="absolute left-1 top-1 bg-white dark:bg-gray-300 w-4 h-4 rounded-full transition-transform duration-300"></span>
                                    </div>
                                    <div>
                                        <label for="active" class="text-sm font-semibold text-gray-700 dark:text-gray-300 cursor-pointer">
                                            Forma de pagamento ativa
                                        </label>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Formas inativas não aparecem nas listas de seleção
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        <!-- Botões de ação -->
                        <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button type="submit"
                                    class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 border border-transparent rounded-xl font-semibold text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-3 focus:ring-emerald-400/50 focus:ring-offset-2 flex-1">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Salvar Forma de Pagamento
                                </button>
                                
                                <a href="{{ route('payment-methods.index') }}"
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
function toggleCardType() {
    const isCardCheckbox = document.getElementById('is_card');
    const cardTypeSection = document.getElementById('card-type-section');
    const cardTypeInputs = document.querySelectorAll('input[name="card_type"]');
    
    if (isCardCheckbox.checked) {
        cardTypeSection.classList.remove('hidden');
        // Torna os radios required apenas visualmente (validação é feita no backend)
        cardTypeInputs.forEach(input => {
            input.dataset.required = 'true';
        });
    } else {
        cardTypeSection.classList.add('hidden');
        cardTypeInputs.forEach(input => {
            input.dataset.required = 'false';
            input.checked = false;
        });
    }
}

// Validação personalizada do formulário
document.addEventListener('DOMContentLoaded', function() {
    toggleCardType();
    
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const isCardCheckbox = document.getElementById('is_card');
        const cardTypeInputs = document.querySelectorAll('input[name="card_type"]');
        let cardTypeSelected = false;
        
        cardTypeInputs.forEach(input => {
            if (input.checked) {
                cardTypeSelected = true;
            }
        });
        
        if (isCardCheckbox.checked && !cardTypeSelected) {
            e.preventDefault();
            alert('Por favor, selecione o tipo do cartão (Crédito ou Débito)');
            return false;
        }
    });
});
</script>

<style>
.toggle-checkbox:checked + label {
    background-color: #10b981;
}
.toggle-checkbox:checked + label + span {
    transform: translateX(1.5rem);
}

/* Estilo para os radio buttons quando selecionados */
input[name="card_type"]:checked + div {
    border-color: #3b82f6;
    background-color: #eff6ff;
}
.dark input[name="card_type"]:checked + div {
    border-color: #60a5fa;
    background-color: rgba(59, 130, 246, 0.1);
}

input[name="card_type"]:checked + div .font-medium {
    color: #1e40af;
}
.dark input[name="card_type"]:checked + div .font-medium {
    color: #93c5fd;
}
</style>
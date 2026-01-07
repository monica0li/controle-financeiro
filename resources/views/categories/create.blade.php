{{-- resources/views/categories/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200">
                    Nova Categoria
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Crie uma nova categoria para suas despesas
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
                        Detalhes da Categoria
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Preencha os dados abaixo para criar uma nova categoria
                    </p>
                </div>

                <form method="POST" action="{{ route('categories.store') }}" class="p-6">
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
                                Nome da Categoria
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" 
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:ring-emerald-400 dark:focus:border-emerald-400 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 transition-all duration-200"
                                required
                                value="{{ old('name') }}"
                                placeholder="Ex: Alimentação, Transporte, Saúde..."
                                autocomplete="off"
                                autofocus>
                        </div>

                        <!-- Status -->
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
                                            Categoria ativa
                                        </label>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Categorias inativas não aparecem nas listas de seleção
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
                                    Salvar Categoria
                                </button>
                                
                                <a href="{{ route('categories.index') }}"
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

<style>
.toggle-checkbox:checked + label {
    background-color: #10b981;
}
.toggle-checkbox:checked + label + span {
    transform: translateX(1.5rem);
}
</style>
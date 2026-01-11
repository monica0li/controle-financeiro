<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200">
                    Nova Categoria
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Adicione uma nova categoria
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                
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
                                Nome
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" 
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:ring-emerald-400 dark:focus:border-emerald-400 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 transition-all duration-200"
                                required
                                value="{{ old('name') }}"
                                placeholder="Ex: Alimentação, Transporte, Lazer..."
                                autocomplete="off"
                                autofocus>
                        </div>

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
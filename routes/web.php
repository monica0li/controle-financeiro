<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\PaymentMethodsController;

Route::get('/test-env', function() {
    return [
        'app_key' => env('APP_KEY') ? '✅ Configurada' : '❌ FALTANDO',
        'db_host' => env('DB_HOST'),
        'db_connected' => function_exists('pg_connect') ? '✅' : '❌',
        'php_version' => phpversion(),
    ];
});

Route::get('/test-db', function() {
    try {
        \DB::connection()->getPdo();
        return "✅ Banco conectado!";
    } catch (\Exception $e) {
        return "❌ Erro: " . $e->getMessage();
    }
});

Route::get('/', function() {
    return "✅ Laravel está funcionando!";
});

Route::get('/debug', function() {
    return [
        'status' => 'ok',
        'php_version' => phpversion(),
        'laravel_version' => app()->version(),
        'app_key' => config('app.key') ? 'Set' : 'Missing',
        'files' => [
            'index_exists' => file_exists(public_path('index.php')) ? '✅' : '❌',
            'htaccess_exists' => file_exists(public_path('.htaccess')) ? '✅' : '❌',
            'storage_writable' => is_writable(storage_path()) ? '✅' : '❌',
        ]
    ];
});

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('transactions.index');
    }
    
    // Se não estiver autenticado, redireciona para login
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // Rotas para transações
    Route::get('/transactions', [TransactionController::class, 'index'])
        ->name('transactions.index');
    
    Route::get('/transactions/create', [TransactionController::class, 'create'])
        ->name('transactions.create');
    
    Route::post('/transactions', [TransactionController::class, 'store'])
        ->name('transactions.store');
});

Route::get('/dashboard', function () {
    return redirect()->route('transactions.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/check-transaction-columns', function() {
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('transactions');
    echo "<h3>Colunas da tabela transactions:</h3>";
    echo "<ul>";
    foreach ($columns as $column) {
        echo "<li>$column</li>";
    }
    echo "</ul>";
    
    // Verificar colunas específicas
    $importantColumns = ['installments', 'current_installment', 'installment_group_id'];
    echo "<h3>Colunas importantes para parcelamento:</h3>";
    echo "<ul>";
    foreach ($importantColumns as $col) {
        if (in_array($col, $columns)) {
            echo "<li style='color: green;'>✓ $col (EXISTE)</li>";
        } else {
            echo "<li style='color: red;'>✗ $col (NÃO EXISTE)</li>";
        }
    }
    echo "</ul>";
});
// Rotas para Categorias
Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [CategoriesController::class, 'index'])->name('index');
    Route::get('/create', [CategoriesController::class, 'create'])->name('create');
    Route::post('/', [CategoriesController::class, 'store'])->name('store');
    Route::get('/{category}/edit', [CategoriesController::class, 'edit'])->name('edit');
    Route::put('/{category}', [CategoriesController::class, 'update'])->name('update');
    Route::delete('/{category}', [CategoriesController::class, 'destroy'])->name('destroy');
    Route::post('/{category}/activate', [CategoriesController::class, 'activate'])->name('activate');
});

// Rotas para Formas de Pagamento
Route::prefix('payment-methods')->name('payment-methods.')->group(function () {
    Route::get('/', [PaymentMethodsController::class, 'index'])->name('index');
    Route::get('/create', [PaymentMethodsController::class, 'create'])->name('create');
    Route::post('/', [PaymentMethodsController::class, 'store'])->name('store');
    Route::get('/{paymentMethod}/edit', [PaymentMethodsController::class, 'edit'])->name('edit');
    Route::put('/{paymentMethod}', [PaymentMethodsController::class, 'update'])->name('update');
    Route::delete('/{paymentMethod}', [PaymentMethodsController::class, 'destroy'])->name('destroy');
    Route::post('/{paymentMethod}/activate', [PaymentMethodsController::class, 'activate'])->name('activate');
});

Route::get('/test-db', function() {
    try {
        \DB::connection()->getPdo();
        return "✅ Banco conectado!";
    } catch (\Exception $e) {
        return "❌ Erro na conexão: " . $e->getMessage();
    }
});

Route::get('/debug', function() {
    $checks = [
        'PHP Version' => phpversion(),
        'Laravel Version' => app()->version(),
        'App Key Set' => config('app.key') ? '✅ Yes' : '❌ No',
        'Environment' => app()->environment(),
        'Debug Mode' => config('app.debug') ? 'On' : 'Off',
        'Storage Writable' => is_writable(storage_path()) ? '✅' : '❌',
        'Cache Writable' => is_writable(base_path('bootstrap/cache')) ? '✅' : '❌',
    ];
    
    // Test database
    try {
        \DB::connection()->getPdo();
        $checks['Database'] = '✅ Connected';
        $checks['Database Name'] = \DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        $checks['Database'] = '❌ Error: ' . $e->getMessage();
    }
    
    return $checks;
});
require __DIR__.'/auth.php';
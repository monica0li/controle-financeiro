<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
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
    return view('dashboard');
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

require __DIR__.'/auth.php';
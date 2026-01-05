<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Para parcelamento
            $table->unsignedInteger('installments')->default(1);
            $table->unsignedInteger('current_installment')->default(1);
            $table->string('installment_group_id')->nullable()->index();
            
            // Para transações recorrentes
            $table->boolean('is_recurring')->default(false);
            $table->string('recurring_frequency')->nullable(); // monthly, weekly, etc
            $table->date('recurring_until')->nullable();
            
            // Para investimentos/economias
            $table->boolean('is_investment')->default(false);
            $table->foreignId('investment_account_id')->nullable()->constrained('investment_accounts')->onDelete('set null');
            
            // Soft deletes para manter histórico
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Remover foreign key primeiro
            $table->dropForeign(['investment_account_id']);
            
            // Remover colunas
            $table->dropColumn([
                'installments',
                'current_installment',
                'installment_group_id',
                'is_recurring',
                'recurring_frequency',
                'recurring_until',
                'is_investment',
                'investment_account_id'
            ]);
            
            $table->dropSoftDeletes();
        });
    }
};
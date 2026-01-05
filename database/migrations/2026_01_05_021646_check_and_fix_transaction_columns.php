<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Verificar e adicionar colunas apenas se não existirem
            
            if (!Schema::hasColumn('transactions', 'installments')) {
                $table->unsignedInteger('installments')->default(1);
            }
            
            if (!Schema::hasColumn('transactions', 'current_installment')) {
                $table->unsignedInteger('current_installment')->default(1);
            }
            
            if (!Schema::hasColumn('transactions', 'installment_group_id')) {
                $table->string('installment_group_id')->nullable()->index();
            }
        });
    }

    public function down()
    {
        // Não remover colunas para evitar perda de dados
        // Schema::table('transactions', function (Blueprint $table) {
        //     $table->dropColumn(['installments', 'current_installment', 'installment_group_id']);
        // });
    }
};
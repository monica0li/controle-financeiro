<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Verificar se as colunas já existem antes de adicionar
        if (!Schema::hasColumn('transactions', 'installments')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->integer('installments')->default(1)->after('description');
            });
        }
        
        if (!Schema::hasColumn('transactions', 'current_installment')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->integer('current_installment')->default(1)->after('installments');
            });
        }
        
        if (!Schema::hasColumn('transactions', 'installment_group_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('installment_group_id')->nullable()->after('current_installment');
            });
        }
    }

    public function down()
    {
        // Não remover na reversão para segurança
    }
};
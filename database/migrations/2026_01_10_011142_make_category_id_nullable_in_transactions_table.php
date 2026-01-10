<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Primeiro, precisamos remover a foreign key constraint
            $table->dropForeign(['category_id']);
            
            // Agora alterar a coluna para nullable
            $table->foreignId('category_id')
                ->nullable()
                ->change()
                ->constrained()
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Na reversão, remover a foreign key e voltar para not nullable
            $table->dropForeign(['category_id']);
            
            $table->foreignId('category_id')
                ->nullable(false)
                ->change()
                ->constrained()
                ->onDelete('cascade');
        });
    }
};
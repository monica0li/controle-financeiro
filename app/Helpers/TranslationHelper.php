<?php

namespace App\Helpers;

class TranslationHelper
{
    /**
     * Traduzir frequência recorrente
     */
    public static function translateFrequency($frequency)
    {
        $translations = [
            'monthly' => 'Mensal',
            'mensal' => 'Mensal',
            'weekly' => 'Semanal',
            'semanal' => 'Semanal',
            'biweekly' => 'Quinzenal',
            'quinzenal' => 'Quinzenal',
            'yearly' => 'Anual',
            'anual' => 'Anual',
        ];
        
        return $translations[$frequency] ?? $frequency;
    }
    
    /**
     * Traduzir tipo de transação
     */
    public static function translateType($type)
    {
        $translations = [
            'entrada' => 'Entrada',
            'saida' => 'Saída',
            'income' => 'Entrada',
            'expense' => 'Saída',
        ];
        
        return $translations[$type] ?? $type;
    }
}
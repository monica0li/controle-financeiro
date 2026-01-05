<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'payment_method_id',
        'type',
        'amount',
        'date',
        'description',
        'installments',
        'current_installment',
        'installment_group_id',
        'is_recurring',
        'recurring_frequency',
        'recurring_until',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'installments' => 'integer',
        'current_installment' => 'integer',
        'is_recurring' => 'boolean',
        'recurring_until' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
    
    // Gerar ID único para grupo de parcelas
    public static function generateInstallmentGroupId()
    {
        return 'INST_' . time() . '_' . Str::random(8);
    }
    
    // Verificar se é uma parcela
    public function getIsInstallmentAttribute()
    {
        return $this->installments > 1;
    }
    
    // Obter todas as parcelas do mesmo grupo
    public function allInstallments()
    {
        if (!$this->installment_group_id) {
            return collect([$this]);
        }
        
        return self::where('installment_group_id', $this->installment_group_id)
            ->orderBy('current_installment')
            ->get();
    }
}
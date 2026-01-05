<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvestmentAccount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'current_balance',
        'target_amount',
        'color',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'current_balance' => 'decimal:2',
        'target_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    
    // Atualizar saldo automaticamente
    public function updateBalance()
    {
        $total = $this->transactions()->sum('amount');
        $this->current_balance = $total;
        $this->save();
    }
}
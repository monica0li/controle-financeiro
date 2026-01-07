<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'active',
        'is_card',
        'card_type'
    ];

    protected $casts = [
        'active' => 'boolean',
        'is_card' => 'boolean'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getDisplayNameAttribute()
    {
        if ($this->is_card && $this->card_type) {
            $type = $this->card_type == 'credit' ? 'Crédito' : 'Débito';
            return "{$type} {$this->name}";
        }
        return $this->name;
    }
}
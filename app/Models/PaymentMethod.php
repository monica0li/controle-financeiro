<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'active',
        'is_card',
        'card_type',
        'user_id' // ADICIONADO
    ];

    protected $casts = [
        'active' => 'boolean',
        'is_card' => 'boolean'
    ];

    // ADICIONADO: Relação com usuário
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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

    // ADICIONADO: Escopo para buscar apenas do usuário atual
    public function scopeForCurrentUser($query)
    {
        return $query->where('user_id', auth()->id());
    }
}
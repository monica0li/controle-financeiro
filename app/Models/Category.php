<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    protected $fillable = [
        'name',
        'active',
        'user_id' // ADICIONADO
    ];

    protected $casts = [
        'active' => 'boolean'
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

    // ADICIONADO: Escopo para buscar apenas do usuário atual
    public function scopeForCurrentUser($query)
    {
        return $query->where('user_id', auth()->id());
    }
}
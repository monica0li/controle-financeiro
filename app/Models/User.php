<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Transaction;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // ADICIONADO: Relação com categorias
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    // ADICIONADO: Relação com formas de pagamento
    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    // ADICIONADO: Método para buscar categorias ativas
    public function activeCategories()
    {
        return $this->categories()->where('active', true);
    }

    // ADICIONADO: Método para buscar formas de pagamento ativas
    public function activePaymentMethods()
    {
        return $this->paymentMethods()->where('active', true);
    }
}
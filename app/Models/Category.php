<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;


class Category extends Model
{
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

}

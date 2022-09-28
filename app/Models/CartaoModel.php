<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartaoModel extends Model
{
    use HasFactory;

    protected $table = 'cartao';

    protected $fillable = [
        'id',
        'code',
        'nome'
    ];
}

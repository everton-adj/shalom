<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComandaModel extends Model
{
    use HasFactory;

    protected $table = 'comanda';

    protected $fillable = [
        'id',
        'nome',
        'item_id',
        'qtde'
    ];
}

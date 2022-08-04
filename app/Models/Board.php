<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    public function pertandingan()
    {
        return $this->belongsTo(Pertandingan::class, 'id_pertandingan');
    }
}

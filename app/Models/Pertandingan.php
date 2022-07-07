<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertandingan extends Model
{
    use HasFactory;
    protected $fillable = ['nama_pertandingan', 'jumlah_pasangan', 'jumlah_board'];

    public function players()
    {
        return $this->hasMany(Player::class, 'id_pertandingan');
    }
}

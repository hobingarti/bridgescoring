<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertandingan extends Model
{
    use HasFactory;
    protected $fillable = ['nama_pertandingan', 'jumlah_pasangan', 'jumlah_board', 'tanggal'];

    public function players()
    {
        return $this->hasMany(Player::class, 'id_pertandingan');
    }

    public function boards()
    {
        return $this->hasMany(Board::class, 'id_pertandingan');
    }

    // mutator
    public function setTanggalAttribute($value)
    {
        $this->attributes['tanggal'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }

    public function getTanggalAttribute($value)
    {
        return $value == '' ? '' : Carbon::parse($value)->format('d/m/Y');
    }
}

<?php

namespace App\Models;

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

    // mutator
    public function setTanggalAttribute($value)
    {
        $arr = explode('/', $value);

        if(count($arr) == 3){
            $this->attributes['tanggal'] = $arr[2].'-'.$arr[1].'-'.$arr[0];
        }else{
            $this->attributes['tanggal'] = '';
        }
    }

    public function getTanggalAttribute($value)
    {
        $arr = explode('-', $value);
        
        if(count($arr) == 3){
            return $arr[2].'/'.$arr[1].'/'.$arr[0];
        }else{
            return '';
        }
    }
}

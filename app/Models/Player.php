<?php

namespace App\Models;

use App\Models\Board;
use App\Models\Match;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;
    protected $fillable = ['nama_player'];

    public function getTotalPointAttribute(){
        $boardsNs = Match::where('id_pemain_ns', '=', $this->id)->get();
        $boardsEw = Match::where('id_pemain_ew', '=', $this->id)->get();

        $totalPoint = 0;
        foreach($boardsNs as $board){
            $totalPoint = $totalPoint + $board->point_ns;
        }

        foreach($boardsEw as $board){
            $totalPoint = $totalPoint + $board->point_ew;
        }

        return $totalPoint;
    }
}

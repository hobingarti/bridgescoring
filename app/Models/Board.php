<?php

namespace App\Models;

use App\Models\Match;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    public function pertandingan()
    {
        return $this->belongsTo(Pertandingan::class, 'id_pertandingan');
    }

    public function matches()
    {
        return $this->hasMany(Match::class, 'id_board');
    }

    public function processPoint()
    {
        $maxPoint =  $this->pertandingan->match_max_count_bye*2-2;

        $matches = Match::where('id_board', '=', $this->id)->where('id_pemain_ns', '!=', 0)->where('id_pemain_ew', '!=', 0)->orderBy('score_ns', 'DESC')->get();
        $arrScore = array();
        $point = $maxPoint;
        foreach($matches as $i => $match){
            $arrScore[$match->score_ns][$i] = $point;
            $point = $point - 2;
        }

        foreach($arrScore as $scoreCluster){
            $sumNilai = 0;
            foreach($scoreCluster as $point){
                $sumNilai = $sumNilai + $point;
            }
            $finalPoint = $sumNilai/count($scoreCluster);
            // echo $finalPoint;
            foreach($scoreCluster as $index => $point){
                $matches[$index]->point_ns = $finalPoint;
                $matches[$index]->point_ew = $maxPoint - $finalPoint;
                $matches[$index]->save();
            }
        }

        Match::where('id_board', '=', $this->id)
            ->where(function($q){
                $q->orWhere('id_pemain_ns', '=', 0);
                $q->orWhere('id_pemain_ew', '=', 0);
            })
            ->update(['point_ns'=>0, 'point_ew'=>0]);

        // echo "<pre>";
        // print_r($arrScore);
        // exit();
    }
}

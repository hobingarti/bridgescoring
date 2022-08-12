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
        $id_jenis_bye = $this->pertandingan->id_jenis_bye;
        $arrScore = array();
        $matches = Match::where('id_board', '=', $this->id)
                        ->where('id_pemain_ns', '!=', 0)
                        ->where('id_pemain_ew', '!=', 0)
                        ->orderBy('score_ns', 'DESC')
                        ->get()->keyBy('id');
        $maxPoint = $this->matches->count()*2-2;
        $point = $maxPoint;

        // pengisian matchbye 
        $matchBye = Match::where('id_board', '=', $this->id)
                        ->where(function($q){
                            $q->where('id_pemain_ns', '=', 0);
                            $q->orWhere('id_pemain_ew', '=', 0);
                        })
                        ->orderBy('score_ns', 'DESC')
                        ->first();
        
        if($matchBye){
            if($id_jenis_bye == 1){
                if($matchBye->id_pemain_ew == 0){
                    $matchBye->point_ns = $point;
                    $matchBye->point_ew = 0;
                    $point = $point-2;
                }else{
                    $matchBye->point_ns = 0;
                    $matchBye->point_ew = $point;
                }
                $matchBye->save();
            }else{
                $maxPoint = $maxPoint-2;
                $matchBye->point_ns = 0;
                $matchBye->point_ew = 0;
                $matchBye->save();

                $point = $point-2;
            }
        }

        // refit points for matches
        foreach($matches as $key => $match){
            $arrScore[$match->score_ns][$key] = $point;
            $point = $point - 2;
        }

        // redistribute point if same score exists
        foreach($arrScore as $scoreCluster){
            $sumPoint = 0;
            foreach($scoreCluster as $point){
                $sumPoint = $sumPoint + $point;
            }
            $finalPoint = $sumPoint/count($scoreCluster);
            // echo $finalPoint;
            foreach($scoreCluster as $index => $point){
                $matches[$index]->point_ns = $finalPoint;
                $matches[$index]->point_ew = $maxPoint - $finalPoint;
                $matches[$index]->save();
            }
        }
    }
}

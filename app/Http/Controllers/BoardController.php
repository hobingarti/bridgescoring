<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Match;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function show(Board $board)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function edit(Board $board)
    {
        $formUrl = url('board/'.$board->id);
        $formMethod = 'put';
        //
        $pertandingan = $board->pertandingan;
        $matchs = Match::where('id_board', '=', $board->id)->get();
        $players = [''=>' - ']+$pertandingan->players->pluck('nomor_player', 'id')->toArray()+['0'=>'Bye'];
        
        // mendapatkan kekurangan match
        $playersCount = $pertandingan->players->count();
        $matchsMaxCount = ceil($playersCount/2);
        $matchLack =  $matchsMaxCount-count($matchs);

        if($matchLack > 0){
            for($i = 0; $i < $matchLack; $i++){
                $match = new Match;
                $matchs[] = $match;
            }
        }

        // pembuatan link next prev board
        $minBoard = $pertandingan->boards->min('id');
        $maxBoard = $pertandingan->boards->max('id');

        $nextBoard = $board->id + 1;
        $prevBoard = $board->id - 1;
        if($board->id == $minBoard){
            $prevBoard = $maxBoard;
        }elseif($board->id == $maxBoard){
            $nextBoard = $minBoard;
        }

        return view('board.form')->with(compact('board', 'players', 'matchs', 'formUrl', 'formMethod', 'pertandingan', 'nextBoard', 'prevBoard'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Board $board)
    {
        //
        $dataMatchs = $request->match;
        foreach($dataMatchs as $dataMatch){
            if($dataMatch['id'] != ''){
                $match = Match::find($dataMatch['id']);
            }else{
                $match = new Match;
            }

            if($dataMatch['score_ns'] != '' && $dataMatch['id_pemain_ns'] != '' && $dataMatch['id_pemain_ew'] != ''){
                $match->id_pemain_ns = $dataMatch['id_pemain_ns'];
                $match->id_pemain_ew = $dataMatch['id_pemain_ew'];
                $match->score_ns = $dataMatch['score_ns'];
                $match->id_board = $board->id;
                $match->save();
            }
        }

        $board->processPoint();

        return redirect('board/'.$board->id.'/edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function destroy(Board $board)
    {
        //
    }
}

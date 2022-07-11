<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Pertandingan;
use App\Models\Player;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

class PertandinganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('pertandingan.index');
    }

    public function listPertandingan()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $pertandingan = new Pertandingan;
        $formUrl = url('pertandingan');
        $formMethod = 'post';

        return view('pertandingan.form')->with(compact('pertandingan', 'formUrl', 'formMethod'));
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
        DB::beginTransaction();
        try {
            $pertandingan = new Pertandingan;
            $pertandingan->fill($request->all());
            if($pertandingan->save()){
                // create boards
                for ($i=1; $i <= $pertandingan->jumlah_board ; $i++) { 
                    $board = new Board;
                    $board->id_pertandingan = $pertandingan->id;
                    $board->nomor_board = $i;
                    $board->save();
                }

                for ($i=1; $i <= $pertandingan->jumlah_pasangan ; $i++) { 
                    $player = new Player;
                    $player->id_pertandingan = $pertandingan->id;
                    $player->nama_player = '';
                    $player->nomor_player = $i;
                    $player->save();
                }
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            print_r($e->getMessage());
        }
    }

    public function managePlayers(Pertandingan $pertandingan)
    {
        $formMethod = 'post';
        $formUrl = url('pertandingan/'.$pertandingan->id.'/updatePlayers');

        return view('pertandingan.managePlayers')->with(compact('pertandingan', 'formMethod', 'formUrl'));
    }

    public function updatePlayers(Pertandingan $pertandingan, Request $request)
    {
        foreach ($request->nama_player as $key => $nama_player) {
            $player = Player::find($key);
            if($player->id_pertandingan == $pertandingan->id)
            {
                $player->nama_player = $nama_player;
                $player->save();
            }
        }

        return redirect(url('pertandingan/'.$pertandingan->id.'/managePlayers'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pertandingan  $pertandingan
     * @return \Illuminate\Http\Response
     */
    public function show(Pertandingan $pertandingan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pertandingan  $pertandingan
     * @return \Illuminate\Http\Response
     */
    public function edit(Pertandingan $pertandingan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pertandingan  $pertandingan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pertandingan $pertandingan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pertandingan  $pertandingan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pertandingan $pertandingan)
    {
        //
    }
}

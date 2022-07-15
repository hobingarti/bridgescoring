<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Pertandingan;
use App\Models\Player;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Datatables;

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
        DB::statement(DB::raw('set @rownum = 0'));
        $data = Pertandingan::select(
                                DB::raw('@rownum  := @rownum  + 1 AS no'), 
                                'id',
                                'tanggal',
                                'nama_pertandingan',
                                'jumlah_pasangan',
                                'jumlah_board'
                            );

        return Datatables::of($data)
            ->addColumn('action', function($data){
                return '<a href="'.url('pertandingan/'.$data->id.'/edit').'" class="btn btn-primary btn-xs"> <i class="fa fa-edit"></i> Edit </a>';
            })
            ->make(true);

        // example
        // DB::statement(DB::raw('set @rownum = 0'));
        // $data = Kuisioner::leftJoin('jenis_kuisioners', 'jenis_kuisioners.id', 'kuisioners.id_jenis_kuisioner')
        //         ->select([
        //             DB::raw('@rownum  := @rownum  + 1 AS no'), 
        //             'kuisioners.*',
        //             'jenis_kuisioners.nama_jenis_kuisioner'
        //         ])
        //         ->where('kuisioners.nama_kuisioner', 'LIKE', '%'.$request->nama_kuisioner.'%')
        //         ->where('kuisioners.id_jenis_kuisioner', 'LIKE', ($request->id_jenis_kuisioner == '' ? '%%' : $request->id_jenis_kuisioner))
        //         ->where('kuisioners.id_tahun_ajar', 'LIKE', ($request->id_tahun_ajar == '' ? '%%' : $request->id_tahun_ajar));

        // if(Rbac::hasPrevilege('kuisioner.is_level_1')){
            

        // }elseif(Rbac::hasPrevilege('kuisioner.is_level_2')){
        //     // disini where id_fakultas
        //     $id_unit = Rbac::activeUnit()->id_unit;

        //     $data = $data->where('kuisioners.id_fakultas', '=', $id_unit);
        // }elseif(Rbac::hasPrevilege('kuisioner.is_level_3')){
        //     // disini where id_jurusan
        //     $id_sunit = Rbac::activeSunit()->id_sunit;

        //     $data = $data->where('kuisioners.id_jurusan', '=', $id_sunit);
        // }else{
        //     // return response()->json(array('response'=>'No Response Available'));
        // }

        // return Datatables::of($data)
        // ->editColumn('id_tahun_ajar', function($data){
        //     if(strlen(strval($data->id_tahun_ajar))!=5){
        //         return "-";
        //     }

        //     return $data->tahun_ajar->nama_tahun_ajar;
        // })
        // ->addcolumn('action', function($data){
        //     $id = ($this->doEncrypt ? Crypt::encrypt($data->id) : $data->id);
        //     return '<a href="'.url("/kuisioner/".$id."/edit").'" title="'.trans('label.tooltip_edit_data').'" data-toggle="modal" data-id="'.$id.'"><span class="label label-primary" data-toggle="tooltip" data-placement="top" title="'.trans('label.tooltip_edit_data').'"><i class="fa fa-edit"></i></span></a>
        //     <a href="'.url("/report/".$id."/report_per_kuisioner").'" title="Lihat Report" data-toggle="modal" data-id="'.$id.'"><span class="label label-success" data-toggle="tooltip" data-placement="top" title="Lihat Report"><i class="fa fa-list"></i></span></a>
        //     <span class="label label-danger label-delete" onClick="delete_kuisioner('.$id.')" data-toggle="tooltip" data-placement="top" title="'.trans('label.tooltip_delete_data').'"><i class="fa fa-times"></i></span>';
        // })
        // ->make(true);
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

<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Match;
use App\Models\Pertandingan;
use App\Models\Player;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Datatables;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

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
                return '<a href="'.url('pertandingan/'.$data->id.'/edit').'" class="btn btn-primary btn-xs"> <i class="fa fa-wrench"></i> Edit </a>
                        <a href="'.url('pertandingan/'.$data->id.'/boards').'" class="btn btn-primary btn-xs"> <i class="fa fa-edit "></i> Score</a>';
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

            return redirect('pertandingan/'.$pertandingan->id.'/boards');
        } catch (\Exception $e) {
            DB::rollback();

            print_r($e->getMessage());

            return redirect('pertandingan/create');
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

    public function boards(Pertandingan $pertandingan, Request $request)
    {
        return view('pertandingan.boards')->with(compact('pertandingan'));
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
        $formUrl = url('pertandingan/'.$pertandingan->id);
        $formMethod = 'put';

        return view('pertandingan.form')->with(compact('pertandingan', 'formUrl', 'formMethod'));
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
        $pertandingan->fill($request->only('nama_pertandingan', 'tanggal'));
        $pertandingan->save();

        return redirect('pertandingan/'.$pertandingan->id.'/edit');
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

    public function ranks(Pertandingan $pertandingan)
    {
        $arrBoard = array();
        $boards = $pertandingan->boards;
        foreach($boards as $board)
        {
            $arrBoards[$board->nomor_board] = '-';
        }

        $arrPlayers = array();
        $players = $pertandingan->players;
        foreach($players as $player)
        {
            $arrPlayers[$player->id]['player'] = $player;
            $arrPlayers[$player->id]['nomor_player'] = $player->nomor_player;
            $arrPlayers[$player->id]['boards'] = $arrBoards;
            $arrPlayers[$player->id]['total_score'] = 0;
        }

        $matches = Match::leftJoin('boards', 'boards.id', 'matches.id_board')
                        ->where('boards.id_pertandingan', '=', $pertandingan->id)
                        ->select('matches.*', 'boards.nomor_board')
                        ->get();
        foreach($matches as $match)
        {
            if($match->id_pemain_ns != 0){
                $arrPlayers[$match->id_pemain_ns]['boards'][$match->nomor_board] = $match->point_ns;
                $arrPlayers[$match->id_pemain_ns]['total_score'] += $match->point_ns;
            }

            if($match->id_pemain_ew != 0){
                $arrPlayers[$match->id_pemain_ew]['boards'][$match->nomor_board] = $match->point_ew;
                $arrPlayers[$match->id_pemain_ew]['total_score'] += $match->point_ew;
            }
        }        

        // echo "<pre>";
        // print_r($arrPlayers);
        // exit();

        return view('pertandingan.ranks')->with(compact('arrPlayers', 'arrBoards', 'pertandingan'));
    }

    public function ranksExcel(Pertandingan $pertandingan)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Pasangan');
        $sheet->setCellValue('C1', 'Total Skor');
        
        $keyAdder = 2;
        $keyP = 0;
        foreach($pertandingan->boards as $key => $board){
            $keyP++;
            $frontP = intdiv(($keyP+$keyAdder), 26);
            $backP = ($keyP+$keyAdder) % 26;

            if($frontP < 1){
                $frontLetter = '';
            }else{
                $frontLetter = chr(64+$frontP);
            }

            $backLetter = chr(65+$backP);
            $sheet->setCellValue($frontLetter.$backLetter.'1', $board->nomor_board);
        }

        $arrPoints = array();
        $matches = Match::leftJoin('boards', 'boards.id', 'matches.id_board')
                        ->leftJoin(DB::raw('players as players_ns'), 'players_ns.id', 'matches.id_pemain_ns')
                        ->leftJoin(DB::raw('players as players_ew'), 'players_ew.id', 'matches.id_pemain_ew')
                        ->where('boards.id_pertandingan', '=', $pertandingan->id)
                        ->select('matches.*', 'boards.nomor_board', DB::raw('players_ns.nomor_player AS nomor_player_ns'), DB::raw('players_ew.nomor_player AS nomor_player_ew'))
                        ->get();

        foreach ($matches as $key => $match) {
            if($match->id_pemain_ns > 0){
                $arrPoints[$match->nomor_player_ns][$match->nomor_board] = $match->point_ns;
            }
            
            if($match->id_pemain_ew > 0){
                $arrPoints[$match->nomor_player_ew][$match->nomor_board] = $match->point_ew;
            }
        }

        // echo "<pre>";
        // print_r($arrPoints);
        // exit();

        $rowNo = 1;
        foreach($pertandingan->players as $key => $player)
        {
            $rowNo++;
            $sheet->setCellValue('A'.$rowNo, $player->nomor_player);
            $sheet->setCellValue('B'.$rowNo, $player->nama_player);
            $sheet->setCellValue('C'.$rowNo, $player->total_point);
            
            if(array_key_exists($player->nomor_player, $arrPoints)){
                foreach($pertandingan->boards as $key => $board){
                    $frontP = intdiv(($board->nomor_board+$keyAdder), 26);
                    $backP = ($board->nomor_board+$keyAdder) % 26;

                    if($frontP < 1){
                        $frontLetter = '';
                    }else{
                        $frontLetter = chr(64+$frontP);
                    }

                    $backLetter = chr(65+$backP);
                    
                    if(array_key_exists($board->nomor_board, $arrPoints[$player->nomor_player])){
                        $sheet->setCellValue($frontLetter.$backLetter.$rowNo, $arrPoints[$player->nomor_player][$board->nomor_board]);
                    }
                }
            }
        }

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Rank '.$pertandingan->nama_pertandingan.'.xlsx"');
        $writer->save("php://output");
    }
}

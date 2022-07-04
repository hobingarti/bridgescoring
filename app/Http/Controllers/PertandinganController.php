<?php

namespace App\Http\Controllers;

use App\Models\Pertandingan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

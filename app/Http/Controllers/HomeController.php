<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// model
use App\Models\Pertandingan;

class HomeController extends Controller
{
    //
    public function index()
    {
        return redirect('pertandingan');
        $pertandingans = Pertandingan::all();
        return view('home.index')->with(compact('pertandingans'));
    }
}

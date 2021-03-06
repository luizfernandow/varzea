<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('welcome');
    }

    public function offline()
    {
        return view('offline');
    }

    public function worldChampionships()
    {
        return view('worldChampionships');
    }

    public function calendar()
    {
        return view('calendar');
    }
}

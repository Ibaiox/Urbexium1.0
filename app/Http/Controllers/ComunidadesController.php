<?php
namespace App\Http\Controllers;

class ComunidadesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('comunidades.index');
    }
}

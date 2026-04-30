<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Localizacion;


class SpotController extends Controller
{
  public function index()
    {
 $spots = Localizacion::paginate(10);

    return view('spots.index', compact('spots'));
    }
}

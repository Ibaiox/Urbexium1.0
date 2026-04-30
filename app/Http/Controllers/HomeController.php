<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Spot;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        return view('dashboard.index', [
            'user'          => $user,
            'recentSpots'   => [],   // $user->spots()->latest()->take(5)->get()
            'recentActivity'=> [],   // Activity::where('user_id', $user->id)->latest()->take(10)->get()
            'nearbySpots'   => [],   // Spot::nearUser($user)->popular()->take(5)->get()
            'favoriteSpots' => [],   // $user->favoriteSpots()->take(5)->get()
            'exploredSpots' => [],   // $user->exploredSpots()->latest('pivot_explored_at')->take(5)->get()
        ]);
    }
}

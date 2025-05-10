<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user()->load('role');
        // redirect to dashboard if user is admin or doctor
        if ($user->role && ($user->role->name == 'admin' || $user->role->name == 'doctor')) {
            return redirect()->to('/dashboard');
        };
        return view('home');
    }
}

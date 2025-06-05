<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    // public function dashboard()
    // {
    //     return view('dashboard', [
    //         'title' => 'Dashboard'
    //     ]);
    // }

    public function dashboard()
    {
        return view('dashboard', [
            'title' => 'Dashboard',
            'user' => Auth::user(),
        ]);
    }

    public function user()
    {
        $user = Auth::user();

        if ($user->level === 'admin') {
            return view('user.admin', ['title' => 'Profil Admin']);
        } else {
            return view('user.mahasiswa', ['title' => 'Profil Mahasiswa']);
        }
    }
}

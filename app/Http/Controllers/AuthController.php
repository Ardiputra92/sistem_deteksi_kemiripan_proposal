<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;               // << Tambahkan ini
use Illuminate\Support\Facades\Hash; // << Tambahkan ini

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // Tambahkan kedua method ini di sini:
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'nim' => 'required|string|max:50',
            'program_studi' => 'required|string|max:100',
            'kelas' => 'required|string|max:50',
            'no_hp' => 'required|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => 'mahasiswa', // default role
            'nim' => $request->nim,
            'program_studi' => $request->program_studi,
            'kelas' => $request->kelas,
            'no_hp' => $request->no_hp,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

}


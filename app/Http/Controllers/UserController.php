<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // public function changePassword(Request $request)
    // {
    //     $request->validate([
    //         'current_password' => 'required',
    //         'new_password' => 'required|string|min:8|confirmed',
    //     ]);

    //     $user = Auth::user();

    //     if (!Hash::check($request->current_password, $user->password)) {
    //         return back()->with('error', 'Password saat ini salah.');
    //     }

    //     // $user->update([
    //     //     'password' => Hash::make($request->new_password),
    //     // ]);

    //     $user->password = Hash::make($request->new_password);
    //     $user->save();

    //     // dd('Password updated:', $user->password);

    //     return back()->with('success', 'Password berhasil diperbarui.');
    // }

    public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|string|min:8|confirmed',
    ]);

    $user = Auth::user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->with('error', 'Password saat ini salah.');
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    // ⬇️ Tampilkan debug setelah berhasil
    dd('Password berhasil diupdate untuk user: ' . $user->email);

    // return back()->with('success', 'Password berhasil diperbarui.');
}

}

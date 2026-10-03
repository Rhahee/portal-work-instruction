<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function create() { return view('auth.login'); }
    public function store(Request $request)
    {
        $credentials = $request->validate(['nik' => ['required', 'string'], 'password' => ['required', 'string']]);
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['nik' => 'NIK atau password tidak sesuai.'])->onlyInput('nik');
        }
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard.index'));
    }
    public function destroy(Request $request)
    {
        Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() { return view('login'); }
    public function showRegister() { return view('register'); }

    public function login(Request $r)
    {
        $c = $r->validate(['username' => 'required', 'password' => 'required'], ['required' => 'Input belum lengkap']);
        if (Auth::attempt($c)) {
            $r->session()->regenerate();
            return redirect()->route('dashboard');
        }
        return back()->withErrors(['username' => 'Username atau Password salah'])->onlyInput('username');
    }

    public function register(Request $r)
    {
        $d = $r->validate([
            'username' => 'required|max:50|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ], ['unique' => 'Username or email is already used.', 'min' => 'Password must be at least 6 characters.']);
        User::create(['name' => $d['username'], 'password' => Hash::make($d['password'])] + $d);
        return redirect()->route('login')->with('ok', 'Account created. Please login.');
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect()->route('login');
    }
}
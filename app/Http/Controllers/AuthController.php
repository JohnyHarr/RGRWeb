<?php

namespace App\Http\Controllers;

use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function registration()
    {
        return view('registration');
    }

    public function adminRegistration()
    {
        return view('adminRegisterNewUser');
    }

    public function authenticate(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        $credentials['email'] = $request->email;
        $credentials['password'] = $request->password;
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], true)) {
            $request->session()->regenerate();
            return response('auth_success', 200)->header('Content-Type', 'text/plain');
        } else {
            return response('auth_failed', 200)->header('Content-Type', 'text/plain');
        }
    }

    public function register(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        $credentials = $request->validate([
            'email' => 'required|string|email|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8|max:50'
        ]);
        $user = User::create([
            'name' => $credentials['username'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password']),
        ]);
        Auth::login($user, true);
        $request->session()->regenerate();
        return redirect()->route('home');
    }

    public function registerNewUser(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8|max:50',
            'role' => ''
        ]);
        $user = User::create([
            'name' => $credentials['username'],
            'email' => $credentials['email'],
            'role' => ($credentials['role'] != null) ? $credentials['role'] : 'user',
            'password' => Hash::make($credentials['password']),
        ]);
        return response('user_registered', 200)->header('Content-Type', 'text/plain');
    }

    public function checkEmail(Request $request)
    {
        $email = $request->getContent();
        $user = User::where('email', $email)->first();
        if ($user) {
            return response('invalid', 200)->header('Content-Type', 'text/plain');
        } else {
            return response('valid', 200)->header('Content-Type', 'text/plain');
        }
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();
            session()->regenerate();
        }
        return redirect()->route('home');
    }

}

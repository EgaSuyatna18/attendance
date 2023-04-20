<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{

    function __construct() {
        $this->middleware(function ($request, $next) {
            if(session()->has('auth')) {
                redirect('/dashboard')->send();
            }
            return $next($request);
        });
    }

    function register() {
        return view('auth.register');
    }

    function hitRegister(Request $request) {
        $validated = $request->validate([
            'name' => 'required|min:4|max:50',
            'email' => 'required|email',
            'password' => 'required|min:8|max:18|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^\w\d\s:])([^\s]){8,}$/'
        ]);

        $response = Http::post(env('ENDPOINT') . '/register', [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password']
        ]);

        if($response->status() != 201) {
            return redirect('/register')->withErrors(['Failed Registered' => 'Registration failed for unknown reason!']);
        }

        return redirect('/login')->with('registered', true);
    }

    function login() {
        return view('auth.login');
    }

    function hitLogin(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $response = Http::post(env('ENDPOINT') . '/login', [
            'email' => $validated['email'],
            'password' => $validated['password']
        ]);

        if($response->status() != 200) {
            return redirect()->back()->withErrors(['failed_login' => 'Username or Password incorrect!']);
        }

        session()->put(['auth' => $response->collect()['data']]);

        $response2 = Http::get(env('ENDPOINT') . '/schedule/schedule');

        if($response2->status() != 200) {
            return redirect()->back()->withErrors(['failed_login' => 'No active schedule!']);
        }

        // dd(env('ENDPOINT') . '/schedule/'. $response2->collect()['id'] . '/' . session()->get('auth')['id']);

        if($request->input('attend')) {
            $response3 = Http::post(env('ENDPOINT') . '/schedule/'. $response2->collect()['id'] . '/' . session()->get('auth')['id']);
            if($response3->status() != 200) {
                return redirect('/login/attend')->withErrors(['failed_login' => 'Failed attend for unknown reason!']);
            }
        }
        
        return redirect('/dashboard')->with('attend', 'Success register attendant to schedule.');
    }

    function logout() {
        session()->flush();
        return redirect('/login');
    }
}

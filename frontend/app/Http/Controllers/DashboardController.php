<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    function __construct() {
        $this->middleware(function ($request, $next) {
            if(!session()->has('auth')) {
                redirect('/login')->send();
            }
            return $next($request);
        });
    }

    function isAdmin() {
        if(session()->get('auth')['role'] != 'admin') {
            return redirect('/dashboard')->send();
        }
    }

    function isLecturer() {
        if(session()->get('auth')['role'] != 'lecturer') {
            return redirect('/dashboard')->send();
        }
    }

    function index() {
        if(session()->get('auth')['role'] == 'user') {
            return view('dashboard.attend', [
                'title' => 'Attend Schedule'
            ]);
        }
        return view('dashboard.index', ['title' => 'Dashboard']);
    }

    function user() {
        self::isAdmin();

        $response = Http::get(env('ENDPOINT') . '/users');

        return view('dashboard.user.index', [
            'title' => 'User',
            'users' => $response->collect()
        ]);
    }

    function addUser(Request $request) {
        self::isAdmin();

        $validated = $request->validate([
            'name' => 'required|min:4|max:50',
            'email' => 'required|email',
            'password' => 'required|min:8|max:18|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^\w\d\s:])([^\s]){8,}$/',
            'role' => 'required|in:admin,user,lecturer'
        ]);

        $response = Http::post(env('ENDPOINT') . '/users', [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role']
        ]);

        if($response->status() != 201) {
            return redirect('/user')->withErrors(['failed' => 'Failed add new user for unknown reason!']);
        }

        return redirect('/user');
    }

    function deleteUser($id) {
        self::isAdmin();

        $response = Http::delete(env('ENDPOINT') . '/users/' . $id);

        if($response->status() != 200) {
            return redirect('/user')->withErrors(['failed' => 'Failed delete user for unknown reason!']);
        }

        return redirect('/user');
    }

    function updateUser(Request $request, $id) {
        self::isAdmin();

        $validated = $request->validate([
            'name' => 'required|min:4|max:50',
            'email' => 'required|email',
            'password' => 'required|min:8|max:18|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^\w\d\s:])([^\s]){8,}$/',
            'role' => 'required|in:admin,user,lecturer'
        ]);

        $response = Http::put(env('ENDPOINT') . '/users/' . $id, [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role']
        ]);

        if($response->status() != 200) {
            return redirect('/user')->withErrors(['failed' => 'Failed update user for unknown reason!']);
        }

        return redirect('/user');
    }

    function schedule() {
        self::isLecturer();

        $response = Http::get(env('ENDPOINT') . '/schedule');

        return view('dashboard.schedule.index', [
            'title' => 'schedule',
            'schedules' => $response->collect()
        ]);
    }


    function addschedule(Request $request) {
        self::isLecturer();

        $validated = $request->validate([
            'name' => 'required|min:3|max:50',
            'date' => 'required',
            'start' => 'required',
            'end' => 'required|after:start'
        ]);

        $response = Http::post(env('ENDPOINT') . '/schedule', [
            'name' => $validated['name'],
            'date' => $validated['date'],
            'start' => $validated['start'] . ':00',
            'end' => $validated['end'] . ':00'
        ]);

        if($response->status() != 200) {
            if($response->status() == 425) {
                return redirect('/schedule')->withErrors(['failed' => 'Schedule crash!']);
            }
            return redirect('/schedule')->withErrors(['failed' => 'Failed add schedule for unknown reason!']);
        }

        return redirect('/schedule');
   }

   function deleteschedule($id) {
        self::isLecturer();

        $response = Http::delete(env('ENDPOINT') . '/schedule/' . $id);

        if($response->status() != 200) {
            return redirect('/schedule')->withErrors(['failed' => 'Failed delete schedule for unknown reason!']);
        }

        return redirect('/schedule');
   }

   function updateschedule(Request $request, $id) {
        self::isLecturer();

        $validated = $request->validate([
            'name' => 'required|min:3|max:50',
            'date' => 'required',
            'start' => 'required',
            'end' => 'required|after:start'
        ]);

        $response = Http::put(env('ENDPOINT') . '/schedule/' . $id, [
            'name' => $validated['name'],
            'date' => $validated['date'],
            'start' => $validated['start'] . ':00',
            'end' => $validated['end'] . ':00'
        ]);

        if($response->status() != 200) {
            if($response->status() == 425) {
                return redirect('/schedule')->withErrors(['failed' => 'Schedule crash!']);
            }
            return redirect('/schedule')->withErrors(['failed' => 'Failed add schedule for unknown reason!']);
        }

        return redirect('/schedule');
    }

    function detail($id) {
        self::isLecturer();

        $response = Http::get(env('ENDPOINT') . '/schedule/' . $id);

        if($response->status() != 200) {
            return redirect('/schedule')->withErrors(['failed' => 'Failed get info for unknown reason!']);
        }

        return view('dashboard.schedule.info', [
            'title' => 'info',
            'users' => $response->collect()['users']
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PresentController extends Controller
{
    function index() {
        $response = Http::get(env('ENDPOINT') . '/schedule/schedule');

        return view('present.index', [
            'title' => 'Submit Your Presence',
            'active' => $response->status() == 200,
            'schedule' => $response->collect()
        ]);
    }

    function attend() {
        return view('auth.login', ['attend' => true]);
    }

}

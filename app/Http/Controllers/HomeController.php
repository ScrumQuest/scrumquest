<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        return view('home', [
            'user' => $request->user()
        ]);
    }
}

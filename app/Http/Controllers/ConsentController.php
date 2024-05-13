<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConsentController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $user = $request->user();

        if($user->consented_at != null) {
            return redirect(route('projects.index'));
        }

        return view('consent', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource
     */
    public function update(Request $request)
    {
        $currentDate = new \DateTime();
        $currentDate = $currentDate->format('Y/m/d');
        $request->validate([
            'currentDate' => "required|date|date_equals:$currentDate",
        ]);

        $user = $request->user();
        $user->consented_at = new \DateTime();
        $user->save();

        return redirect(route('projects.index'));
    }
}

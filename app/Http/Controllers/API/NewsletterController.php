<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255',
        ]);

        $newsletter = \App\Models\Newsletter::updateOrCreate([
            'owner_id' => owner()->id,
            'email'    => $request->email,
        ]);

        return response()->json([
            'message' => 'You has been subscribed to ' . owner()->name . ' newsletter.',
        ]);
    }
}

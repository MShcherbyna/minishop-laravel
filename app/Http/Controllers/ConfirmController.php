<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfirmController extends Controller
{
    public function index()
    {
        if (! session()->has('success_message')) {
            return redirect('/');
        }

        return view('success');
    }
}

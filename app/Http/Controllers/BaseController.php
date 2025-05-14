<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected function handleException(\Closure $callback)
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            // return redirect()->back()->with('error', $e->getMessage());
            return redirect()->route('dashboard')->with('error', $e->getMessage());
        }
    }
}

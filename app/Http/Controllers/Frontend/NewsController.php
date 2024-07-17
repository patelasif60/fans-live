<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Show news details page.
     */
    public function show(Request $request)
    {
        return redirect()->route('login');
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Get iOS Json.
     */
    public function iosJson()
    {
        $data = file_get_contents(resource_path('ios/apple-app-site-association'));

        return response($data, 200)
                  ->header('Content-Type', 'application/json');
    }
}

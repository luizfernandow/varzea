<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NuxtController extends Controller
{
    /**
     * Handle the SPA request.
     */
    public function __invoke(Request $request) : string
    {
        // If the request expects JSON, it means that
        // someone sent a request to an invalid route.
        if ($request->expectsJson()) {
            abort(404);
        }

        return $this->renderNuxtPage();
    }

    /**
     * Render the Nuxt page.
     */
    protected function renderNuxtPage() : string
    {
        return file_get_contents(public_path('index.html'));
    }
}

<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('About', [
            'appName'        => config('app.name'),
            'laravelVersion' => app()->version(),
            'features'       => [
                'Laravel mengurus routing, validasi, dan database.',
                'Inertia menghubungkan controller ke komponen halaman.',
                'Svelte 5 menampilkan UI dari props.',
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Welcome', [
            'title' => 'My Laravel App',
        ]);
    }

    /**
     * Change language
     *
     * @param string $language
     * @return RedirectResponse
     */
    public function changeLanguage(string $language): RedirectResponse
    {
        Session::put('website_language', $language);

        return redirect()->back();
    }

}

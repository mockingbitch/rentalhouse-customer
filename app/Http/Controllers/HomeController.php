<?php

namespace App\Http\Controllers;

use App\Services\HouseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Constructor
     *
     * @param HouseService $houseService
     */
    public function __construct(
        protected HouseService $houseService,
    )
    {
    }

    public function index(Request $request): Response
    {
        dd($this->houseService->listHouse());
        return Inertia::render('HomePage', [
            'houses' => $this->houseService->listHouse(),
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

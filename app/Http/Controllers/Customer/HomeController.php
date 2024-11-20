<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\HouseService;
use Illuminate\Http\RedirectResponse;
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
    ) {
    }

    /**
     * Home page
     * Created by PhongTranNTQ
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('HomePage');
    }

    /**
     * Change language
     * Created by PhongTranNTQ
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

<?php

namespace App\Http\Controllers;

use App\Support\StorefrontCatalog;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(StorefrontCatalog $catalog): View
    {
        return view('home.index', [
            'title' => 'Voltix – Electronics Store',
            'categories' => $catalog->categories(),
        ]);
    }
}

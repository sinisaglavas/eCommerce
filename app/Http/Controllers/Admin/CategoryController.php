<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\StorefrontCatalog;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(StorefrontCatalog $catalog): View
    {
        return view('admin.categories.index', [
            'categories' => $catalog->categories(),
        ]);
    }
}

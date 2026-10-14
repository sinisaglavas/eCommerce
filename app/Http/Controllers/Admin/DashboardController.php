<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'productsCount' => Product::query()->count(),
            'categoriesCount' => Category::query()->count(),
            'ordersCount' => Order::query()->count(),
            'recentOrders' => Order::query()
                ->latest('created_at')
                ->latest('id')
                ->limit(5)
                ->get(),
        ]);
    }
}

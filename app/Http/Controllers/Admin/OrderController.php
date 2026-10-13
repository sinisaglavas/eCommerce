<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\DemoOrderCatalog;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(DemoOrderCatalog $catalog): View
    {
        return view('admin.orders.index', ['orders' => $catalog->orders(), 'statuses' => $catalog->statuses()]);
    }

    public function show(string $order, DemoOrderCatalog $catalog): View
    {
        $selectedOrder = $catalog->orders()->firstWhere('number', $order);
        abort_unless($selectedOrder, 404);

        return view('admin.orders.show', ['order' => $selectedOrder, 'statuses' => $catalog->statuses()]);
    }
}

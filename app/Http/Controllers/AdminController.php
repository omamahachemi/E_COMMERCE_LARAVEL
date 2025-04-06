<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $productCount = Product::count();
        // $productCount = 0;
        $categoryCount = Category::count();
        // $categoryCount = 0;
        $orderCount = Order::count();
        // $orderCount = 0;
        

        $latestProducts = Product::latest()->take(3)->get();
        $latestOrders = Order::latest()->take(3)->get();
        $latestUsers = User::latest()->take(3)->get();

        return view('admin.dashboard', compact('productCount', 'categoryCount', 'orderCount', 'latestProducts', 'latestOrders', 'latestUsers'));
    }
}

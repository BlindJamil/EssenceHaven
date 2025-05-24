<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProducts = Product::where('featured', true)->with('category')->take(4)->get();
        return view('home', compact('featuredProducts'));
    }
}
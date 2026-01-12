<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function show(Request $request, $table = null)
    {
        if (!$table) {
            $table = $request->query('table', '1'); // Default to table 1 if not set
        }

        $categories = Category::with(['products' => function($query) {
            $query->where('is_available', true);
        }])->orderBy('sort_order')->get();

        return view('menu.index', compact('categories', 'table'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    // Get a list of sizes
    public function index()
    {
        $sizes = Size::all();
        return response()->json($sizes);
    }
}
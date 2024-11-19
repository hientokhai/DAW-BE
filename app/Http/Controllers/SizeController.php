<?php

namespace App\Http\Controllers;
use App\Traits\JsonResponse;
use App\Models\Size;

class SizeController extends Controller
{
    use JsonResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sizes = Size::all();

        return $this->successResponse($sizes, 'Size list.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
}
<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Color;

class ColorController extends Controller
{
    use JsonResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colors = Color::all();

        return $this->successResponse($colors, 'Color list.');
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
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'color_name' => 'required|string|max:255|unique:colors,color_name',
                'color_code' => 'required|string|max:255',
            ], [
                'color_name.required' => 'The color name is empty.',
                'name.unique' => 'Color name already exists. ',
                'color_code.required' => 'The color code is empty.',
            ]);

            $color = Color::create($validatedData);

            return $this->successResponse($color, 'Color created successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Find Color By ID
            $color = Color::find($id);
            if (!$color) {
                return $this->errorResponse('Color not found.');
            }

            // Validate the request data
            $validatedData = $request->validate([
                'color_name' => 'required|string|max:255|unique:colors,color_name',
            ], [
                'color_name.required' => 'The color name is empty.',
                'name.unique' => 'Color name already exists. ',
            ]);

            // Save
            $color->color_name = $validatedData['color_name'];
            $color->save();

            return $this->successResponse($color, 'Color updated successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $color = Color::find($id);

            if (!$color) {
                return $this->errorResponse('Color not found.');
            }

            // Kiểm tra xem có sản phẩm nào đang sử dụng size này không
            $productsUsingSize = ProductVariant::where('color_id', $id)->exists();

            if ($productsUsingSize) {
                return $this->errorResponse('Cannot delete color because it is used by one or more products.');
            }

            $color->delete();

            return $this->successResponse([], 'Color deleted successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
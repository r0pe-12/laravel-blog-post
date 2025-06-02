<?php

namespace App\Http\Controllers;

use App\Http\Resources\Categories\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories = Category::all();
        $collection = CategoryResource::collection($categories);

        return $this->sendResponse($collection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = \Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:categories'],
            'description' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $data = $validator->validated();

        $category = Category::create($data);
        return $this->sendResponse(new CategoryResource($category));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
        return $this->sendResponse(new CategoryResource($category));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
        $validator = \Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($category, 'id')],
            'description' => ['required', 'string', 'max:255']
        ]);


        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $data = $validator->validated();
        $category->update($data);
        return $this->sendResponse(new CategoryResource($category));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return $this->sendResponse([], 'Category deleted successfully.');
    }
}

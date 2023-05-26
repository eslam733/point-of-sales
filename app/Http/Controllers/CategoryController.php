<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function store(Request $request)
    {
        $this->authorize('createCategory', Category::class);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'min:2'],
        ]);
    
        if ($validator->fails()) {
            return $this->errorResponse('Validation error', $validator->errors(), 400);
        }

        $category = Category::create([
            'name' => $request->get('name')
        ]);

        return $this->successResponse('Category created', $category, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $categories = Category::paginate(10);

        return $this->successResponse('Category created', $categories ?? [], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

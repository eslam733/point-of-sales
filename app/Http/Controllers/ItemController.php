<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('createItem', Item::class);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'min:2'],
            'min_people' => ['required', 'integer'],
            'max_people' => ['required', 'integer'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ]);
    
        if ($validator->fails()) {
            return $this->errorResponse('Validation error', $validator->errors(), 400);
        }

        $item = Item::create($request->all());

        return $this->successResponse('Item has been saved', $item, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $this->authorize('deleteItem', Item::class);

        $item = Item::where('id', $id)->first();

        if (empty($item)) {
            return $this->errorResponse('Validation error', 'item not found', 404);
        }

        $item->delete();

        return $this->successResponse('Item has been deleted', $item, 200);
    }
}

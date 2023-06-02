<?php

namespace App\Http\Controllers;

use App\Models\FeatureItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeatureItemController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $this->authorize('createItemFeature', FeatureItem::class);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'min:2'],
            'price' => ['required', 'numeric'],
            'item_id' => ['required', 'integer', 'exists:items,id'],
        ]);
    
        if ($validator->fails()) {
            return $this->errorResponse('Validation error', $validator->errors(), 400);
        }

        $featureItem = FeatureItem::create($request->all());

        return $this->successResponse('feature item has been saved', $featureItem, 200);
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
        //
    }
}

<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser {

    protected function successResponse($message = null, $data, $code = 200)
	{
		return response()->json([
			'status'=> true, 
			'message' => $message, 
			'data' => $data
		], $code);
	}

	protected function errorResponse($message = null, $error, $code)
	{
		return response()->json([
			'status'=> false,
			'message' => $message,
			'error' => $error
		], $code);
	}

}
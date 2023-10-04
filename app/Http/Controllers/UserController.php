<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request) {
        return $this->successResponse('users', User::get(), 200);
    }

    public function updatePhone(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'phone' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', $validator->errors(), 400);
        }

        $phone = $request->get('phone');

        $user = auth()->user();

        if (empty($user)) {
            return $this->errorResponse('User not found', [], 404);
        }

        User::where('id', $user->id)->update([
            'phone' => $phone,
        ]);

        return $this->successResponse('Phone updated', [], 200);
    }

    public function updateDeviceToken(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'deviceToken' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', $validator->errors(), 400);
        }

        $deviceToken = $request->get('deviceToken');

        $user = auth()->user();

        if (empty($user)) {
            return $this->errorResponse('User not found', [], 404);
        }

        User::where('id', $user->id)->update([
            'device_token' => $deviceToken,
        ]);

        return $this->successResponse('Device Token updated', [], 200);
    }

    public function checkNumber(Request $request,  $id) {
        $user = User::where('id', $id)->first();

        if (empty($user)) {
            return $this->errorResponse('User not found', [], 404);
        }

        if (!empty($user->phone)) {
            return $this->successResponse('User have a number', [], 200);
        }

        return $this->errorResponse('User not have a number', [], 400);
    }

    public function destory(Request $request, $id) {
        $user = User::where('id', $id)->first();

        if (empty($user)) return $this->errorResponse('user not found', [], 404);

        if ($user->isAdmin()) {
            return $this->errorResponse('can\'t delete admin', [], 403);
        }

        $user->delete();

        return $this->successResponse('user deleted', [], 200);
    }
}

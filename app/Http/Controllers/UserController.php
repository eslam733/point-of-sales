<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request) {
        return $this->successResponse('users', User::get(), 200);
    }

    public function destory(Request $request, $id) {
        $user = User::where('id', $id)->first();

        if ($user->isAdmin()) {
            return $this->successResponse('can\'t delete admin', [], 403);
        }

        $user->delete();

        return $this->successResponse('user deleted', [], 200);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    public function auth(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'accessToken' => ['required',],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', $validator->errors(), 400);
        }

        try {
            $user = Socialite::driver('google')->userFromToken($request->get('accessToken'));
            $finduser = User::where('google_id', $user->id)->first();

            if ($finduser) {

                User::where('email', $user->email)->update([
                    'avatar' => $user->avatar
                ]);
                $user = User::where('email', $user->email)->first();
                $user['token'] = $user->createToken($user->email)->plainTextToken;

            } else {

                $user = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'avatar' => $user->avatar,
                    'email_verified_at' => Carbon::now(),
                    'role_id' => Role::getUserRoleId(),
                    'password' => encrypt(env('DUMMY_GOOGLE_PASSWORD'))
                ]);

                $user['token'] = $user->createToken($user->email)->plainTextToken;
            }

            return $this->successResponse('Login success', $user, 200);

        } catch (Exception $e) {
            return $this->errorResponse('Access token expert', [], 401);
        }
    }

}

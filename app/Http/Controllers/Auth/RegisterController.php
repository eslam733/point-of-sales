<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    // public function register(Request $request) {
    //     $validator = Validator::make($request->all(), [
    //         'email' => ['required', 'unique:users', 'email'],
    //         'password' => ['required', 'min:7'],
    //         'name' => ['required', 'min:2'],
    //     ]);
    
    //     if ($validator->fails()) {
    //         return $this->errorResponse('Validation error', $validator->errors(), 400);
    //     }

    //     $user = $this->create($request->all());

    //     if(!empty($user)) {
    //         return $this->successResponse('User has been created', $user, 200);
    //     } else {
    //         return $this->errorResponse('Can\'t create user', [], 400);
    //     }
    // }

    public function googleRegsiter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'accessToken' => ['required',],
        ]);
    
        if ($validator->fails()) {
            return $this->errorResponse('Validation error', $validator->errors(), 400);
        }

        try {
            $user = Socialite::driver('google')->userFromToken($request->get('accessToken'));
            // $user = Socialite::driver('google')->user();
            $finduser = User::where('google_id', $user->id)->first();
            if ($finduser) {
                User::where('email', $user->email)->update([
                    'avatar' => $user->avatar
                ]);
                $user = User::where('email', $user->email)->first();
                $user['token'] = $user->createToken($user->email)->plainTextToken;
                return $this->successResponse('Login success', $user, 200);
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'avatar' => $user->avatar,
                    'email_verified_at' => Carbon::now(),
                    'password' => encrypt(env('DUMMY_GOOGLE_PASSWORD'))
                ]);
                $newUser['token'] = $newUser->createToken($newUser->email)->plainTextToken;
                return $this->successResponse('Login success', $newUser, 200);
            }
        } catch (Exception $e) {
            dd($e);
            return $this->errorResponse('Can\'t create user', [], 400);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\LoginLog;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }
    /**
     * Create user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request){
      
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telefono' => 'required|string|min:8|max:8',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        
       $login = $this->login();
       
       if($login->original['status'] == 'success'){
            LoginLog::create([
                'name' => $user->name,
                'email' => $user->email,
                'url' => $request->path(),
            ]);
       }
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'login' => $login->original,
            ]
        );
    }
     /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function login()
    { 
        
        $credentials = request(['email', 'password']);
       
        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                                        'status' => 'error',
                                        'message' => 'Unauthorized',
                                    ], 401);
        }

        return $this->respondWithToken($token); # If all credentials are correct - we are going to generate a new access token and send it back on response
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        # Here we just get information about current user
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout(); # This is just logout function that will destroy access token of current user

        return response()->json([
                                    'status' => 'success',
                                    'message' => 'Successfully logged out',
                                ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        # When access token will be expired, we are going to generate a new one wit this function 
        # and return it here in response
        return $this->respondWithToken(auth()->refresh());
    }

      /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        # This function is used to make JSON response with new
        # access token of current user
        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


}

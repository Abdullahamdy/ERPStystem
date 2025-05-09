<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Utils\BusinessUtil;
use App\Utils\ModuleUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    protected $businessUtil;
    protected $moduleUtil;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(BusinessUtil $businessUtil, ModuleUtil $moduleUtil)
    {
        $this->businessUtil = $businessUtil;
        $this->moduleUtil = $moduleUtil;
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->respondData($validator->errors(), 422);
        }
        $user = User::where('username', $request->username)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->respondError(__('auth.failed'), 401);
        }
        if (!$user->business->is_active) {
            return $this->respondError(__('lang_v1.business_inactive'), 403);
        } elseif ($user->status != 'active') {
            return response()->json([
                'success' => false,
                'message' => __('lang_v1.user_inactive')
            ], 403);
        } elseif (!$user->allow_login) {
            return $this->respondError(__('lang_v1.login_not_allowed'), 403);
        } elseif (($user->user_type == 'user_customer') &&
            !$this->moduleUtil->hasThePermissionInSubscription($user->business_id, 'crm_module')
        ) {
          
            return $this->respondError( __('lang_v1.business_dont_have_crm_subscription'), 403);

        }

        $this->businessUtil->activityLog($user, 'login', null, [], false, $user->business_id);
        $token = $user->createToken('auth-token')->accessToken;

        $data = [
            'access_token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'business_id' => $user->business_id,
                'user_type' => $user->user_type,
            ]
        ];
        return $this->respondData($data, 'succes login');
    }


    /**
     * Register a new user (if needed).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Add registration logic if needed
        // This is just a placeholder and should be customized to your requirements

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'username' => 'required|string|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Your user creation logic here...

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(Auth::guard('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::guard('api')->refresh());
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
        $user = Auth::guard('api')->user();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'business_id' => $user->business_id,
                'user_type' => $user->user_type,
                // Add any other user fields you need in the API response
            ]
        ]);
    }
}

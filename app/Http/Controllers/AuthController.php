<?php

namespace App\Http\Controllers;

use App\Enum\ErrorCodes;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User\User;
use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use App\Contracts\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    /**
     * Constructor
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        public UserRepositoryInterface $userRepository,
    )
    {
    }

    /**
     * Login View
     * Created by PhongTranNTQ
     *
     * @Route get("/login", name="login")
     * @return Response
     */
    public function loginView(): Response
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Login
     * Created by PhongTranNTQ
     *
     * @Route post("/login")
     * @param LoginRequest $request
     * @return Application|RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function login(LoginRequest $request): Application|Redirector|RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember') ?? false;
        Log::info('Attempting login for email: ' . $credentials['email']);

        if (Auth::attempt($credentials, $remember)) {
            // Regenerate session to prevent session fixation
            $request->session()->regenerate();

            if (null === auth()->user()->email_verified_at) {
                return redirect()
                    ->route('home')
                    ->with('email', $request->email);
            }

            return redirect()->intended(route('home'))
                ->with('success', __('messages.login.SM-001'));
        }
        Log::warning('Login failed for email: ' . $credentials['email']);

        throw ValidationException::withMessages([
            'error' => __('messages.login.EM-001'),
        ]);
    }

    /**
     * Register View
     * Created by PhongTranNTQ
     *
     * @Route get("/register", name="register")
     * @return Response
     */
    public function registerView(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Register account by Email
     * Created by PhongTranNTQ
     *
     * @Route post("/register")
     * @param RegisterRequest $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
//        try {
            $user = new User();
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            throw ValidationException::withMessages([
                'success' => __('messages.login.SM-001'),
            ]);
            //        } catch (Exception $e) {
//            throw ValidationException::withMessages([
//                'error' => __('messages.login.EM-001'),
//            ]);
//        }
    }

    /**
     * Show user data
     *
     * @return JsonResponse
     * @throws ApiException
     */
    public function show(): JsonResponse
    {
        try {
            $user = Auth::user();

            return response()->json([
                'data' => $user
            ],200);
        } catch (Exception $exception) {
            throw new ApiException(ErrorCodes::NOT_FOUND, $exception);
        }
    }

    /**
     * Display the specified resource.
     */
    public function logout(): JsonResponse
    {
        if(Auth::guard('api')->check()){
            $accessToken = Auth::guard('api')->user()->token();

            \DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $accessToken->id)
                ->update(['revoked' => true]);
            $accessToken->revoke();

            return response()->json([
                'message'   => __('message.logout.success')
            ],200);
        }

        return response()->json([
            'data' => __('message.login.failed')
        ],401);
    }

    /**
     * Require Login
     * @return JsonResponse
     */
    public function requireLogin(): JsonResponse
    {
        return response()->json([
            'message' => 'Unauthorized'
        ], 200);
    }
}

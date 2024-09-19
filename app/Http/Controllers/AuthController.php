<?php

namespace App\Http\Controllers;

use App\Enum\ErrorCodes;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use App\Contracts\Repositories\UserRepositoryInterface;
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
        if (Auth::attempt([
            'email'    => $request->email,
            'password' => $request->password
        ])
        ) {
            if (auth()->user()->email_verified_at === null) :
                return redirect()
                    ->route('register.success')
                    ->with('email', $request->email);
            endif;
            if (session()->has('url.destination')) :
                return redirect(session()->pull('url.destination'));
            endif;

            return redirect()
                ->route('top')
                ->with('success', __('messages.login.SM-001'));
        }

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
     * @return Response
     */
    public function register(RegisterRequest $request)
    {
        dd(1);
        try {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = $this->userRepository->create($input);
            $result['token'] = $user->createToken(env('APP_NAME'))->accessToken;
            $result['first_name'] = $user->first_name;
            $result['last_name'] = $user->last_name;
            $name = $user->first_name . ' ' . $user->last_name;
            $host = request()->root();
            $client = new ClientRepository();
            $newClient = $client->create(
                $user->id,
                $name,
                $host,
                'users',
                false,
                true
            );
            $result['credential'] = [
                'client_name'       => $newClient['name'],
                'client_id'         => $newClient['id'],
                'client_secret_key' => $newClient['secret'],
            ];

            return response()->json([
                'success'   => true,
                'result'    => $result
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ], 200);
        }
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

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailVerificationJob;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Str;
use App\Jobs\SendPasswordResetJob;
use Illuminate\Support\Facades\App;
use App\Jobs\SendPasswordResetSuccessJob;
use App\trait\ApiResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Verified;
use Spatie\Permission\Models\Role;

class UserAuthController extends Controller
{
    use ApiResponse;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        // Ensure at least one identifier is provided
        if (!$request->filled('email') && !$request->filled('phone')) {
            return $this->validationErrorResponse(
                [
                    'email' => __('auth.email_or_phone_required'),
                    'phone' => __('auth.email_or_phone_required'),
                ],
                __('auth.validation_failed')
            );
        }

        if ($validator->fails()) {
            return $this->validationErrorResponse(
                $validator->errors(),
                __('auth.validation_failed')
            );
        }

        try {
            DB::beginTransaction();

            // Check if user exists by email or phone
            $user = null;
            if ($request->filled('email')) {
                $user = User::where('email', $request->email)->first();
            }
            if (!$user && $request->filled('phone')) {
                $user = User::where('phone', $request->phone)->first();
            }

            if ($user) {
                // If user already has a password, block registration
                if ($user->password) {
                    return $this->validationErrorResponse(
                        ['email' => __('auth.email_already_registered')],
                        __('auth.validation_failed')
                    );
                }
                // If user exists but no password (social login), allow setting password
                $user->update([
                    'name' => $request->name,
                    'password' => Hash::make($request->password),
                    'language' => $request->header('X-Language') ?? config('app.fallback_locale'),
                ]);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'language' => $request->header('X-Language') ?? config('app.fallback_locale'),
                    'password' => Hash::make($request->password),
                ]);
            }

            $token = $user->createToken('register_auth')->plainTextToken;

            DB::commit();

            $user->assignRole('exporter');

            return $this->successResponse(
                [
                    'user' => $user,
                    'access_token' => $token,
                ],
                __('auth.user_registered'),
                Response::HTTP_CREATED,
                'Registration Success'
            );
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error('Registration Error: ' . $exception->getMessage());
            return $this->errorResponse(
                __('auth.unexpected_error'),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Registration Error'
            );
        }
    }



    public function login(Request $request)
    {

        $language = $request->header('X-Language', 'en');

        try {
            $request->validate([
                'email' => 'nullable|email',
                'phone' => 'nullable|string|max:20',
                'password' => 'required',
            ]);

            // Ensure at least one identifier is provided
            if (!$request->filled('email') && !$request->filled('phone')) {
                throw ValidationException::withMessages([
                    'email' => __('auth.email_or_phone_required'),
                    'phone' => __('auth.email_or_phone_required'),
                ]);
            }




            // Find user by email or phone
            $user = null;
            if ($request->filled('email')) {
                $user = User::where('email', $request->email)->first();
            } elseif ($request->filled('phone')) {
                $user = User::where('phone', $request->phone)->first();
            }

            if (!$user) {
                throw ValidationException::withMessages([
                    $request->filled('email') ? 'email' : 'phone' => __('auth.user_not_found'),
                ]);
            }


            if ($user && !$user->password && $user->socialAccounts()->exists()) {
                throw ValidationException::withMessages([
                    'email' => __('auth.registered_with_social', [
                        'provider' => ucfirst($user->socialAccounts()->first()->provider ?? 'social login')
                    ]),
                ]);
            }


            if (!Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'password' => __('auth.password_incorrect'),
                ]);
            }

            // Update user's preferred language in the database
            $user->update(['language' => $language]);

            $token = $user->createToken('authToken')->plainTextToken;


            return $this->successResponse(
                [
                    'user' => $user,
                    'access_token' => $token,
                ],
                __('auth.login_success'),
                Response::HTTP_OK,
                'Login Success'
            );
        } catch (ValidationException $e) {
            return $this->validationErrorResponse(
                $e->errors(),
                __('auth.validation_failed')
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                __('auth.unexpected_error'),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Server Error'
            );
        }
    }

    public function user(Request $request)
    {
        return $this->successResponse(
            ['user' => $request->user()],
            'User fetched successfully'
        );
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $cookie = cookie()->forget('jwt');

        return $this->successResponse(
            ['message' => 'Logged out successfully'],
            'Logged out successfully'
        )->withCookie($cookie);
    }



    public function sendResetLinkEmail(Request $request)
    {
        // Validate email input
        $request->validate(['email' => 'required|email']);

        // Find the user by email
        $user = User::where('email', $request->email)->first();


        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => __('auth.email_not_found'),
            ], 404);
        }

        // Determine the language for the user, fallback to default if not available
        $lang = $request->header('X-Language') ?? $user->language ?? config('app.fallback_locale');

        // Update the user's preferred language if not already set
        if ($user) {
            $user->update(['language' => $lang]);
        }

        // Generate the password reset token
        $token = Password::createToken($user);

        // Get the frontend URL and the language-specific path
        $frontendUrl = config('app.frontend_url');

        // Generate the reset URL with the language prefix
        $resetUrl = "{$frontendUrl}{$lang}/reset-password/{$token}?email=" . urlencode($user->email);

        // Dispatch the job to send the reset email with the custom URL
        SendPasswordResetJob::dispatch($user, $resetUrl);

        return response()->json([
            'status' => 'success',
            'message' => __('auth.reset_link_sent'),
        ]);
    }



    public function resetPassword(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed', // Password confirmation
        ]);

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => __('auth.email_not_found'),
            ], Response::HTTP_NOT_FOUND);
        }

        // Validate the password reset token
        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        // Determine the language for the user, fallback to default if not available
        $lang = $request->header('X-Language') ?? $user->language ?? config('app.fallback_locale');

        // Update the user's preferred language if not already set
        if ($user) {
            $user->update(['language' => $lang]);
        }

        // Get the frontend URL and the language-specific path
        $frontendUrl = config('app.frontend_url');

        $resetUrl = "{$frontendUrl}{$lang}";
        // If reset is successful, dispatch the success email job
        if ($status === Password::PASSWORD_RESET) {
            // Dispatch the job to send success notification
            SendPasswordResetSuccessJob::dispatch($user, $resetUrl);

            return response()->json([
                'status' => 'success',
                'message' => __('auth.password_reset_success'),
            ]);
        }

        if ($status === Password::INVALID_TOKEN) {
            return response()->json([
                'status' => 'error',
                'message' => __('auth.invalid_token'), // Inform the user about the invalid token
            ], Response::HTTP_BAD_REQUEST);
        }

        // Return error if reset failed
        return response()->json([
            'status' => 'error',
            'message' => __('auth.password_reset_failed'),
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }


    public function updateUserName(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->name = $request->input('name');
        $user->save();

        return response()->json(['message' => 'Name updated successfully.']);
    }



    public function verifyEmail(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // $lang = $user->preferred_language;
        $lang = $user->language ?? config('app.fallback_locale');
        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(config('app.frontend_url') . "/{$lang}/verify-email?status=already_verified");
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }





        return redirect()->intended(config('app.frontend_url') . "/{$lang}/verify-email?status=verified");
    }


    public function resendVerificationEmail(Request $request)
    {


        $lang = $request->header('X-Language') ?? $request->user()->language ?? config('app.fallback_locale');

        if ($request->user()) {
            $request->user()->update(['language' => $lang]);
        }



        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'status' => 'info',
                'message' => __('auth.email_already_verified'),

            ]);
        }

        // Dispatch the job to send verification email
        // SendVerificationEmail::dispatch($request->user());

        SendEmailVerificationJob::dispatch($request->user());

        return response()->json([
            'status' => 'success',
            'message' => __('auth.verification_link_sent'),

        ]);
    }


    public function socialLogin(Request $request)
    {


        $request->validate([
            'provider' => 'required|string',
            'provider_id' => 'required|string',
            'email' => 'required|email',
            'name' => 'required|string',
            'avatar' => 'nullable|string',
            'access_token' => 'nullable|string',
            'data' => 'nullable|array', // for extra profile info if sent
        ]);



        // Find or create user
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $user = User::create([
                'email' => $request->email,
                'name' => $request->name,
                // Add other fields if needed
            ]);
        }

        // Save or update social account
        $socialAccount = $user->socialAccounts()->updateOrCreate(
            [
                'provider' => $request->provider,
                'provider_id' => $request->provider_id,
            ],
            [
                'avatar' => $request->avatar,
                'data'   => $request->data ? json_encode($request->data) : json_encode([]),
            ]
        );

        if (!$socialAccount) {
            Log::error('Failed to save social account', ['request' => $request->all()]);
        }

        $token = $user->createToken('social_auth')->plainTextToken;

        // Ensure the role exists
        $roleName = 'exporter';
        $role = Role::firstOrCreate(['name' => $roleName]);

        // Assign the role to the user if not already assigned
        if (!$user->hasRole($roleName)) {
            $user->assignRole($roleName);
        }

        return $this->successResponse(
            [
                'user' => $user,
                'access_token' => $token,
            ],
            __('auth.login_success'),
            Response::HTTP_OK,
            'Login Success'
        );
    }
}

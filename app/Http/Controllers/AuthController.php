<?php

namespace App\Http\Controllers;

use App\Http\Requests\Login;
use App\Http\Requests\RefreshTokenRequest;
use App\Http\Requests\Register;
use App\Http\Requests\ResetPassword;
use App\Models\RefreshToken;
use App\Models\User;
use App\Traits\HandleResponse;
use App\Traits\HandleToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    use HandleResponse, HandleToken;

    public function register(Register $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'gender' => $request->gender,
            'password' => Hash::make($request->password)
        ]);
        $access_token = $this->generateNewAccessToken($user);
        return $this->data(compact('access_token'), __("messages.create"), 201);
    }

    public function login(Login $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!Hash::check($request->password, $user->password)) {
            return $this->errorsMessage(['error' => __('messages.password')]);
        }

        if (!$user->email_verified_at) {
            $access_token = $this->generateNewAccessToken($user);
            return $this->data(compact('access_token'), __('messages.verify'), 404);
        }

        $access_token = $this->generateNewAccessToken($user);
        $refresh_token = $this->storeRefreshToken($user);

        return $this->data(compact('user', 'access_token', 'refresh_token'), __('messages.success_login'));
    }

    public function refreshToken(RefreshTokenRequest $request)
    {
        
        $token = $this->isValidRefreshToken($request->refresh_token);

        // here you should navigate to login page
        if (!$token){
            return $this->errorsMessage(['error' => __('messages.refresh_token')]);
        }

        $user  = $token->user ;

        $access_token = $this->generateNewAccessToken($user);
        $refresh_token = $this->storeRefreshToken($user);
    
        return $this->data(compact('user', 'access_token', 'refresh_token'), __('messages.update'));
    }

    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        RefreshToken::where('user_id', $user->id)->delete();
        return $this->successMessage(__('messages.logout'));
    }

    public function resetPassword(ResetPassword $request)
    {
        // تنفيذ عملية إعادة تعيين كلمة المرور
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );
        // التحقق من نجاح العملية
        if ($status === Password::PASSWORD_RESET) {
            return $this->successMessage(__("messages.update"));
        }
        return $this->errorsMessage(['error' => __('messages.reset_password')]);
    }

}

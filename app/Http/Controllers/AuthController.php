<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckEmail;
use App\Http\Requests\Login;
use App\Http\Requests\Register;
use App\Http\Requests\ResetPassword;
use App\Models\User;
use App\Traits\HandleResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    use HandleResponse;

    public function register(Register $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'gender' => $request->gender,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken('token')->plainTextToken;
        return $this->data(compact('user', 'token'), 'Created Successfully', 201);
    }

    public function login(Login $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!Hash::check($request->password, $user->password)) {
            return $this->errorsMessage(['error' => 'Email Or Password Has Been Failed']);
        }
        if (!$user->email_verified_at) {
            $user->token = $user->createToken('token')->plainTextToken;
            return $this->data(compact('user'), 'Email Must Be Verified', 404);
        }
        $user->token = $user->createToken('token')->plainTextToken;
        $user->image_url = asset('images/users/'.$user->image);
        return $this->data(compact('user'), 'Login Successfully');
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return $this->successMessage('Logout Successfully');
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
            return $this->successMessage('Updated Successfully');
        }
        return $this->errorsMessage(['error' => 'Email Or Token Is Not Valid']);
    }
}

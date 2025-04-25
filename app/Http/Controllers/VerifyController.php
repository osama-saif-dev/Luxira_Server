<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckEmail;
use App\Http\Requests\VerifyCode;
use App\Mail\SendCode;
use App\Mail\SendCodeForgetPassword;
use App\Models\User;
use App\Traits\HandleResponse;
use App\Traits\HandleToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class VerifyController extends Controller
{
    use HandleResponse, HandleToken;

    public function sendCode()
    {
        $authanticated_user = Auth::user();
        $user = User::find($authanticated_user->id);

        // generate code and code_expired_at 
        $code                   = rand(10000, 99999);
        $code_expired_at        = now()->addMinutes(3);

        // update code and code_expired_at in db
        $user->code             = $code;
        $user->code_expired_at  = $code_expired_at;
        $user->save();

        // send mail to user authantication
        $stringCode = (string) $code;
        Mail::to($authanticated_user->email)->send(new SendCode($stringCode, $authanticated_user->first_name, $authanticated_user->last_name));
        return $this->successMessage(__('messages.success_code'));
    }

    public function checkCode(VerifyCode $request)
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $token = $user->createToken('token')->plainTextToken;
        $now = now();

        if ($request->code != $user->code) {
            return $this->errorsMessage(['error' => __('messages.invalid_code')]);
        }

        if ($now > $user->code_expired_at) {
            return $this->errorsMessage(['error' => __('messages.expired_code')]);
        }

        $user->email_verified_at = $now;
        $user->save();

        $access_token = $this->generateNewAccessToken($user);
        $refresh_token = $this->storeRefreshToken($user);

        return $this->data(compact('user', 'access_token', 'refresh_token'), __('messages.success_verify'));
    }

    public function verifyForgetPassword(CheckEmail $request)
    {
        $user = User::where('email', $request->email)->first();
        $token = Password::createToken($user);
        $link = "http://localhost:5173/setpassword?token=" . urlencode($token) . "&email=" . urlencode($user->email);
        Mail::to($user->email)->send(new SendCodeForgetPassword($link, $user->first_name, $user->last_name));
        return $this->successMessage(__('messages.verify_forget_password'));
    }
}

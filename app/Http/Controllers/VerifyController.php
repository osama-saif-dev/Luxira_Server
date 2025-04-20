<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckEmail;
use App\Http\Requests\VerifyCode;
use App\Mail\SendCode;
use App\Mail\SendCodeForgetPassword;
use App\Models\User;
use App\Traits\HandleResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class VerifyController extends Controller
{
    use HandleResponse;

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
        return $this->successMessage('Send Code Successfully');
    }

    public function checkCode(VerifyCode $request)
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $token = $user->createToken('token')->plainTextToken;
        $now = now();

        if ($request->code != $user->code) {
            return $this->errorsMessage(['error' => 'Code Is Invalid']);
        }

        if ($now > $user->code_expired_at) {
            return $this->errorsMessage(['error' => 'Code Is Expired, Please Resend Code Again']);
        }

        $user->email_verified_at = $now;
        $user->save();

        $user->image_url = asset('images/users/' . $user->image);
        return $this->data(compact('user', 'token'), 'Verify Successfully');
    }

    public function verifyForgetPassword(CheckEmail $request)
    {
        $user = User::where('email', $request->email)->first();
        $token = Password::createToken($user);
        $link = "http://localhost:3000/reset-password?token=" . urlencode($token) . "&email=" . urlencode($user->email);
        Mail::to($user->email)->send(new SendCodeForgetPassword($link, $user->first_name, $user->last_name));
        return $this->successMessage('We Sent Route To Your Email, Please Check Your Email');
    }
}

<?php

namespace App\Traits;

use App\Models\RefreshToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

trait HandleToken
{
    use HandleResponse;

    public function isValidRefreshToken($refresh_token_input)
    {
        $tokens = RefreshToken::where('expire_at', '>', now())->get();
        foreach ($tokens as $token) {
            if (Hash::check($refresh_token_input, $token->refresh_token)) {
                return $token;
            }
        }
        return null;
    }

    public function generateNewAccessToken($user)
    {
        $token = $user->createToken('token');
        $access_token = $token->plainTextToken;
        $tokenModel = $token->accessToken;
        $tokenModel->expires_at = now()->addHour();
        $tokenModel->save();
        return $access_token;
    }

    public function storeRefreshToken($user)
    {
        RefreshToken::where('user_id', $user->id)->delete();

        $refresh_token = Str::random(60);

        RefreshToken::create([
            'user_id' => $user->id,
            'refresh_token' => Hash::make($refresh_token),
            'expire_at' => now()->addMonth()
        ]);

        return $refresh_token;
    }
}

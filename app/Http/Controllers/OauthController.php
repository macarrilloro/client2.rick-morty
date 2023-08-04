<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Providers\RouteServiceProvider;
use InvalidArgumentException;

class OauthController extends Controller
{
    public function redirect(Request $request){
        $request->session()->put('state', $state = Str::random(40));
 
        $query = http_build_query([
            'client_id' => config('services.api.client_id'),
            'redirect_uri' => route('callback'),
            'response_type' => 'code',
            'scope' => '',
            'state' => $state,
        ]);
     
        return redirect('http://api.rick-morty.test/oauth/authorize?'.$query);
    }

    public function callback(Request $request){
        $state = $request->session()->pull('state');
 
        throw_unless(
            strlen($state) > 0 && $state === $request->state,
            InvalidArgumentException::class,
            'Invalid state value.'
        );
     
        $response = Http::asForm()->post('http://api.rick-morty.test/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.api.client_id'),
            'client_secret' => config('services.api.client_secret'),
            'redirect_uri' => route('callback'),
            'code' => $request->code,
        ]);
        $access_token = $response->json();
        if ($response->status() == 200) {
            $crateAccess = AccessToken::create([
                'user_id' => auth()->user()->id,
                'access_token' => $access_token['access_token'],
                'refresh_token' => $access_token['refresh_token'],
                'expires_at' => now()->addSecond($access_token['expires_in'])
            ]);
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}

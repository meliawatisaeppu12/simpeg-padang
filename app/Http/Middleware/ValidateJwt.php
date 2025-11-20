<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\File;

class ValidateJwt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasHeader('Auth')) {

            try {

                $token  = str_replace([' ', 'Bearer'], '', $request->header('Auth'));

                list($headersB64, $payloadB64, $sig) = explode('.', $token);

                $alg = json_decode(base64_decode($headersB64), true)['alg'];

                try {

                    $path = storage_path('/certificates/public.pem');

                    $file = File::get($path);

                    JWT::$leeway = 0; // $leeway in seconds

                    $decoded = JWT::decode($token, new Key($file, $alg));

                    $nip = $decoded->sub;

                    try {

                        $response = Http::withHeaders([

                            'Authorization' => $request->header('Authorization')

                        ])->get(route('verify.token', $nip));

                        if ($response->status() == 200) {

                            if ($response['isValid']) {

                                $user = User::where('username', $nip)->first();

                                if(!empty($user)){

                                    // Auth::login($user);

                                    return $next($request);
                                }

                                return redirect()->route('login');

                            }

                            return redirect()->route('login');
                        } else {

                            return redirect()->route('login');
                        }
                    } catch (\Throwable $th) {

                        return redirect()->route('login');
                    }
                } catch (\Throwable $th) {

                    return redirect()->route('login');
                }
            } catch (\Throwable $th) {

                return redirect()->route('login');
            }
        }

        return redirect()->route('login');
    }
}

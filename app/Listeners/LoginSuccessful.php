<?php

namespace App\Listeners;

use App\Models\V2\DataUtama;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class LoginSuccessful
{
    protected $nip;

    protected $url;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $this->nip = $event->user->username;

        // $authToken = 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3MzE5NTMxNDcsImlhdCI6MTczMTkwOTk0NywianRpIjoiNTBkN2YwZWQtNTYwMC00MDAxLWIxMTEtN2MzODExYTI5M2I4IiwiaXNzIjoiaHR0cHM6Ly9zc28tc2lhc24uYmtuLmdvLmlkL2F1dGgvcmVhbG1zL3B1YmxpYy1zaWFzbiIsImF1ZCI6WyJpZGlzIiwiYWNjb3VudCJdLCJzdWIiOiJhYjllMjI1MC00MWZkLTQ0OWMtYjFlZi01MWE3MGI4MDQ2ODkiLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJwYWRhbmdrb3Rhd3MiLCJzZXNzaW9uX3N0YXRlIjoiYzczNTM5YWMtYzUxNS00ZWEyLWI0NTUtNmI3YWJiM2FjNGJhIiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVuY2FuYWFuOmtpcmltLXVzdWwtcmluY2lhbi1mb3JtYXNpIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjppbnN0YW5zaS1tb25pdG9yLXBlcmVuY2FuYWFuLWtlcGVnYXdhaWFuIiwicm9sZTpzaWFzbi1pbnN0YW5zaTprcDphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjpyZWtvbiIsInJvbGU6c2lhc24taW5zdGFuc2k6a3A6b3BlcmF0b3IiLCJyb2xlOm1hbmFqZW1lbi13czpkZXZlbG9wZXIiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpza2s6YXBwcm92YWwiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnNrazpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW5jYW5hYW46aW5zdGFuc2ktb3BlcmF0b3Itc290ayIsInJvbGU6ZGFzaGJvYXJkLW9wZXJhc2lvbmFsOmluc3RhbnNpIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjp1c3VsLXJpbmNpYW4tZm9ybWFzaSIsInJvbGU6ZGlzcGFrYXRpOmluc3RhbnNpOm9wZXJhdG9yIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW5nYWRhYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlbWJlcmhlbnRpYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOmlwYXNuOm1vbml0b3JpbmciLCJyb2xlOnNpYXNuLWluc3RhbnNpOmtwOnBhcmFmIiwicm9sZTpzaWFzbi1pbnN0YW5zaTp0bS1pbnN0YW5zaSIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVtYmVyaGVudGlhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW5jYW5hYW46aW5zdGFuc2ktcGVuZXRhcGFuLXNvdGsiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46cGFyYWYiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnByb2ZpbGFzbjp2aWV3cHJvZmlsIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpza2s6cGFyYWYiLCJyb2xlOnNpYXNuLWluc3RhbnNpOmFkbWluOmFkbWluIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnsiaWRpcyI6eyJyb2xlcyI6WyJhZ2VuY3ktYWRtaW4iXX0sImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IlRFR1VIIFNVV0FOREEiLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiIxOTkxMDQyNDIwMTUwMjEwMDIiLCJnaXZlbl9uYW1lIjoiVEVHVUgiLCJmYW1pbHlfbmFtZSI6IlNVV0FOREEiLCJlbWFpbCI6InN1d2FuZGEudGVndWhAZ21haWwuY29tIn0.XftJ00ZAW6r_q06N9Nl_q_W8xSSS3421HxQRzgooZTCD7P7w3y0tmfHidpmzCAD3yM5Jl4iE7dOxg2IxsYvlYQYbAQTtJTGKcbyLKtRkn6ZWG4h6P_vm_TnnossH-MnJD1MZ0eMfeZXujBhMRIt3o2VX_LOuzx62gyWuJrCFoqg1E9bp2MYu9fmvfH6p1u_RQbxz1cByAcIbmpzZpOHNNZYj8Mmn1AdRdJC_azKcD9psmCJLZhYEJwAofew6q-khKWTREj9PNghvs8dsdQYlLPcHHQsBoMEq63qfzjq02u6tsgr8YMyaKYU8fwHQY0iFvC4c_Yva51aiEa6VfnZ06g';

        try {
            // $id = DataUtama::select('id')->where('nip_baru', $this->nip)->first()->id;

            // $token = DB::table('auth')->select('*')->orderBy('id', 'DESC')->limit(1)->get();

            // $this->url = 'https://apimws.bkn.go.id:8243/apisiasn/1.0/pns/photo/'.$id;

            // if ($token->count() == 0) {
            //     $newToken = $this->getToken();
            //     $result = $this->tryToken($authToken, $newToken['authorizationToken']);
            //     if ($result) {
            //         $this->fetchData($authToken, $newToken['authorizationToken'], $this->nip);
            //     }
            // } else {
            //     $result = $this->tryToken($authToken, $token->first()->authorizationToken);
            //     if ($result) {
            //         $this->fetchData($authToken, $token->first()->authorizationToken, $this->nip);
            //     } else {
            //         $newToken = $this->getToken();
            //         $result = $this->tryToken($authToken, $newToken['authorizationToken']);
            //         if ($result) {
            //             $this->fetchData($authToken, $newToken['authorizationToken'], $this->nip);
            //         }
            //     }
            // }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    private function getToken()
    {
        $authorizationToken = Http::withBasicAuth('f0Mwkq1udsBNkaE0aGeVjEftJUEa', 'd48MBaJ92Xhapg7tJQaYrWJVa7Ma')
            ->post('https://apimws.bkn.go.id/oauth2/token', [
                'grant_type' => 'client_credentials'
            ]);

        if ($authorizationToken->failed()) {

            throw new RuntimeException("Gagal Mendapatkan Authorization Token! " . $authorizationToken, $authorizationToken->status());
        }

        $insert = DB::table('auth')->insert([
            'authorizationToken' => $authorizationToken['access_token']
        ]);

        if ($insert) {
            return [
                'authorizationToken' => $authorizationToken['access_token']
            ];
        }

        return false;
    }

    private function tryToken($authToken, $authorizationToken)
    {
        $http_request = Http::withHeaders([
            'accept' => 'application/json',
            'Auth' => 'bearer ' . $authToken,
            'Authorization' => 'Bearer ' . $authorizationToken
        ])->get('https://apimws.bkn.go.id:8243/apisiasn/1.0/pns/data-utama/' . $this->nip);

        if ($http_request->failed()) {
            if (isset($http_request['code'])) {
                if ($http_request['code'] == "900803") {
                    throw new RuntimeException("Exceeded Quota! Skipped " . $this->nip . ' | ' . $http_request, $http_request->status());
                }
            }

            return false;
        }

        return true;
    }

    public function fetchData($authToken, $authorizationToken, $nip)
    {
        $http_request = Http::withHeaders([
            'accept' => 'application/json',
            'Auth' => 'bearer ' . $authToken,
            'Authorization' => 'Bearer ' . $authorizationToken
        ])->get($this->url);

        if($http_request->status() == 200)
        {
            Storage::disk('public')->put('photo/'.$nip.'.jpg',$http_request->getBody());
        }
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\V2\PersonalAccess;
use App\Models\V2\PositionAccess;
use App\Models\V2\DataUtama;

class DeviceManagement
{
    private $akses_id = 9;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        } else {
            $data = DataUtama::select('jabatan_struktural_id', 'jabatan_fungsional_id', 'jabatan_fungsional_umum_id')->where('nip_baru', Auth::user()->username)->first();
            $jabatan_struktural_id = $data->jabatan_struktural_id;
            $jabatan_fungsional_id = $data->jabatan_fungsional_id;
            $jabatan_fungsional_umum_id = $data->jabatan_fungsional_umum_id;
            $jabatan_id = !empty($jabatan_struktural_id) ? $jabatan_struktural_id : (!empty($jabatan_fungsional_id) ? $jabatan_fungsional_id : $jabatan_fungsional_umum_id);

            if (empty($jabatan_id)) {
                return redirect()->route('dashboard');
            } else {
                $access = PositionAccess::whereIn('access_id', [$this->akses_id])->where('jabatan_id', $jabatan_id)->get();
                if ($access->count() > 0) {
                    return $next($request);
                } else {
                    $personal_access = PersonalAccess::whereIn('access_id', [$this->akses_id])->where('pns_id', Auth::user()->v2Profile->id)->get();

                    if ($personal_access->count() > 0) {

                        return $next($request);
                    } else {
                        return redirect()->route('dashboard');
                    }
                }
            }
        }
    }
}

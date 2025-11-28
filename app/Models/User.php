<?php

namespace App\Models;

use App\Models\V2\DataUtama;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Passport\HasApiTokens;
use App\Models\V1\DataAsn;
use App\Models\V2\DataNonAsn;
use App\Models\V2\Device;
use App\Models\V2\KelompokAbsen;
use App\Models\V2\KelompokAbsenReadOnly;
use App\Models\V2\NonAsn;
use App\Models\V2\PersonalAccess;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'unor_induk_id',
        'unor_induk_nama',
    ];

    /**
     * Find the user instance for the given username.
     *
     * @param  string  $username
     * @return \App\User
     */
    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    public function profile()
    {
        return $this->hasOne(DataAsn::class, 'NIP_BARU', 'username');
    }

    public function v2Profile()
    {
        return $this->hasOne(DataUtama::class, 'nip_baru', 'username');
    }
    
    public function dataNonAsn()
    {
        return $this->hasOne(DataNonAsn::class, 'username', 'username');
    }

    public function nonAsn()
    {
        return $this->hasOne(NonAsn::class, 'username', 'username');
    }

    public function getUnorIndukIdAttribute()
    {
        return $this->jenis_kepegawaian == 'asn' ? KelompokAbsenReadOnly::where('unor_id', $this->v2Profile->unor_id)->first()->unor_induk_id : '-';
    }

    public function getUnorIndukNamaAttribute()
    {
        return
            $this->jenis_kepegawaian == 'asn' ? KelompokAbsenReadOnly::where('unor_id', $this->v2Profile->unor_id)->first()->unor_induk_nama : '-';
    }

    public function hasAccessCode($accessCode)
    {
        return $this->jenis_kepegawaian == 'asn' ? (PersonalAccess::where('pns_id', $this->v2Profile->id)->where('access_id', $accessCode)->count() > 0 ? true : false) : '-';
    }
}

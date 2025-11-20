<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class DeviceNonAsn extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'devices_non_asn';

    public function dataNonAsn()
    {
        return $this->belongsTo(NonAsn::class, 'username', 'username');
    }

    public function library()
    {
        return $this->hasOne(DeviceLibrary::class,'device','device');
    }

    /**
     * Specifies the user's FCM tokens
     *
     * @return string|array
     */
    public function routeNotificationForFcm()
    {
        return $this->notification_token;
    }
}

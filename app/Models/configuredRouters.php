<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class configuredRouters extends Model
{
    protected $table = 'configured_routers';
    protected $fillable = ['router_model', 'serial_number', 'mac_batch', 'configured_by'];

    public function routerModel()
    {
        return $this->belongsTo(routerModel::class, 'router_model');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'configured_by');
    }

    public function macAddress()
    {
        return $this->hasOne(MacAddress::class, 'batchid', 'mac_batch')->where('assigned', true);
    }    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class configuredRouters extends Model
{
    protected $table = 'configured_routers';
    protected $fillable = ['router_model', 'serial_number', 'mac_batch'];

    public function routerModel()
    {
        return $this->belongsTo(routerModel::class, 'router_model');
    }
}

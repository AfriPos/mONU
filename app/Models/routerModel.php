<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class routerModel extends Model
{
    protected $table = 'router_models';
    protected $fillable = ['brand', 'model_name', 'default_username', 'default_password'];
}

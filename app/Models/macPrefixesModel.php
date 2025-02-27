<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class macPrefixesModel extends Model
{
    protected $table = 'mac_prefixes';
    protected $fillable = ['prefix', 'status'];    
}

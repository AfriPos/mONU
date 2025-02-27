<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $fillable = ['organization_id', 'balance', 'account_balance'];
}


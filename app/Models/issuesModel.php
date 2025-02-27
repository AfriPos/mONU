<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class issuesModel extends Model
{
    protected $table = 'issue_types';
    protected $fillable = ['issue'];
}

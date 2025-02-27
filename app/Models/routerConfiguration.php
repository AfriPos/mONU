<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class routerConfiguration extends Model
{
    protected $table = 'router_configurations';
    protected $fillable = ['router_model_id', 'issue_id', 'configuration'];

    public function routerModel()
    {
        return $this->belongsTo(routerModel::class);
    }

    public function issue()
    {
        return $this->belongsTo(issuesModel::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class invoiceModel extends Model
{
    protected $table = 'invoices';
    protected $fillable = ['invoice_number', 'amount', 'status'];
}

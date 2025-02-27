<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class paymentModel extends Model
{
    protected $table = 'payments';
    protected $fillable = ['invoice_id', 'amount', 'payment_method', 'transaction_id', 'status', 'payment_date'];
    
    public function invoice()
    {
        return $this->belongsTo(invoiceModel::class, 'invoice_id');
    }
}

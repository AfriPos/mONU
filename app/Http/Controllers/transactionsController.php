<?php

namespace App\Http\Controllers;

use App\Models\paymentModel;
use Illuminate\Http\Request;

class transactionsController extends Controller
{
    public function index(){
        $payments = paymentModel::with('invoice')->get();
        return view('transactions.index', compact('payments'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::latest()->get(); // atau paginate() kalau mau paging
        return view('riwayat', compact('transactions'));
    }
}

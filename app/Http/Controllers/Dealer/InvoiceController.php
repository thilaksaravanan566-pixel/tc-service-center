<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        $dealer = Auth::user()->dealer;
        $invoices = $dealer->invoices()->with(['serviceOrder', 'dealerOrder'])->latest()->paginate(15);
        return view('dealer.invoices.index', compact('invoices'));
    }

    public function show($id)
    {
        $dealer = Auth::user()->dealer;
        $invoice = $dealer->invoices()->with(['serviceOrder', 'items'])->findOrFail($id);
        return view('dealer.invoices.show', compact('invoice'));
    }
}

<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Models\Expenses\ExpenseOfPurchase;
use App\Models\Suppliers\SupplierPaymentDetails;
use Illuminate\Http\Request;

class PurchasePendingsController extends Controller
{
    public function purchase_pending_expense()
    {
        $expense = ExpenseOfPurchase::where(company())->get();
        return view('Pendings.purchaseExpense',compact('expense'));
    }

    public function purchase_supplier_payment()
    {
        $payment = SupplierPaymentDetails::where(company())->get();
        return view('Pendings.purchasePayment',compact('payment'));
    }
    
}

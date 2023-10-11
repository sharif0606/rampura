<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Models\Expenses\ExpenseOfPurchase;
use App\Models\Suppliers\SupplierPaymentDetails;
use App\Models\Accounts\Child_one;
use App\Models\Accounts\Child_two;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class PurchasePendingsController extends Controller
{
    public function purchase_pending_expense(Request $request){
        $expense = ExpenseOfPurchase::where(company());
        if ($request->fdate) {
            $tdate = $request->tdate ? $request->tdate : $request->fdate;
            $startDate = Carbon::createFromFormat('Y-m-d', $request->fdate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $tdate)->endOfDay();
            $expense = $expense->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        if ($request->head_name) 
            $expense = $expense->whereIn('child_two_id',  $request->head_name);
        if ($request->lot_no) 
            $expense = $expense->whereIn('lot_no',  explode(",",$request->lot_no));

        $expense = $expense->paginate(15);
        
        $childone = Child_one::where(company())->where('head_code',5310)->first();
        $childTow = Child_two::where(company())->where('child_one_id',$childone->id)->get();

        return view('Pendings.purchaseExpense',compact('expense','childTow'));
    }

    public function purchase_supplier_payment(){
        $payment = SupplierPaymentDetails::where(company())->get();
        return view('Pendings.purchasePayment',compact('payment'));
    }
    
}

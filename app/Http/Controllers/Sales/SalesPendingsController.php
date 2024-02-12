<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Expenses\ExpenseOfSales;
use App\Models\Customers\CustomerPaymentDetails;
use App\Models\Accounts\Child_one;
use App\Models\Accounts\Child_two;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalesPendingsController extends Controller
{
    public function sales_pending_expense(Request $request){
        $expense = ExpenseOfSales::where(company())->where('status',0);
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
        
        $childone = Child_one::where(company())->where('head_code',5320)->first();
        $childTow = Child_two::where(company())->where('child_one_id',$childone->id)->get();

        return view('Pendings.salesExpense',compact('expense','childTow'));
    }

    public function sales_customer_payment(Request $request){
        $payment = CustomerPaymentDetails::where(company())->where('status',0);
        if ($request->fdate) {
            $tdate = $request->tdate ? $request->tdate : $request->fdate;
            $startDate = Carbon::createFromFormat('Y-m-d', $request->fdate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $tdate)->endOfDay();
            $payment = $payment->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        if ($request->head_name) 
            $payment = $payment->whereIn('p_table_id',  $request->head_name);
        if ($request->lc_no) 
            $payment = $payment->whereIn('lc_no',  explode(",",$request->lc_no));

        $payment = $payment->paginate(15);
        
        $paymethod=array();
        $account_data=Child_one::whereIn('head_code',[1110,1120])->where(company())->get();
        
        if($account_data){
            foreach($account_data as $ad){
                $shead=Child_two::where('child_one_id',$ad->id);
                if($shead->count() > 0){
					$shead=$shead->get();
                    foreach($shead as $sh){
                        $paymethod[]=array(
                                        'id'=>$sh->id,
                                        'head_code'=>$sh->head_code,
                                        'head_name'=>$sh->head_name,
                                        'table_name'=>'child_twos'
                                    );
                    }
                }else{
                    $paymethod[]=array(
                        'id'=>$ad->id,
                        'head_code'=>$ad->head_code,
                        'head_name'=>$ad->head_name,
                        'table_name'=>'child_ones'
                    );
                }
                
            }
        }

        return view('Pendings.salesPayment',compact('payment','paymethod'));
    }

    // public function sales_customer_payment(){
    //     $payment = SupplierPaymentDetails::where(company())->get();
    //     return view('Pendings.salesPayment',compact('payment'));
    // }
    
}

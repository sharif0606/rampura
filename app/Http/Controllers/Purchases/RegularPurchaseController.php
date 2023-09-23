<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;

use App\Models\Purchases\Regular_purchase;
use App\Models\Accounts\Child_one;
use App\Models\Accounts\Child_two;
use App\Models\Expenses\ExpenseOfPurchase;
use App\Models\Purchases\Purchase_details;
use App\Models\Stock\Stock;
use App\Models\Suppliers\Supplier;
use App\Models\Products\Product;
use App\Models\Settings\Branch;
use App\Models\Settings\Warehouse;
use App\Models\Settings\Company;
use Illuminate\Http\Request;
use App\Http\Requests\Purchases\AddNewRequest;
use App\Http\Requests\Purchases\UpdateRequest;
use App\Http\Traits\ResponseTrait;
use App\Models\Suppliers\SupplierPayment;
use App\Models\Suppliers\SupplierPaymentDetails;
use Exception;
use DB;
use Carbon\Carbon;

class RegularPurchaseController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( currentUser()=='owner')
            $purchases = Regular_purchase::where(company())->paginate(10);
        else
            $purchases = Regular_purchase::where(company())->where(branch())->paginate(10);
            
        
        return view('regularPurchase.index',compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = Branch::where(company())->get();
        if( currentUser()=='owner'){
            $suppliers = Supplier::where(company())->get();
            $Warehouses = Warehouse::where(company())->get();
            $childone = Child_one::where(company())->where('head_code',5310)->first();
            $childTow = Child_two::where(company())->where('child_one_id',$childone->id)->get();
        }else{
            $suppliers = Supplier::where(company())->where(branch())->get();
            $Warehouses = Warehouse::where(company())->where(branch())->get();
        }

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
        
        return view('regularPurchase.create',compact('branches','suppliers','Warehouses','childTow','paymethod'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $pur= new Regular_purchase;
            $pur->supplier_id=$request->supplierName;
            $pur->voucher_no='VR-'.Carbon::now()->format('m-y').'-'. str_pad((Regular_purchase::whereYear('created_at', Carbon::now()->year)->count() + 1),4,"0",STR_PAD_LEFT);
            $pur->purchase_date = date('Y-m-d', strtotime($request->purchase_date));
            $pur->grand_total=$request->tgrandtotal;
            $pur->company_id=company()['company_id'];
            $pur->branch_id=$request->branch_id;
            $pur->warehouse_id=$request->warehouse_id;
            $pur->created_by=currentUserId();
            $pur->payment_status=0;
            $pur->status=1;
            if($pur->save()){
                if($request->child_two_id){
                    foreach($request->child_two_id as $j=>$child_two_id){
                        $ex = new ExpenseOfPurchase;
                        $ex->regular_purchase_id=$pur->id;
                        $ex->company_id=company()['company_id'];
                        $ex->child_two_id=$child_two_id;
                        $ex->sign_for_calculate=$request->sign_for_calculate[$j];
                        $ex->cost_amount=$request->cost_amount[$j];
                        $ex->lot_no=$request->lc_no[$j];
                        $ex->status= 0;
                        $ex->save();
                    }
                }
                if($request->product_id){
                    foreach($request->product_id as $i=>$product_id){
                        $pd=new Purchase_details;
                        $pd->regular_purchase_id=$pur->id;
                        $pd->product_id=$product_id;
                        $pd->company_id=company()['company_id'];
                        $pd->lot_no=$request->lot_no[$i];
                        $pd->brand=$request->brand[$i];
                        $pd->quantity_bag=$request->qty_bag[$i];
                        $pd->quantity_kg=$request->qty_kg[$i];
                        $pd->less_quantity_kg=$request->less_qty_kg[$i];
                        $pd->actual_quantity=$request->actual_qty[$i];
                        $pd->rate_kg=$request->rate_in_kg[$i];
                        $pd->amount=$request->amount[$i];
                        if($pd->save()){
                            $stock=new Stock;
                            $stock->regular_purchase_id=$pur->id;
                            $stock->product_id=$product_id;
                            $stock->company_id=company()['company_id'];
                            $stock->branch_id=$request->branch_id;
                            $stock->warehouse_id=$request->warehouse_id;
                            $stock->lot_no=$pd->lot_no;
                            $stock->brand=$pd->brand;
                            $stock->quantity=$pd->actual_quantity;
                            $stock->batch_id= rand(111,999).uniqid().$product_id;
                            $stock->unit_price=$pd->rate_kg;
                            $stock->quantity_bag=$pd->quantity_bag;
                            $stock->total_amount=$pd->amount;
                            $stock->save();
                        }
                    }
                }
                if($request->total_pay_amount){
                    $payment=new SupplierPayment;
                    $payment->regular_purchase_id = $pur->id;
                    $payment->company_id = company()['company_id'];
                    $payment->supplier_id = $request->supplierName;
                    $payment->purchase_date = date('Y-m-d', strtotime($request->purchase_date));
                    $payment->regular_purchase_invoice = $pur->voucher_no;
                    $payment->total_amount = $request->total_pay_amount;
                    $payment->total_payment = $request->total_payment;
                    $payment->total_due = $request->total_due;
                    $payment->status=0;
                    if($payment->save()){
                        if($request->payment_head){
                            foreach($request->payment_head as $i=>$ph){
                                $pay=new SupplierPaymentDetails;
                                $pay->regular_purchase_id = $pur->id;
                                $pay->company_id=company()['company_id'];
                                $pay->supplier_payment_id=$payment->id;
                                $pay->supplier_id=$request->supplierName;
                                $pay->p_table_name=explode('~',$ph)[0];
                                $pay->p_table_id=explode('~',$ph)[1];
                                $pay->p_head_name=explode('~',$ph)[2];
                                $pay->p_head_code=explode('~',$ph)[3];
                                $pay->lc_no=$request->lc_no_payment[$i];
                                $pay->amount=$request->pay_amount[$i];
                                $pay->status=0;
                                $pay->save();
                            }
                        }
                    }
                }
                DB::commit();
                
                return redirect()->route(currentUser().'.rpurchase.index')->with($this->resMessageHtml(true,null,'Successfully created'));
            }else
                return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }catch(Exception $e){
            DB::rollback();
            // dd($e);
            return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchases\regular_purchase  $regular_purchase
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $show_data= Regular_purchase::findOrFail(encryptor('decrypt',$id));
        $purDetail= Purchase_details::where('regular_purchase_id',$show_data->id)->get();
        $expense = ExpenseOfPurchase::where('beparian_purchase_id',$show_data->id)->get();
        return view('regularPurchase.show',compact('show_data','purDetail','expense'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchases\regular_purchase  $regular_purchase
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branches = Branch::where(company())->get();
        if( currentUser()=='owner'){
            $suppliers = Supplier::where(company())->get();
            $Warehouses = Warehouse::where(company())->get();
            $purchase = Regular_purchase::findOrFail(encryptor('decrypt',$id));
            $purchaseDetails = Purchase_details::where('regular_purchase_id',$purchase->id)->get();
            $childone = Child_one::where(company())->where('head_code',5310)->first();
            $childTow = Child_two::where(company())->where('child_one_id',$childone->id)->get();
            // $expense = ExpenseOfPurchase::where('regular_purchase_id',$purchase->id)->pluck('cost_amount','child_two_id');
            $expense = ExpenseOfPurchase::where('regular_purchase_id',$purchase->id)->get();
            $supplerPayment = SupplierPayment::where(company())->where('regular_purchase_id',$purchase->id)->first();
            $supplierPaymentDetails = SupplierPaymentDetails::where(company())->where('supplier_payment_id',$supplerPayment->id)->get();
        }else{
            $suppliers = Supplier::where(company())->where(branch())->get();
            $Warehouses = Warehouse::where(company())->where(branch())->get();
        }

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
        
        return view('regularPurchase.edit',compact('branches','suppliers','Warehouses','purchase','purchaseDetails','childTow','expense','paymethod','supplerPayment','supplierPaymentDetails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchases\regular_purchase  $regular_purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $pur= Regular_purchase::findOrFail(encryptor('decrypt',$id));
            $pur->supplier_id=$request->supplierName;
            $pur->purchase_date = date('Y-m-d', strtotime($request->purchase_date));
            $pur->grand_total=$request->tgrandtotal;
            $pur->company_id=company()['company_id'];
            $pur->branch_id=$request->branch_id;
            $pur->warehouse_id=$request->warehouse_id;
            $pur->updated_by=currentUserId();

            if($pur->save()){
                if($request->child_two_id){
                    ExpenseOfPurchase::where('regular_purchase_id',$pur->id)->delete();
                    foreach($request->child_two_id as $j=>$child_two_id){
                        $ex = new ExpenseOfPurchase;
                        $ex->regular_purchase_id=$pur->id;
                        $ex->company_id=company()['company_id'];
                        $ex->child_two_id=$child_two_id;
                        $ex->sign_for_calculate=$request->sign_for_calculate[$j];
                        $ex->cost_amount=$request->cost_amount[$j];
                        $ex->lot_no=$request->lc_no[$j];
                        $ex->status= 0;
                        $ex->save();
                    }
                }
                if($request->product_id){
                    Purchase_details::where('regular_purchase_id',$pur->id)->delete();
                    Stock::where('regular_purchase_id',$pur->id)->delete();
                    foreach($request->product_id as $i=>$product_id){
                        if($request->lot_no[$i]>0){
                            $pd=new Purchase_details;
                            $pd->regular_purchase_id=$pur->id;
                            $pd->product_id=$product_id;
                            $pd->company_id=company()['company_id'];
                            $pd->lot_no=$request->lot_no[$i];
                            $pd->brand=$request->brand[$i];
                            $pd->quantity_bag=$request->qty_bag[$i];
                            $pd->quantity_kg=$request->qty_kg[$i];
                            $pd->less_quantity_kg=$request->less_qty_kg[$i];
                            $pd->actual_quantity=$request->actual_qty[$i];
                            $pd->rate_kg=$request->rate_in_kg[$i];
                            $pd->amount=$request->amount[$i];
                            if($pd->save()){
                                $stock=new Stock;
                                $stock->regular_purchase_id=$pur->id;
                                $stock->product_id=$product_id;
                                $stock->company_id=company()['company_id'];
                                $stock->branch_id=$request->branch_id;
                                $stock->warehouse_id=$request->warehouse_id;
                                $stock->lot_no=$pd->lot_no;
                                $stock->brand=$pd->brand;
                                $stock->quantity=$pd->actual_quantity;
                                $stock->batch_id=$request->batch_id;
                                $stock->unit_price=$pd->rate_kg;
                                $stock->quantity_bag=$pd->quantity_bag;
                                $stock->total_amount=$pd->amount;
                                $stock->save();
                            }
                        }
                    }
                }
                if($request->total_pay_amount){
                    SupplierPayment::where('regular_purchase_id',$pur->id)->delete();
                    SupplierPaymentDetails::where('regular_purchase_id',$pur->id)->delete();
                    $payment=new SupplierPayment;
                    $payment->regular_purchase_id = $pur->id;
                    $payment->company_id = company()['company_id'];
                    $payment->supplier_id = $request->supplierName;
                    $payment->purchase_date = date('Y-m-d', strtotime($request->purchase_date));
                    $payment->regular_purchase_invoice = $pur->voucher_no;
                    $payment->total_amount = $request->total_pay_amount;
                    $payment->total_payment = $request->total_payment;
                    $payment->total_due = $request->total_due;
                    $payment->status=0;
                    if($payment->save()){
                        if($request->payment_head){
                            foreach($request->payment_head as $i=>$ph){
                                $pay=new SupplierPaymentDetails;
                                $pay->regular_purchase_id = $pur->id;
                                $pay->company_id=company()['company_id'];
                                $pay->supplier_payment_id=$payment->id;
                                $pay->supplier_id=$request->supplierName;
                                $pay->p_table_name=explode('~',$ph)[0];
                                $pay->p_table_id=explode('~',$ph)[1];
                                $pay->p_head_name=explode('~',$ph)[2];
                                $pay->p_head_code=explode('~',$ph)[3];
                                $pay->lc_no=$request->lc_no_payment[$i];
                                $pay->amount=$request->pay_amount[$i];
                                $pay->status=0;
                                $pay->save();
                            }
                        }
                    }
                }
                DB::commit();
                
                return redirect()->route(currentUser().'.rpurchase.index')->with($this->resMessageHtml(true,null,'Successfully Update'));
            }else
                return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }catch(Exception $e){
            DB::rollback();
            // dd($e);
            return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchases\regular_purchase  $regular_purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(regular_purchase $regular_purchase)
    {
        //
    }
}

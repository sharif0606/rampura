<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;

use App\Models\Sales\Sales;
use App\Models\Accounts\Child_one;
use App\Models\Accounts\Child_two;
use App\Models\Expenses\ExpenseOfSales;
use App\Models\Stock\Stock;
use App\Models\Sales\Sales_details;
use Illuminate\Http\Request;
use App\Models\Settings\Branch;
use App\Models\Settings\Warehouse;
use App\Models\Settings\Company;
use App\Models\Customers\Customer;
use App\Models\Products\Product;
use App\Http\Requests\Sales\AddNewRequest;
use App\Http\Traits\ResponseTrait;
use App\Models\Customers\CustomerPayment;
use App\Models\Customers\CustomerPaymentDetails;
use Exception;
use DB;
use Carbon\Carbon;

class SalesController extends Controller
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
            $sales = Sales::where(company())->paginate(10);
        else
            $sales = Sales::where(company())->where(branch())->paginate(10);


        return view('sales.index',compact('sales'));
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
            $customers = Customer::where(company())->get();
            $Warehouses = Warehouse::where(company())->get();
            $childone = Child_one::where(company())->where('head_code',5320)->first();
            $childTow = Child_two::where(company())->where('child_one_id',$childone->id)->get();
        }else{
            $customers = Customer::where(company())->where(branch())->get();
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

        return view('sales.create',compact('branches','customers','Warehouses','childTow','paymethod'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function product_sc(Request $request)
    {
        if($request->name){
            if($request->batch_id)
                $product=DB::select("SELECT products.id,products.product_name,products.bar_code,stocks.lot_no,stocks.unit_price,stocks.batch_id,stocks.brand,sum(stocks.quantity_bag) as bag_qty,sum(stocks.quantity) as qty FROM `products` JOIN stocks on stocks.product_id=products.id WHERE stocks.company_id=".company()['company_id']." and stocks.branch_id=".$request->branch_id." and stocks.warehouse_id=".$request->warehouse_id." and (stocks.lot_no like '%". $request->name ."%' or stocks.brand like '%". $request->name ."%') and (stocks.batch_id is not null or stocks.batch_id != '') and stocks.batch_id not in (".rtrim($request->batch_id,',').") GROUP BY stocks.product_id,stocks.lot_no,stocks.brand,stocks.batch_id");
            else
                $product=DB::select("SELECT products.id,products.product_name,products.bar_code,stocks.lot_no,stocks.unit_price,stocks.batch_id,stocks.brand,sum(stocks.quantity_bag) as bag_qty,sum(stocks.quantity) as qty FROM `products` JOIN stocks on stocks.product_id=products.id WHERE stocks.company_id=".company()['company_id']." and stocks.branch_id=".$request->branch_id." and stocks.warehouse_id=".$request->warehouse_id." and (stocks.lot_no like '%". $request->name ."%' or stocks.brand like '%". $request->name ."%') and (stocks.batch_id is not null or stocks.batch_id != '') GROUP BY stocks.product_id,stocks.lot_no,stocks.brand,stocks.batch_id");
            //$dd="SELECT products.id,products.product_name,products.bar_code,stocks.lot_no,stocks.brand,sum(stocks.quantity_bag) as qty FROM `products` JOIN stocks on stocks.product_id=products.id WHERE stocks.company_id=".company()['company_id']." and stocks.branch_id=".$request->branch_id." and stocks.warehouse_id=".$request->warehouse_id." and (stocks.lot_no like '%". $request->name ."%' or stocks.brand like '%". $request->name ."%') GROUP BY stocks.id";
            print_r(json_encode($product));
        }

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function product_sc_d(Request $request)
    {
        if($request->item_id){
            list($item_id,$lot_no,$brand,$batch_id)=explode("^",$request->item_id);
            $product=collect(\DB::select("SELECT products.id,products.product_name,products.bar_code,stocks.lot_no,stocks.unit_price,sum(stocks.quantity_bag) as bag_qty, sum(stocks.quantity) as qty, stocks.brand FROM `products` JOIN stocks on stocks.product_id=products.id WHERE stocks.company_id=".company()['company_id']." and stocks.branch_id=".$request->branch_id." and stocks.warehouse_id=".$request->warehouse_id." and stocks.product_id=".$item_id." and stocks.lot_no='".$lot_no."' and stocks.brand='".$brand."' and stocks.batch_id='".$batch_id."' GROUP BY stocks.batch_id"))->first();

            $data='<tr class="productlist text-center">';
            $data.='<td class="py-2 px-1">'.$product->product_name.'<input name="product_id[]" type="hidden" value="'.$product->id.'" class="product_id_list"><input name="stockqty[]" type="hidden" value="'.$product->qty.'" class="stockqty"><input name="batch_id[]" type="hidden" value="'.$batch_id.'" class="batch_id_list"></td>';
            $data.='<td class="py-2 px-1"><input readonly name="lot_no[]" type="text" class="form-control lot_no" value="'.$product->lot_no.'"></td>';
            $data.='<td class="py-2 px-1"><input readonly name="brand[]" type="text" class="form-control brand"  value="'.$product->brand.'"></td>';
            $data.='<td class="py-2 px-1"><input  type="text" class="form-control stock_bag" value="'.$product->bag_qty.'" disabled></td>';
            $data.='<td class="py-2 px-1"><input  type="text" class="form-control" value="'.$product->qty.'" disabled></td>';
            $data.='<td class="py-2 px-1"><input onkeyup="get_cal(this)" name="qty_bag[]" type="text" class="form-control qty_bag"></td>';
            $data.='<td class="py-2 px-1"><input onkeyup="get_cal(this)" name="qty_kg[]" type="text" class="form-control qty_kg"></td>';
            $data.='<td class="py-2 px-1"><input onkeyup="get_cal(this)" name="less_qty_kg[]" type="text" class="form-control less_qty_kg"></td>';
            $data.='<td class="py-2 px-1"><input onkeyup="get_cal(this)" name="actual_qty[]" readonly type="text" class="form-control actual_qty" value="0"></td>';
            $data.='<td class="py-2 px-1"><input onkeyup="get_cal(this)" name="rate_in_kg[]" type="text" class="form-control rate_in_kg" value=""></td>';
            $data.='<td class="py-2 px-1"><input name="amount[]" readonly type="text" class="form-control amount" value="0"></td>';
            $data.='<td class="py-2 px-1 text-danger"><i style="font-size:1.7rem" onclick="removerow(this)" class="bi bi-dash-circle-fill"></i></td>';
            $data.='</tr>';

            print_r(json_encode($data));
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddNewRequest $request)
    {

        DB::beginTransaction();
        try{
            $pur= new Sales;
            $pur->customer_id=$request->customerName;
            $pur->voucher_no='VR-'.Carbon::now()->format('m-y').'-'. str_pad((Sales::whereYear('created_at', Carbon::now()->year)->count() + 1),4,"0",STR_PAD_LEFT);
            $pur->sales_date=date('Y-m-d', strtotime($request->sales_date));
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
                        $ex = new ExpenseOfSales;
                        $ex->company_id=company()['company_id'];
                        $ex->sales_id=$pur->id;
                        $ex->child_two_id=$child_two_id;
                        $ex->cost_amount=$request->cost_amount[$j];
                        $ex->lot_no=$request->lc_no[$j];
                        $ex->status= 0;
                        $ex->save();
                    }
                }
                if($request->product_id){
                    foreach($request->product_id as $i=>$product_id){
                        $pd=new Sales_details;
                        $pd->company_id=company()['company_id'];
                        $pd->sales_id=$pur->id;
                        $pd->product_id=$product_id;
                        $pd->lot_no=$request->lot_no[$i];
                        $pd->batch_id=$request->batch_id[$i];
                        $pd->brand=$request->brand[$i];
                        $pd->quantity_bag=$request->qty_bag[$i];
                        $pd->quantity_kg=$request->qty_kg[$i];
                        $pd->less_quantity_kg=$request->less_qty_kg[$i];
                        $pd->actual_quantity=$request->actual_qty[$i];
                        $pd->rate_kg=$request->rate_in_kg[$i];
                        $pd->amount=$request->amount[$i];
                        if($pd->save()){
                            $stock=new Stock;
                            $stock->product_id=$product_id;
                            $stock->sales_id=$pur->id;
                            $stock->company_id=company()['company_id'];
                            $stock->branch_id=$request->branch_id;
                            $stock->warehouse_id=$request->warehouse_id;
                            $stock->quantity='-'.$pd->actual_quantity;
                            $stock->quantity_bag='-'.$pd->quantity_bag;
                            $stock->lot_no=$pd->lot_no;
                            $stock->brand=$pd->brand;
                            $stock->batch_id=$pd->batch_id;
                            $stock->unit_price=$pd->rate_kg;
                            $stock->total_amount=$pd->amount;
                            $stock->save();
                            DB::commit();
                        }
                    }
                }

                if($request->total_pay_amount){
                    $payment=new CustomerPayment;
                    $payment->sales_id = $pur->id;
                    $payment->company_id = company()['company_id'];
                    $payment->customer_id = $request->customerName;
                    $payment->sales_date = date('Y-m-d', strtotime($request->sales_date));
                    $payment->sales_invoice = $pur->voucher_no;
                    $payment->total_amount = $request->total_pay_amount;
                    $payment->total_payment = $request->total_payment;
                    $payment->total_due = $request->total_due;
                    $payment->status=0;
                    if($payment->save()){
                        if($request->payment_head){
                            foreach($request->payment_head as $i=>$ph){
                                $pay=new CustomerPaymentDetails;
                                $pay->sales_id = $pur->id;
                                $pay->company_id=company()['company_id'];
                                $pay->customer_payment_id=$payment->id;
                                $pay->customer_id=$request->customerName;
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

                return redirect()->route(currentUser().'.sales.index')->with($this->resMessageHtml(true,null,'Successfully created'));
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
     * @param  \App\Models\Sales\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $show_data= Sales::findOrFail(encryptor('decrypt',$id));
        $salesDetail= Sales_details::where('sales_id',$show_data->id)->get();
        $expense = ExpenseOfSales::where('sales_id',$show_data->id)->get();
        return view('sales.show',compact('show_data','salesDetail','expense'));
    }

    public function saleView($id)
    {
        $show_data= Sales::findOrFail(encryptor('decrypt',$id));
        $salesDetail= Sales_details::where('sales_id',$show_data->id)->get();
        return view('sales.saleBill',compact('show_data','salesDetail'));
    }
    public function saleMemo($id)
    {
        $show_data= Sales::findOrFail(encryptor('decrypt',$id));
        $salesDetail= Sales_details::where('sales_id',$show_data->id)->get();
        $expense = ExpenseOfSales::where('sales_id',$show_data->id)->get();
        return view('sales.memo',compact('show_data','salesDetail','expense'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sales\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branches = Branch::where(company())->get();
        if( currentUser()=='owner'){
            $customers = Customer::where(company())->get();
            $Warehouses = Warehouse::where(company())->get();
            $sales = Sales::findOrFail(encryptor('decrypt',$id));
            $salesDetails = DB::select("SELECT sales_details.*, (select sum(stocks.quantity_bag) as bag_qty from stocks where stocks.batch_id=sales_details.batch_id) as bag_qty ,(select sum(stocks.quantity) as bag_qty from stocks where stocks.batch_id=sales_details.batch_id) as qty , (select product_name from products where products.id=sales_details.product_id) as productName FROM `sales_details` where sales_details.sales_id=".$sales->id." ");

            $childone = Child_one::where(company())->where('head_code',5320)->first();
            $childTow = Child_two::where(company())->where('child_one_id',$childone->id)->get();
            // $expense = ExpenseOfSales::where('sales_id',$sales->id)->pluck('cost_amount','child_two_id');
            $expense = ExpenseOfSales::where('sales_id',$sales->id)->get();
            $customerPayment = CustomerPayment::where(company())->where('sales_id',$sales->id)->first();
            $customerPaymentDetails = CustomerPaymentDetails::where(company())->where('customer_payment_id',$customerPayment->id)->get();
        }else{
            $customers = Customer::where(company())->where(branch())->get();
            $Warehouses = Warehouse::where(company())->where(branch())->get();
            $sales = Sales::findOrFail(encryptor('decrypt',$id));
            $salesDetails = DB::select("SELECT sales_details.*, (select sum(stocks.quantity_bag) as bag_qty from stocks where stocks.batch_id=sales_details.batch_id) as bag_qty ,(select sum(stocks.quantity) as bag_qty from stocks where stocks.batch_id=sales_details.batch_id) as qty , (select product_name from products where products.id=sales_details.product_id) as productName FROM `sales_details` where sales_details.sales_id=".$sales->id." ");
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

        return view('sales.edit',compact('branches','customers','Warehouses','sales','salesDetails','childTow','expense','paymethod','customerPayment','customerPaymentDetails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sales\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $pur=  Sales::findOrFail(encryptor('decrypt',$id));
            $pur->customer_id=$request->customerName;
            $pur->sales_date=date('Y-m-d', strtotime($request->sales_date));
            $pur->grand_total=$request->tgrandtotal;
            $pur->company_id=company()['company_id'];
            $pur->branch_id=$request->branch_id;
            $pur->warehouse_id=$request->warehouse_id;
            $pur->updated_by=currentUserId();
            $pur->payment_status=0;
            $pur->status=1;
            if($pur->save()){
                if($request->child_two_id){
                    ExpenseOfSales::where('sales_id',$pur->id)->delete();
                    foreach($request->child_two_id as $j=>$child_two_id){
                        $ex = new ExpenseOfSales;
                        $ex->company_id=company()['company_id'];
                        $ex->sales_id=$pur->id;
                        $ex->child_two_id=$child_two_id;
                        $ex->cost_amount=$request->cost_amount[$j];
                        $ex->lot_no=$request->lc_no[$j];
                        $ex->status= 0;
                        $ex->save();
                    }
                }
                if($request->product_id){
                    Sales_details::where('sales_id',$pur->id)->delete();
                    Stock::where('sales_id',$pur->id)->delete();
                    foreach($request->product_id as $i=>$product_id){
                        if($request->lot_no[$i]>0){
                            $pd=new Sales_details;
                            $pd->sales_id=$pur->id;
                            $pd->product_id=$product_id;
                            $pd->company_id=company()['company_id'];
                            $pd->lot_no=$request->lot_no[$i];
                            $pd->batch_id=$request->batch_id[$i];
                            $pd->brand=$request->brand[$i];
                            $pd->quantity_bag=$request->qty_bag[$i];
                            $pd->quantity_kg=$request->qty_kg[$i];
                            $pd->less_quantity_kg=$request->less_qty_kg[$i];
                            $pd->actual_quantity=$request->actual_qty[$i];
                            $pd->rate_kg=$request->rate_in_kg[$i];
                            $pd->amount=$request->amount[$i];
                            if($pd->save()){
                                $stock=new Stock;
                                $stock->product_id=$product_id;
                                $stock->sales_id=$pur->id;
                                $stock->company_id=company()['company_id'];
                                $stock->branch_id=$request->branch_id;
                                $stock->warehouse_id=$request->warehouse_id;
                                $stock->quantity='-'.$pd->actual_quantity;
                                $stock->quantity_bag='-'.$pd->quantity_bag;
                                $stock->lot_no=$pd->lot_no;
                                $stock->brand=$pd->brand;
                                $stock->batch_id=$pd->batch_id;
                                $stock->unit_price=$pd->rate_kg;
                                $stock->total_amount=$pd->amount;
                                $stock->save();


                            }
                        }
                    }
                }
                if($request->total_pay_amount){
                    CustomerPayment::where('sales_id',$pur->id)->delete();
                    CustomerPaymentDetails::where('sales_id',$pur->id)->delete();
                    $payment=new CustomerPayment;
                    $payment->sales_id = $pur->id;
                    $payment->company_id = company()['company_id'];
                    $payment->customer_id = $request->customerName;
                    $payment->sales_date = date('Y-m-d', strtotime($request->sales_date));
                    $payment->sales_invoice = $pur->voucher_no;
                    $payment->total_amount = $request->total_pay_amount;
                    $payment->total_payment = $request->total_payment;
                    $payment->total_due = $request->total_due;
                    $payment->status=0;
                    if($payment->save()){
                        if($request->payment_head){
                            foreach($request->payment_head as $i=>$ph){
                                $pay=new CustomerPaymentDetails;
                                $pay->sales_id = $pur->id;
                                $pay->company_id=company()['company_id'];
                                $pay->customer_payment_id=$payment->id;
                                $pay->customer_id=$request->customerName;
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

                return redirect()->route(currentUser().'.sales.index')->with($this->resMessageHtml(true,null,'Successfully Update'));
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
     * @param  \App\Models\Sales\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sales $sales)
    {
        //
    }
}

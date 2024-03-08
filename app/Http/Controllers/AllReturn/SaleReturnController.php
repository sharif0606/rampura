<?php

namespace App\Http\Controllers\AllReturn;
use App\Http\Controllers\Controller;

use App\Models\AllReturn\Sale_return;
use App\Models\AllReturn\Sale_return_detail;
use App\Models\Accounts\Child_one;
use App\Models\Accounts\Child_two;
use App\Models\Settings\Branch;
use App\Models\Settings\Warehouse;
use App\Models\Stock\Stock;
use App\Models\Customers\Customer;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Models\Customers\CustomerPayment;
use App\Models\Customers\CustomerPaymentDetails;
use App\Models\Expenses\ExpenseOfSales;
use App\Models\Sales\BagDetail;
use App\Models\Sales\Sales;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaleReturnController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customers = Customer::where(company())->get();
        $sales = Sale_return::where(company());
        $sales = Sale_return::with('sale_lot','customer','warehouse','createdBy','updatedBy')->where(company());

        if($request->nane)
            $sales=$sales->where('customer_id','like','%'.$request->nane.'%');
        if($request->sales_date)
            $sales=$sales->where('sales_date',$request->sales_date);

        if($request->lot_no){
            $lotno=$request->lot_no;
            $sales=$sales->whereHas('sale_lot', function($q) use ($lotno){
                $q->where('lot_no', $lotno);
            });
        }

        $sales=$sales->orderBy('id', 'DESC')->paginate(12);

        return view('salesReturn.index',compact('sales','customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = Branch::where(company())->get();
        $customers = Customer::where(company())->get();
        $walking_customer = Customer::whereNotIn('is_walking', [0])->where(company())->first();
        $Warehouses = Warehouse::where(company())->get();
        $childone = Child_one::where(company())->where('head_code',5320)->first();
        $childTow = Child_two::where(company())->where('child_one_id',$childone->id)->get();

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

        return view('salesReturn.create',compact('branches','customers','walking_customer','Warehouses','childTow','paymethod'));
    }

    public function product_sc(Request $request)
    {
        if ($request->name) {
            $salesIds = Sales::where(company())->where('customer_id', $request->customer_id)->pluck('id');
            //$salesIds = implode(',', $salesIds);
            $batchIdCondition = $request->batch_id ? "stocks.batch_id NOT IN (". rtrim($request->batch_id, ','). ")" : '';

            $products = DB::table('products')
                ->select('products.id', 'products.product_name', 'products.bar_code', 'stocks.lot_no', 'stocks.unit_price', 'stocks.batch_id', 'stocks.brand', 'stocks.quantity_bag as bag_qty', 'stocks.quantity as qty')
                ->join('stocks', 'stocks.product_id', '=', 'products.id')
                ->join('sales_details', 'sales_details.product_id', '=', 'products.id')
                ->where('stocks.company_id', company()['company_id'])
                ->whereIn('sales_details.sales_id', $salesIds)
                ->where('stocks.branch_id', $request->branch_id)
                ->where('stocks.warehouse_id', $request->warehouse_id)
                ->where(function ($query) use ($request) {
                    $query->where('stocks.lot_no', 'like', '%' . $request->name . '%')
                        ->orWhere('stocks.brand', 'like', '%' . $request->name . '%')
                        ->orWhere('products.product_name', 'like', '%' . $request->name . '%');
                })
                ->whereNotNull('stocks.batch_id')
                ->where('stocks.batch_id', '!=', '')
                ->whereRaw('(stocks.batch_id IS NOT NULL OR stocks.batch_id != "")');

                if($batchIdCondition)
                    $products = $products->whereRaw($batchIdCondition);

                $products = $products->groupBy('stocks.product_id', 'stocks.lot_no', 'stocks.brand', 'stocks.batch_id')
                ->get();

            return response()->json($products);
        }
    }

    public function product_sc_d(Request $request){
        if($request->item_id){
            $salesIds = Sales::where(company())->where('customer_id', $request->customer_id)->pluck('id')->toArray();
            $salesIds = implode(',', $salesIds);

            list($item_id,$lot_no,$brand,$batch_id)=explode("^",$request->item_id);
            $product=collect(DB::select("SELECT products.id,products.product_name,products.bar_code,stocks.lot_no,stocks.unit_price, sum(stocks.quantity_bag) as bag_qty, sum(stocks.quantity) as qty, stocks.brand FROM `products` JOIN stocks on stocks.product_id=products.id WHERE stocks.company_id=".company()['company_id']." and stocks.branch_id=".$request->branch_id." and stocks.sales_id in ($salesIds) and stocks.sales_id is not null and stocks.warehouse_id=".$request->warehouse_id." and stocks.product_id=".$item_id." and stocks.lot_no='".$lot_no."' and stocks.brand='".$brand."' and stocks.batch_id='".$batch_id."' and stocks.deleted_at is null GROUP BY stocks.batch_id"))->first();

            $data='<tr class="productlist text-center">';
            $data.='<td class="py-2 px-1">'.$product->product_name.'<input name="product_id[]" type="hidden" value="'.$product->id.'" class="product_id_list"><input name="stockqty[]" type="hidden" value="'.abs($product->qty).'" class="stockqty"><input name="batch_id[]" type="hidden" value="'.$batch_id.'" class="batch_id_list"></td>';
            $data.='<td class="py-2 px-1"><input readonly name="lot_no[]" type="text" class="form-control lot_no" value="'.$product->lot_no.'"></td>';
            $data.='<td class="py-2 px-1"><input readonly name="brand[]" type="text" class="form-control brand"  value="'.$product->brand.'"></td>';
            $data.='<td class="py-2 px-1"><input  type="text" class="form-control stock_bag" value="'.abs($product->bag_qty).'" disabled></td>';
            $data.='<td class="py-2 px-1"><input  type="text" class="form-control" value="'.abs($product->qty).'" disabled></td>';
            $data.='<td class="py-2 px-1" style="position:relative;">
                        <input onkeyup="get_cal(this)" name="qty_bag[]" type="text" class="form-control qty_bag" value="0">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#bagDetail'.$product->id.'" style="position:absolute; right:3px; top:14px; border:none; background-color:transparent; color: #435EBE; font-size:1.2rem;"><i class="bi bi-plus-square-fill"></i></button>
                        <div class="modal fade" id="bagDetail'.$product->id.'" tabindex="-1" role="dialog" aria-labelledby="balanceTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header py-1">
                                        <h5 class="modal-title" id="batchTitle">Bag Details</h5>
                                        <button type="button" class="close text-danger" data-bs-dismiss="modal" aria-label="Close">
                                            <i class="bi bi-x-lg" style="font-size: 1.5rem;"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body text-center" id="bagRow">
                                        <div class="row">
                                            <div class="col-2">
                                                <label for="lot_no" class="form-label">Lot Number</label>
                                                <input type="text" class="form-control" value="'.$product->lot_no.'" name="bag_lot_no['.$product->id.'][]" readonly>
                                            </div>
                                            <div class="col-2">
                                                <label for="bagno" class="form-label">Bag No</label>
                                                <input type="text" class="form-control" name="bag_no['.$product->id.'][]" placeholder="bag no">
                                            </div>
                                            <div class="col-3">
                                                <label for="bagno" class="form-label">Quantity Kg</label>
                                                <input type="number" class="form-control" name="quantity_detail['.$product->id.'][]" placeholder="quantity">
                                            </div>
                                            <div class="col-3">
                                                <label for="bagno" class="form-label">Comment</label>
                                                <input type="text" class="form-control" name="bag_comment['.$product->id.'][]" placeholder="quantity">
                                            </div>
                                            <div class="col-2 text-start">
                                                <label for="bagno" class="form-label">Action</label><br>
                                                <span class="text-primary"><i style="font-size: 1.3rem;" onclick="addBagRow(this,'.$product->id.')" class="bi bi-plus-square-fill"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>';
            $data.='<td class="py-2 px-1"><input onkeyup="get_cal(this)" name="qty_kg[]" type="text" class="form-control qty_kg" value="0"></td>';
            $data.='<td class="py-2 px-1"><input onkeyup="get_cal(this)" name="less_qty_kg[]" type="text" class="form-control less_qty_kg" value="0"></td>';
            $data.='<td class="py-2 px-1"><input onkeyup="get_cal(this)" name="actual_qty[]" readonly type="text" class="form-control actual_qty" value="0"></td>';
            $data.='<td class="py-2 px-1"><input onkeyup="get_cal(this)" name="rate_in_kg[]" type="text" class="form-control rate_in_kg" value="0" required></td>';
            $data.='<td class="py-2 px-1"><input name="amount[]" readonly type="text" class="form-control amount" value="0" required></td>';
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
    public function store(Request $request){
        DB::beginTransaction();
        try{

            $lot_noa=array();// lot/ lc no wise all cost
            $customeranypayment=0;

            $pur= new Sale_return;
            $pur->customer_id=$request->customerName;
            $pur->voucher_no='VR-'.Carbon::now()->format('m-y').'-'. str_pad((Sale_return::whereYear('created_at', Carbon::now()->year)->count() + 1),4,"0",STR_PAD_LEFT);
            $pur->voucher_type= $request->voucher_type;
            $pur->return_date=date('Y-m-d', strtotime($request->return_date));
            $pur->grand_total=$request->tgrandtotal;
            $pur->company_id=company()['company_id'];
            $pur->branch_id=$request->branch_id;
            $pur->warehouse_id=$request->warehouse_id;
            $pur->note=$request->note;
            $pur->created_by=currentUserId();

            $pur->payment_status=0;
            $pur->status=1;
            if($pur->save()){
                if($request->product_id){
                    foreach($request->product_id as $i=>$product_id){
                        $pd=new Sale_return_detail;
                        $pd->company_id=company()['company_id'];
                        $pd->sales_return_id=$pur->id;
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
                            $stock->sales_return_id=$pur->id;
                            $stock->company_id=company()['company_id'];
                            $stock->branch_id=$request->branch_id;
                            $stock->warehouse_id=$request->warehouse_id;
                            $stock->quantity=$pd->actual_quantity;
                            $stock->quantity_bag=$pd->quantity_bag;
                            $stock->lot_no=$pd->lot_no;
                            $stock->brand=$pd->brand;
                            $stock->batch_id=$pd->batch_id;
                            $stock->unit_price=$pd->rate_kg;
                            $stock->total_amount=$pd->amount;
                            $stock->stock_date=$pur->return_date;
                            $stock->save();
                            //calculate lot/lc payment
                            if(isset($lot_noa[$pd->lot_no])){
                                $lot_noa[$pd->lot_no]= $lot_noa[$pd->lot_no] + $pd->amount;
                            }else{
                                $lot_noa[$pd->lot_no]=$pd->amount;
                            }

                            if(isset($request->bag_lot_no[$product_id])){
                                foreach($request->bag_lot_no[$product_id] as $b=>$bag_lot_no){
                                    if($request->quantity_detail[$product_id][$b] > 0){
                                        $bag = new BagDetail;
                                        $bag->sales_return_id = $pur->id;
                                        $bag->company_id=company()['company_id'];
                                        $bag->sales_return_detail_id = $pd->id;
                                        $bag->product_id = $pd->product_id;
                                        $bag->lot_no = $bag_lot_no;
                                        $bag->bag_no = $request->bag_no[$product_id][$b];
                                        $bag->quantity_kg = $request->quantity_detail[$product_id][$b];
                                        $bag->comment = $request->bag_comment[$product_id][$b];
                                        $bag->save();
                                    }
                                }
                            }
                        }
                    }
                }
                
                if($request->child_two_id){
                    foreach($request->child_two_id as $j=>$child_two_id){
                        if($request->cost_amount[$j] > 0){
                            $ex = new ExpenseOfSales;
                            $ex->company_id=company()['company_id'];
                            $ex->sales_return_id=$pur->id;
                            $ex->child_two_id=explode('~',$child_two_id)[1];
                            $ex->sign_for_calculate=$request->sign_for_calculate[$j];
                            $ex->cost_amount=$request->cost_amount[$j];
                            $ex->lot_no=$request->lc_no[$j];
                            $ex->status= 0;
                            $ex->save();
                             //calculate lot/lc payment
                            if(isset($lot_noa[$request->lc_no[$j]])){
                                $lot_noa[$request->lc_no[$j]]= $lot_noa[$request->lc_no[$j]] + $request->cost_amount[$j];
                            }else{
                                $lot_noa[$request->lc_no[$j]]=$request->cost_amount[$j];
                            }
                        }
                    }
                }
              
                if($request->total_pay_amount){
                    $payment=new CustomerPayment;
                    $payment->sales_return_id = $pur->id;
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
                                if($request->pay_amount[$i] > 0){
                                    $customeranypayment=1;// check if full due or partial due 1= partial due or paid, 0 = full due
                                    $pay=new CustomerPaymentDetails;
                                    $pay->sales_return_id = $pur->id;
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
                }
                DB::commit();
                return redirect()->route(currentUser().'.salesReturn.index')->with($this->resMessageHtml(true,null,'Successfully created'));
            }else
                return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }catch(Exception $e){
            DB::rollback();
             //dd($e);
            return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Return\Sale_return  $sale_return
     * @return \Illuminate\Http\Response
     */
    public function show(Sale_return $sale_return)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Return\Sale_return  $sale_return
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale_return $sale_return)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Return\Sale_return  $sale_return
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sale_return $sale_return)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Return\Sale_return  $sale_return
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale_return $sale_return)
    {
        //
    }
}
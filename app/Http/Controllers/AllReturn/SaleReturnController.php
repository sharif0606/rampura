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
use App\Models\Vouchers\GeneralLedger;
use App\Models\Vouchers\GeneralVoucher;
use App\Models\Vouchers\SaleReturnVoucher;
use App\Models\Vouchers\SalReturnVoucherBkdn;
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
        if($request->return_date)
            $sales=$sales->where('return_date',$request->return_date);

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
                ->select('products.id', 'products.product_name', 'products.bar_code', 'stocks.lot_no', 'stocks.unit_price', 'stocks.batch_id', 'stocks.brand', DB::raw('sum(stocks.quantity_bag) as bag_qty'), DB::raw('sum(stocks.quantity) as qty'))
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
            $product=collect(DB::select("SELECT products.id,products.product_name,products.bar_code,stocks.lot_no,stocks.sales_id,stocks.unit_price, sum(stocks.quantity_bag) as bag_qty, sum(stocks.quantity) as qty, stocks.brand FROM `products` JOIN stocks on stocks.product_id=products.id WHERE stocks.company_id=".company()['company_id']." and stocks.branch_id=".$request->branch_id." and stocks.sales_id in ($salesIds) and stocks.sales_id is not null and stocks.warehouse_id=".$request->warehouse_id." and stocks.product_id=".$item_id." and stocks.lot_no='".$lot_no."' and stocks.brand='".$brand."' and stocks.batch_id='".$batch_id."' and stocks.deleted_at is null GROUP BY stocks.batch_id"))->first();

            $data='<tr class="productlist text-center">';
            $data.='<td class="py-2 px-1">'.$product->product_name.'<input name="product_id[]" type="hidden" value="'.$product->id.'" class="product_id_list"><input name="stockqty[]" type="hidden" value="'.abs($product->qty).'" class="stockqty"><input name="batch_id[]" type="hidden" value="'.$batch_id.'" class="batch_id_list"><input name="salesId" type="hidden" value="'.$product->sales_id.'"></td>';
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

    public function create_voucher_no(){
		$voucher_no="";
		$query = GeneralVoucher::where(company())->latest()->first();
		if(!empty($query)){
		    $voucher_no = $query->voucher_no;
			$voucher_no+=1;
			$gv=new GeneralVoucher;
			$gv->voucher_no=$voucher_no;
			$gv->company_id=company()['company_id'];
			if($gv->save())
				return $voucher_no;
			else
				return $voucher_no="";
		}else {
			$voucher_no=10000001;
			$gv=new GeneralVoucher;
			$gv->voucher_no=$voucher_no;
			$gv->company_id=company()['company_id'];
			if($gv->save())
				return $voucher_no;
			else
				return $voucher_no="";
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
            $pur->sales_id=$request->salesId;
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
                        $pd->sales_id=$request->salesId;
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
                            $stock->sales_id= $pd->sales_id;
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
                    $payment->sales_date = date('Y-m-d', strtotime($request->return_date));
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

                /* hit to account voucher */
                $vouchersIds=array();
                /* create due voucher */
                $voucher_no = $this->create_voucher_no();
                if(!empty($voucher_no)){
                    $jv=new SaleReturnVoucher;
                    $jv->voucher_no=$voucher_no;
                    $jv->company_id =company()['company_id'];
                    $jv->customer=$request->customer_r_name;
                    $jv->lc_no=$request->lot_no?implode(', ',array_unique($request->lot_no)):"";
                    $jv->current_date=date('Y-m-d', strtotime($request->return_date));
                    $jv->pay_name=$request->customer_r_name;
                    $jv->purpose="Sales Due";
                    $jv->credit_sum=$request->total_pay_amount;
                    $jv->debit_sum=$request->total_pay_amount;
                    $jv->cheque_no="";
                    $jv->bank="";
                    $jv->cheque_dt="";
                    $jv->created_by=currentUserId();
                    if($request->has('slip')){
                        $imageName= rand(111,999).time().'.'.$request->slip->extension();
                        $request->slip->move(public_path('uploads/slip'), $imageName);
                        $jv->slip=$imageName;
                    }
                    if($jv->save()){
                        $vouchersIds[]=$jv->id;
                        $customer_head=Child_two::select('id')->where(company())->where('head_code',"1130".$request->customerName)->first()->toArray()['id'];
                        foreach($lot_noa as $lc=>$amount){
                            if($amount > 0){
                                $jvb=new SalReturnVoucherBkdn;
                                $jvb->sale_return_voucher_id=$jv->id;
                                $jvb->customer_id=$request->customerName;
                                $jvb->lc_no=$lc;
                                $jvb->company_id =company()['company_id'];
                                $jvb->particulars="Sales due";
                                $jvb->account_code="1130".$request->customerName."-".$request->customer_r_name; //2=>head name 3=> head code
                                $jvb->table_name="child_twos";
                                $jvb->table_id=$customer_head;
                                $jvb->debit=$amount;
                                $jvb->created_at=$jv->current_date;
                                if($jvb->save()){
                                    $table_name=$jvb->table_name;
                                    if($table_name=="master_accounts"){$field_name="master_account_id";}
                                    else if($table_name=="sub_heads"){$field_name="sub_head_id";}
                                    else if($table_name=="child_ones"){$field_name="child_one_id";}
                                    else if($table_name=="child_twos"){$field_name="child_two_id";}
                                    $gl=new GeneralLedger;
                                    $gl->sale_return_voucher_id=$jv->id;
                                    $gl->company_id =company()['company_id'];
                                    $gl->journal_title=$request->customerName;
                                    $gl->account_title=$jvb->account_code;
                                    $gl->rec_date=$jv->current_date;
                                    $gl->jv_id=$voucher_no;
                                    $gl->sale_return_voucher_bkdn_id=$jvb->id;
                                    $gl->created_by=currentUserId();
                                    $gl->dr=$jvb->debit;
                                    $gl->lc_no=$jvb->lc_no;
                                    $gl->{$field_name}=$jvb->table_id;
                                    $gl->save();
                                }
                            }
                        }
                        // credit side sales
                        foreach($request->product_id as $i=>$product_id){
                            $jvb=new SalReturnVoucherBkdn;
                            $jvb->sale_return_voucher_id=$jv->id;
                            
                            $jvb->customer_id=$request->customerName;
                            $jvb->lc_no=$request->lot_no[$i]?$request->lot_no[$i]:"";

                            $jvb->company_id =company()['company_id'];
                            $jvb->particulars="Sales";
                            $jvb->account_code="4110-Sales";
                            $jvb->table_name="child_ones";
                            $jvb->table_id=Child_one::select('id')->where(company())->where('head_code',"4110")->first()->toArray()['id'];
                            $jvb->credit=$request->amount[$i];
                            $jvb->created_at=$jv->current_date;
                            if($jvb->save()){
                                $table_name=$jvb->table_name;
                                if($table_name=="master_accounts"){$field_name="master_account_id";}
                                else if($table_name=="sub_heads"){$field_name="sub_head_id";}
                                else if($table_name=="child_ones"){$field_name="child_one_id";}
                                else if($table_name=="child_twos"){$field_name="child_two_id";}
                                $gl=new GeneralLedger;
                                $gl->sale_return_voucher_id=$jv->id;
                                $gl->company_id =company()['company_id'];
                                $gl->journal_title=$request->customerName;
                                $gl->account_title=$jvb->account_code;
                                $gl->rec_date=$jv->current_date;
                                $gl->jv_id=$voucher_no;
                                $gl->sale_return_voucher_bkdn_id=$jvb->id;
                                $gl->created_by=currentUserId();
                                $gl->cr=$jvb->credit;
                                $gl->lc_no=$jvb->lc_no;
                                $gl->{$field_name}=$jvb->table_id;
                                $gl->save();
                            }
                        }
                        // credit side sales expense
                        if($request->child_two_id){
                            foreach($request->child_two_id as $j=>$child_two_id){
                                if($request->cost_amount[$j] > 0){
                                    $jvb=new SalReturnVoucherBkdn;
                                    $jvb->sale_return_voucher_id=$jv->id;
                                    
                                    $jvb->customer_id=$request->customerName;
                                    $jvb->lc_no=$request->lc_no[$j]?$request->lc_no[$j]:"";
    
                                    $jvb->company_id =company()['company_id'];
                                    $jvb->particulars="Sales Expense";
                                    $jvb->account_code=explode('~',$child_two_id)[3]."-".explode('~',$child_two_id)[2]; //2=>head name 3=> head code
                                    $jvb->table_name=explode('~',$child_two_id)[0];
                                    $jvb->table_id=explode('~',$child_two_id)[1];
                                    $jvb->credit=$request->cost_amount[$j];
                                    $jvb->created_at=$jv->current_date;
                                    if($jvb->save()){
                                        $table_name=$jvb->table_name;
                                        if($table_name=="master_accounts"){$field_name="master_account_id";}
                                        else if($table_name=="sub_heads"){$field_name="sub_head_id";}
                                        else if($table_name=="child_ones"){$field_name="child_one_id";}
                                        else if($table_name=="child_twos"){$field_name="child_two_id";}
                                        $gl=new GeneralLedger;
                                        $gl->sale_return_voucher_id=$jv->id;
                                        $gl->company_id =company()['company_id'];
                                        $gl->journal_title=$request->customerName;
                                        $gl->account_title=$jvb->account_code;
                                        $gl->rec_date=$jv->current_date;
                                        $gl->jv_id=$voucher_no;
                                        $gl->sale_return_voucher_bkdn_id=$jvb->id;
                                        $gl->created_by=currentUserId();
                                        $gl->cr=$jvb->credit;
                                        $gl->lc_no=$jvb->lc_no;
                                        $gl->{$field_name}=$jvb->table_id;
                                        $gl->save();
                                    }
                                }
                            }
                        }
                    }
                }
                if($customeranypayment==1){
                    /* create payment voucher */
                    $voucher_no = $this->create_voucher_no();
                    if(!empty($voucher_no)){
                        $jv=new SaleReturnVoucher;
                        $jv->voucher_no=$voucher_no;
                        $jv->company_id =company()['company_id'];
                        $jv->customer=$request->customer_r_name;
                        $jv->lc_no=$request->lc_no_payment?implode(', ',array_unique($request->lc_no_payment)):"";
                        $jv->current_date=date('Y-m-d', strtotime($request->return_date));
                        $jv->pay_name=$request->customer_r_name;
                        $jv->purpose="Sales Payment";
                        $jv->credit_sum=$request->total_payment;
                        $jv->debit_sum=$request->total_payment;
                        $jv->cheque_no="";
                        $jv->bank="";
                        $jv->cheque_dt="";
                        $jv->created_by=currentUserId();
                        if($request->has('slip')){
                            $imageName= rand(111,999).time().'.'.$request->slip->extension();
                            $request->slip->move(public_path('uploads/slip'), $imageName);
                            $jv->slip=$imageName;
                        }
                        if($jv->save()){
                            $vouchersIds[]=$jv->id;
                            $payment_head=$request->payment_head;
                            $customer_head=Child_two::select('id')->where('head_code',"1130".$request->customerName)->first()->toArray()['id'];

                            foreach($payment_head as $i=>$acccode){
                                if($request->pay_amount[$i] > 0){
                                    /* debit side */
                                    $jvb=new SalReturnVoucherBkdn;
                                    $jvb->sale_return_voucher_id=$jv->id;
                                    $jvb->customer_id=$request->customerName;
                                    $jvb->lc_no=!empty($request->lc_no_payment[$i])?$request->lc_no_payment[$i]:0;
                                    $jvb->company_id =company()['company_id'];
                                    $jvb->particulars="Sales Payment";
                                    $jvb->account_code=explode('~',$acccode)[3]."-".explode('~',$acccode)[2]; //2=>head name 3=> head code
                                    $jvb->table_name=explode('~',$acccode)[0];
                                    $jvb->table_id=explode('~',$acccode)[1];
                                    $jvb->debit=$request->pay_amount[$i];
                                    $jvb->created_at=$jv->current_date;
                                    if($jvb->save()){
                                        $table_name=$jvb->table_name;
                                        if($table_name=="master_accounts"){$field_name="master_account_id";}
                                        else if($table_name=="sub_heads"){$field_name="sub_head_id";}
                                        else if($table_name=="child_ones"){$field_name="child_one_id";}
                                        else if($table_name=="child_twos"){$field_name="child_two_id";}
                                        $gl=new GeneralLedger;
                                        $gl->sale_return_voucher_id=$jv->id;
                                        $gl->company_id =company()['company_id'];
                                        $gl->journal_title=$request->customerName;
                                        $gl->account_title=$jvb->account_code;
                                        $gl->rec_date=$jv->current_date;
                                        $gl->jv_id=$voucher_no;
                                        $gl->sale_return_voucher_bkdn_id=$jvb->id;
                                        $gl->created_by=currentUserId();
                                        $gl->dr=$jvb->debit;
                                        $gl->lc_no=$jvb->lc_no;
                                        $gl->{$field_name}=$jvb->table_id;
                                        $gl->save();
                                    }
                                    /* credit side */
                                    $jvc=new SalReturnVoucherBkdn;
                                    $jvc->sale_return_voucher_id=$jv->id;
                                    $jvc->customer_id=$request->customerName;
                                    $jvc->lc_no=!empty($request->lc_no_payment[$i])?$request->lc_no_payment[$i]:0;
                                    $jvc->company_id =company()['company_id'];
                                    $jvc->particulars="Sales Payment";
                                    $jvc->account_code="1130".$request->customerName."-".$request->customer_r_name; //2=>head name 3=> head code
                                    $jvc->table_name="child_twos";
                                    $jvc->table_id=$customer_head;
                                    $jvc->credit=$request->pay_amount[$i];
                                    $jvc->created_at=$jv->current_date;
                                    if($jvc->save()){
                                        $table_name=$jvc->table_name;
                                        if($table_name=="master_accounts"){$field_name="master_account_id";}
                                        else if($table_name=="sub_heads"){$field_name="sub_head_id";}
                                        else if($table_name=="child_ones"){$field_name="child_one_id";}
                                        else if($table_name=="child_twos"){$field_name="child_two_id";}
                                        $gl=new GeneralLedger;
                                        $gl->sale_return_voucher_id=$jv->id;
                                        $gl->company_id =company()['company_id'];
                                        $gl->journal_title=$request->customerName;
                                        $gl->rec_date=$jv->current_date;
                                        $gl->jv_id=$voucher_no;
                                        $gl->sale_return_voucher_bkdn_id=$jvc->id;
                                        $gl->created_by=currentUserId();
                                        $gl->cr=$jvc->credit;
                                        $gl->{$field_name}=$jvc->table_id;
                                        $gl->save();
                                    }
                                }
                            }
                            
                        }
                    }
                }
                Sale_return::where('id', $pur->id)->update(['reference_no' =>implode(',',$vouchersIds)]);

                DB::commit();
                return redirect()->route(currentUser().'.salesReturn.index')->with($this->resMessageHtml(true,null,'Successfully created'));
            }else
                return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }catch(Exception $e){
            DB::rollback();
             dd($e);
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
    public function edit($id)
    {
        $branches = Branch::where(company())->get();
        if( currentUser()=='owner'){
            $customers = Customer::where(company())->get();
            $Warehouses = Warehouse::where(company())->get();
            $return = Sale_return::findOrFail(encryptor('decrypt',$id));
            //$salesDetails = DB::select("SELECT sales_details.*, (select sum(stocks.quantity_bag) as bag_qty from stocks where stocks.batch_id=sales_details.batch_id and stocks.product_id=sales_details.product_id and stocks.deleted_at is null and sales_details.deleted_at is null ) as bag_qty ,(select sum(stocks.quantity) as bag_qty from stocks where stocks.batch_id=sales_details.batch_id and stocks.product_id=sales_details.product_id and stocks.deleted_at is null and sales_details.deleted_at is null) as qty , (select product_name from products where products.id=sales_details.product_id) as productName FROM `sales_details` where sales_details.sales_id=".$sales->id."");

            $returnDetails = DB::table('sale_return_details')
            ->select(
                'sale_return_details.*',
                DB::raw('(SELECT SUM(stocks.quantity_bag) FROM stocks WHERE stocks.batch_id = sale_return_details.batch_id AND stocks.product_id = sale_return_details.product_id AND stocks.deleted_at IS NULL AND stocks.sales_id = '.$return->sales_id.') AS bag_qty'),
                DB::raw('(SELECT SUM(stocks.quantity) FROM stocks WHERE stocks.batch_id = sale_return_details.batch_id AND stocks.product_id = sale_return_details.product_id AND stocks.deleted_at IS NULL AND stocks.sales_id = '.$return->sales_id.') AS qty'),
                DB::raw('(SELECT product_name FROM products WHERE products.id = sale_return_details.product_id) AS productName')
            )
            ->where('sale_return_details.sales_return_id', $return->id)
            ->whereNull('sale_return_details.deleted_at')
            ->get();
            
            $bagDetailsBySalesDetail = [];
            $bagDetails = [];
            foreach ($returnDetails as $sd) {
                $bagDetails = BagDetail::where('sales_return_id', $return->id)
                    ->where('sales_return_detail_id', $sd->id)
                    ->get();

                $bagDetailsBySalesDetail[$sd->id] = $bagDetails;
            }

            $childone = Child_one::where(company())->where('head_code',5320)->first();
            $childTow = Child_two::where(company())->where('child_one_id',$childone->id)->get();
            // $expense = ExpenseOfSales::where('sales_id',$sales->id)->pluck('cost_amount','child_two_id');
            $expense = ExpenseOfSales::where('sales_return_id',$return->id)->get();
            $customerPayment = CustomerPayment::where(company())->where('sales_return_id',$return->id)->first();
            $customerPaymentDetails = CustomerPaymentDetails::where(company())->where('customer_payment_id',$customerPayment->id)->get();
        }else{
            $customers = Customer::where(company())->where(branch())->get();
            $Warehouses = Warehouse::where(company())->where(branch())->get();
            $return = Sale_return::findOrFail(encryptor('decrypt',$id));
            //$salesDetails = DB::select("SELECT sales_details.*, (select sum(stocks.quantity_bag) as bag_qty from stocks where stocks.batch_id=sales_details.batch_id and stocks.product_id=sales_details.product_id and stocks.deleted_at is null and sales_details.deleted_at is null ) as bag_qty ,(select sum(stocks.quantity) as bag_qty from stocks where stocks.batch_id=sales_details.batch_id and stocks.product_id=sales_details.product_id and stocks.deleted_at is null and sales_details.deleted_at is null) as qty , (select product_name from products where products.id=sales_details.product_id) as productName FROM `sales_details` where sales_details.sales_id=".$sales->id."");
            $returnDetails = DB::table('sale_return_details')
            ->select(
                'sale_return_details.*',
                DB::raw('(SELECT SUM(stocks.quantity_bag) FROM stocks WHERE stocks.batch_id = sale_return_details.batch_id AND stocks.product_id = sale_return_details.product_id AND stocks.deleted_at IS NULL AND stocks.sales_id = '.$return->sales_id.') AS bag_qty'),
                DB::raw('(SELECT SUM(stocks.quantity) FROM stocks WHERE stocks.batch_id = sale_return_details.batch_id AND stocks.product_id = sale_return_details.product_id AND stocks.deleted_at IS NULL AND stocks.sales_id = '.$return->sales_id.') AS qty'),
                DB::raw('(SELECT product_name FROM products WHERE products.id = sale_return_details.product_id) AS productName')
            )
            ->where('sale_return_details.sales_return_id', $return->id)
            ->whereNull('sale_return_details.deleted_at')
            ->get();
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

        return view('salesReturn.edit',compact('branches','customers','Warehouses','return','returnDetails','bagDetails','bagDetailsBySalesDetail','childTow','expense','paymethod','customerPayment','customerPaymentDetails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Return\Sale_return  $sale_return
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{

            $lot_noa=array();// lot/ lc no wise all cost
            $customeranypayment=0;

            $pur= Sale_return::findOrFail(encryptor('decrypt',$id));
            $pur->customer_id=$request->customerName;
            $pur->sales_id=$request->salesId;
            $pur->voucher_type= $request->voucher_type;
            $pur->return_date=date('Y-m-d', strtotime($request->return_date));
            $pur->grand_total=$request->tgrandtotal;
            $pur->company_id=company()['company_id'];
            $pur->branch_id=$request->branch_id;
            $pur->warehouse_id=$request->warehouse_id;
            $pur->note=$request->note;
            $pur->updated_by=currentUserId();

            $pur->payment_status=0;
            $pur->status=1;
            if($pur->save()){
                if($request->product_id){
                    Sale_return_detail::where('sales_return_id',$pur->id)->forceDelete();
                    BagDetail::where('sales_return_id',$pur->id)->forceDelete();
                    Stock::where('sales_return_id',$pur->id)->forceDelete();
                    foreach($request->product_id as $i=>$product_id){
                        $pd=new Sale_return_detail;
                        $pd->company_id=company()['company_id'];
                        $pd->sales_return_id=$pur->id;
                        $pd->product_id=$product_id;
                        $pd->lot_no=$request->lot_no[$i];
                        $pd->sales_id=$request->salesId;
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
                            $stock->sales_id= $pd->sales_id;
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
                    ExpenseOfSales::where('sales_return_id',$pur->id)->forceDelete();
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
                    CustomerPayment::where('sales_return_id',$pur->id)->forceDelete();
                    CustomerPaymentDetails::where('sales_return_id',$pur->id)->forceDelete();
                    $payment=new CustomerPayment;
                    $payment->sales_return_id = $pur->id;
                    $payment->company_id = company()['company_id'];
                    $payment->customer_id = $request->customerName;
                    $payment->sales_date = date('Y-m-d', strtotime($request->return_date));
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
                $purrefArr=explode(',',$pur->reference_no);
                $vnon=SaleReturnVoucher::whereIn('id',$purrefArr)->pluck('voucher_no');
                GeneralVoucher::whereIn('voucher_no',$vnon)->forceDelete();
                SaleReturnVoucher::whereIn('id',$purrefArr)->forceDelete();
                SalReturnVoucherBkdn::whereIn('sale_return_voucher_id',$purrefArr)->forceDelete();
                GeneralLedger::whereIn('sale_return_voucher_id',$purrefArr)->forceDelete();
                /* hit to account voucher */
                $vouchersIds=array();
                /* create due voucher */
                $voucher_no = $this->create_voucher_no();
                if(!empty($voucher_no)){
                    $jv=new SaleReturnVoucher;
                    $jv->voucher_no=$voucher_no;
                    $jv->company_id =company()['company_id'];
                    $jv->customer=$request->customer_r_name;
                    $jv->lc_no=$request->lot_no?implode(', ',array_unique($request->lot_no)):"";
                    $jv->current_date=date('Y-m-d', strtotime($request->return_date));
                    $jv->pay_name=$request->customer_r_name;
                    $jv->purpose="Sales Due";
                    $jv->credit_sum=$request->total_pay_amount;
                    $jv->debit_sum=$request->total_pay_amount;
                    $jv->cheque_no="";
                    $jv->bank="";
                    $jv->cheque_dt="";
                    $jv->updated_by=currentUserId();
                    if($request->has('slip')){
                        $imageName= rand(111,999).time().'.'.$request->slip->extension();
                        $request->slip->move(public_path('uploads/slip'), $imageName);
                        $jv->slip=$imageName;
                    }
                    if($jv->save()){
                        $vouchersIds[]=$jv->id;
                        $customer_head=Child_two::select('id')->where('head_code',"1130".$request->customerName)->first()->toArray()['id'];
                        foreach($lot_noa as $lc=>$amount){
                            if($amount > 0){
                                $jvb=new SalReturnVoucherBkdn;
                                $jvb->sale_return_voucher_id=$jv->id;
                                $jvb->customer_id=$request->customerName;
                                $jvb->lc_no=$lc;
                                $jvb->company_id =company()['company_id'];
                                $jvb->particulars="Sales due";
                                $jvb->account_code="1130".$request->customerName."-".$request->customer_r_name; //2=>head name 3=> head code
                                $jvb->table_name="child_twos";
                                $jvb->table_id=$customer_head;
                                $jvb->debit=$amount;
                                $jvb->created_at=$jv->current_date;
                                if($jvb->save()){
                                    $table_name=$jvb->table_name;
                                    if($table_name=="master_accounts"){$field_name="master_account_id";}
                                    else if($table_name=="sub_heads"){$field_name="sub_head_id";}
                                    else if($table_name=="child_ones"){$field_name="child_one_id";}
                                    else if($table_name=="child_twos"){$field_name="child_two_id";}
                                    $gl=new GeneralLedger;
                                    $gl->sale_return_voucher_id=$jv->id;
                                    $gl->company_id =company()['company_id'];
                                    $gl->journal_title=$request->customerName;
                                    $gl->account_title=$jvb->account_code;
                                    $gl->rec_date=$jv->current_date;
                                    $gl->jv_id=$voucher_no;
                                    $gl->sale_return_voucher_bkdn_id=$jvb->id;
                                    $gl->updated_by=currentUserId();
                                    $gl->dr=$jvb->debit;
                                    $gl->lc_no=$jvb->lc_no;
                                    $gl->{$field_name}=$jvb->table_id;
                                    $gl->save();
                                }
                            }
                        }
                        // credit side sales
                        foreach($request->product_id as $i=>$product_id){
                            $jvb=new SalReturnVoucherBkdn;
                            $jvb->sale_return_voucher_id=$jv->id;
                            
                            $jvb->customer_id=$request->customerName;
                            $jvb->lc_no=$request->lot_no[$i]?$request->lot_no[$i]:"";

                            $jvb->company_id =company()['company_id'];
                            $jvb->particulars="Sales";
                            $jvb->account_code="4110-Sales";
                            $jvb->table_name="child_ones";
                            $jvb->table_id=Child_one::select('id')->where(company())->where('head_code',"4110")->first()->toArray()['id'];
                            $jvb->credit=$request->amount[$i];
                            $jvb->created_at=$jv->current_date;
                            if($jvb->save()){
                                $table_name=$jvb->table_name;
                                if($table_name=="master_accounts"){$field_name="master_account_id";}
                                else if($table_name=="sub_heads"){$field_name="sub_head_id";}
                                else if($table_name=="child_ones"){$field_name="child_one_id";}
                                else if($table_name=="child_twos"){$field_name="child_two_id";}
                                $gl=new GeneralLedger;
                                $gl->sale_return_voucher_id=$jv->id;
                                $gl->company_id =company()['company_id'];
                                $gl->journal_title=$request->customerName;
                                $gl->account_title=$jvb->account_code;
                                $gl->rec_date=$jv->current_date;
                                $gl->jv_id=$voucher_no;
                                $gl->sale_return_voucher_bkdn_id=$jvb->id;
                                $gl->updated_by=currentUserId();
                                $gl->cr=$jvb->credit;
                                $gl->lc_no=$jvb->lc_no;
                                $gl->{$field_name}=$jvb->table_id;
                                $gl->save();
                            }
                        }
                        // credit side sales expense
                        if($request->child_two_id){
                            foreach($request->child_two_id as $j=>$child_two_id){
                                if($request->cost_amount[$j] >0){
                                    $jvb=new SalReturnVoucherBkdn;
                                    $jvb->sale_return_voucher_id=$jv->id;
                                    
                                    $jvb->customer_id=$request->customerName;
                                    $jvb->lc_no=$request->lc_no[$j]?$request->lc_no[$j]:"";
    
                                    $jvb->company_id =company()['company_id'];
                                    $jvb->particulars="Sales Expense";
                                    $jvb->account_code=explode('~',$child_two_id)[3]."-".explode('~',$child_two_id)[2]; //2=>head name 3=> head code
                                    $jvb->table_name=explode('~',$child_two_id)[0];
                                    $jvb->table_id=explode('~',$child_two_id)[1];
                                    $jvb->credit=$request->cost_amount[$j];
                                    $jvb->created_at=$jv->current_date;
                                    if($jvb->save()){
                                        $table_name=$jvb->table_name;
                                        if($table_name=="master_accounts"){$field_name="master_account_id";}
                                        else if($table_name=="sub_heads"){$field_name="sub_head_id";}
                                        else if($table_name=="child_ones"){$field_name="child_one_id";}
                                        else if($table_name=="child_twos"){$field_name="child_two_id";}
                                        $gl=new GeneralLedger;
                                        $gl->sale_return_voucher_id=$jv->id;
                                        $gl->company_id =company()['company_id'];
                                        $gl->journal_title=$request->customerName;
                                        $gl->account_title=$jvb->account_code;
                                        $gl->rec_date=$jv->current_date;
                                        $gl->jv_id=$voucher_no;
                                        $gl->sale_return_voucher_bkdn_id=$jvb->id;
                                        $gl->lc_no=$jvb->lc_no;
                                        $gl->updated_by=currentUserId();
                                        $gl->cr=$jvb->credit;
                                        $gl->{$field_name}=$jvb->table_id;
                                        $gl->save();
                                    }
                                }
                            }
                        }
                    }
                }
                /* create payment voucher */
                if($customeranypayment==1){
                    $voucher_no = $this->create_voucher_no();
                    if(!empty($voucher_no)){
                        $jv=new SaleReturnVoucher;
                        $jv->voucher_no=$voucher_no;
                        $jv->company_id =company()['company_id'];
                        $jv->customer=$request->customer_r_name;
                        $jv->lc_no=$request->lc_no_payment?implode(', ',array_unique($request->lc_no_payment)):"";
                        $jv->current_date=date('Y-m-d', strtotime($request->return_date));
                        $jv->pay_name=$request->customer_r_name;
                        $jv->purpose="Sales Payment";
                        $jv->credit_sum=$request->total_payment;
                        $jv->debit_sum=$request->total_payment;
                        $jv->cheque_no="";
                        $jv->bank="";
                        $jv->cheque_dt="";
                        $jv->updated_by=currentUserId();
                        if($request->has('slip')){
                            $imageName= rand(111,999).time().'.'.$request->slip->extension();
                            $request->slip->move(public_path('uploads/slip'), $imageName);
                            $jv->slip=$imageName;
                        }
                        if($jv->save()){
                            $vouchersIds[]=$jv->id;
                            $payment_head=$request->payment_head;
                            $customer_head=Child_two::select('id')->where('head_code',"1130".$request->customerName)->first()->toArray()['id'];

                            foreach($payment_head as $i=>$acccode){
                                if($request->pay_amount[$i] > 0){
                                    /* debit side */
                                    $jvb=new SalReturnVoucherBkdn;
                                    $jvb->sale_return_voucher_id=$jv->id;
                                    $jvb->customer_id=$request->customerName;
                                    $jvb->lc_no=!empty($request->lc_no_payment[$i])?$request->lc_no_payment[$i]:0;
                                    $jvb->company_id =company()['company_id'];
                                    $jvb->particulars="Sales Payment";
                                    $jvb->account_code=explode('~',$acccode)[3]."-".explode('~',$acccode)[2]; //2=>head name 3=> head code
                                    $jvb->table_name=explode('~',$acccode)[0];
                                    $jvb->table_id=explode('~',$acccode)[1];
                                    $jvb->debit=$request->pay_amount[$i];
                                    $jvb->created_at=$jv->current_date;
                                    if($jvb->save()){
                                        $table_name=$jvb->table_name;
                                        if($table_name=="master_accounts"){$field_name="master_account_id";}
                                        else if($table_name=="sub_heads"){$field_name="sub_head_id";}
                                        else if($table_name=="child_ones"){$field_name="child_one_id";}
                                        else if($table_name=="child_twos"){$field_name="child_two_id";}
                                        $gl=new GeneralLedger;
                                        $gl->sale_return_voucher_id=$jv->id;
                                        $gl->company_id =company()['company_id'];
                                        $gl->journal_title=$request->customerName;
                                        $gl->account_title=$jvb->account_code;
                                        $gl->rec_date=$jv->current_date;
                                        $gl->jv_id=$voucher_no;
                                        $gl->sale_return_voucher_bkdn_id=$jvb->id;
                                        $gl->updated_by=currentUserId();
                                        $gl->dr=$jvb->debit;
                                        $gl->lc_no=$jvb->lc_no;
                                        $gl->{$field_name}=$jvb->table_id;
                                        $gl->save();
                                    }
                                    /* credit side */
                                    $jvc=new SalReturnVoucherBkdn;
                                    $jvc->sale_return_voucher_id=$jv->id;
                                    $jvc->customer_id=$request->customerName;
                                    $jvc->lc_no=!empty($request->lc_no_payment[$i])?$request->lc_no_payment[$i]:0;
                                    $jvc->company_id =company()['company_id'];
                                    $jvc->particulars="Sales Payment";
                                    $jvc->account_code="1130".$request->customerName."-".$request->customer_r_name; //2=>head name 3=> head code
                                    $jvc->table_name="child_twos";
                                    $jvc->table_id=$customer_head;
                                    $jvc->credit=$request->pay_amount[$i];
                                    $jvc->created_at=$jv->current_date;
                                    if($jvc->save()){
                                        $table_name=$jvc->table_name;
                                        if($table_name=="master_accounts"){$field_name="master_account_id";}
                                        else if($table_name=="sub_heads"){$field_name="sub_head_id";}
                                        else if($table_name=="child_ones"){$field_name="child_one_id";}
                                        else if($table_name=="child_twos"){$field_name="child_two_id";}
                                        $gl=new GeneralLedger;
                                        $gl->sale_return_voucher_id=$jv->id;
                                        $gl->company_id =company()['company_id'];
                                        $gl->journal_title=$request->customerName;
                                        $gl->rec_date=$jv->current_date;
                                        $gl->jv_id=$voucher_no;
                                        $gl->sale_return_voucher_bkdn_id=$jvc->id;
                                        $gl->updated_by=currentUserId();
                                        $gl->cr=$jvc->credit;
                                        $gl->{$field_name}=$jvc->table_id;
                                        $gl->save();
                                    }
                                }
                            }
                            
                        }
                    }
                }
                Sale_return::where('id', $pur->id)->update(['reference_no' =>implode(',',$vouchersIds)]);

                DB::commit();
                return redirect()->route(currentUser().'.salesReturn.index')->with($this->resMessageHtml(true,null,'Successfully Updated'));
            }else
                return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }catch(Exception $e){
            DB::rollback();
             //dd($e);
            return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }
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

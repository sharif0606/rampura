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
use App\Models\Purchases\PurReceiveInformation;
use App\Models\Suppliers\SupplierPayment;
use App\Models\Suppliers\SupplierPaymentDetails;
use App\Models\Vouchers\GeneralLedger;
use App\Models\Vouchers\GeneralVoucher;
use App\Models\Vouchers\PurchaseVoucher;
use App\Models\Vouchers\PurVoucherBkdns;
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
    public function index(Request $request)
    {
        $suppliers= Supplier::where(company())->get();
        $purchases = Regular_purchase::where(company());
        // if( currentUser()=='owner')
        //     $purchases = Regular_purchase::where(company());
        // else
        //     $purchases = Regular_purchase::where(company())->where(branch());
            
        if($request->nane)
        $purchases=$purchases->where('supplier_id','like','%'.$request->nane.'%');

        $purchases=$purchases->orderBy('id', 'DESC')->paginate(12);

        return view('regularPurchase.index',compact('purchases','suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = Branch::where(company())->get();
        $suppliers = Supplier::where(company())->get();
        $Warehouses = Warehouse::where(company())->get();
        // if( currentUser()=='owner'){
        //     $suppliers = Supplier::where(company())->get();
        //     $Warehouses = Warehouse::where(company())->get();
        // }else{
        //     $suppliers = Supplier::where(company())->where(branch())->get();
        //     $Warehouses = Warehouse::where(company())->where(branch())->get();
        // }
        $childone = Child_one::where(company())->whereIn('head_code',[5310,4120])->pluck('id');
        $childTow = Child_two::where(company())->whereIn('child_one_id',$childone)->get();

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

     public function create_voucher_no(){
		$voucher_no="";
		$query = GeneralVoucher::latest()->first();
		if(!empty($query)){
		    $voucher_no = $query->voucher_no;
			$voucher_no+=1;
			$gv=new GeneralVoucher;
			$gv->voucher_no=$voucher_no;
			if($gv->save())
				return $voucher_no;
			else
				return $voucher_no="";
		}else {
			$voucher_no=10000001;
			$gv=new GeneralVoucher;
			$gv->voucher_no=$voucher_no;
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
    public function store(AddNewRequest $request)
    {
        DB::beginTransaction();
        try{
            $lot_noa=array();// lot/ lc no wise all cost
            $lot_noInc=array();// lot/ lc no wise all Income

            $pur= new Regular_purchase;
            $pur->supplier_id=$request->supplierName;
            $pur->voucher_no='VR-'.Carbon::now()->format('m-y').'-'. str_pad((Regular_purchase::whereYear('created_at', Carbon::now()->year)->count() + 1),4,"0",STR_PAD_LEFT);
            $pur->purchase_date = date('Y-m-d', strtotime($request->purchase_date));
            $pur->grand_total=$request->tgrandtotal;
            $pur->company_id=company()['company_id'];
            $pur->branch_id=$request->branch_id;
            $pur->warehouse_id=$request->warehouse_id;
            $pur->note=$request->note;
            $pur->created_by=currentUserId();
            $pur->payment_status=0;
            $pur->status=1;
            if($pur->save()){
                $pri = new PurReceiveInformation;
                $pri->company_id=company()['company_id'];
                $pri->regular_purchase_id=$pur->id;
                $pri->bl_no = $request->bl_no;
                $pri->bl_date = $request->bl_date;
                $pri->port_no = $request->port_no;
                $pri->port_name = $request->port_name;
                $pri->assesment_no = $request->assesment_no;
                $pri->assesment_date = $request->assesment_date;
                $pri->truck_no = $request->truck_no;
                $pri->truck_date = $request->truck_date;
                $pri->sea_no = $request->sea_no;
                $pri->sea_date = $request->sea_date;
                $pri->save();
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
                            
                            //calculate lot/lc payment
                            if(isset($lot_noa[$pd->lot_no])){
                                $lot_noa[$pd->lot_no]= $lot_noa[$pd->lot_no] + $pd->amount;
                            }else{
                                $lot_noa[$pd->lot_no]=$pd->amount;
                            }
                        }
                    }
                }
                if($request->child_two_id){
                    foreach($request->child_two_id as $j=>$child_two_id){
                        if($request->cost_amount[$j] > 0){
                            $ex = new ExpenseOfPurchase;
                            $ex->regular_purchase_id=$pur->id;
                            $ex->company_id=company()['company_id'];
                            $ex->child_two_id=explode('~',$child_two_id)[1];
                            $ex->sign_for_calculate=$request->sign_for_calculate[$j];
                            $ex->cost_amount=$request->cost_amount[$j];
                            $ex->lot_no=$request->lc_no[$j];
                            $ex->status= 0;
                            $ex->save();
                            //calculate lot/lc payment
                            if(isset($lot_noa[$request->lc_no[$j]])){
                                if($request->sign_for_calculate[$j]=="+")
                                    $lot_noa[$request->lc_no[$j]]= $lot_noa[$request->lc_no[$j]] + $request->cost_amount[$j];
                                else if(isset($lot_noInc[$request->lc_no[$j]]))
                                    $lot_noInc[$request->lc_no[$j]]= $lot_noInc[$request->lc_no[$j]] + $request->cost_amount[$j];
                                else
                                    $lot_noInc[$request->lc_no[$j]]=$request->cost_amount[$j];
                            }else{
                                if($request->sign_for_calculate[$j]=="+")
                                    $lot_noa[$request->lc_no[$j]]=$request->cost_amount[$j];
                                else
                                    $lot_noInc[$request->lc_no[$j]]=$request->cost_amount[$j];
                            }
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

                

                /* hit to account voucher */
                $vouchersIds=array();
                /* create due voucher */
                $voucher_no = $this->create_voucher_no();
                if(!empty($voucher_no)){
                    $jv=new PurchaseVoucher;
                    $jv->voucher_no=$voucher_no;
                    $jv->company_id =company()['company_id'];
                    $jv->supplier=$request->supplier_r_name;
                    $jv->lc_no=$request->lot_no?implode(', ',array_unique($request->lot_no)):"";
                    $jv->current_date=date('Y-m-d', strtotime($request->purchase_date));
                    $jv->pay_name=$request->supplier_r_name;
                    $jv->purpose="Purchase Due";
                    $jv->credit_sum=$request->tgrandtotal - array_sum($lot_noInc);
                    $jv->debit_sum=$request->tgrandtotal - array_sum($lot_noInc);
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
                        // debit side purchase
                        foreach($request->product_id as $i=>$product_id){
                            $jvb=new PurVoucherBkdns;
                            $jvb->purchase_voucher_id=$jv->id;
                            $jvb->supplier_id=$request->supplierName;
                            $jvb->lc_no=$request->lot_no[$i]?$request->lot_no[$i]:"";

                            $jvb->company_id =company()['company_id'];
                            $jvb->particulars="Purchase";
                            $jvb->account_code="Purchase-5330";
                            $jvb->table_name="child_ones";
                            $jvb->table_id="8";
                            $jvb->debit=$request->amount[$i];
                            $jvb->created_at=$jv->current_date;
                            if($jvb->save()){
                                $table_name=$jvb->table_name;
                                if($table_name=="master_accounts"){$field_name="master_account_id";}
                                else if($table_name=="sub_heads"){$field_name="sub_head_id";}
                                else if($table_name=="child_ones"){$field_name="child_one_id";}
                                else if($table_name=="child_twos"){$field_name="child_two_id";}
                                $gl=new GeneralLedger;
                                $gl->purchase_voucher_id=$jv->id;
                                $gl->company_id =company()['company_id'];
                                $gl->journal_title=$jvb->particulars;
                                $gl->rec_date=$jv->current_date;
                                $gl->lc_no=$jvb->lc_no;
                                $gl->jv_id=$voucher_no;
                                $gl->purchase_voucher_bkdn_id=$jvb->id;
                                $gl->created_by=currentUserId();
                                $gl->dr=$jvb->debit;
                                $gl->{$field_name}=$jvb->table_id;
                                $gl->save();
                            }
                        }
                        // debit side purchase expense
                        if($request->child_two_id){
                            foreach($request->child_two_id as $j=>$child_two_id){
                                if($request->cost_amount[$j] > 0 && $request->sign_for_calculate[$j]=="+"){
                                    $jvb=new PurVoucherBkdns;
                                    $jvb->purchase_voucher_id=$jv->id;
                                    $jvb->supplier_id=$request->supplierName;
                                    $jvb->lc_no=$request->lc_no[$j]?$request->lc_no[$j]:"";

                                    $jvb->company_id =company()['company_id'];
                                    $jvb->particulars="Purchase Expense";
                                    $jvb->account_code=explode('~',$child_two_id)[2]."-".explode('~',$child_two_id)[3]; //2=>head name 3=> head code
                                    $jvb->table_name=explode('~',$child_two_id)[0];
                                    $jvb->table_id=explode('~',$child_two_id)[1];
                                    $jvb->debit=$request->cost_amount[$j];
                                    $jvb->created_at=$jv->current_date;
                                    if($jvb->save()){
                                        $table_name=$jvb->table_name;
                                        if($table_name=="master_accounts"){$field_name="master_account_id";}
                                        else if($table_name=="sub_heads"){$field_name="sub_head_id";}
                                        else if($table_name=="child_ones"){$field_name="child_one_id";}
                                        else if($table_name=="child_twos"){$field_name="child_two_id";}
                                        $gl=new GeneralLedger;
                                        $gl->purchase_voucher_id=$jv->id;
                                        $gl->company_id =company()['company_id'];
                                        $gl->journal_title=$jvb->particulars;
                                        $gl->rec_date=$jv->current_date;
                                        $gl->lc_no=$jvb->lc_no;
                                        $gl->jv_id=$voucher_no;
                                        $gl->purchase_voucher_bkdn_id=$jvb->id;
                                        $gl->created_by=currentUserId();
                                        $gl->dr=$jvb->debit;
                                        $gl->{$field_name}=$jvb->table_id;
                                        $gl->save();
                                    }
                                }
                            }
                        }
                        // credit side purchase
                        $sup_head=Child_two::select('id')->where('head_code',"2130".$request->supplierName)->first()->toArray()['id'];
                        foreach($lot_noa as $lc=>$amount){
                            if($amount > 0){
                                $jvb=new PurVoucherBkdns;
                                $jvb->purchase_voucher_id=$jv->id;
                                $jvb->supplier_id=$request->supplierName;
                                $jvb->lc_no=$lc;
                                $jvb->company_id =company()['company_id'];
                                $jvb->particulars="Purchase due";
                                $jvb->account_code=$request->supplier_r_name."-2130".$request->supplierName; //2=>head name 3=> head code
                                $jvb->table_name="child_twos";
                                $jvb->table_id=$sup_head;
                                $jvb->credit=$amount;
                                $jvb->created_at=$jv->current_date;
                                if($jvb->save()){
                                    $table_name=$jvb->table_name;
                                    if($table_name=="master_accounts"){$field_name="master_account_id";}
                                    else if($table_name=="sub_heads"){$field_name="sub_head_id";}
                                    else if($table_name=="child_ones"){$field_name="child_one_id";}
                                    else if($table_name=="child_twos"){$field_name="child_two_id";}
                                    $gl=new GeneralLedger;
                                    $gl->purchase_voucher_id=$jv->id;
                                    $gl->company_id =company()['company_id'];
                                    $gl->journal_title=$jvb->particulars;
                                    $gl->rec_date=$jv->current_date;
                                    $gl->lc_no=$jvb->lc_no;
                                    $gl->jv_id=$voucher_no;
                                    $gl->purchase_voucher_bkdn_id=$jvb->id;
                                    $gl->created_by=currentUserId();
                                    $gl->cr=$jvb->credit;
                                    $gl->{$field_name}=$jvb->table_id;
                                    $gl->save();
                                }
                            }
                        }
                    }
                }
                /* create income voucher */
                if(array_sum($lot_noInc) > 0){
                    $voucher_no = $this->create_voucher_no();
                    if(!empty($voucher_no)){
                        $jv=new PurchaseVoucher;
                        $jv->voucher_no=$voucher_no;
                        $jv->company_id =company()['company_id'];
                        $jv->supplier=$request->supplier_r_name;
                        $jv->lc_no=$request->lot_no?implode(', ',array_unique($request->lot_no)):"";
                        $jv->current_date=date('Y-m-d', strtotime($request->purchase_date));
                        $jv->pay_name=$request->supplier_r_name;
                        $jv->purpose="Purchase Income";
                        $jv->credit_sum=array_sum($lot_noInc);
                        $jv->debit_sum=array_sum($lot_noInc);
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
                            // debit side purchase
                            $sup_head=Child_two::select('id')->where('head_code',"2130".$request->supplierName)->first()->toArray()['id'];
                            foreach($lot_noInc as $lc=>$amount){
                                if($amount > 0){
                                    $jvb=new PurVoucherBkdns;
                                    $jvb->purchase_voucher_id=$jv->id;
                                    $jvb->supplier_id=$request->supplierName;
                                    $jvb->lc_no=$lc;
                                    $jvb->company_id =company()['company_id'];
                                    $jvb->particulars="Purchase Income due";
                                    $jvb->account_code=$request->supplier_r_name."-2130".$request->supplierName; //2=>head name 3=> head code
                                    $jvb->table_name="child_twos";
                                    $jvb->table_id=$sup_head;
                                    $jvb->debit=$amount;
                                    $jvb->created_at=$jv->current_date;
                                    if($jvb->save()){
                                        $table_name=$jvb->table_name;
                                        if($table_name=="master_accounts"){$field_name="master_account_id";}
                                        else if($table_name=="sub_heads"){$field_name="sub_head_id";}
                                        else if($table_name=="child_ones"){$field_name="child_one_id";}
                                        else if($table_name=="child_twos"){$field_name="child_two_id";}
                                        $gl=new GeneralLedger;
                                        $gl->purchase_voucher_id=$jv->id;
                                        $gl->company_id =company()['company_id'];
                                        $gl->journal_title=$jvb->particulars;
                                        $gl->rec_date=$jv->current_date;
                                        $gl->lc_no=$jvb->lc_no;
                                        $gl->jv_id=$voucher_no;
                                        $gl->purchase_voucher_bkdn_id=$jvb->id;
                                        $gl->created_by=currentUserId();
                                        $gl->dr=$jvb->debit;
                                        $gl->{$field_name}=$jvb->table_id;
                                        $gl->save();
                                    }
                                }
                            }
                            // credit side purchase expense
                            if($request->child_two_id){
                                foreach($request->child_two_id as $j=>$child_two_id){
                                    if($request->cost_amount[$j] > 0 && $request->sign_for_calculate[$j]=="-"){
                                        $jvb=new PurVoucherBkdns;
                                        $jvb->purchase_voucher_id=$jv->id;
                                        $jvb->supplier_id=$request->supplierName;
                                        $jvb->lc_no=$request->lc_no[$j]?$request->lc_no[$j]:"";

                                        $jvb->company_id =company()['company_id'];
                                        $jvb->particulars="Purchase Income";
                                        $jvb->account_code=explode('~',$child_two_id)[2]."-".explode('~',$child_two_id)[3]; //2=>head name 3=> head code
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
                                            $gl->purchase_voucher_id=$jv->id;
                                            $gl->company_id =company()['company_id'];
                                            $gl->journal_title=$jvb->particulars;
                                            $gl->rec_date=$jv->current_date;
                                            $gl->lc_no=$jvb->lc_no;
                                            $gl->jv_id=$voucher_no;
                                            $gl->purchase_voucher_bkdn_id=$jvb->id;
                                            $gl->created_by=currentUserId();
                                            $gl->cr=$jvb->credit;
                                            $gl->{$field_name}=$jvb->table_id;
                                            $gl->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                
                Regular_purchase::where('id', $pur->id)->update(['reference_no' =>implode(',',$vouchersIds)]);
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
        }else{
            $suppliers = Supplier::where(company())->where(branch())->get();
            $Warehouses = Warehouse::where(company())->where(branch())->get();
        }
            $purchase = Regular_purchase::findOrFail(encryptor('decrypt',$id));
            $purReceiveInfo = PurReceiveInformation::where('regular_purchase_id',$purchase->id)->get();
            $purchaseDetails = Purchase_details::where('regular_purchase_id',$purchase->id)->get();
            $childone = Child_one::where(company())->whereIn('head_code',[5310,4120])->pluck('id');
            $childTow = Child_two::where(company())->whereIn('child_one_id',$childone)->get();
            $expense = ExpenseOfPurchase::where('regular_purchase_id',$purchase->id)->get();
            $supplerPayment = SupplierPayment::where(company())->where('regular_purchase_id',$purchase->id)->first();
            $supplierPaymentDetails = SupplierPaymentDetails::where(company())->where('supplier_payment_id',$supplerPayment->id)->get();

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
        
        return view('regularPurchase.edit',compact('branches','suppliers','Warehouses','purchase','purReceiveInfo','purchaseDetails','childTow','expense','paymethod','supplerPayment','supplierPaymentDetails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchases\regular_purchase  $regular_purchase
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try{
            $lot_noa=array();// lot/ lc no wise all cost
            $lot_noInc=array();// lot/ lc no wise all Income

            $pur= Regular_purchase::findOrFail(encryptor('decrypt',$id));
            $pur->supplier_id=$request->supplierName;
            $pur->purchase_date = date('Y-m-d', strtotime($request->purchase_date));
            $pur->grand_total=$request->tgrandtotal;
            $pur->company_id=company()['company_id'];
            $pur->branch_id=$request->branch_id;
            $pur->warehouse_id=$request->warehouse_id;
            $pur->note=$request->note;
            $pur->updated_by=currentUserId();

            if($pur->save()){
                $fpri = PurReceiveInformation::where('regular_purchase_id',$pur->id)->where(company())->first();
                if($fpri){
                    $fpri->bl_no = $request->bl_no;
                    $fpri->bl_date = $request->bl_date;
                    $fpri->port_no = $request->port_no;
                    $fpri->port_name = $request->port_name;
                    $fpri->assesment_no = $request->assesment_no;
                    $fpri->assesment_date = $request->assesment_date;
                    $fpri->truck_no = $request->truck_no;
                    $fpri->truck_date = $request->truck_date;
                    $fpri->sea_no = $request->sea_no;
                    $fpri->sea_date = $request->sea_date;
                    $fpri->save();
                }else{
                    $pri = new PurReceiveInformation;
                    $pri->company_id=company()['company_id'];
                    $pri->regular_purchase_id=$pur->id;
                    $pri->bl_no = $request->bl_no;
                    $pri->bl_date = $request->bl_date;
                    $pri->port_no = $request->port_no;
                    $pri->port_name = $request->port_name;
                    $pri->assesment_no = $request->assesment_no;
                    $pri->assesment_date = $request->assesment_date;
                    $pri->truck_no = $request->truck_no;
                    $pri->truck_date = $request->truck_date;
                    $pri->sea_no = $request->sea_no;
                    $pri->sea_date = $request->sea_date;
                    $pri->save();
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
                                
                                //calculate lot/lc payment
                                if(isset($lot_noa[$pd->lot_no])){
                                    $lot_noa[$pd->lot_no]= $lot_noa[$pd->lot_no] + $pd->amount;
                                }else{
                                    $lot_noa[$pd->lot_no]=$pd->amount;
                                }
                            }
                        }
                    }
                }
                if($request->child_two_id){
                    ExpenseOfPurchase::where('regular_purchase_id',$pur->id)->delete();
                    foreach($request->child_two_id as $j=>$child_two_id){
                        if($request->cost_amount[$j] > 0){
                            $ex = new ExpenseOfPurchase;
                            $ex->regular_purchase_id=$pur->id;
                            $ex->company_id=company()['company_id'];
                            $ex->child_two_id=explode('~',$child_two_id)[1];
                            $ex->sign_for_calculate=$request->sign_for_calculate[$j];
                            $ex->cost_amount=$request->cost_amount[$j];
                            $ex->lot_no=$request->lc_no[$j];
                            $ex->status= 0;
                            $ex->save();
                            //calculate lot/lc payment
                            if(isset($lot_noa[$request->lc_no[$j]])){
                                if($request->sign_for_calculate[$j]=="+")
                                    $lot_noa[$request->lc_no[$j]]= $lot_noa[$request->lc_no[$j]] + $request->cost_amount[$j];
                                else if(isset($lot_noInc[$request->lc_no[$j]]))
                                    $lot_noInc[$request->lc_no[$j]]= $lot_noInc[$request->lc_no[$j]] + $request->cost_amount[$j];
                                else
                                    $lot_noInc[$request->lc_no[$j]]=$request->cost_amount[$j];
                            }else{
                                if($request->sign_for_calculate[$j]=="+")
                                    $lot_noa[$request->lc_no[$j]]=$request->cost_amount[$j];
                                else
                                    $lot_noInc[$request->lc_no[$j]]=$request->cost_amount[$j];
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
                /* hit to account voucher */
                $purrefArr=explode(',',$pur->reference_no);
                PurchaseVoucher::whereIn('id',$purrefArr)->delete();
                PurVoucherBkdns::whereIn('purchase_voucher_id',$purrefArr)->delete();
                GeneralLedger::whereIn('purchase_voucher_id',$purrefArr)->delete();
                $vouchersIds=array();
                /* create due voucher */
                $voucher_no = $this->create_voucher_no();
                if(!empty($voucher_no)){
                    $jv=new PurchaseVoucher;
                    $jv->voucher_no=$voucher_no;
                    $jv->company_id =company()['company_id'];
                    $jv->supplier=$request->supplier_r_name;
                    $jv->lc_no=$request->lot_no?implode(', ',array_unique($request->lot_no)):"";
                    $jv->current_date=date('Y-m-d', strtotime($request->purchase_date));
                    $jv->pay_name=$request->supplier_r_name;
                    $jv->purpose="Purchase Due";
                    $jv->credit_sum=$request->tgrandtotal - array_sum($lot_noInc);
                    $jv->debit_sum=$request->tgrandtotal - array_sum($lot_noInc);
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
                        // debit side purchase
                        foreach($request->product_id as $i=>$product_id){
                            $jvb=new PurVoucherBkdns;
                            $jvb->purchase_voucher_id=$jv->id;
                            $jvb->supplier_id=$request->supplierName;
                            $jvb->lc_no=$request->lot_no[$i]?$request->lot_no[$i]:"";

                            $jvb->company_id =company()['company_id'];
                            $jvb->particulars="Purchase";
                            $jvb->account_code="Purchase-5330";
                            $jvb->table_name="child_ones";
                            $jvb->table_id="8";
                            $jvb->debit=$request->amount[$i];
                            $jvb->created_at=$jv->current_date;
                            if($jvb->save()){
                                $table_name=$jvb->table_name;
                                if($table_name=="master_accounts"){$field_name="master_account_id";}
                                else if($table_name=="sub_heads"){$field_name="sub_head_id";}
                                else if($table_name=="child_ones"){$field_name="child_one_id";}
                                else if($table_name=="child_twos"){$field_name="child_two_id";}
                                $gl=new GeneralLedger;
                                $gl->purchase_voucher_id=$jv->id;
                                $gl->company_id =company()['company_id'];
                                $gl->journal_title=$jvb->particulars;
                                $gl->rec_date=$jv->current_date;
                                $gl->lc_no=$jvb->lc_no;
                                $gl->jv_id=$voucher_no;
                                $gl->purchase_voucher_bkdn_id=$jvb->id;
                                $gl->created_by=currentUserId();
                                $gl->dr=$jvb->debit;
                                $gl->{$field_name}=$jvb->table_id;
                                $gl->save();
                            }
                        }
                        // debit side purchase expense
                        if($request->child_two_id){
                            foreach($request->child_two_id as $j=>$child_two_id){
                                if($request->cost_amount[$j] > 0 && $request->sign_for_calculate[$j]=="+"){
                                    $jvb=new PurVoucherBkdns;
                                    $jvb->purchase_voucher_id=$jv->id;
                                    $jvb->supplier_id=$request->supplierName;
                                    $jvb->lc_no=$request->lc_no[$j]?$request->lc_no[$j]:"";

                                    $jvb->company_id =company()['company_id'];
                                    $jvb->particulars="Purchase Expense";
                                    $jvb->account_code=explode('~',$child_two_id)[2]."-".explode('~',$child_two_id)[3]; //2=>head name 3=> head code
                                    $jvb->table_name=explode('~',$child_two_id)[0];
                                    $jvb->table_id=explode('~',$child_two_id)[1];
                                    $jvb->debit=$request->cost_amount[$j];
                                    $jvb->created_at=$jv->current_date;
                                    if($jvb->save()){
                                        $table_name=$jvb->table_name;
                                        if($table_name=="master_accounts"){$field_name="master_account_id";}
                                        else if($table_name=="sub_heads"){$field_name="sub_head_id";}
                                        else if($table_name=="child_ones"){$field_name="child_one_id";}
                                        else if($table_name=="child_twos"){$field_name="child_two_id";}
                                        $gl=new GeneralLedger;
                                        $gl->purchase_voucher_id=$jv->id;
                                        $gl->company_id =company()['company_id'];
                                        $gl->journal_title=$jvb->particulars;
                                        $gl->rec_date=$jv->current_date;
                                        $gl->lc_no=$jvb->lc_no;
                                        $gl->jv_id=$voucher_no;
                                        $gl->purchase_voucher_bkdn_id=$jvb->id;
                                        $gl->created_by=currentUserId();
                                        $gl->dr=$jvb->debit;
                                        $gl->{$field_name}=$jvb->table_id;
                                        $gl->save();
                                    }
                                }
                            }
                        }
                        // credit side purchase
                        $sup_head=Child_two::select('id')->where('head_code',"2130".$request->supplierName)->first()->toArray()['id'];
                        foreach($lot_noa as $lc=>$amount){
                            if($amount > 0){
                                $jvb=new PurVoucherBkdns;
                                $jvb->purchase_voucher_id=$jv->id;
                                $jvb->supplier_id=$request->supplierName;
                                $jvb->lc_no=$lc;
                                $jvb->company_id =company()['company_id'];
                                $jvb->particulars="Purchase due";
                                $jvb->account_code=$request->supplier_r_name."-2130".$request->supplierName; //2=>head name 3=> head code
                                $jvb->table_name="child_twos";
                                $jvb->table_id=$sup_head;
                                $jvb->credit=$amount;
                                $jvb->created_at=$jv->current_date;
                                if($jvb->save()){
                                    $table_name=$jvb->table_name;
                                    if($table_name=="master_accounts"){$field_name="master_account_id";}
                                    else if($table_name=="sub_heads"){$field_name="sub_head_id";}
                                    else if($table_name=="child_ones"){$field_name="child_one_id";}
                                    else if($table_name=="child_twos"){$field_name="child_two_id";}
                                    $gl=new GeneralLedger;
                                    $gl->purchase_voucher_id=$jv->id;
                                    $gl->company_id =company()['company_id'];
                                    $gl->journal_title=$jvb->particulars;
                                    $gl->rec_date=$jv->current_date;
                                    $gl->lc_no=$jvb->lc_no;
                                    $gl->jv_id=$voucher_no;
                                    $gl->purchase_voucher_bkdn_id=$jvb->id;
                                    $gl->created_by=currentUserId();
                                    $gl->cr=$jvb->credit;
                                    $gl->{$field_name}=$jvb->table_id;
                                    $gl->save();
                                }
                            }
                        }
                    }
                }
                /* create income voucher */
                if(array_sum($lot_noInc) > 0){
                    $voucher_no = $this->create_voucher_no();
                    if(!empty($voucher_no)){
                        $jv=new PurchaseVoucher;
                        $jv->voucher_no=$voucher_no;
                        $jv->company_id =company()['company_id'];
                        $jv->supplier=$request->supplier_r_name;
                        $jv->lc_no=$request->lot_no?implode(', ',array_unique($request->lot_no)):"";
                        $jv->current_date=date('Y-m-d', strtotime($request->purchase_date));
                        $jv->pay_name=$request->supplier_r_name;
                        $jv->purpose="Purchase Income";
                        $jv->credit_sum=array_sum($lot_noInc);
                        $jv->debit_sum=array_sum($lot_noInc);
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
                            // debit side purchase
                            $sup_head=Child_two::select('id')->where('head_code',"2130".$request->supplierName)->first()->toArray()['id'];
                            foreach($lot_noInc as $lc=>$amount){
                                if($amount > 0){
                                    $jvb=new PurVoucherBkdns;
                                    $jvb->purchase_voucher_id=$jv->id;
                                    $jvb->supplier_id=$request->supplierName;
                                    $jvb->lc_no=$lc;
                                    $jvb->company_id =company()['company_id'];
                                    $jvb->particulars="Purchase Income due";
                                    $jvb->account_code=$request->supplier_r_name."-2130".$request->supplierName; //2=>head name 3=> head code
                                    $jvb->table_name="child_twos";
                                    $jvb->table_id=$sup_head;
                                    $jvb->debit=$amount;
                                    $jvb->created_at=$jv->current_date;
                                    if($jvb->save()){
                                        $table_name=$jvb->table_name;
                                        if($table_name=="master_accounts"){$field_name="master_account_id";}
                                        else if($table_name=="sub_heads"){$field_name="sub_head_id";}
                                        else if($table_name=="child_ones"){$field_name="child_one_id";}
                                        else if($table_name=="child_twos"){$field_name="child_two_id";}
                                        $gl=new GeneralLedger;
                                        $gl->purchase_voucher_id=$jv->id;
                                        $gl->company_id =company()['company_id'];
                                        $gl->journal_title=$jvb->particulars;
                                        $gl->rec_date=$jv->current_date;
                                        $gl->lc_no=$jvb->lc_no;
                                        $gl->jv_id=$voucher_no;
                                        $gl->purchase_voucher_bkdn_id=$jvb->id;
                                        $gl->created_by=currentUserId();
                                        $gl->dr=$jvb->debit;
                                        $gl->{$field_name}=$jvb->table_id;
                                        $gl->save();
                                    }
                                }
                            }
                            // credit side purchase expense
                            if($request->child_two_id){
                                foreach($request->child_two_id as $j=>$child_two_id){
                                    if($request->cost_amount[$j] > 0 && $request->sign_for_calculate[$j]=="-"){
                                        $jvb=new PurVoucherBkdns;
                                        $jvb->purchase_voucher_id=$jv->id;
                                        $jvb->supplier_id=$request->supplierName;
                                        $jvb->lc_no=$request->lc_no[$j]?$request->lc_no[$j]:"";

                                        $jvb->company_id =company()['company_id'];
                                        $jvb->particulars="Purchase Income";
                                        $jvb->account_code=explode('~',$child_two_id)[2]."-".explode('~',$child_two_id)[3]; //2=>head name 3=> head code
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
                                            $gl->purchase_voucher_id=$jv->id;
                                            $gl->company_id =company()['company_id'];
                                            $gl->journal_title=$jvb->particulars;
                                            $gl->rec_date=$jv->current_date;
                                            $gl->lc_no=$jvb->lc_no;
                                            $gl->jv_id=$voucher_no;
                                            $gl->purchase_voucher_bkdn_id=$jvb->id;
                                            $gl->created_by=currentUserId();
                                            $gl->cr=$jvb->credit;
                                            $gl->{$field_name}=$jvb->table_id;
                                            $gl->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                
                Regular_purchase::where('id', $pur->id)->update(['reference_no' =>implode(',',$vouchersIds)]);
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

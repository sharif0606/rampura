<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Models\Products\LcNumber;
use App\Models\Settings\Branch;
use App\Models\Settings\Warehouse;
use App\Models\Stock\InitialStock;
use App\Models\Stock\InitialStockDetail;
use App\Models\Stock\Stock;
use App\Models\Suppliers\Supplier;
use App\Models\Vouchers\GeneralLedger;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\ResponseTrait;
use App\Models\Accounts\Child_one;
use App\Models\Accounts\Child_two;
use App\Models\Vouchers\GeneralVoucher;
use App\Models\Vouchers\InitialStockVoucher;
use App\Models\Vouchers\InitialStockVoucherBkdn;
use App\Models\Vouchers\PurchaseVoucher;
use App\Models\Vouchers\PurVoucherBkdns;
use Carbon\Carbon;

class InitialStockController extends Controller
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
        $purchases = InitialStock::with('purchase_lot','supplier','warehouse','createdBy','updatedBy')->where(company());
        if($request->nane)
            $purchases=$purchases->where('supplier_id','like','%'.$request->nane.'%');
        
        if($request->lot_no){
            $lotno=$request->lot_no;
            $purchases=$purchases->whereHas('purchase_lot', function($q) use ($lotno){
                $q->where('lot_no', $lotno);
            });
        }
        $purchases=$purchases->orderBy('id', 'DESC')->paginate(12);
        
        return view('InitialStock.index',compact('purchases','suppliers'));
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
        return view('InitialStock.create',compact('branches','suppliers','Warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

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
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{

            $lot_noa=array();// lot/ lc no wise all cost

            $pur= new InitialStock;
            $pur->supplier_id=$request->supplierName;
            $pur->voucher_no='VR-'.Carbon::now()->format('m-y').'-'. str_pad((InitialStock::whereYear('created_at', Carbon::now()->year)->count() + 1),4,"0",STR_PAD_LEFT);
            $pur->initial_stock_date = date('Y-m-d', strtotime($request->purchase_date));
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
                        $pd=new InitialStockDetail;
                        $pd->company_id=company()['company_id'];
                        $pd->initial_stock_id=$pur->id;
                        $pd->product_id=$product_id;
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
                            $stock->initial_stock_id=$pur->id;
                            $stock->initial_stock_detail_id=$pd->id;
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
                            $stock->stock_date=$pur->purchase_date;
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

                /* hit to account voucher */
                $vouchersIds=array();
                /* create due voucher */
                $voucher_no = $this->create_voucher_no();
                if(!empty($voucher_no)){
                    $jv=new InitialStockVoucher;
                    $jv->voucher_no=$voucher_no;
                    $jv->company_id =company()['company_id'];
                    $jv->supplier=$request->supplier_r_name;
                    $jv->lc_no=$request->lot_no?implode(', ',array_unique($request->lot_no)):"";
                    $jv->current_date=date('Y-m-d', strtotime($request->purchase_date));
                    $jv->pay_name=$request->supplier_r_name;
                    $jv->purpose="Intial Stock Due";
                    $jv->credit_sum=$request->tgrandtotal;
                    $jv->debit_sum=$request->tgrandtotal;
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
                            $jvb=new InitialStockVoucherBkdn;
                            $jvb->initial_stock_voucher_id=$jv->id;
                            $jvb->supplier_id=$request->supplierName;
                            $jvb->lc_no=$request->lot_no[$i]?$request->lot_no[$i]:"";

                            $jvb->company_id =company()['company_id'];
                            $jvb->particulars="Opening Stock";
                            $jvb->account_code="1150-Opening Stock";
                            $jvb->table_name="child_ones";
                            $jvb->table_id=Child_one::select('id')->where(company())->where('head_code',"1150")->first()->toArray()['id'];
                            $jvb->debit=$request->amount[$i];
                            $jvb->created_at=$jv->current_date;
                            if($jvb->save()){
                                $table_name=$jvb->table_name;
                                if($table_name=="master_accounts"){$field_name="master_account_id";}
                                else if($table_name=="sub_heads"){$field_name="sub_head_id";}
                                else if($table_name=="child_ones"){$field_name="child_one_id";}
                                else if($table_name=="child_twos"){$field_name="child_two_id";}
                                $gl=new GeneralLedger;
                                $gl->initial_stock_voucher_id=$jv->id;
                                $gl->company_id =company()['company_id'];
                                $gl->journal_title=$request->supplierName;
                                $gl->account_title=$jvb->account_code;
                                $gl->rec_date=$jv->current_date;
                                $gl->lc_no=$jvb->lc_no;
                                $gl->jv_id=$voucher_no;
                                $gl->initial_stock_voucher_bkdn_id=$jvb->id;
                                $gl->created_by=currentUserId();
                                $gl->dr=$jvb->debit;
                                $gl->{$field_name}=$jvb->table_id;
                                $gl->save();
                            }
                        }
                        
                        // credit side purchase
                        $sup_head=Child_two::select('id')->where(company())->where('head_code',"2130".$request->supplierName)->first()->toArray()['id'];
                        foreach($lot_noa as $lc=>$amount){
                            if($amount > 0){
                                $jvb=new InitialStockVoucherBkdn;
                                $jvb->initial_stock_voucher_id=$jv->id;
                                $jvb->supplier_id=$request->supplierName;
                                $jvb->lc_no=$lc;
                                $jvb->company_id =company()['company_id'];
                                $jvb->particulars="OS-Purchase due";
                                $jvb->account_code="2130".$request->supplierName.'-'.$request->supplier_r_name; //2=>head name 3=> head code
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
                                    $gl->initial_stock_voucher_id=$jv->id;
                                    $gl->company_id =company()['company_id'];
                                    $gl->journal_title=$request->supplierName;
                                    $gl->account_title=$jvb->account_code;
                                    $gl->rec_date=$jv->current_date;
                                    $gl->lc_no=$jvb->lc_no;
                                    $gl->jv_id=$voucher_no;
                                    $gl->initial_stock_voucher_bkdn_id=$jvb->id;
                                    $gl->created_by=currentUserId();
                                    $gl->cr=$jvb->credit;
                                    $gl->{$field_name}=$jvb->table_id;
                                    $gl->save();
                                }
                            }
                        }
                    }
                }
                InitialStock::where('id', $pur->id)->update(['reference_no' =>implode(',',$vouchersIds)]);

                if($request->lot_no){
                    foreach($request->lot_no as $i=>$lclotno){
                        $oldlc=LcNumber::where('product_id',$request->product_id[$i])->where('lot_no',$lclotno)->first();
                        if(!$oldlc){
                            $newlc=new LcNumber;
                            $newlc->product_id=$request->product_id[$i];
                            $newlc->company_id= company()['company_id'];
                            $newlc->lot_name=$lclotno;
                            $newlc->lot_no=$lclotno;
                            $newlc->billable=1;
                            $newlc->save();
                        }
                    }
                }
                DB::commit();
                
                return redirect()->route(currentUser().'.initialStock.index')->with($this->resMessageHtml(true,null,'Successfully created'));
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
     * @param  \App\Models\Stock\InitialStock  $initialStock
     * @return \Illuminate\Http\Response
     */
    public function show(InitialStock $initialStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stock\InitialStock  $initialStock
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branches = Branch::where(company())->get();
        $suppliers = Supplier::where(company())->get();
        $Warehouses = Warehouse::where(company())->get();

        $purchase = InitialStock::findOrFail(encryptor('decrypt',$id));
        $purchaseDetails = InitialStockDetail::where('initial_stock_id',$purchase->id)->get();
        
        return view('InitialStock.edit',compact('branches','suppliers','Warehouses','purchase','purchaseDetails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stock\InitialStock  $initialStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $lot_noa=array();// lot/ lc no wise all cost

            $pur= InitialStock::findOrFail(encryptor('decrypt',$id));
            $pur->supplier_id=$request->supplierName;
            $pur->voucher_no='VR-'.Carbon::now()->format('m-y').'-'. str_pad((InitialStock::whereYear('created_at', Carbon::now()->year)->count() + 1),4,"0",STR_PAD_LEFT);
            $pur->initial_stock_date = date('Y-m-d', strtotime($request->purchase_date));
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
                    InitialStockDetail::where('initial_stock_id',$pur->id)->delete();
                    Stock::where('initial_stock_id',$pur->id)->delete();
                    foreach($request->product_id as $i=>$product_id){
                        $pd=new InitialStockDetail;
                        $pd->company_id=company()['company_id'];
                        $pd->initial_stock_id=$pur->id;
                        $pd->product_id=$product_id;
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
                            $stock->initial_stock_id=$pur->id;
                            $stock->product_id=$product_id;
                            $stock->company_id=company()['company_id'];
                            $stock->branch_id=$request->branch_id;
                            $stock->warehouse_id=$request->warehouse_id;
                            $stock->lot_no=$pd->lot_no;
                            $stock->brand=$pd->brand;
                            $stock->quantity=$pd->actual_quantity;
                            $stock->batch_id=$request->batch_id[$i] ?? rand(111,999).uniqid().$product_id;
                            $stock->unit_price=$pd->rate_kg;
                            $stock->quantity_bag=$pd->quantity_bag;
                            $stock->total_amount=$pd->amount;
                            $stock->stock_date=$pur->purchase_date;
                            $stock->initial_stock_detail_id=$pd->id;
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

                /* hit to account voucher */
                $purrefArr=explode(',',$pur->reference_no);
                $vnon=InitialStockVoucher::whereIn('id',$purrefArr)->pluck('voucher_no');
                GeneralVoucher::whereIn('voucher_no',$vnon)->forceDelete();
                InitialStockVoucher::whereIn('id',$purrefArr)->delete();
                InitialStockVoucherBkdn::whereIn('initial_stock_voucher_id',$purrefArr)->delete();
                GeneralLedger::whereIn('initial_stock_voucher_id',$purrefArr)->delete();

                $vouchersIds=array();
                /* create due voucher */
                $voucher_no = $this->create_voucher_no();
                if(!empty($voucher_no)){
                    $jv=new InitialStockVoucher;
                    $jv->voucher_no=$voucher_no;
                    $jv->company_id =company()['company_id'];
                    $jv->supplier=$request->supplier_r_name;
                    $jv->lc_no=$request->lot_no?implode(', ',array_unique($request->lot_no)):"";
                    $jv->current_date=date('Y-m-d', strtotime($request->purchase_date));
                    $jv->pay_name=$request->supplier_r_name;
                    $jv->purpose="Intial Stock Due";
                    $jv->credit_sum=$request->tgrandtotal;
                    $jv->debit_sum=$request->tgrandtotal;
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
                            $jvb=new InitialStockVoucherBkdn;
                            $jvb->initial_stock_voucher_id=$jv->id;
                            $jvb->supplier_id=$request->supplierName;
                            $jvb->lc_no=$request->lot_no[$i]?$request->lot_no[$i]:"";

                            $jvb->company_id =company()['company_id'];
                            $jvb->particulars="Opening Stock";
                            $jvb->account_code="1150-Opening Stock";
                            $jvb->table_name="child_ones";
                            $jvb->table_id=Child_one::select('id')->where(company())->where('head_code',"1150")->first()->toArray()['id'];
                            $jvb->debit=$request->amount[$i];
                            $jvb->created_at=$jv->current_date;
                            if($jvb->save()){
                                $table_name=$jvb->table_name;
                                if($table_name=="master_accounts"){$field_name="master_account_id";}
                                else if($table_name=="sub_heads"){$field_name="sub_head_id";}
                                else if($table_name=="child_ones"){$field_name="child_one_id";}
                                else if($table_name=="child_twos"){$field_name="child_two_id";}
                                $gl=new GeneralLedger;
                                $gl->initial_stock_voucher_id=$jv->id;
                                $gl->company_id =company()['company_id'];
                                $gl->journal_title=$request->supplierName;
                                $gl->account_title=$jvb->account_code;
                                $gl->rec_date=$jv->current_date;
                                $gl->lc_no=$jvb->lc_no;
                                $gl->jv_id=$voucher_no;
                                $gl->initial_stock_voucher_bkdn_id=$jvb->id;
                                $gl->created_by=currentUserId();
                                $gl->dr=$jvb->debit;
                                $gl->{$field_name}=$jvb->table_id;
                                $gl->save();
                            }
                        }
                        
                        // credit side purchase
                        $sup_head=Child_two::select('id')->where(company())->where('head_code',"2130".$request->supplierName)->first()->toArray()['id'];
                        foreach($lot_noa as $lc=>$amount){
                            if($amount > 0){
                                $jvb=new InitialStockVoucherBkdn;
                                $jvb->initial_stock_voucher_id=$jv->id;
                                $jvb->supplier_id=$request->supplierName;
                                $jvb->lc_no=$lc;
                                $jvb->company_id =company()['company_id'];
                                $jvb->particulars="OS-Purchase due";
                                $jvb->account_code="2130".$request->supplierName.'-'.$request->supplier_r_name; //2=>head name 3=> head code
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
                                    $gl->initial_stock_voucher_id=$jv->id;
                                    $gl->company_id =company()['company_id'];
                                    $gl->journal_title=$request->supplierName;
                                    $gl->account_title=$jvb->account_code;
                                    $gl->rec_date=$jv->current_date;
                                    $gl->lc_no=$jvb->lc_no;
                                    $gl->jv_id=$voucher_no;
                                    $gl->initial_stock_voucher_bkdn_id=$jvb->id;
                                    $gl->created_by=currentUserId();
                                    $gl->cr=$jvb->credit;
                                    $gl->{$field_name}=$jvb->table_id;
                                    $gl->save();
                                }
                            }
                        }
                    }
                }
                InitialStock::where('id', $pur->id)->update(['reference_no' =>implode(',',$vouchersIds)]);

                if($request->lot_no){
                    foreach($request->lot_no as $i=>$lclotno){
                        $oldlc=LcNumber::where('product_id',$request->product_id[$i])->where('lot_no',$lclotno)->first();
                        if(!$oldlc){
                            $newlc=new LcNumber;
                            $newlc->product_id=$request->product_id[$i];
                            $newlc->company_id= company()['company_id'];
                            $newlc->lot_name=$lclotno;
                            $newlc->lot_no=$lclotno;
                            $newlc->billable=1;
                            $newlc->save();
                        }
                    }
                }
                DB::commit();
                
                return redirect()->route(currentUser().'.initialStock.index')->with($this->resMessageHtml(true,null,'Successfully Updated'));
            }else
                return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }catch(Exception $e){
            DB::rollback();
             dd($e);
            return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stock\InitialStock  $initialStock
     * @return \Illuminate\Http\Response
     */
    public function destroy(InitialStock $initialStock)
    {
        //
    }
}

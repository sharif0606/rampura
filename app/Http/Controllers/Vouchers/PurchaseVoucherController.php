<?php

namespace App\Http\Controllers\Vouchers;

use App\Http\Controllers\Controller;

use App\Models\Settings\Company;
use App\Models\Vouchers\PurchaseVoucher;
use App\Models\Vouchers\PurVoucherBkdns;
use App\Models\Vouchers\GeneralVoucher;
use App\Models\Vouchers\GeneralLedger;
use App\Models\Expenses\ExpenseOfPurchase;
use App\Models\Accounts\Child_one;
use App\Models\Accounts\Child_two;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Models\Customers\Customer;
use DB;
use Session;
use Exception;

class PurchaseVoucherController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data= PurchaseVoucher::where(company())->orderBy('id', 'DESC')->paginate(15);
        return view('voucher.purchaseVoucher.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
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
		if($request->exp_id){
			$expense=ExpenseOfPurchase::whereIn('id',$request->exp_id)->get();
			return view('voucher.purchaseVoucher.autocreate',compact('paymethod','expense'));
		}else{
			return view('voucher.purchaseVoucher.create',compact('paymethod'));
		}
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
    public function store(Request $request){
        try {
            DB::beginTransaction();
            $voucher_no = $this->create_voucher_no();
            if(!empty($voucher_no)){
                $jv=new PurchaseVoucher;
                $jv->voucher_no=$voucher_no;
                $jv->company_id =company()['company_id'];
                $jv->supplier=$request->supplier_name?implode(', ',array_unique($request->supplier_name)):"";
                $jv->lc_no=$request->lc_no?implode(', ',array_unique($request->lc_no)):"";
                $jv->current_date=$request->current_date;
                $jv->pay_name="";
                $jv->purpose="";
                $jv->credit_sum=$request->debit_sum;
                $jv->debit_sum=$request->debit_sum;
                $jv->cheque_no=$request->cheque_no;
                $jv->bank=$request->bank;
                $jv->cheque_dt=$request->cheque_dt;
                $jv->created_by=currentUserId();
				if($request->has('slip')){
					$imageName= rand(111,999).time().'.'.$request->slip->extension();
					$request->slip->move(public_path('uploads/slip'), $imageName);
					$jv->slip=$imageName;
				}
                if($jv->save()){
                    $account_codes=$request->account_code;
                    $table_id=$request->table_id;
                    $credit=$request->credit;
                    $debit=$request->debit;
                    
					if(sizeof($account_codes)>0){
                        foreach($account_codes as $i=>$acccode){
                            $jvb=new PurVoucherBkdns;
                            $jvb->purchase_voucher_id=$jv->id;
                            $jvb->supplier_id=!empty($request->supplier_id[$i])?$request->supplier_id[$i]:0;
                            $jvb->lc_no=!empty($request->lc_no[$i])?$request->lc_no[$i]:0;
                            $jvb->company_id =company()['company_id'];
                            $jvb->particulars=!empty($request->remarks[$i])?$request->remarks[$i]:"";
                            $jvb->account_code=!empty($acccode)?$acccode:"";
                            $jvb->table_name=!empty($request->table_name[$i])?$request->table_name[$i]:"";
                            $jvb->table_id=!empty($request->table_id[$i])?$request->table_id[$i]:"";
                            $jvb->debit=!empty($request->debit[$i])?$request->debit[$i]:0;
                            $jvb->created_at=$request->current_date." 00:00:00";
                            if($jvb->save()){
                                $table_name=$request->table_name[$i];
                                if($table_name=="master_accounts"){$field_name="master_account_id";}
    							else if($table_name=="sub_heads"){$field_name="sub_head_id";}
    							else if($table_name=="child_ones"){$field_name="child_one_id";}
    							else if($table_name=="child_twos"){$field_name="child_two_id";}
    							$gl=new GeneralLedger;
                                $gl->purchase_voucher_id=$jv->id;
                                $gl->company_id =company()['company_id'];
                                $gl->journal_title=!empty($acccode)?$acccode:"";
                                $gl->rec_date=$request->current_date;
                                $gl->jv_id=$voucher_no;
                                $gl->purchase_voucher_bkdn_id=$jvb->id;
                                $gl->created_by=currentUserId();
                                $gl->dr=!empty($request->debit[$i])?$request->debit[$i]:0;
                                $gl->{$field_name}=!empty($request->table_id[$i])?$request->table_id[$i]:"";
                                $gl->save();
                            }
                        }
                    }

					if($credit){
                        $credit=explode('~',$credit);
                        $jvb=new PurVoucherBkdns;
                        $jvb->purchase_voucher_id=$jv->id;
                        $jvb->supplier_id="";
                        $jvb->lc_no=$request->lc_no?implode(',',$request->lc_no):"";
                        $jvb->company_id =company()['company_id'];
                        $jvb->particulars="Received from";
                        $jvb->account_code=$credit[2];
                        $jvb->table_name=$credit[0];
                        $jvb->table_id=$credit[1];
                        $jvb->credit=$request->debit_sum;
                        if($jvb->save()){
                            $table_name=$credit[0];
                            if($table_name=="master_accounts"){$field_name="master_account_id";}
							else if($table_name=="sub_heads"){$field_name="sub_head_id";}
							else if($table_name=="child_ones"){$field_name="child_one_id";}
							else if($table_name=="child_twos"){$field_name="child_two_id";}
							$gl=new GeneralLedger;
                            $gl->purchase_voucher_id=$jv->id;
                            $gl->company_id =company()['company_id'];
                            $gl->journal_title=$credit[2];
                            $gl->rec_date=$request->current_date;
                            $gl->jv_id=$voucher_no;
                            $gl->purchase_voucher_bkdn_id=$jvb->id;
                            $gl->created_by=currentUserId();
                            $gl->cr=$request->debit_sum;
                            $gl->{$field_name}=$credit[1];
                            $gl->save();
                        }
                    }
                }
                DB::commit();
				
				if($request->expense_id)
					ExpenseOfPurchase::whereIn('id', $request->expense_id)->update(['invoice_id' => $jv->id,'status' => 1]);

				return redirect()->route(currentUser().'.purchase_voucher.index')->with($this->resMessageHtml(true,null,'Successfully created'));
			}else{
				return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
			}
		}catch (Exception $e) {
			// dd($e);
			DB::rollBack();
			return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CreditVoucher  $creditVoucher
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerVoucher $customerVoucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CreditVoucher  $creditVoucher
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data=PurchaseVoucher::findOrFail(encryptor('decrypt',$id));
		$purvoucherbkdn=PurVoucherBkdns::where('purchase_voucher_id',encryptor('decrypt',$id))->get();
		return view('voucher.purchaseVoucher.edit',compact('data','purvoucherbkdn'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CreditVoucher  $creditVoucher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cv= PurchaseVoucher::findOrFail(encryptor('decrypt',$id));
		$cv->current_date = $request->current_date;
		$cv->pay_name = $request->pay_name;
		$cv->purpose = $request->purpose;
		$cv->cheque_no = $request->cheque_no;
		$cv->cheque_dt = $request->cheque_dt;
		$cv->bank = $request->bank;
		if($request->has('slip')){
			$imageName= rand(111,999).time().'.'.$request->slip->extension();
			$request->slip->move(public_path('uploads/slip'), $imageName);
			$cv->slip=$imageName;
		}
        $cv->save();
        return redirect()->route(currentUser().'.purchase_voucher.index')->with($this->resMessageHtml(true,null,'Successfully Updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CreditVoucher  $creditVoucher
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerVoucher $customerVoucher)
    {
        //
    }
}

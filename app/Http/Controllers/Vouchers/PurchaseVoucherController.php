<?php

namespace App\Http\Controllers\Vouchers;

use App\Http\Controllers\Controller;

use App\Models\Settings\Company;
use App\Models\Vouchers\PurchaseVoucher;
use App\Models\Vouchers\PurchaseVoucherBkdns;
use App\Models\Vouchers\GeneralVoucher;
use App\Models\Vouchers\GeneralLedger;
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
        $data= PurchaseVoucher::where(company())->paginate(10);
        return view('voucher.customerVoucher.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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

        return view('voucher.customerVoucher.create',compact('paymethod'));
    }
    public function get_head(Request $request){
        
        $row='';
		$needle = $request->code;
		$needle = strtolower($needle);
        $company=company()['company_id'];
		
		$master_headArr=array();
		$fcoaArr=array();
		$fcoa_bkdnArr=array();
		$sub_fcoa_bkdnArr=array();
		
		$master = DB::select("SELECT * FROM master_accounts where company_id={$company} ORDER BY id ASC");
		if ($master){
			foreach ($master as $master_row){
			$master_headArr["{$master_row->id}"]=array(
													"id"=>$master_row->id,
													"parent"=>"",
													"head"=>$master_row->head_name,
													"coa_code"=>$master_row->head_code,
													"lavel"=>"master_accounts"
												);
				
					
			$sub1Arr = DB::select("SELECT * FROM sub_heads WHERE master_head_id = {$master_row->id} and company_id={$company} ORDER BY id ASC");
			if ($sub1Arr){
				foreach ($sub1Arr as $sub1_row){
				        $fcoaArr["{$sub1_row->id}"]=array(
														"id"=>$sub1_row->id,
														"parent"=>$master_row->id,
														"head"=>$sub1_row->head_name,
														"coa_code"=>$sub1_row->head_code,
														"lavel"=>"sub_heads"
													);
				$sub2Arr = DB::select("SELECT * FROM child_ones WHERE sub_head_id = {$sub1_row->id} and company_id={$company} ORDER BY id ASC");
				if ($sub2Arr){
					foreach ($sub2Arr as $sub2_row){
					    if($sub1_row->id==1 || $sub1_row->id==2){/*nothing will get*/}else{
					        $fcoa_bkdnArr["{$sub2_row->id}"]=array(
															"id"=>$sub2_row->id,
															"parent"=>$sub1_row->id,
															"head"=>$sub2_row->head_name,
															"coa_code"=>$sub2_row->head_code,
															"lavel"=>"child_ones"
															);
					        $sub3Arr = DB::select("SELECT * FROM child_twos WHERE child_one_id = {$sub2_row->id} and company_id={$company} ORDER BY id ASC");
        					if ($sub3Arr){
        						foreach ($sub3Arr as $sub3_row){
        						  $sub_fcoa_bkdnArr["{$sub3_row->id}"]=array(
        																"id"=>$sub3_row->id,
        																"parent"=>$sub2_row->id,
        																"head"=>$sub3_row->head_name,
        																"coa_code"=>$sub3_row->head_code,
        																"lavel"=>"child_twos"
        															);
        						}
        					}
					    }
				    }
				}
				}
			}
			}
		}
		
		$masterSingleArr=array();
		$fcoaSingleArr=array();
		$fcoa_bkdnSingleArr=array();
		$sub_fcoa_bkdnSingleArr=array();
		
		if(sizeof($master_headArr)>0){
			foreach($master_headArr as $masterheadArr){
				$masterhead_coa_id 	= $masterheadArr["id"];
				$masterhead_coa 	= $masterheadArr["head"];
				$coa_code			= $masterheadArr["coa_code"];
				$lavel				= $masterheadArr["lavel"];
				$result=$this->search_array_key($fcoaArr,'parent',$masterhead_coa_id);
				if(sizeof($result)<=0){
					$masterSingleArr[]=$coa_code."-".$masterhead_coa."-".$lavel."-".$masterhead_coa_id;
				}	
			}
		}
		
		if(sizeof($fcoaArr)>0){
			foreach($fcoaArr as $fcoa_Arr){
				$fcoa_id 	= $fcoa_Arr["id"];
				$fcoa_head 	= $fcoa_Arr["head"];
				$coa_code	= $fcoa_Arr["coa_code"];
				$lavel		= $fcoa_Arr["lavel"];
				$result=$this->search_array_key($fcoa_bkdnArr,'parent',$fcoa_id);
				if(sizeof($result)<=0){
					$fcoaSingleArr[]=$coa_code."-".$fcoa_head."-".$lavel."-".$fcoa_id;
				}	
			}
		}
		
		if(sizeof($fcoa_bkdnArr)>0){
			foreach($fcoa_bkdnArr as $fcoabkdnArr){
				$fcoa_bkdn_id 	= $fcoabkdnArr["id"];
				$fcoa_bkdn 		= $fcoabkdnArr["head"];
				$coa_code		= $fcoabkdnArr["coa_code"];
				$lavel			= $fcoabkdnArr["lavel"];
				$result=$this->search_array_key($sub_fcoa_bkdnArr,'parent',$fcoa_bkdn_id);
				if(sizeof($result)<=0){
					$fcoa_bkdnSingleArr[]=$coa_code."-".$fcoa_bkdn."-".$lavel."-".$fcoa_bkdn_id;
				}	
			}
		}
		
		if(sizeof($sub_fcoa_bkdnArr)>0){
			foreach($sub_fcoa_bkdnArr as $subfcoabkdnArr){
				$sub_fcoa_bkdn_id 	= $subfcoabkdnArr["id"];
				$sub_fcoa_bkdn 		= $subfcoabkdnArr["head"];
				$coa_code			= $subfcoabkdnArr["coa_code"];
				$lavel				= $subfcoabkdnArr["lavel"];
				
				/*$result=$this->search_array_key($coa_subArr,'parent',$sub_fcoa_bkdn_id);
				if(sizeof($result)<=0){*/
					$sub_fcoa_bkdnSingleArr[]=$coa_code."-".$sub_fcoa_bkdn."-".$lavel."-".$sub_fcoa_bkdn_id;
				//}	
			}
		}
		
		$FinalResult = array_merge($masterSingleArr, $fcoaSingleArr);
		$FinalResult = array_merge($FinalResult, $fcoa_bkdnSingleArr);
		$FinalResult = array_merge($FinalResult, $sub_fcoa_bkdnSingleArr);
		
		$FinalResultBuild=array();
		$FinalResultTmp=array();
		$FinalResultTmp = $FinalResult;
		foreach($FinalResultTmp as $FinalResultStr){
			$coacode='';
			$coaname='';
			$FinalResultARR=explode("-",$FinalResultStr);
			$coacode=$FinalResultARR[0];
			$coaname=$FinalResultARR[1];
			$FinalResultBuild[]= $coacode."-".$coaname;	
		}
		
		$StrToLowerFRES=array();
		foreach($FinalResult as $StrToLower){
			$StrToLowerFRES[]= strtolower($StrToLower);	
		}
		
		$StrToLowerRes=array();
		foreach($FinalResultBuild as $StrToLower){
			$StrToLowerRes[]= strtolower($StrToLower);	
		}
		
		$ret=array();
		$ret = array_values(array_filter($StrToLowerRes, function($var) use ($needle){
			return strpos($var, $needle) !== false;
		}));
		
		
		$res=array();
		foreach($ret as $retstr){
			$res[] = array_values(array_filter($StrToLowerFRES, function($var) use ($retstr){
				return strpos($var, $retstr) !== false;
			}));
		}
		
		$resss=array();
		$resss = $this->array_flatten($res);
		
		if(sizeof($resss)>0){
			foreach($resss as $ret_value){
				$ret_valueArr=explode("-",$ret_value);
				$codenumber = $ret_valueArr[0];
				$head 		= ucwords($ret_valueArr[1]);
				$tableName 	= $ret_valueArr[2];
				$tableId 	= $ret_valueArr[3];
				$display_value = $codenumber."-".$head;
				if(!empty($tableName) && !empty($tableId)){
					$list_array[] = array('table_name'=>$tableName,'table_id'=>$tableId,'display_value'=>$display_value);
				}
			}
		}
		
		print json_encode($list_array);
    }
    
    public function search_array_key($array, $key, $value){
		$results = array();
		if (is_array($array)){	
			if (isset($array[$key]) && $array[$key] == $value){
				$results[] = $array;
			}
			foreach ($array as $subarray){
				$results = array_merge($results, $this->search_array_key($subarray, $key, $value));
			}
		}		
		return $results;
	}
	
	public function array_flatten($array){
		$return = array();
		array_walk_recursive($array, function($x) use (&$return) { $return[] = $x; });
		return $return;
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
                $jv=new CustomerVoucher;
                $jv->voucher_no=$voucher_no;
                $jv->company_id =company()['company_id'];
                $jv->customer_id=$request->customer_id;
                $jv->lc_no=$request->lc_no;
                $jv->current_date=$request->current_date;
                $jv->pay_name=$request->pay_name;
                $jv->purpose=$request->purpose;
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
                    
                    if($credit){
                        $credit=explode('~',$credit);
                        $jvb=new CusVoucherBkdns;
                        $jvb->customer_voucher_id=$jv->id;
                        $jvb->customer_id=$request->customer_id;
                        $jvb->lc_no=$request->lc_no;
                        $jvb->company_id =company()['company_id'];
                        $jvb->particulars="Received from";
                        $jvb->account_code=$credit[2];
                        $jvb->table_name=$credit[0];
                        $jvb->table_id=$credit[1];
                        $jvb->debit=$request->debit_sum;
                        if($jvb->save()){
                            $table_name=$credit[0];
                            if($table_name=="master_accounts"){$field_name="master_account_id";}
							else if($table_name=="sub_heads"){$field_name="sub_head_id";}
							else if($table_name=="child_ones"){$field_name="child_one_id";}
							else if($table_name=="child_twos"){$field_name="child_two_id";}
							$gl=new GeneralLedger;
                            $gl->customer_voucher_id=$jv->id;
                            $gl->company_id =company()['company_id'];
                            $gl->journal_title=$credit[2];
                            $gl->rec_date=$request->current_date;
                            $gl->jv_id=$voucher_no;
                            $gl->customer_voucher_bkdn_id=$jvb->id;
                            $gl->created_by=currentUserId();
                            $gl->dr=$request->debit_sum;
                            $gl->{$field_name}=$credit[1];
                            $gl->save();
                        }
                    }
					if(sizeof($account_codes)>0){
                        foreach($account_codes as $i=>$acccode){
                            $jvb=new CusVoucherBkdns;
                            $jvb->customer_voucher_id=$jv->id;
                            $jvb->customer_id=$request->customer_id;
                            $jvb->lc_no=$request->lc_no;
                            $jvb->company_id =company()['company_id'];
                            $jvb->particulars=!empty($request->remarks[$i])?$request->remarks[$i]:"";
                            $jvb->account_code=!empty($acccode)?$acccode:"";
                            $jvb->table_name=!empty($request->table_name[$i])?$request->table_name[$i]:"";
                            $jvb->table_id=!empty($request->table_id[$i])?$request->table_id[$i]:"";
                            $jvb->credit=!empty($request->debit[$i])?$request->debit[$i]:0;
                            if($jvb->save()){
                                $table_name=$request->table_name[$i];
                                if($table_name=="master_accounts"){$field_name="master_account_id";}
    							else if($table_name=="sub_heads"){$field_name="sub_head_id";}
    							else if($table_name=="child_ones"){$field_name="child_one_id";}
    							else if($table_name=="child_twos"){$field_name="child_two_id";}
    							$gl=new GeneralLedger;
                                $gl->customer_voucher_id=$jv->id;
                                $gl->company_id =company()['company_id'];
                                $gl->journal_title=!empty($acccode)?$acccode:"";
                                $gl->rec_date=$request->current_date;
                                $gl->jv_id=$voucher_no;
                                $gl->customer_voucher_bkdn_id=$jvb->id;
                                $gl->created_by=currentUserId();
                                $gl->cr=!empty($request->debit[$i])?$request->debit[$i]:0;
                                $gl->{$field_name}=!empty($request->table_id[$i])?$request->table_id[$i]:"";
                                $gl->save();
                            }
                        }
                    }
                }
                DB::commit();
				return redirect()->route(currentUser().'.cusVoucher.index')->with($this->resMessageHtml(true,null,'Successfully created'));
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
        $customerVoucher=CustomerVoucher::findOrFail(encryptor('decrypt',$id));
		$cusvoucherbkdn=CusVoucherBkdns::where('customer_voucher_id',$id)->get();
		return view('voucher.customerVoucher.edit',compact('customerVoucher','cusvoucherbkdn'));
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
        $cv= CustomerVoucher::findOrFail(encryptor('decrypt',$id));
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
        return redirect()->route(currentUser().'.cusVoucher.index')->with($this->resMessageHtml(true,null,'Successfully Updated'));
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

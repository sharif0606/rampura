<?php

namespace App\Http\Controllers\Accounts\Report;

use App\Http\Controllers\Controller;

use App\Models\Vouchers\PurVoucherBkdns;
use App\Models\Vouchers\SalVoucherBkdns;

use App\Models\Vouchers\GeneralLedger;

use Illuminate\Http\Request;

use DB;

class HeadReportController extends Controller{
    public function index(Request $r){
        $headlists=Generalledger::groupBy(['master_account_id','sub_head_id','child_one_id','child_two_id'])->where(company())->get();
        
        $startDate=$endDate=date("Y-m-d");
        
        if($r->current_date){
            $current_date=explode(' / ',$r->current_date);
            $startDate=date('Y-m-d',strtotime($current_date[0]));
            $endDate=date('Y-m-d',strtotime($current_date[1]));
        }
        $opening_bal=$head_id=$table_id_name=$head_code=0;
        $accData=$accOldData=array();
        if($r->head_id){
            $head_id=explode('~',$r->head_id);
            $head_code=$head_id[3];
            $opening_bal=$head_id[2];
            $table_id_name=$head_id[1];
            $head_id=$head_id[0];
        }
        if($head_id){
            $accOldData=Generalledger::where('rec_date', '<',$startDate)->where($table_id_name,$head_id)->orderBy('rec_date')->where(company())->get();
            $checkpurchase=Generalledger::where($table_id_name,$head_id)->whereNotNull('purchase_voucher_bkdn_id')->where(company())->count();
            if($checkpurchase){
                /* for supplier */
                $suppliercode=PurVoucherBkdns::whereBetween('created_at', [$startDate, $endDate])->where('supplier_id',substr($head_code,4))->pluck('id');
                $accData=Generalledger::whereBetween('rec_date', [$startDate, $endDate])->whereIn('purchase_voucher_bkdn_id',$suppliercode)->orderBy('rec_date')->where(company())->get();
            }else{
                /* for customer */
                $checksales=Generalledger::where($table_id_name,$head_id)->whereNotNull('sales_voucher_bkdn_id')->where(company())->count();
                if($checksales){
                    $customercode=SalVoucherBkdns::whereBetween('created_at', [$startDate, $endDate])->where('customer_id',substr($head_code,4))->pluck('id');
                    $accData=Generalledger::whereBetween('rec_date', [$startDate, $endDate])->whereIn('sales_voucher_bkdn_id',$customercode)->orderBy('rec_date')->where(company())->get();
                }else{
                    /* for other */
                    $accData=Generalledger::whereBetween('rec_date', [$startDate, $endDate])->where($table_id_name,$head_id)->orderBy('rec_date')->where(company())->get();
                }
            }
        }
        
        $head_id=$head_id.$table_id_name;
        return view("accounts.report.headreport",compact("head_id","headlists","accOldData","accData","opening_bal","startDate","endDate"));
    }
}
<?php

namespace App\Http\Controllers\Accounts\Report;

use App\Http\Controllers\Controller;

use App\Models\Accounts\Chieldheadtwo;
use App\Models\Accounts\Chieldheadone;
use App\Models\Accounts\Subhead;
use App\Models\Accounts\Masterhead;

use App\Models\Vouchers\GeneralLedger;

use Illuminate\Http\Request;

use DB;

class HeadReportController extends Controller{
    public function index(Request $r){
        $headlists=Generalledger::groupBy(['master_account_id','sub_head_id','child_one_id','child_two_id'])->get();
        
        $startDate=$endDate=date("Y-m-d");
        
        if($r->current_date){
            $current_date=explode(' / ',$r->current_date);
            $startDate=date('Y-m-d',strtotime($current_date[0]));
            $endDate=date('Y-m-d',strtotime($current_date[1]));
        }
        $opening_bal=$head_id=$table_id_name=0;
        $accData=$accOldData=array();
        if($r->head_id){
            $head_id=explode('-',$r->head_id);
            $opening_bal=$head_id[2];
            $table_id_name=$head_id[1];
            $head_id=$head_id[0];
        }
        if($head_id){
            $accOldData=Generalledger::where('rec_date', '<',$startDate)->where($table_id_name,$head_id)->orderBy('rec_date')->get();
            $accData=Generalledger::whereBetween('rec_date', [$startDate, $endDate])->where($table_id_name,$head_id)->orderBy('rec_date')->get();
        }
        
        $head_id=$head_id.$table_id_name;
        return view("accounts.report.headreport",compact("head_id","headlists","accOldData","accData","opening_bal","startDate","endDate"));
    }
}
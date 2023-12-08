<?php
namespace App\Http\Controllers\Account\Report;
use App\Http\Controllers\Controller;

use App\Models\Chieldheadtwo;
use App\Models\Chieldheadone;
use App\Models\Subhead;
use App\Models\Masterhead;

use App\Models\Generalledger;

use Illuminate\Http\Request;

use DB;

class ProfitLossController extends Controller{
    
    public function index(Request $r){
        
        $cm=date('n');
        $cy=date('Y');
        if($r->current_month)
            $cm=$r->current_month;
        if($r->current_year)
            $cy=$r->current_year;
        $incDataYear=$this->income($cy,$cm,"qy");
        $incDataMonth=$this->income($cy,$cm,"qm");
        $expDataYear=$this->expence($cy,$cm,"qy");
        $expDataMonth=$this->expence($cy,$cm,"qm");
		$data['expDataMonth']=array();
		$data['incDataMonth']=array();
		// Expenses
		foreach($expDataMonth as $edm){
			$data['expDataMonth'][explode('-',$edm->head_name)[0]]=$edm->cost;
		}

		// Income
		foreach($incDataMonth as $idm){
			$data['incDataMonth'][explode('-',$idm->head_name)[0]]=$idm->income;
		}
		
		return view("accounts.report.profitloss_report",compact("incDataYear","expDataYear","data","cy","cm"));
    }
    
    function income($y,$m,$type){
        if($m>6){
    		$qy=" date(generalledgers.v_date) BETWEEN '".$y."-07-01' and '".$y."-".$m."-31"."' ";
    		$qm=" date(generalledgers.v_date) BETWEEN '".$y."-".$m."-01' and '".$y."-".$m."-31"."' ";}
    	else{
    		$qy=" date(generalledgers.v_date) BETWEEN '".($y-1)."-07-01' and '".$y."-".$m."-31"."' ";
    		$qm=" date(generalledgers.v_date) BETWEEN '".$y."-".$m."-01' and '".$y."-".$m."-31' ";
    	}
    	
        $income=Subhead::whereIn('masterhead_id',[4])->pluck('id')->toArray();
        $incomechildone=Chieldheadone::whereIn('sub_head_id',$income)->pluck('id')->toArray();
        $incomechildtwo=Chieldheadtwo::whereIn('child_one_id',$incomechildone)->pluck('id')->toArray();
        
        $incwhere="(";
        if($income){
            $incomeimp=implode(',',$income);
            $incwhere.="sub_head_id in ($incomeimp)";
        }
        if($incomechildone){
            $incomechildoneimp=implode(',',$incomechildone);
            $incwhere.=" or child_one_id in ($incomechildoneimp)";
        }
        if($incomechildtwo){
            $incomechildtwoimp=implode(',',$incomechildtwo);
            $incwhere.=" or child_two_id in ($incomechildtwoimp)";
        }
        $incwhere.=")";
        
        $dataincome=DB::select("select 
                            (CASE 
                                WHEN generalledgers.sub_head_id THEN (select concat(head_code,'-',head_name) from subheads where subheads.id=generalledgers.sub_head_id)
                                WHEN generalledgers.child_one_id THEN (select concat(head_code,'-',head_name) from child_ones where child_ones.id=generalledgers.child_one_id)
                                WHEN generalledgers.child_two_id THEN (select concat(head_code,'-',head_name) from child_twos where child_twos.id=generalledgers.child_two_id)
                                ELSE 'None-0'
                            END) AS head_name,
        (sum(generalledgers.cr) - sum(generalledgers.dr)) as income from generalledgers where  $incwhere and ${$type} group by sub_head_id,child_one_id,child_two_id");
        return $dataincome;
    
    }
    
    function expence($y,$m,$type){
        if($m>6){
    		$qy=" date(generalledgers.v_date) BETWEEN '".$y."-07-01' and '".$y."-".$m."-31"."' ";
    		$qm=" date(generalledgers.v_date) BETWEEN '".$y."-".$m."-01' and '".$y."-".$m."-31"."' ";}
    	else{
    		$qy=" date(generalledgers.v_date) BETWEEN '".($y-1)."-07-01' and '".$y."-".$m."-31"."' ";
    		$qm=" date(generalledgers.v_date) BETWEEN '".$y."-".$m."-01' and '".$y."-".$m."-31' ";
    	}
    	
        $expence=Subhead::whereIn('masterhead_id',[5])->pluck('id')->toArray();
        $expencechildone=Chieldheadone::whereIn('sub_head_id',$expence)->pluck('id')->toArray();
        $expencechildtwo=Chieldheadtwo::whereIn('child_one_id',$expencechildone)->pluck('id')->toArray();
        
        $expwhere="(";
        if($expence){
            $expenceimp=implode(',',$expence);
            $expwhere.="sub_head_id in ($expenceimp)";
        }
        if($expencechildone){
            $expencechildoneimp=implode(',',$expencechildone);
            $expwhere.=" or child_one_id in ($expencechildoneimp)";
        }
        if($expencechildtwo){
            $expencechildtwoimp=implode(',',$expencechildtwo);
            $expwhere.=" or child_two_id in ($expencechildtwoimp)";
        }
        $expwhere.=")";
        
        $dataexpence=DB::select("select 
                            (CASE 
                                WHEN generalledgers.sub_head_id THEN (select concat(head_code,'-',head_name) from subheads where subheads.id=generalledgers.sub_head_id)
                                WHEN generalledgers.child_one_id THEN (select concat(head_code,'-',head_name) from child_ones where child_ones.id=generalledgers.child_one_id)
                                WHEN generalledgers.child_two_id THEN (select concat(head_code,'-',head_name) from child_twos where child_twos.id=generalledgers.child_two_id)
                                ELSE 'None-0'
                            END) AS head_name,
        (sum(generalledgers.dr) - sum(generalledgers.cr)) as cost from generalledgers where  $expwhere and ${$type} group by sub_head_id,child_one_id,child_two_id");
        return $dataexpence;
    
    }
}
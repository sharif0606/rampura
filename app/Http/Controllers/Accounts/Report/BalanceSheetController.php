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

class BalanceSheetController extends Controller{
    
    public function index(Request $r){
        $cm=date('n');
        $cy=date('Y');
        if($r->current_month)
            $cm=$r->current_month;
        if($r->current_year)
            $cy=$r->current_year;
            
        if($cm>6) {
    		$qy=" date(generalledgers.v_date) BETWEEN '".$cy."-07-01' and '".$cy."-".$cm."-31"."' ";
		    $qly=" date(generalledgers.v_date) BETWEEN '2020-07-01' and '".$cy."-".($cm - ($cm-6))."-31"."' ";
    	}else{
    		$qy=" date(generalledgers.v_date) BETWEEN '".($cy-1)."-07-01' and '".$cy."-".$cm."-31"."' ";
		    $qly=" date(generalledgers.v_date) BETWEEN '2020-07-01' and '".($cy-1)."-".$cm."-31"."' ";
    	}
            
        $incDataYear=$this->income($cy,$cm,$qy);
        $incDataMonth=$this->income($cy,$cm,$qly);
        $expDataYear=$this->expence($cy,$cm,$qy);
        $expDataMonth=$this->expence($cy,$cm,$qly);
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
		
		return view("accounts.report.balancesheet",compact("incDataYear","expDataYear","data","cy","cm","qy","qly"));
    }
    
    function income($y,$m,$type){
        $income=Subhead::whereIn('masterhead_id',[4])->pluck('id')->toArray();
        $incomechildone=Chieldheadone::whereIn('subhead_id',$income)->pluck('id')->toArray();
        $incomechildtwo=Chieldheadtwo::whereIn('chieldheadone_id',$incomechildone)->pluck('id')->toArray();
        
        $incwhere="(";
        if($income){
            $incomeimp=implode(',',$income);
            $incwhere.="subhead_id in ($incomeimp)";
        }
        if($incomechildone){
            $incomechildoneimp=implode(',',$incomechildone);
            $incwhere.=" or chieldheadone_id in ($incomechildoneimp)";
        }
        if($incomechildtwo){
            $incomechildtwoimp=implode(',',$incomechildtwo);
            $incwhere.=" or chieldheadtwo_id in ($incomechildtwoimp)";
        }
        $incwhere.=")";
        
        $dataincome=DB::select("select (sum(generalledgers.cr) - sum(generalledgers.dr)) as income
                                from generalledgers where 
                                $incwhere and $type group by subhead_id,chieldheadone_id,chieldheadtwo_id");
        return $dataincome;
    
    }
    
    function expence($y,$m,$type){
    	
        $expence=Subhead::whereIn('masterhead_id',[5])->pluck('id')->toArray();
        $expencechildone=Chieldheadone::whereIn('subhead_id',$expence)->pluck('id')->toArray();
        $expencechildtwo=Chieldheadtwo::whereIn('chieldheadone_id',$expencechildone)->pluck('id')->toArray();
        
        $expwhere="(";
        if($expence){
            $expenceimp=implode(',',$expence);
            $expwhere.="subhead_id in ($expenceimp)";
        }
        if($expencechildone){
            $expencechildoneimp=implode(',',$expencechildone);
            $expwhere.=" or chieldheadone_id in ($expencechildoneimp)";
        }
        if($expencechildtwo){
            $expencechildtwoimp=implode(',',$expencechildtwo);
            $expwhere.=" or chieldheadtwo_id in ($expencechildtwoimp)";
        }
        $expwhere.=")";
        
        $dataexpence=DB::select("select 
                            (sum(generalledgers.dr) - sum(generalledgers.cr)) as cost
                            from generalledgers where 
                            $expwhere and $type group by subhead_id,chieldheadone_id,chieldheadtwo_id");
        return $dataexpence;
    
    }
}
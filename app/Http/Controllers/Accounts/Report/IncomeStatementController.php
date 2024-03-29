<?php

namespace App\Http\Controllers\Accounts\Report;

use App\Http\Controllers\Controller;

use App\Models\Vouchers\GeneralLedger;
use App\Models\Accounts\Master_account;
use App\Models\Accounts\Sub_head;
use App\Models\Accounts\Child_one;
use App\Models\Accounts\Child_two;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Models\Settings\Company;
use App\Models\Products\LcNumber;
use Illuminate\Support\Facades\DB;

class IncomeStatementController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('accounts.incomeStatement.index');
    }

    // public function details(Request $r){
    //     $month=$r->month;
    //     $year=$r->year;
    //     $acc_head=Master_account::with('sub_head')->where(company())->get();
    //     /* operating income */
    //     $incomeheadop=array();
    //     $incomeheadopone=array();
    //     $incomeheadoptwo=array();
    //     /* nonoperating income */
    //     $incomeheadnop=array();
    //     $incomeheadnopone=array();
    //     $incomeheadnoptwo=array();

    //     /* operating expense */
    //     $expenseheadop=array();
    //     $expenseheadopone=array();
    //     $expenseheadoptwo=array();
    //     /* nonoperating expense */
    //     $expenseheadnop=array();
    //     $expenseheadnopone=array();
    //     $expenseheadnoptwo=array();
    //     $tax_data=array();

    //     foreach($acc_head as $ah){
    //         if($ah->head_code=="4000"){
    //             if($ah->sub_head){
    //                 foreach($ah->sub_head as $sub_head){
    //                     if($sub_head->head_code=="4100"){/* operating income */
    //                         if($sub_head->child_one->count() > 0){
    //                             foreach($sub_head->child_one as $child_one){
    //                                 if($child_one->head_code!="4110"){/* remove sales head */
    //                                     if($child_one->child_two->count() > 0){
    //                                         foreach($child_one->child_two as $child_two){
    //                                             $incomeheadoptwo[]=$child_two->id;
    //                                         }
    //                                     }else{
    //                                         $incomeheadopone[]=$child_one->id;
    //                                     }
    //                                 }
    //                             }
    //                         }else{
    //                             $incomeheadop[]=$sub_head->id;
    //                         }
    //                     }else if ($sub_head->head_code=="4200"){ /* nonoperating income */
    //                         if($sub_head->child_one->count() > 0){
    //                             foreach($sub_head->child_one as $child_one){
    //                                 if($child_one->child_two->count() > 0){
    //                                     foreach($child_one->child_two as $child_two){
    //                                         $incomeheadnoptwo[]=$child_two->id;
    //                                     }
    //                                 }else{
    //                                     $incomeheadnopone[]=$child_one->id;
    //                                 }
    //                             }
    //                         }else{
    //                             $incomeheadnop[]=$sub_head->id;
    //                         }
    //                     }
    //                 }
    //             }
    //         }else if($ah->head_code=="5000"){
    //             if($ah->sub_head){
    //                 foreach($ah->sub_head as $sub_head){
    //                     if($sub_head->head_code=="5200"){/* operating expense */
    //                         if($sub_head->child_one->count() > 0){
    //                             foreach($sub_head->child_one as $child_one){
    //                                 if($child_one->head_code!="5330"){/* remove Purchase head */
    //                                     if($child_one->child_two->count() > 0){
    //                                         foreach($child_one->child_two as $child_two){
    //                                             $expenseheadoptwo[]=$child_two->id;
    //                                         }
    //                                     }else{
    //                                         $expenseheadopone[]=$child_one->id;
    //                                     }
    //                                 }
    //                             }
    //                         }else{
    //                             $expenseheadop[]=$sub_head->id;
    //                         }
    //                     }else if ($sub_head->head_code=="5300"){ /* nonoperating expense */
    //                         if($sub_head->child_one->count() > 0){
    //                             foreach($sub_head->child_one as $child_one){
    //                                 if($child_one->child_two->count() > 0){
    //                                     foreach($child_one->child_two as $child_two){
    //                                         $expenseheadnoptwo[]=$child_two->id;
    //                                     }
    //                                 }else{
    //                                     if($child_one->head_code!="5310")
    //                                         $expenseheadnopone[]=$child_one->id;
    //                                     else
    //                                         $tax_data[]=$child_one->id;
    //                                 }
    //                             }
    //                         }else{
    //                             $expenseheadnop[]=$sub_head->id;
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     if($month){
    //         $datas=$year."-".$month."-01";
    //         $datae=$year."-".$month."-31";
    //     }else{
    //         $datas=$year."-01-01";
    //         $datae=$year."-12-31";
    //     }
    //         //DB::connection()->enableQueryLog();
    //         /* operating income */
    //         $opincome=GeneralLedger::selectRaw('journal_title, sum(cr) as cr,sum(dr) as dr')->where(company())->whereBetween('rec_date',[$datas,$datae])
    //         ->where(function($query) use ($incomeheadop,$incomeheadopone,$incomeheadoptwo){
    //             if($incomeheadop)
    //                 $query->orWhere(function($query) use ($incomeheadop){
    //                     $query->whereIn('sub_head_id',$incomeheadop);
    //                 });
    //             if($incomeheadopone)
    //                 $query->orWhere(function($query) use ($incomeheadopone){
    //                     $query->whereIn('child_one_id',$incomeheadopone);
    //                 });
    //             if($incomeheadoptwo)
    //                 $query->orWhere(function($query) use ($incomeheadoptwo){
    //                     $query->whereIn('child_two_id',$incomeheadoptwo);
    //                 });
    //         })->groupBy('journal_title')
    //         ->get();

    //         //$queries = DB::getQueryLog();
    //         //print_r($queries);
    //         //dd($queries);
    //         /* nonoperating income */
    //         $nonopincome=GeneralLedger::selectRaw('journal_title, sum(cr) as cr,sum(dr) as dr')->whereBetween('rec_date',[$datas,$datae])
    //         ->where(function($query) use ($incomeheadnop,$incomeheadnopone,$incomeheadnoptwo){
    //             $query->orWhere(function($query) use ($incomeheadnop){
    //                  $query->whereIn('sub_head_id',$incomeheadnop);
    //             });
    //             $query->orWhere(function($query) use ($incomeheadnopone){
    //                  $query->whereIn('child_one_id',$incomeheadnopone);
    //             });
    //             $query->orWhere(function($query) use ($incomeheadnoptwo){
    //                  $query->whereIn('child_two_id',$incomeheadnoptwo);
    //             });
    //         })->groupBy('journal_title')
    //         ->get();
            
    //         /* operating expense */
    //         $opexpense=GeneralLedger::selectRaw('journal_title, sum(cr) as cr,sum(dr) as dr')->whereBetween('rec_date',[$datas,$datae])
    //         ->where(function($query) use ($expenseheadop,$expenseheadopone,$expenseheadoptwo){
    //             $query->orWhere(function($query) use ($expenseheadop){
    //                  $query->whereIn('sub_head_id',$expenseheadop);
    //             });
    //             $query->orWhere(function($query) use ($expenseheadopone){
    //                  $query->whereIn('child_one_id',$expenseheadopone);
    //             });
    //             $query->orWhere(function($query) use ($expenseheadoptwo){
    //                  $query->whereIn('child_two_id',$expenseheadoptwo);
    //             });
    //         })
    //         ->get();
            

    //         /* nonoperating expense */
    //         $nonopexpense=GeneralLedger::selectRaw('journal_title, sum(cr) as cr,sum(dr) as dr')->whereBetween('rec_date',[$datas,$datae])
    //         ->where(function($query) use ($expenseheadnop,$expenseheadnopone,$expenseheadnoptwo){
    //             $query->orWhere(function($query) use ($expenseheadnop){
    //                  $query->whereIn('sub_head_id',$expenseheadnop);
    //             });
    //             $query->orWhere(function($query) use ($expenseheadnopone){
    //                  $query->whereIn('child_one_id',$expenseheadnopone);
    //             });
    //             $query->orWhere(function($query) use ($expenseheadnoptwo){
    //                  $query->whereIn('child_two_id',$expenseheadnoptwo);
    //             });
    //         })
    //         ->get();
    //         /* tax expense */
    //         $taxamount=GeneralLedger::whereBetween('rec_date',[$datas,$datae])
    //         ->where(function($query) use ($tax_data){
    //             $query->orWhere(function($query) use ($tax_data){
    //                  $query->whereIn('child_one_id',$tax_data);
    //             });
    //         })
    //         ->get();
            
    //     $data='<div class="col-lg-12 stretch-card">
                
    //             <div class="card">
    //                 <h4 class="card-title">Income Statement</h4>
    //                 </p>
    //                 <table class="table table-bordered">
    //                 <thead>
    //                     <tr>
    //                     <th>#</th>
    //                     <th> Particulars </th>
    //                     <th> Amount </th>
    //                     </tr>
    //                 </thead>
    //                 <tbody>';
    //                 $i=1;
    //                 $opinc=0;
    //                 $nonopinc=0;
    //                 $opexp=0;
    //                 $nonopexp=0;
    //                 $tax=0;
    //                 /* operating income */
    //                 if($opincome){
    //                     foreach($opincome as $opi){
    //                         $opinc+=$opi->cr;
    //                         $data.='<tr class="table-info">';
    //                         $data.='<td>'.$i++.'</td>';
    //                         $data.='<td> '.$opi->journal_title.' </td>';
    //                         $data.='<td class="text-right"> '.$opi->cr.' </td>';
    //                         $data.='</tr>';
    //                     }
    //                 }
    //                 $data.='<tr>
    //                         <th> </th>
    //                         <th class="text-right"> Gross Operating Income </th>
    //                         <th class="text-right"> '.$opinc.' </th>
    //                         </tr>';
    //                 /* operating Expense */
    //                 if($opexpense){
    //                     foreach($opexpense as $opi){
    //                         $opexp+=$opi->dr;
    //                         $data.='<tr class="table-info">';
    //                         $data.='<td>'.$i++.'</td>';
    //                         $data.='<td> '.$opi->journal_title.' </td>';
    //                         $data.='<td class="text-right"> '.$opi->dr.' </td>';
    //                         $data.='</tr>';
    //                     }
    //                 }
    //                 $data.='<tr>
    //                         <th> </th>
    //                         <th class="text-right"> Total Operating Expense </th>
    //                         <th class="text-right"> '.$opexp.' </th>
    //                         </tr>';
    //                 $data.='<tr>
    //                         <th> </th>
    //                         <th class="text-right"> Net Operating Income </th>
    //                         <th class="text-right"> '.($opinc - $opexp).' </th>
    //                         </tr>';
    //                 /* nonoperating income */
    //                 if($nonopincome){
    //                     foreach($nonopincome as $opi){
    //                         $nonopinc+=$opi->cr;
    //                         $data.='<tr class="table-info">';
    //                         $data.='<td>'.$i++.'</td>';
    //                         $data.='<td> '.$opi->journal_title.' </td>';
    //                         $data.='<td class="text-right"> '.$opi->cr.' </td>';
    //                         $data.='</tr>';
                            
    //                     }
    //                 }
    //                 $data.='<tr>
    //                         <th> </th>
    //                         <th class="text-right"> Gross Nonoperating Income Total </th>
    //                         <th class="text-right"> '.$nonopinc.' </th>
    //                         </tr>';
                    
                    
    //                 /* nonoperating Expense */
    //                 if($nonopexpense){
    //                     foreach($nonopexpense as $opi){
    //                         $nonopexp+=$opi->dr;
    //                         $data.='<tr class="table-info">';
    //                         $data.='<td>'.$i++.'</td>';
    //                         $data.='<td> '.$opi->journal_title.' </td>';
    //                         $data.='<td class="text-right"> '.$opi->dr.' </td>';
    //                         $data.='</tr>';
                            
    //                     }
    //                 }
    //                 $data.='<tr>
    //                         <th> </th>
    //                         <th class="text-right"> Total Nonoperating Expense </th>
    //                         <th class="text-right"> '.$nonopexp.' </th>
    //                         </tr>';
    //                 $data.='<tr>
    //                         <th> </th>
    //                         <th class="text-right"> Net Nonoperating Income </th>
    //                         <th class="text-right"> '.($nonopinc - $nonopexp).' </th>
    //                         </tr>';
    //                 $data.='<tr>
    //                         <th> </th>
    //                         <th class="text-right"> Net Income Before Tax</th>
    //                         <th class="text-right"> '.(($nonopinc + $opinc)  - ($opexp + $nonopexp)).' </th>
    //                         </tr>';
    //                 if($taxamount){
    //                     foreach($taxamount as $t){
    //                         $tax+=$t->dr;
    //                         $data.='<tr class="table-info">';
    //                         $data.='<td>'.$i++.'</td>';
    //                         $data.='<td> '.$t->journal_title.' </td>';
    //                         $data.='<td class="text-right"> '.$t->dr.' </td>';
    //                         $data.='</tr>';
                            
    //                     }
    //                 }
    //                 $data.='<tr>
    //                         <th> </th>
    //                         <th class="text-right"> Net Income</th>
    //                         <th class="text-right"> '.(($nonopinc + $opinc)  - ($opexp + $nonopexp + $tax)).' </th>
    //                         </tr>';

    //         $data.='</tbody>
    //             </table>
    //         </div>
            
    //     </div>';
    //     echo  json_encode($data);
    //     //print_r($r->year);
    // }

    public function details(Request $r){
        $month=$r->month;
        $year=$r->year;
        $accheaddata=Company::find(company()['company_id']);

        $acc_head=Master_account::with('sub_head')->where(company())->get();
        /* operating income */
        $incomeheadop=array();
        $incomeheadopone=array();
        $incomeheadoptwo=array();
        /* nonoperating income */
        $incomeheadnop=array();
        $incomeheadnopone=array();
        $incomeheadnoptwo=array();

        /* operating expense */
        $expenseheadop=array();
        $expenseheadopone=array();
        $expenseheadoptwo=array();
        /* nonoperating expense */
        $expenseheadnop=array();
        $expenseheadnopone=array();
        $expenseheadnoptwo=array();
        $tax_data=array();
        
        $incomeheadop=Sub_head::whereIn('head_code',explode(',',$accheaddata->income_head))->where(company())->pluck('id');
        $incomeheadopone=Child_one::whereIn('head_code',explode(',',$accheaddata->income_head))->where(company())->pluck('id');
        $incomeheadoptwo=Child_two::whereIn('head_code',explode(',',$accheaddata->income_head))->where(company())->pluck('id');

        $expenseheadop=Sub_head::whereIn('head_code',explode(',',$accheaddata->expense_head))->where(company())->pluck('id');
        $expenseheadopone=Child_one::whereIn('head_code',explode(',',$accheaddata->expense_head))->where(company())->pluck('id');
        $expenseheadoptwo=Child_two::whereIn('head_code',explode(',',$accheaddata->expense_head))->where(company())->pluck('id');
        $stock=DB::select("SELECT `product_id`, sum(`quantity`) as qty, (((select sum(`total_amount`) FROM stocks as stp WHERE stp.product_id=stocks.product_id and stp.quantity > 0 and stp.deleted_at is null) / (select sum(`quantity`) FROM stocks as stp WHERE stp.product_id=stocks.product_id and stp.quantity > 0 and stp.deleted_at is null) ) * sum(`quantity`)) as rate FROM `stocks` WHERE `stock_date` <= '2024-02-08' and deleted_at is null GROUP BY `product_id`");
        $stock_price=0;
        if($stock){
            foreach($stock as $st){
                $stock_price+= $st->rate;
            }
            
        }
        
       
        if($month){
            $datas=$year."-".$month."-01";
            $datae=$year."-".$month."-31";
        }else{
            $datas=$year."-01-01";
            $datae=$year."-12-31";
        }
            //DB::connection()->enableQueryLog();
            /* operating income */
            $opincome=GeneralLedger::selectRaw('account_title, sum(cr) as cr,sum(dr) as dr')->where(company())->whereBetween('rec_date',[$datas,$datae])
            ->where(function($query) use ($incomeheadop,$incomeheadopone,$incomeheadoptwo){
                if($incomeheadop)
                    $query->orWhere(function($query) use ($incomeheadop){
                        $query->whereIn('sub_head_id',$incomeheadop);
                    });
                if($incomeheadopone)
                    $query->orWhere(function($query) use ($incomeheadopone){
                        $query->whereIn('child_one_id',$incomeheadopone);
                    });
                if($incomeheadoptwo)
                    $query->orWhere(function($query) use ($incomeheadoptwo){
                        $query->whereIn('child_two_id',$incomeheadoptwo);
                    });
            })->groupBy('account_title')
            ->get();

            //$queries = DB::getQueryLog();
            //print_r($queries);
            //dd($queries);
            /* nonoperating income */
            $nonopincome=GeneralLedger::selectRaw('account_title, sum(cr) as cr,sum(dr) as dr')->whereBetween('rec_date',[$datas,$datae])
            ->where(function($query) use ($incomeheadnop,$incomeheadnopone,$incomeheadnoptwo){
                $query->orWhere(function($query) use ($incomeheadnop){
                     $query->whereIn('sub_head_id',$incomeheadnop);
                });
                $query->orWhere(function($query) use ($incomeheadnopone){
                     $query->whereIn('child_one_id',$incomeheadnopone);
                });
                $query->orWhere(function($query) use ($incomeheadnoptwo){
                     $query->whereIn('child_two_id',$incomeheadnoptwo);
                });
            })->groupBy('account_title')
            ->get();
            
            /* operating expense */
            $opexpense=GeneralLedger::selectRaw('account_title, sum(cr) as cr,sum(dr) as dr')->whereBetween('rec_date',[$datas,$datae])
            ->where(function($query) use ($expenseheadop,$expenseheadopone,$expenseheadoptwo){
                $query->orWhere(function($query) use ($expenseheadop){
                     $query->whereIn('sub_head_id',$expenseheadop);
                });
                $query->orWhere(function($query) use ($expenseheadopone){
                     $query->whereIn('child_one_id',$expenseheadopone);
                });
                $query->orWhere(function($query) use ($expenseheadoptwo){
                     $query->whereIn('child_two_id',$expenseheadoptwo);
                });
            })->groupBy('account_title')
            ->get();
            

            /* nonoperating expense */
            $nonopexpense=GeneralLedger::selectRaw('account_title, sum(cr) as cr,sum(dr) as dr')->whereBetween('rec_date',[$datas,$datae])
            ->where(function($query) use ($expenseheadnop,$expenseheadnopone,$expenseheadnoptwo){
                $query->orWhere(function($query) use ($expenseheadnop){
                     $query->whereIn('sub_head_id',$expenseheadnop);
                });
                $query->orWhere(function($query) use ($expenseheadnopone){
                     $query->whereIn('child_one_id',$expenseheadnopone);
                });
                $query->orWhere(function($query) use ($expenseheadnoptwo){
                     $query->whereIn('child_two_id',$expenseheadnoptwo);
                });
            })->groupBy('account_title')
            ->get();
            /* tax expense */
            $taxamount=GeneralLedger::whereBetween('rec_date',[$datas,$datae])
            ->where(function($query) use ($tax_data){
                $query->orWhere(function($query) use ($tax_data){
                     $query->whereIn('child_one_id',$tax_data);
                });
            })
            ->get();
            
        $data='<div class="col-lg-12 stretch-card">
                
                <div class="card">
                    <h4 class="card-title">Income Statement</h4>
                    </p>
                    <table class="table table-bordered">
                    <thead>
                        <tr>
                        <th>#</th>
                        <th> Particulars </th>
                        <th> Amount </th>
                        </tr>
                    </thead>
                    <tbody>';
                    $i=1;
                    $opinc=0;
                    $nonopinc=0;
                    $opexp=0;
                    $nonopexp=0;
                    $tax=0;
                    /* operating income */
                    if($opincome){
                        foreach($opincome as $opi){
                            $opinc+=$opi->cr;
                            $data.='<tr class="table-info">';
                            $data.='<td>'.$i++.'</td>';
                            $data.='<td> '.$opi->account_title.' </td>';
                            $data.='<td class="text-right"> '.$opi->cr.' </td>';
                            $data.='</tr>';
                        }
                    }
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Gross Operating Income </th>
                            <th class="text-right"> '.$opinc.' </th>
                            </tr>';
                    /* operating Expense */
                    if($opexpense){
                        foreach($opexpense as $opi){
                            if(explode('-',$opi->account_title)[0] == "5330"){
                                $opi->dr=($opi->dr - $stock_price);
                            }elseif(explode('-',$opi->account_title)[1] == "5330"){
                                $opi->dr=($opi->dr - $stock_price);
                            }
                            $opexp+=$opi->dr;
                            $data.='<tr class="table-info">';
                            $data.='<td>'.$i++.'</td>';
                            $data.='<td> '.$opi->account_title.' </td>';
                            $data.='<td class="text-right"> '.$opi->dr.' </td>';
                            $data.='</tr>';
                        }
                    }
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Total Operating Expense </th>
                            <th class="text-right"> '.$opexp.' </th>
                            </tr>';
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Net Operating Income </th>
                            <th class="text-right"> '.($opinc - $opexp).' </th>
                            </tr>';
                    /* nonoperating income */
                    if($nonopincome){
                        foreach($nonopincome as $opi){
                            $nonopinc+=$opi->cr;
                            $data.='<tr class="table-info">';
                            $data.='<td>'.$i++.'</td>';
                            $data.='<td> '.$opi->account_title.' </td>';
                            $data.='<td class="text-right"> '.$opi->cr.' </td>';
                            $data.='</tr>';
                            
                        }
                    }
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Gross Nonoperating Income Total </th>
                            <th class="text-right"> '.$nonopinc.' </th>
                            </tr>';
                    
                    
                    /* nonoperating Expense */
                    if($nonopexpense){
                        foreach($nonopexpense as $opi){
                            $nonopexp+=$opi->dr;
                            $data.='<tr class="table-info">';
                            $data.='<td>'.$i++.'</td>';
                            $data.='<td> '.$opi->account_title.' </td>';
                            $data.='<td class="text-right"> '.$opi->dr.' </td>';
                            $data.='</tr>';
                            
                        }
                    }
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Total Nonoperating Expense </th>
                            <th class="text-right"> '.$nonopexp.' </th>
                            </tr>';
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Net Nonoperating Income </th>
                            <th class="text-right"> '.($nonopinc - $nonopexp).' </th>
                            </tr>';
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Net Income Before Tax</th>
                            <th class="text-right"> '.(($nonopinc + $opinc)  - ($opexp + $nonopexp)).' </th>
                            </tr>';
                    if($taxamount){
                        foreach($taxamount as $t){
                            $tax+=$t->dr;
                            $data.='<tr class="table-info">';
                            $data.='<td>'.$i++.'</td>';
                            $data.='<td> '.$t->account_title.' </td>';
                            $data.='<td class="text-right"> '.$t->dr.' </td>';
                            $data.='</tr>';
                            
                        }
                    }
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Net Income</th>
                            <th class="text-right"> '.(($nonopinc + $opinc)  - ($opexp + $nonopexp + $tax)).' </th>
                            </tr>';

            $data.='</tbody>
                </table>
            </div>
            
        </div>';
        echo  json_encode($data);
        //print_r($r->year);
    }

    public function details_without_sales(Request $r){
        $month=$r->month;
        $year=$r->year;
        $accheaddata=Company::find(company()['company_id']);

        $acc_head=Master_account::with('sub_head')->where(company())->get();
        /* operating income */
        $incomeheadop=array();
        $incomeheadopone=array();
        $incomeheadoptwo=array();
        /* nonoperating income */
        $incomeheadnop=array();
        $incomeheadnopone=array();
        $incomeheadnoptwo=array();

        /* operating expense */
        $expenseheadop=array();
        $expenseheadopone=array();
        $expenseheadoptwo=array();
        /* nonoperating expense */
        $expenseheadnop=array();
        $expenseheadnopone=array();
        $expenseheadnoptwo=array();
        $tax_data=array();
        $salesincome=0;

        
        $incomeheadop=Sub_head::whereIn('head_code',explode(',',$accheaddata->income_head))->where(company())->pluck('id');
        $incomeheadopone=Child_one::whereIn('head_code',explode(',',$accheaddata->income_head))->where(company())->pluck('id');
        $incomeheadoptwo=Child_two::whereIn('head_code',explode(',',$accheaddata->income_head))->where(company())->pluck('id');

        $expenseheadop=Sub_head::whereIn('head_code',explode(',',$accheaddata->expense_head))->where(company())->pluck('id');
        $expenseheadopone=Child_one::whereIn('head_code',explode(',',$accheaddata->expense_head))->where(company())->pluck('id');
        $expenseheadoptwo=Child_two::whereIn('head_code',explode(',',$accheaddata->expense_head))->where(company())->pluck('id');
       
        $stock=DB::select("SELECT `product_id`, sum(`quantity`) as qty, (((select sum(`total_amount`) FROM stocks as stp WHERE stp.product_id=stocks.product_id and stp.quantity > 0 and stp.deleted_at is null) / (select sum(`quantity`) FROM stocks as stp WHERE stp.product_id=stocks.product_id and stp.quantity > 0 and stp.deleted_at is null) ) * sum(`quantity`)) as rate FROM `stocks` WHERE `stock_date` <= '2024-02-08' and deleted_at is null GROUP BY `product_id`");
        $stock_price=0;
        if($stock){
            foreach($stock as $st){
                $stock_price+= $st->rate;
            }
            
        }

        // print_r($incomeheadopone);
        // die();
       
        if($month){
            $datas=$year."-".$month."-01";
            $datae=$year."-".$month."-31";
        }else{
            $datas=$year."-01-01";
            $datae=$year."-12-31";
        }
        /* remove sales amount from non billable purchase */
        $lot_no=LcNumber::where(company())->where('billable',0)->pluck('lot_no');
        $sales_acc_id=Child_one::where('head_code','4110')->where(company())->pluck('id');
        if($sales_acc_id)
            $salesincome=GeneralLedger::where(company())->whereBetween('rec_date',[$datas,$datae])->whereIn('lc_no',$lot_no)->whereIn('child_one_id',$sales_acc_id)->groupBy('child_one_id')->sum('cr');

            //DB::connection()->enableQueryLog();
            /* operating income */
            $opincome=GeneralLedger::selectRaw('account_title, sum(cr) as cr,sum(dr) as dr,child_one_id')->where(company())->whereBetween('rec_date',[$datas,$datae])
            ->where(function($query) use ($incomeheadop,$incomeheadopone,$incomeheadoptwo){
                if($incomeheadop)
                    $query->orWhere(function($query) use ($incomeheadop){
                        $query->whereIn('sub_head_id',$incomeheadop);
                    });
                if($incomeheadopone)
                    $query->orWhere(function($query) use ($incomeheadopone){
                        $query->whereIn('child_one_id',$incomeheadopone);
                    });
                if($incomeheadoptwo)
                    $query->orWhere(function($query) use ($incomeheadoptwo){
                        $query->whereIn('child_two_id',$incomeheadoptwo);
                    });
            })->groupBy('account_title')
            ->get();

            //$queries = DB::getQueryLog();
            //print_r($queries);
            //dd($queries);
            /* nonoperating income */
            $nonopincome=GeneralLedger::selectRaw('account_title, sum(cr) as cr,sum(dr) as dr')->whereBetween('rec_date',[$datas,$datae])
            ->where(function($query) use ($incomeheadnop,$incomeheadnopone,$incomeheadnoptwo){
                $query->orWhere(function($query) use ($incomeheadnop){
                     $query->whereIn('sub_head_id',$incomeheadnop);
                });
                $query->orWhere(function($query) use ($incomeheadnopone){
                     $query->whereIn('child_one_id',$incomeheadnopone);
                });
                $query->orWhere(function($query) use ($incomeheadnoptwo){
                     $query->whereIn('child_two_id',$incomeheadnoptwo);
                });
            })->groupBy('account_title')
            ->get();
            
            /* operating expense */
            $opexpense=GeneralLedger::selectRaw('account_title, sum(cr) as cr,sum(dr) as dr')->whereBetween('rec_date',[$datas,$datae])
            ->where(function($query) use ($expenseheadop,$expenseheadopone,$expenseheadoptwo){
                $query->orWhere(function($query) use ($expenseheadop){
                     $query->whereIn('sub_head_id',$expenseheadop);
                });
                $query->orWhere(function($query) use ($expenseheadopone){
                     $query->whereIn('child_one_id',$expenseheadopone);
                });
                $query->orWhere(function($query) use ($expenseheadoptwo){
                     $query->whereIn('child_two_id',$expenseheadoptwo);
                });
            })->groupBy('account_title')
            ->get();
            

            /* nonoperating expense */
            $nonopexpense=GeneralLedger::selectRaw('account_title, sum(cr) as cr,sum(dr) as dr')->whereBetween('rec_date',[$datas,$datae])
            ->where(function($query) use ($expenseheadnop,$expenseheadnopone,$expenseheadnoptwo){
                $query->orWhere(function($query) use ($expenseheadnop){
                     $query->whereIn('sub_head_id',$expenseheadnop);
                });
                $query->orWhere(function($query) use ($expenseheadnopone){
                     $query->whereIn('child_one_id',$expenseheadnopone);
                });
                $query->orWhere(function($query) use ($expenseheadnoptwo){
                     $query->whereIn('child_two_id',$expenseheadnoptwo);
                });
            })->groupBy('account_title')
            ->get();
            /* tax expense */
            $taxamount=GeneralLedger::whereBetween('rec_date',[$datas,$datae])
            ->where(function($query) use ($tax_data){
                $query->orWhere(function($query) use ($tax_data){
                     $query->whereIn('child_one_id',$tax_data);
                });
            })
            ->get();
            
        $data='<div class="col-lg-12 stretch-card">
                
                <div class="card">
                    <h4 class="card-title">Income Statement</h4>
                    </p>
                    <table class="table table-bordered">
                    <thead>
                        <tr>
                        <th>#</th>
                        <th> Particulars </th>
                        <th> Amount </th>
                        </tr>
                    </thead>
                    <tbody>';
                    $i=1;
                    $opinc=0;
                    $nonopinc=0;
                    $opexp=0;
                    $nonopexp=0;
                    $tax=0;
                    /* operating income */
                    if($opincome){
                        foreach($opincome as $opi){
                            $opicr=0;
                            if(isset($sales_acc_id[0])){
                                if($opi->child_one_id==$sales_acc_id[0])
                                    $opicr=$opi->cr - $salesincome;
                                else
                                    $opicr=$opi->cr;
                            }else{
                                $opicr=$opi->cr;
                            }
                            $opinc+=$opicr;
                            $data.='<tr class="table-info">';
                            $data.='<td>'.$i++.'</td>';
                            $data.='<td> '.$opi->account_title.' </td>';
                            $data.='<td class="text-right"> '.$opicr.' </td>';
                            $data.='</tr>';
                        }
                    }
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Gross Operating Income </th>
                            <th class="text-right"> '.$opinc.' </th>
                            </tr>';
                    /* operating Expense */
                    if($opexpense){
                        foreach($opexpense as $opi){
                            if(explode('-',$opi->account_title)[0] == "5330"){
                                $opi->dr=($opi->dr - $stock_price);
                            }elseif(explode('-',$opi->account_title)[1] == "5330"){
                                $opi->dr=($opi->dr - $stock_price);
                            }
                            $opexp+=$opi->dr;
                            $data.='<tr class="table-info">';
                            $data.='<td>'.$i++.'</td>';
                            $data.='<td> '.$opi->account_title.' </td>';
                            $data.='<td class="text-right"> '.$opi->dr.' </td>';
                            $data.='</tr>';
                        }
                    }
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Total Operating Expense </th>
                            <th class="text-right"> '.$opexp.' </th>
                            </tr>';
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Net Operating Income </th>
                            <th class="text-right"> '.($opinc - $opexp).' </th>
                            </tr>';
                    /* nonoperating income */
                    if($nonopincome){
                        foreach($nonopincome as $opi){
                            $nonopinc+=$opi->cr;
                            $data.='<tr class="table-info">';
                            $data.='<td>'.$i++.'</td>';
                            $data.='<td> '.$opi->account_title.' </td>';
                            $data.='<td class="text-right"> '.$opi->cr.' </td>';
                            $data.='</tr>';
                            
                        }
                    }
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Gross Nonoperating Income Total </th>
                            <th class="text-right"> '.$nonopinc.' </th>
                            </tr>';
                    
                    
                    /* nonoperating Expense */
                    if($nonopexpense){
                        foreach($nonopexpense as $opi){
                            $nonopexp+=$opi->dr;
                            $data.='<tr class="table-info">';
                            $data.='<td>'.$i++.'</td>';
                            $data.='<td> '.$opi->account_title.' </td>';
                            $data.='<td class="text-right"> '.$opi->dr.' </td>';
                            $data.='</tr>';
                            
                        }
                    }
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Total Nonoperating Expense </th>
                            <th class="text-right"> '.$nonopexp.' </th>
                            </tr>';
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Net Nonoperating Income </th>
                            <th class="text-right"> '.($nonopinc - $nonopexp).' </th>
                            </tr>';
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Net Income Before Tax</th>
                            <th class="text-right"> '.(($nonopinc + $opinc)  - ($opexp + $nonopexp)).' </th>
                            </tr>';
                    if($taxamount){
                        foreach($taxamount as $t){
                            $tax+=$t->dr;
                            $data.='<tr class="table-info">';
                            $data.='<td>'.$i++.'</td>';
                            $data.='<td> '.$t->account_title.' </td>';
                            $data.='<td class="text-right"> '.$t->dr.' </td>';
                            $data.='</tr>';
                            
                        }
                    }
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Net Income</th>
                            <th class="text-right"> '.(($nonopinc + $opinc)  - ($opexp + $nonopexp + $tax)).' </th>
                            </tr>';

            $data.='</tbody>
                </table>
            </div>
            
        </div>';
        echo  json_encode($data);
        //print_r($r->year);
    }


    public function details_sales_purchase(Request $r){
        $month=$r->month;
        $year=$r->year;
        $acc_head=Master_account::with('sub_head')->where(company())->get();
        /* operating income */
        $incomeheadop=array();
        $incomeheadopone=array();
        $incomeheadoptwo=array();
        /* nonoperating income */
        $incomeheadnop=array();
        $incomeheadnopone=array();
        $incomeheadnoptwo=array();

        /* operating expense */
        $expenseheadop=array();
        $expenseheadopone=array();
        $expenseheadoptwo=array();
        /* nonoperating expense */
        $expenseheadnop=array();
        $expenseheadnopone=array();
        $expenseheadnoptwo=array();
        $tax_data=array();

        foreach($acc_head as $ah){
            if($ah->head_code=="4000"){
                if($ah->sub_head){
                    foreach($ah->sub_head as $sub_head){
                        if($sub_head->head_code=="4100"){/* operating income */
                            if($sub_head->child_one->count() > 0){
                                foreach($sub_head->child_one as $child_one){
                                    if($child_one->child_two->count() > 0){
                                        foreach($child_one->child_two as $child_two){
                                            $incomeheadoptwo[]=$child_two->id;
                                        }
                                    }else{
                                        $incomeheadopone[]=$child_one->id;
                                    }
                                }
                            }else{
                                $incomeheadop[]=$sub_head->id;
                            }
                        }else if ($sub_head->head_code=="4200"){ /* nonoperating income */
                            if($sub_head->child_one->count() > 0){
                                foreach($sub_head->child_one as $child_one){
                                    if($child_one->child_two->count() > 0){
                                        foreach($child_one->child_two as $child_two){
                                            $incomeheadnoptwo[]=$child_two->id;
                                        }
                                    }else{
                                        $incomeheadnopone[]=$child_one->id;
                                    }
                                }
                            }else{
                                $incomeheadnop[]=$sub_head->id;
                            }
                        }
                    }
                }
            }else if($ah->head_code=="5000"){
                if($ah->sub_head){
                    foreach($ah->sub_head as $sub_head){
                        if($sub_head->head_code=="5200"){/* operating income */
                            if($sub_head->child_one->count() > 0){
                                foreach($sub_head->child_one as $child_one){
                                    if($child_one->child_two->count() > 0){
                                        foreach($child_one->child_two as $child_two){
                                            $expenseheadoptwo[]=$child_two->id;
                                        }
                                    }else{
                                        $expenseheadopone[]=$child_one->id;
                                    }
                                }
                            }else{
                                $expenseheadop[]=$sub_head->id;
                            }
                        }else if ($sub_head->head_code=="5300"){ /* nonoperating income */
                            if($sub_head->child_one->count() > 0){
                                foreach($sub_head->child_one as $child_one){
                                    if($child_one->child_two->count() > 0){
                                        foreach($child_one->child_two as $child_two){
                                            $expenseheadnoptwo[]=$child_two->id;
                                        }
                                    }else{
                                        if($child_one->head_code!="5310")
                                            $expenseheadnopone[]=$child_one->id;
                                        else
                                            $tax_data[]=$child_one->id;
                                    }
                                }
                            }else{
                                $expenseheadnop[]=$sub_head->id;
                            }
                        }
                    }
                }
            }
        }

        if($month){
            $datas=$year."-".$month."-01";
            $datae=$year."-".$month."-31";
        }else{
            $datas=$year."-01-01";
            $datae=$year."-12-31";
        }
            //DB::connection()->enableQueryLog();
            /* operating income */
            $opincome=GeneralLedger::selectRaw('account_title, sum(cr) as cr,sum(dr) as dr')->where(company())->whereBetween('rec_date',[$datas,$datae])
            ->where(function($query) use ($incomeheadop,$incomeheadopone,$incomeheadoptwo){
                if($incomeheadop)
                    $query->orWhere(function($query) use ($incomeheadop){
                        $query->whereIn('sub_head_id',$incomeheadop);
                    });
                if($incomeheadopone)
                    $query->orWhere(function($query) use ($incomeheadopone){
                        $query->whereIn('child_one_id',$incomeheadopone);
                    });
                if($incomeheadoptwo)
                    $query->orWhere(function($query) use ($incomeheadoptwo){
                        $query->whereIn('child_two_id',$incomeheadoptwo);
                    });
            })->groupBy('account_title')
            ->get();

            //$queries = DB::getQueryLog();
            //print_r($queries);
            //dd($queries);
            /* nonoperating income */
            $nonopincome=GeneralLedger::selectRaw('account_title, sum(cr) as cr,sum(dr) as dr')->whereBetween('rec_date',[$datas,$datae])
            ->where(function($query) use ($incomeheadnop,$incomeheadnopone,$incomeheadnoptwo){
                $query->orWhere(function($query) use ($incomeheadnop){
                     $query->whereIn('sub_head_id',$incomeheadnop);
                });
                $query->orWhere(function($query) use ($incomeheadnopone){
                     $query->whereIn('child_one_id',$incomeheadnopone);
                });
                $query->orWhere(function($query) use ($incomeheadnoptwo){
                     $query->whereIn('child_two_id',$incomeheadnoptwo);
                });
            })->groupBy('account_title')
            ->get();
            
            /* operating expense */
            $opexpense=GeneralLedger::selectRaw('account_title, sum(cr) as cr,sum(dr) as dr')->whereBetween('rec_date',[$datas,$datae])
            ->where(function($query) use ($expenseheadop,$expenseheadopone,$expenseheadoptwo){
                $query->orWhere(function($query) use ($expenseheadop){
                     $query->whereIn('sub_head_id',$expenseheadop);
                });
                $query->orWhere(function($query) use ($expenseheadopone){
                     $query->whereIn('child_one_id',$expenseheadopone);
                });
                $query->orWhere(function($query) use ($expenseheadoptwo){
                     $query->whereIn('child_two_id',$expenseheadoptwo);
                });
            })->groupBy('account_title')
            ->get();
            

            /* nonoperating expense */
            $nonopexpense=GeneralLedger::selectRaw('account_title, sum(cr) as cr,sum(dr) as dr')->whereBetween('rec_date',[$datas,$datae])
            ->where(function($query) use ($expenseheadnop,$expenseheadnopone,$expenseheadnoptwo){
                $query->orWhere(function($query) use ($expenseheadnop){
                     $query->whereIn('sub_head_id',$expenseheadnop);
                });
                $query->orWhere(function($query) use ($expenseheadnopone){
                     $query->whereIn('child_one_id',$expenseheadnopone);
                });
                $query->orWhere(function($query) use ($expenseheadnoptwo){
                     $query->whereIn('child_two_id',$expenseheadnoptwo);
                });
            })->groupBy('account_title')
            ->get();
            /* tax expense */
            $taxamount=GeneralLedger::whereBetween('rec_date',[$datas,$datae])
            ->where(function($query) use ($tax_data){
                $query->orWhere(function($query) use ($tax_data){
                     $query->whereIn('child_one_id',$tax_data);
                });
            })
            ->get();
            
        $data='<div class="col-lg-12 stretch-card">
                
                <div class="card">
                    <h4 class="card-title">Income Statement</h4>
                    </p>
                    <table class="table table-bordered">
                    <thead>
                        <tr>
                        <th>#</th>
                        <th> Particulars </th>
                        <th> Amount </th>
                        </tr>
                    </thead>
                    <tbody>';
                    $i=1;
                    $opinc=0;
                    $nonopinc=0;
                    $opexp=0;
                    $nonopexp=0;
                    $tax=0;
                    /* operating income */
                    if($opincome){
                        foreach($opincome as $opi){
                            $opinc+=$opi->cr;
                            $data.='<tr class="table-info">';
                            $data.='<td>'.$i++.'</td>';
                            $data.='<td> '.$opi->account_title.' </td>';
                            $data.='<td class="text-right"> '.$opi->cr.' </td>';
                            $data.='</tr>';
                        }
                    }
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Gross Operating Income </th>
                            <th class="text-right"> '.$opinc.' </th>
                            </tr>';
                    /* operating Expense */
                    if($opexpense){
                        foreach($opexpense as $opi){
                            $opexp+=$opi->dr;
                            $data.='<tr class="table-info">';
                            $data.='<td>'.$i++.'</td>';
                            $data.='<td> '.$opi->account_title.' </td>';
                            $data.='<td class="text-right"> '.$opi->dr.' </td>';
                            $data.='</tr>';
                        }
                    }
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Total Operating Expense </th>
                            <th class="text-right"> '.$opexp.' </th>
                            </tr>';
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Net Operating Income </th>
                            <th class="text-right"> '.($opinc - $opexp).' </th>
                            </tr>';
                    /* nonoperating income */
                    if($nonopincome){
                        foreach($nonopincome as $opi){
                            $nonopinc+=$opi->cr;
                            $data.='<tr class="table-info">';
                            $data.='<td>'.$i++.'</td>';
                            $data.='<td> '.$opi->account_title.' </td>';
                            $data.='<td class="text-right"> '.$opi->cr.' </td>';
                            $data.='</tr>';
                            
                        }
                    }
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Gross Nonoperating Income Total </th>
                            <th class="text-right"> '.$nonopinc.' </th>
                            </tr>';
                    
                    
                    /* nonoperating Expense */
                    if($nonopexpense){
                        foreach($nonopexpense as $opi){
                            $nonopexp+=$opi->dr;
                            $data.='<tr class="table-info">';
                            $data.='<td>'.$i++.'</td>';
                            $data.='<td> '.$opi->account_title.' </td>';
                            $data.='<td class="text-right"> '.$opi->dr.' </td>';
                            $data.='</tr>';
                            
                        }
                    }
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Total Nonoperating Expense </th>
                            <th class="text-right"> '.$nonopexp.' </th>
                            </tr>';
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Net Nonoperating Income </th>
                            <th class="text-right"> '.($nonopinc - $nonopexp).' </th>
                            </tr>';
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Net Income Before Tax</th>
                            <th class="text-right"> '.(($nonopinc + $opinc)  - ($opexp + $nonopexp)).' </th>
                            </tr>';
                    if($taxamount){
                        foreach($taxamount as $t){
                            $tax+=$t->dr;
                            $data.='<tr class="table-info">';
                            $data.='<td>'.$i++.'</td>';
                            $data.='<td> '.$t->account_title.' </td>';
                            $data.='<td class="text-right"> '.$t->dr.' </td>';
                            $data.='</tr>';
                            
                        }
                    }
                    $data.='<tr>
                            <th> </th>
                            <th class="text-right"> Net Income</th>
                            <th class="text-right"> '.(($nonopinc + $opinc)  - ($opexp + $nonopexp + $tax)).' </th>
                            </tr>';

            $data.='</tbody>
                </table>
            </div>
            
        </div>';
        echo  json_encode($data);
        //print_r($r->year);
    }

}

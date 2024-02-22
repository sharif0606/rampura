<?php

namespace App\Http\Controllers\Return;
use App\Http\Controllers\Controller;

use App\Models\Return\Regular_purchase_return;
use App\Models\Return\Regular_purchase_return_detail;
use App\Models\Accounts\Child_one;
use App\Models\Accounts\Child_two;
use App\Models\Purchases\Beparian_purchase;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\Regular_purchase;
use App\Models\Settings\Branch;
use App\Models\Settings\Warehouse;
use App\Models\Stock\Stock;
use App\Models\Suppliers\Supplier;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RegularPurchaseReturnController extends Controller
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
        //if( currentUser()=='owner')
            $purchases = Regular_purchase_return::with('purchase_lot','supplier','warehouse','createdBy','updatedBy')->where(company());
        //else
            //$purchases = Purchase::where(company())->where(branch());

        if($request->nane)
            $purchases=$purchases->where('supplier_id','like','%'.$request->nane.'%');
        
        if($request->lot_no){
            $lotno=$request->lot_no;
            $purchases=$purchases->whereHas('purchase_lot', function($q) use ($lotno){
                $q->where('lot_no', $lotno);
            });
        }

        $purchases=$purchases->orderBy('id', 'DESC')->paginate(12);
        
        return view('regularReturn.index',compact('purchases','suppliers'));
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
        
        return view('regularReturn.create',compact('branches','suppliers','Warehouses','childTow','paymethod'));
        
    }
    public function product_search(Request $request)
    {
        if($request->name){
            $regular_purchase_id = Regular_purchase::where(company())->where('supplier_id',$request->supplier_id)->pluck('id')->toArray();
            if($regular_purchase_id){
                $regular_purchase_id=implode(',',$regular_purchase_id);
            }else{
                $regular_purchase_id=0;
            }
            if($request->batch_id){
                $product=DB::select("SELECT products.id,products.product_name,products.bar_code,stocks.lot_no,
                                stocks.unit_price,stocks.batch_id,stocks.brand,
                                sum(stocks.quantity_bag) as bag_qty,
                                sum(stocks.quantity) as qty FROM `products`
                                JOIN stocks on stocks.product_id=products.id
                                JOIN purchase_details on purchase_details.product_id=products.id
                                WHERE stocks.company_id=".company()['company_id']."
                                and stocks.branch_id=".$request->branch_id." 
                                and stocks.warehouse_id=".$request->warehouse_id." 
                                and (stocks.lot_no like '%". $request->name ."%' 
                                or stocks.brand like '%". $request->name ."%' or 
                                products.product_name like '%". $request->name ."%') 
                                and (stocks.batch_id is not null or stocks.batch_id != '') 
                                and purchase_details.regular_purchase_id in ($regular_purchase_id) 
                                and stocks.batch_id not in (".rtrim($request->batch_id,',').")
                                GROUP BY stocks.product_id,stocks.lot_no,stocks.brand");
            }else{
                $product=DB::select("SELECT products.id,products.product_name,products.bar_code,stocks.lot_no,
                                stocks.unit_price,stocks.batch_id,stocks.brand,
                                sum(stocks.quantity_bag) as bag_qty,
                                sum(stocks.quantity) as qty FROM `products`
                                JOIN stocks on stocks.product_id=products.id
                                JOIN purchase_details on purchase_details.product_id=products.id
                                WHERE stocks.company_id=".company()['company_id']."
                                and stocks.branch_id=".$request->branch_id." 
                                and stocks.warehouse_id=".$request->warehouse_id." 
                                and (stocks.lot_no like '%". $request->name ."%' 
                                or stocks.brand like '%". $request->name ."%' or 
                                products.product_name like '%". $request->name ."%') 
                                and (stocks.batch_id is not null or stocks.batch_id != '') 
                                and purchase_details.regular_purchase_id in ($regular_purchase_id)
                                GROUP BY stocks.product_id,stocks.lot_no,stocks.brand");
            }
            
            print_r(json_encode($product));  
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
            $pur= new Regular_purchase_return;
            $pur->supplier_id=$request->supplierName;
            $pur->voucher_no='VR-'.Carbon::now()->format('m-y').'-'. str_pad((Regular_purchase_return::whereYear('created_at', Carbon::now()->year)->count() + 1),4,"0",STR_PAD_LEFT);
            $pur->return_date = date('Y-m-d', strtotime($request->return_date));
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
                        $pd=new Regular_purchase_return_detail;
                        $pd->company_id=company()['company_id'];
                        $pd->purchase_return_id=$pur->id;
                        $pd->product_id=$product_id;
                        $pd->lot_no=$request->lot_no[$i];
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
                            $stock->regular_purchase_return_id=$pur->id;
                            $stock->product_id=$product_id;
                            $stock->company_id=company()['company_id'];
                            $stock->branch_id=$request->branch_id;
                            $stock->warehouse_id=$request->warehouse_id;
                            $stock->lot_no=$pd->lot_no;
                            $stock->brand=$pd->brand;
                            $stock->quantity='-'.$pd->actual_quantity;
                            $stock->batch_id= $pd->batch_id;
                            $stock->unit_price=$pd->rate_kg;
                            $stock->quantity_bag='-'.$pd->quantity_bag;
                            $stock->total_amount=$pd->amount;
                            $stock->stock_date=$pur->return_date;
                            $stock->save();
                        }
                    }
                }
                
                DB::commit();
                
                return redirect()->route(currentUser().'.regularReturn.index')->with($this->resMessageHtml(true,null,'Successfully created'));
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
     * @param  \App\Models\Return\Regular_purchase_return  $regular_purchase_return
     * @return \Illuminate\Http\Response
     */
    public function show(Regular_purchase_return $regular_purchase_return)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Return\Regular_purchase_return  $regular_purchase_return
     * @return \Illuminate\Http\Response
     */
    public function edit(Regular_purchase_return $regular_purchase_return)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Return\Regular_purchase_return  $regular_purchase_return
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Regular_purchase_return $regular_purchase_return)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Return\Regular_purchase_return  $regular_purchase_return
     * @return \Illuminate\Http\Response
     */
    public function destroy(Regular_purchase_return $regular_purchase_return)
    {
        //
    }
}

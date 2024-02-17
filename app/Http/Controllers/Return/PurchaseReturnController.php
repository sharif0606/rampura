<?php

namespace App\Http\Controllers\Return;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Child_one;
use App\Models\Accounts\Child_two;
use App\Models\Purchases\Beparian_purchase;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\Regular_purchase;
use App\Models\Return\Purchase_return;
use App\Models\Settings\Branch;
use App\Models\Settings\Warehouse;
use App\Models\Suppliers\Supplier;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurchaseReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $suppliers= Supplier::where(company())->get();
        //if( currentUser()=='owner')
            $purchases = Purchase_return::with('purchase_lot','supplier','warehouse','createdBy','updatedBy')->where(company());
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
        
        return view('purchaseReturn.index',compact('purchases','suppliers'));
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
        
        return view('purchaseReturn.create',compact('branches','suppliers','Warehouses','childTow','paymethod'));
        
    }

    public function product_search(Request $request)
    {
        if($request->name){
            $purchase_id = Purchase::where(company())->where('supplier_id',$request->supplier_id)->pluck('id')->toArray();
            if($purchase_id){
                $purchase_id=implode(',',$purchase_id);
            }else{
                $purchase_id="";
            }
            $beparian_purchase_id = Beparian_purchase::where(company())->where('supplier_id',$request->supplier_id)->pluck('id')->toArray();
            if($beparian_purchase_id){
                $beparian_purchase_id=implode(',',$beparian_purchase_id);
            }else{
                $beparian_purchase_id=0;
            }
            $regular_purchase_id = Regular_purchase::where(company())->where('supplier_id',$request->supplier_id)->pluck('id')->toArray();
            if($regular_purchase_id){
                $regular_purchase_id=implode(',',$regular_purchase_id);
            }else{
                $regular_purchase_id=0;
            }
           
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
                                and ( purchase_details.purchase_id in ($purchase_id) or
                                        purchase_details.beparian_purchase_id in ($beparian_purchase_id) or
                                        purchase_details.regular_purchase_id in ($regular_purchase_id))
                                GROUP BY stocks.product_id,stocks.lot_no,stocks.brand");
            
            print_r(json_encode($product));  
        }
        
    }
    public function product_sc_d(Request $request){
        if($request->item_id){
            list($item_id,$lot_no,$brand,$batch_id)=explode("^",$request->item_id);
            $product=collect(DB::select("SELECT products.id,products.product_name,products.bar_code,stocks.lot_no,stocks.unit_price,sum(stocks.quantity_bag) as bag_qty, sum(stocks.quantity) as qty, stocks.brand FROM `products` JOIN stocks on stocks.product_id=products.id WHERE stocks.company_id=".company()['company_id']." and stocks.branch_id=".$request->branch_id." and stocks.warehouse_id=".$request->warehouse_id." and stocks.product_id=".$item_id." and stocks.lot_no='".$lot_no."' and stocks.brand='".$brand."' and stocks.batch_id='".$batch_id."' and stocks.deleted_at is null GROUP BY stocks.batch_id"))->first();

            $data='<tr class="productlist text-center">';
            $data.='<td class="py-2 px-1">'.$product->product_name.'<input name="product_id[]" type="hidden" value="'.$product->id.'" class="product_id_list"><input name="stockqty[]" type="hidden" value="'.$product->qty.'" class="stockqty"><input name="batch_id[]" type="hidden" value="'.$batch_id.'" class="batch_id_list"></td>';
            $data.='<td class="py-2 px-1"><input readonly name="lot_no[]" type="text" class="form-control lot_no" value="'.$product->lot_no.'"></td>';
            $data.='<td class="py-2 px-1"><input readonly name="brand[]" type="text" class="form-control brand"  value="'.$product->brand.'"></td>';
            $data.='<td class="py-2 px-1"><input  type="text" class="form-control stock_bag" value="'.$product->bag_qty.'" disabled></td>';
            $data.='<td class="py-2 px-1"><input  type="text" class="form-control" value="'.$product->qty.'" disabled></td>';
            $data.='<td class="py-2 px-1"><input onkeyup="get_cal(this)" name="qty_kg[]" type="text" class="form-control qty_kg" value="0"></td>';
            $data.='<td class="py-2 px-1"><input onkeyup="get_cal(this)" name="less_qty_kg[]" type="text" class="form-control less_qty_kg" value="0"></td>';
            $data.='<td class="py-2 px-1"><input onkeyup="get_cal(this)" name="actual_qty[]" readonly type="text" class="form-control actual_qty" value="0"></td>';
            $data.='<td class="py-2 px-1"><input onkeyup="get_cal(this)" name="rate_in_kg[]" type="text" class="form-control rate_in_kg" value="0" required></td>';
            $data.='<td class="py-2 px-1"><input name="amount[]" readonly type="text" class="form-control amount" value="0" required></td>';
            $data.='<td class="py-2 px-1 text-danger"><i style="font-size:1.7rem" onclick="removerow(this)" class="bi bi-dash-circle-fill"></i></td>';
            $data.='</tr>';

            print_r(json_encode($data));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Return\Purchase_return  $purchase_return
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase_return $purchase_return)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Return\Purchase_return  $purchase_return
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase_return $purchase_return)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Return\Purchase_return  $purchase_return
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase_return $purchase_return)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Return\Purchase_return  $purchase_return
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase_return $purchase_return)
    {
        //
    }
}

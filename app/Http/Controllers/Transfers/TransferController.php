<?php

namespace App\Http\Controllers\Transfers;

use App\Http\Controllers\Controller;

use App\Models\Transfers\Transfer;
use App\Models\Transfers\Transfer_detail;
use App\Models\Stock\Stock;
use App\Models\Settings\Branch;
use App\Models\Settings\Warehouse;
use App\Models\Settings\Company;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Exception;

class TransferController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $transfers = Transfer::where(company())->get();
        return view('transfer.index',compact('transfers'));
    }


    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = Branch::where(company())->get();
        if( currentUser()=='owner'){
            $transfer = Transfer::where(company())->get();
            $warehouses = Warehouse::where(company())->get();
        }else{
            $transfer = Transfer::where(company())->where(branch())->get();
            $warehouses = Warehouse::where(company())->where(branch())->get();
        }
        
        return view('transfer.create',compact('transfer','branches','warehouses'));
    }



    public function product_scr(Request $request)
    {
        if($request->name){
            if($request->oldpro)
                $product=DB::select("SELECT products.id,products.price,products.product_name,products.bar_code,sum(stocks.quantity) as qty FROM `products` JOIN stocks on stocks.product_id=products.id WHERE stocks.company_id=".company()['company_id']." and stocks.branch_id=".$request->branch_id." and stocks.warehouse_id=".$request->warehouse_id." and (products.product_name like '%". $request->name ."%' or products.bar_code like '%". $request->name ."%') and products.id not in (".rtrim($request->oldpro,',').") GROUP BY products.id");
            else
                $product=DB::select("SELECT products.id,products.price,products.product_name,products.bar_code,sum(stocks.quantity) as qty FROM `products` JOIN stocks on stocks.product_id=products.id WHERE stocks.company_id=".company()['company_id']." and stocks.branch_id=".$request->branch_id." and stocks.warehouse_id=".$request->warehouse_id." and (products.product_name like '%". $request->name ."%' or products.bar_code like '%". $request->name ."%') GROUP BY products.id");
            
            print_r(json_encode($product));  
        }
        
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function product_scr_d(Request $request)
    {
        if($request->item_id){
            $product=collect(DB::select("SELECT products.id,products.price,products.product_name,products.bar_code,sum(stocks.quantity) as qty FROM `products` JOIN stocks on stocks.product_id=products.id WHERE stocks.company_id=".company()['company_id']." and stocks.branch_id=".$request->branch_id." and stocks.warehouse_id=".$request->warehouse_id." and products.id=".$request->item_id." GROUP BY products.id"))->first();
            
            $data='<tr class="productlist">';
            $data.='<td class="p-2">'.$product->product_name.'<input name="product_id[]" type="hidden" value="'.$product->id.'" class="product_id_list"><input name="stockqty[]" type="hidden" value="'.$product->qty.'" class="stockqty"></td>';
            $data.='<td class="p-2"><input onkeyup="get_cal(this)" name="qty[]" type="text" class="form-control qty" value="0"></td>';
            $data.='<td class="p-2"><input onkeyup="get_cal(this)" name="price[]" type="text" class="form-control price" value="'.$product->price.'"></td>';
            $data.='<td class="p-2"><input onkeyup="get_cal(this)" name="tax[]" type="text" class="form-control tax" value=""></td>';
            $data.='<td class="p-2">
                        <select onchange="get_cal(this)" class="form-control form-select mt-2 discount_type" name="discount_type[]">
                            <option value="0">%</option>
                            <option value="1">Fixed</option>
                        </select>
                    </td>';
            $data.='<td class="p-2"><input onkeyup="get_cal(this)" name="discount[]" type="text" class="form-control discount" value="0"></td>';
            $data.='<td class="p-2"><input name="unit_cost[]" readonly type="text" class="form-control unit_cost" value="0"></td>';
            $data.='<td class="p-2"><input name="subtotal[]" readonly type="text" class="form-control subtotal" value="0"></td>';
            $data.='<td class="p-2 text-danger"><i style="font-size:1.7rem" onclick="removerow(this)" class="bi bi-dash-circle-fill"></i></td>';
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
        DB::beginTransaction();
        try{
            $pur= new Transfer;
            $pur->transfer_date=$request->transfer_date;
            $pur->quantity=$request->total_qty;
            $pur->company_id=company()['company_id'];
            $pur->branch_id=$request->branch_id;
            $pur->warehouse_form=$request->warehouse_from;
            $pur->warehouse_to=$request->warehouse_to;
            $pur->created_by=currentUserId();
            if($pur->save()){
                if($request->product_id){
                    foreach($request->product_id as $i=>$product_id){
                        $pd=new Transfer_detail;
                        $pd->transfer_id=$pur->id;
                        $pd->product_id=$product_id;
                        $pd->quantity=$request->qty[$i];
                        $pd->unit_price=$request->price[$i];
                        $pd->tax=$request->tax[$i];
                        $pd->discount=$request->discount[$i];
                        $pd->sub_amount=$request->unit_cost[$i];
                        $pd->total_amount=$request->subtotal[$i];
                        if($pd->save()){
                            /* fromw warehouse stock out */
                            $stock=new Stock;
                            $stock->product_id=$product_id;
                            $stock->transfer_id =$pur->id;
                            $stock->company_id=company()['company_id'];
                            $stock->branch_id=$request->branch_id;
                            $stock->warehouse_id=$request->warehouse_from;
                            $stock->quantity='-'.$pd->quantity;
                            $stock->unit_price=$request->price[$i];
                            $stock->tax=$pd->tax;
                            $stock->discount=$pd->discount;
                            $stock->save();
                            /* to warehouse stock in*/
                            $stockt=new Stock;
                            $stockt->product_id=$product_id;
                            $stockt->transfer_id =$pur->id;
                            $stockt->company_id=company()['company_id'];
                            $stockt->branch_id=$request->branch_id;
                            $stockt->warehouse_id=$request->warehouse_to;
                            $stockt->quantity=$pd->quantity;
                            $stockt->unit_price=$request->price[$i];
                            $stockt->tax=$pd->tax;
                            $stockt->discount=$pd->discount;
                            $stockt->save();

                            DB::commit();
                        }

                    }

                }
                
                return redirect()->route(currentUser().'.transfer.index')->with($this->resMessageHtml(true,null,'Successfully created'));
            }else
                return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }catch(Exception $e){
            DB::rollback();
            // dd($e);
            return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function show(Transfer $transfer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function edit(Transfer $transfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transfer $transfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transfer $transfer)
    {
        //
    }
}

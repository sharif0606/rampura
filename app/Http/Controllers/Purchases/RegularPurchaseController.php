<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;

use App\Models\Purchases\Regular_purchase;
use App\Models\Purchases\Purchase_details;
use App\Models\Stock\Stock;
use App\Models\Suppliers\Supplier;
use App\Models\Products\Product;
use App\Models\Settings\Branch;
use App\Models\Settings\Warehouse;
use App\Models\Settings\Company;
use Illuminate\Http\Request;
use App\Http\Requests\Purchases\AddNewRequest;
use App\Http\Requests\Purchases\UpdateRequest;
use App\Http\Traits\ResponseTrait;
use Exception;
use DB;
use Carbon\Carbon;

class RegularPurchaseController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( currentUser()=='owner')
            $purchases = Regular_purchase::where(company())->paginate(10);
        else
            $purchases = Regular_purchase::where(company())->where(branch())->paginate(10);
            
        
        return view('regularPurchase.index',compact('purchases'));
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
            $suppliers = Supplier::where(company())->get();
            $Warehouses = Warehouse::where(company())->get();
        }else{
            $suppliers = Supplier::where(company())->where(branch())->get();
            $Warehouses = Warehouse::where(company())->where(branch())->get();
        }
        
        return view('regularPurchase.create',compact('branches','suppliers','Warehouses'));
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
            $pur= new Regular_purchase;
            $pur->supplier_id=$request->supplierName;
            $pur->voucher_no='VR-'.Carbon::now()->format('m-y').'-'. str_pad((Regular_purchase::whereYear('created_at', Carbon::now()->year)->count() + 1),4,"0",STR_PAD_LEFT);
            $pur->purchase_date = date('Y-m-d', strtotime($request->purchase_date));
            $pur->reference_no=$request->reference_no;
            $pur->grand_total=$request->tgrandtotal;
            $pur->company_id=company()['company_id'];
            $pur->branch_id=$request->branch_id;
            $pur->warehouse_id=$request->warehouse_id;
            $pur->created_by=currentUserId();

            $pur->payment_status=0;
            $pur->status=1;
            if($pur->save()){
                if($request->product_id){
                    foreach($request->product_id as $i=>$product_id){
                        $pd=new Purchase_details;
                        $pd->regular_purchase_id=$pur->id;
                        $pd->product_id=$product_id;
                        $pd->lot_no=$request->lot_no[$i];
                        $pd->brand=$request->brand[$i];
                        $pd->quantity_bag=$request->qty_bag[$i];
                        $pd->quantity_kg=$request->qty_kg[$i];
                        $pd->discount=$request->discount[$i];
                        $pd->less_quantity_kg=$request->less_qty_kg[$i];
                        $pd->actual_quantity=$request->actual_qty[$i];
                        $pd->rate_kg=$request->rate_in_kg[$i];
                        $pd->amount=$request->amount[$i];
                        $pd->purchase_commission=$request->purchase_commission[$i];
                        $pd->transport_cost=$request->transport_cost[$i];
                        $pd->unloading_cost=$request->unloading_cost[$i];
                        $pd->sale_income_per_bag=$request->sales_income_per_bag[$i];
                        $pd->total_amount=$request->total_amount[$i];
                        if($pd->save()){
                            $oldstock = Stock::where('unit_price',$pd->rate_kg)->where('product_id',$product_id)->where('branch_id',$request->branch_id)->where('warehouse_id',$request->warehouse_id)->where('lot_no',$pd->lot_no)->where('brand',$pd->brand)->where(company())->pluck('batch_id');
                            if(count($oldstock)> 0){
                                $batch_id=$oldstock[0];
                                //DB::table('stocks')->where('id',$oldstock[0])->increment('quantity', $pd->actual_quantity);
                                //DB::table('stocks')->where('id',$oldstock[0])->increment('quantity_bag', $pd->quantity_bag);
                            }else{
                                $batch_id=rand(111,999).uniqid().$product_id;
                            }
                                $stock=new Stock;
                                $stock->regular_purchase_id=$pur->id;
                                $stock->product_id=$product_id;
                                $stock->company_id=company()['company_id'];
                                $stock->branch_id=$request->branch_id;
                                $stock->warehouse_id=$request->warehouse_id;
                                $stock->lot_no=$pd->lot_no;
                                $stock->brand=$pd->brand;
                                $stock->quantity=$pd->actual_quantity + $pd->discount;
                                $stock->discount=$pd->discount;
                                $stock->batch_id=$batch_id;
                                $stock->unit_price=$pd->rate_kg;
                                $stock->quantity_bag=$pd->quantity_bag;
                                $stock->total_amount=$pd->total_amount;
                                $stock->save();
                            
                            DB::commit();
                        }

                    }
                }
                
                return redirect()->route(currentUser().'.rpurchase.index')->with($this->resMessageHtml(true,null,'Successfully created'));
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
     * @param  \App\Models\Purchases\regular_purchase  $regular_purchase
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $show_data= Regular_purchase::findOrFail(encryptor('decrypt',$id));
        $purDetail= Purchase_details::where('regular_purchase_id',$show_data->id)->get();
        return view('regularPurchase.show',compact('show_data','purDetail'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchases\regular_purchase  $regular_purchase
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branches = Branch::where(company())->get();
        if( currentUser()=='owner'){
            $suppliers = Supplier::where(company())->get();
            $Warehouses = Warehouse::where(company())->get();
            $purchase = Regular_purchase::findOrFail(encryptor('decrypt',$id));
            $purchaseDetails = Purchase_details::where('regular_purchase_id',$purchase->id)->get();
        }else{
            $suppliers = Supplier::where(company())->where(branch())->get();
            $Warehouses = Warehouse::where(company())->where(branch())->get();
        }
        
        return view('regularPurchase.edit',compact('branches','suppliers','Warehouses','purchase','purchaseDetails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchases\regular_purchase  $regular_purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $pur= Regular_purchase::findOrFail(encryptor('decrypt',$id));
            $pur->supplier_id=$request->supplierName;
            $pur->purchase_date = date('Y-m-d', strtotime($request->purchase_date));
            $pur->reference_no=$request->reference_no;
            $pur->grand_total=$request->tgrandtotal;
            $pur->company_id=company()['company_id'];
            $pur->branch_id=$request->branch_id;
            $pur->warehouse_id=$request->warehouse_id;
            $pur->created_by=currentUserId();

            $pur->payment_status=0;
            $pur->status=1;
            if($pur->save()){
                if($request->product_id){
                    Purchase_details::where('regular_purchase_id',$pur->id)->delete();
                    Stock::where('regular_purchase_id',$pur->id)->delete();
                    foreach($request->product_id as $i=>$product_id){
                        if($request->lot_no[$i]>0){
                            $pd=new Purchase_details;
                            $pd->regular_purchase_id=$pur->id;
                            $pd->product_id=$product_id;
                            $pd->lot_no=$request->lot_no[$i];
                            $pd->brand=$request->brand[$i];
                            $pd->quantity_bag=$request->qty_bag[$i];
                            $pd->quantity_kg=$request->qty_kg[$i];
                            $pd->discount=$request->discount[$i];
                            $pd->less_quantity_kg=$request->less_qty_kg[$i];
                            $pd->actual_quantity=$request->actual_qty[$i];
                            $pd->rate_kg=$request->rate_in_kg[$i];
                            $pd->amount=$request->amount[$i];
                            $pd->purchase_commission=$request->purchase_commission[$i];
                            $pd->transport_cost=$request->transport_cost[$i];
                            $pd->unloading_cost=$request->unloading_cost[$i];
                            $pd->sale_income_per_bag=$request->sales_income_per_bag[$i];
                            $pd->total_amount=$request->total_amount[$i];
                            if($pd->save()){
                                $oldstock = Stock::where('unit_price',$pd->rate_kg)->where('product_id',$product_id)->where('branch_id',$request->branch_id)->where('warehouse_id',$request->warehouse_id)->where('lot_no',$pd->lot_no)->where('brand',$pd->brand)->where(company())->pluck('batch_id');
                                if(count($oldstock)> 0){
                                    $batch_id=$oldstock[0];
                                    //DB::table('stocks')->where('id',$oldstock[0])->increment('quantity', $pd->actual_quantity);
                                    //DB::table('stocks')->where('id',$oldstock[0])->increment('quantity_bag', $pd->quantity_bag);
                                }else{
                                    $batch_id=rand(111,999).uniqid().$product_id;
                                }
                                    $stock=new Stock;
                                    $stock->regular_purchase_id=$pur->id;
                                    $stock->product_id=$product_id;
                                    $stock->company_id=company()['company_id'];
                                    $stock->branch_id=$request->branch_id;
                                    $stock->warehouse_id=$request->warehouse_id;
                                    $stock->lot_no=$pd->lot_no;
                                    $stock->brand=$pd->brand;
                                    $stock->quantity=$pd->actual_quantity + $pd->discount;
                                    $stock->discount=$pd->discount;
                                    $stock->batch_id=$batch_id;
                                    $stock->unit_price=$pd->rate_kg;
                                    $stock->quantity_bag=$pd->quantity_bag;
                                    $stock->total_amount=$pd->total_amount;
                                    $stock->save();
                                
                                DB::commit();
                            }
                        }
                    }
                }
                
                return redirect()->route(currentUser().'.rpurchase.index')->with($this->resMessageHtml(true,null,'Successfully Update'));
            }else
                return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }catch(Exception $e){
            DB::rollback();
            // dd($e);
            return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchases\regular_purchase  $regular_purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(regular_purchase $regular_purchase)
    {
        //
    }
}

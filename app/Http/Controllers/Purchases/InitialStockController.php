<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Models\Products\LcNumber;
use App\Models\Settings\Branch;
use App\Models\Settings\Warehouse;
use App\Models\Stock\InitialStock;
use App\Models\Stock\InitialStockDetail;
use App\Models\Stock\Stock;
use App\Models\Suppliers\Supplier;
use App\Models\Vouchers\GeneralLedger;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\ResponseTrait;
use Carbon\Carbon;

class InitialStockController extends Controller
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
        $purchases = InitialStock::with('purchase_lot','supplier','warehouse','createdBy','updatedBy')->where(company());
        if($request->nane)
            $purchases=$purchases->where('supplier_id','like','%'.$request->nane.'%');
        
        if($request->lot_no){
            $lotno=$request->lot_no;
            $purchases=$purchases->whereHas('purchase_lot', function($q) use ($lotno){
                $q->where('lot_no', $lotno);
            });
        }
        $purchases=$purchases->orderBy('id', 'DESC')->paginate(12);
        
        return view('InitialStock.index',compact('purchases','suppliers'));
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
        return view('InitialStock.create',compact('branches','suppliers','Warehouses'));
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
            $pur= new InitialStock;
            $pur->supplier_id=$request->supplierName;
            $pur->voucher_no='VR-'.Carbon::now()->format('m-y').'-'. str_pad((InitialStock::whereYear('created_at', Carbon::now()->year)->count() + 1),4,"0",STR_PAD_LEFT);
            $pur->initial_stock_date = date('Y-m-d', strtotime($request->purchase_date));
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
                        $pd=new InitialStockDetail;
                        $pd->company_id=company()['company_id'];
                        $pd->initial_stock_id=$pur->id;
                        $pd->product_id=$product_id;
                        $pd->lot_no=$request->lot_no[$i];
                        $pd->brand=$request->brand[$i];
                        $pd->quantity_bag=$request->qty_bag[$i];
                        $pd->quantity_kg=$request->qty_kg[$i];
                        $pd->less_quantity_kg=$request->less_qty_kg[$i];
                        $pd->actual_quantity=$request->actual_qty[$i];
                        $pd->rate_kg=$request->rate_in_kg[$i];
                        $pd->amount=$request->amount[$i];
                        if($pd->save()){
                            $stock=new Stock;
                            $stock->initial_stock_id=$pur->id;
                            $stock->product_id=$product_id;
                            $stock->company_id=company()['company_id'];
                            $stock->branch_id=$request->branch_id;
                            $stock->warehouse_id=$request->warehouse_id;
                            $stock->lot_no=$pd->lot_no;
                            $stock->brand=$pd->brand;
                            $stock->quantity=$pd->actual_quantity;
                            $stock->batch_id= rand(111,999).uniqid().$product_id;
                            $stock->unit_price=$pd->rate_kg;
                            $stock->quantity_bag=$pd->quantity_bag;
                            $stock->total_amount=$pd->amount;
                            $stock->stock_date=$pur->purchase_date;
                            $stock->save();
                        }
                    }
                }

                if($request->lot_no){
                    foreach($request->lot_no as $i=>$lclotno){
                        $oldlc=LcNumber::where('product_id',$request->product_id[$i])->where('lot_no',$lclotno)->first();
                        if(!$oldlc){
                            $newlc=new LcNumber;
                            $newlc->product_id=$request->product_id[$i];
                            $newlc->company_id= company()['company_id'];
                            $newlc->lot_name=$lclotno;
                            $newlc->lot_no=$lclotno;
                            $newlc->billable=1;
                            $newlc->save();
                        }
                    }
                }
                DB::commit();
                
                return redirect()->route(currentUser().'.initialStock.index')->with($this->resMessageHtml(true,null,'Successfully created'));
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
     * @param  \App\Models\Stock\InitialStock  $initialStock
     * @return \Illuminate\Http\Response
     */
    public function show(InitialStock $initialStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stock\InitialStock  $initialStock
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branches = Branch::where(company())->get();
        $suppliers = Supplier::where(company())->get();
        $Warehouses = Warehouse::where(company())->get();

        $purchase = InitialStock::findOrFail(encryptor('decrypt',$id));
        $purchaseDetails = InitialStockDetail::where('initial_stock_id',$purchase->id)->get();
        
        return view('InitialStock.edit',compact('branches','suppliers','Warehouses','purchase','purchaseDetails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stock\InitialStock  $initialStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $pur= InitialStock::findOrFail(encryptor('decrypt',$id));
            $pur->supplier_id=$request->supplierName;
            $pur->voucher_no='VR-'.Carbon::now()->format('m-y').'-'. str_pad((InitialStock::whereYear('created_at', Carbon::now()->year)->count() + 1),4,"0",STR_PAD_LEFT);
            $pur->initial_stock_date = date('Y-m-d', strtotime($request->purchase_date));
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
                    InitialStockDetail::where('initial_stock_id',$pur->id)->delete();
                    Stock::where('initial_stock_id',$pur->id)->delete();
                    foreach($request->product_id as $i=>$product_id){
                        $pd=new InitialStockDetail;
                        $pd->company_id=company()['company_id'];
                        $pd->initial_stock_id=$pur->id;
                        $pd->product_id=$product_id;
                        $pd->lot_no=$request->lot_no[$i];
                        $pd->brand=$request->brand[$i];
                        $pd->quantity_bag=$request->qty_bag[$i];
                        $pd->quantity_kg=$request->qty_kg[$i];
                        $pd->less_quantity_kg=$request->less_qty_kg[$i];
                        $pd->actual_quantity=$request->actual_qty[$i];
                        $pd->rate_kg=$request->rate_in_kg[$i];
                        $pd->amount=$request->amount[$i];
                        if($pd->save()){
                            $stock=new Stock;
                            $stock->initial_stock_id=$pur->id;
                            $stock->product_id=$product_id;
                            $stock->company_id=company()['company_id'];
                            $stock->branch_id=$request->branch_id;
                            $stock->warehouse_id=$request->warehouse_id;
                            $stock->lot_no=$pd->lot_no;
                            $stock->brand=$pd->brand;
                            $stock->quantity=$pd->actual_quantity;
                            $stock->batch_id= rand(111,999).uniqid().$product_id;
                            $stock->unit_price=$pd->rate_kg;
                            $stock->quantity_bag=$pd->quantity_bag;
                            $stock->total_amount=$pd->amount;
                            $stock->stock_date=$pur->purchase_date;
                            $stock->save();
                        }
                    }
                }

                if($request->lot_no){
                    foreach($request->lot_no as $i=>$lclotno){
                        $oldlc=LcNumber::where('product_id',$request->product_id[$i])->where('lot_no',$lclotno)->first();
                        if(!$oldlc){
                            $newlc=new LcNumber;
                            $newlc->product_id=$request->product_id[$i];
                            $newlc->company_id= company()['company_id'];
                            $newlc->lot_name=$lclotno;
                            $newlc->lot_no=$lclotno;
                            $newlc->billable=1;
                            $newlc->save();
                        }
                    }
                }
                DB::commit();
                
                return redirect()->route(currentUser().'.initialStock.index')->with($this->resMessageHtml(true,null,'Successfully Updated'));
            }else
                return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }catch(Exception $e){
            DB::rollback();
             dd($e);
            return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stock\InitialStock  $initialStock
     * @return \Illuminate\Http\Response
     */
    public function destroy(InitialStock $initialStock)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Return;
use App\Http\Controllers\Controller;

use App\Models\Return\Sale_return;
use App\Models\Return\Sale_return_detail;
use App\Models\Accounts\Child_one;
use App\Models\Accounts\Child_two;
use App\Models\Settings\Branch;
use App\Models\Settings\Warehouse;
use App\Models\Stock\Stock;
use App\Models\Customers\Customer;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaleReturnController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customers = Customer::where(company())->get();
        $sales = Sales::where(company());
        $sales = Sales::with('sale_lot','customer','warehouse','createdBy','updatedBy')->where(company());

        if($request->nane)
            $sales=$sales->where('customer_id','like','%'.$request->nane.'%');
        if($request->sales_date)
            $sales=$sales->where('sales_date',$request->sales_date);

        if($request->lot_no){
            $lotno=$request->lot_no;
            $sales=$sales->whereHas('sale_lot', function($q) use ($lotno){
                $q->where('lot_no', $lotno);
            });
        }

        $sales=$sales->orderBy('id', 'DESC')->paginate(12);

        return view('sales.index',compact('sales','customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Models\Return\Sale_return  $sale_return
     * @return \Illuminate\Http\Response
     */
    public function show(Sale_return $sale_return)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Return\Sale_return  $sale_return
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale_return $sale_return)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Return\Sale_return  $sale_return
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sale_return $sale_return)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Return\Sale_return  $sale_return
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale_return $sale_return)
    {
        //
    }
}

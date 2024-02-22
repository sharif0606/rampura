<?php

namespace App\Http\Controllers\Return;
use App\Http\Controllers\Controller;

use App\Models\Return\Beparian_purchase_return;
use App\Models\Accounts\Child_one;
use App\Models\Accounts\Child_two;
use App\Models\Purchases\Beparian_purchase;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\Regular_purchase;
use App\Models\Return\Purchase_return;
use App\Models\Return\Purchase_return_details;
use App\Models\Settings\Branch;
use App\Models\Settings\Warehouse;
use App\Models\Stock\Stock;
use App\Models\Suppliers\Supplier;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BeparianPurchaseReturnController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\Return\Beparian_purchase_return  $beparian_purchase_return
     * @return \Illuminate\Http\Response
     */
    public function show(Beparian_purchase_return $beparian_purchase_return)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Return\Beparian_purchase_return  $beparian_purchase_return
     * @return \Illuminate\Http\Response
     */
    public function edit(Beparian_purchase_return $beparian_purchase_return)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Return\Beparian_purchase_return  $beparian_purchase_return
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Beparian_purchase_return $beparian_purchase_return)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Return\Beparian_purchase_return  $beparian_purchase_return
     * @return \Illuminate\Http\Response
     */
    public function destroy(Beparian_purchase_return $beparian_purchase_return)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Vouchers;

use App\Models\Vouchers\PurchaseReturnVoucher;
use App\Models\Vouchers\PurReturnVoucherBkdn;
use Illuminate\Http\Request;
use App\Models\Vouchers\GeneralLedger;
use App\Models\Expenses\ExpenseOfPurchase;
use App\Models\Accounts\Child_one;
use App\Models\Accounts\Child_two;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Session;
use Exception;

class PurchaseReturnVoucherController extends VoucherController
{
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
     * @param  \App\Models\Vouchers\PurchaseReturnVoucher  $purchaseReturnVoucher
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseReturnVoucher $purchaseReturnVoucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vouchers\PurchaseReturnVoucher  $purchaseReturnVoucher
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseReturnVoucher $purchaseReturnVoucher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vouchers\PurchaseReturnVoucher  $purchaseReturnVoucher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseReturnVoucher $purchaseReturnVoucher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vouchers\PurchaseReturnVoucher  $purchaseReturnVoucher
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseReturnVoucher $purchaseReturnVoucher)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Vouchers;

use App\Models\Vouchers\SaleReturnVoucher;
use App\Models\Vouchers\SalReturnVoucherBkdn;
use App\Models\Vouchers\GeneralLedger;
use App\Models\Expenses\ExpenseOfPurchase;
use App\Models\Accounts\Child_one;
use App\Models\Accounts\Child_two;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Session;
use Exception;

class SaleReturnVoucherController extends VoucherController
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
     * @param  \App\Models\Vouchers\SaleReturnVoucher  $saleReturnVoucher
     * @return \Illuminate\Http\Response
     */
    public function show(SaleReturnVoucher $saleReturnVoucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vouchers\SaleReturnVoucher  $saleReturnVoucher
     * @return \Illuminate\Http\Response
     */
    public function edit(SaleReturnVoucher $saleReturnVoucher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vouchers\SaleReturnVoucher  $saleReturnVoucher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleReturnVoucher $saleReturnVoucher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vouchers\SaleReturnVoucher  $saleReturnVoucher
     * @return \Illuminate\Http\Response
     */
    public function destroy(SaleReturnVoucher $saleReturnVoucher)
    {
        //
    }
}

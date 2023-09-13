<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;


use App\Models\Purchases\Purchase;
use App\Models\Sales\Sales_details;
use App\Models\Sales\Sales;
use App\Models\Stock\Stock;
use App\Models\Suppliers\Supplier;
use App\Models\Customers\Customer;
use App\Models\Expenses\ExpenseOfPurchase;
use App\Models\Expenses\ExpenseOfSales;
use App\Models\Purchases\Purchase_details;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function stockreport(Request $request)
    {
        $where=false;
        if($request->fdate){
            $tdate=$request->tdate?$request->tdate:$request->fdate;
            $where=" where (date(stocks.`created_at`) BETWEEN '".$request->fdate."' and '".$tdate."') ";
        }

        $stock= DB::select("SELECT products.product_name,stocks.*,sum(stocks.quantity) as qty,sum(stocks.quantity_bag) as bagQty, AVG(stocks.unit_price) as avunitprice FROM `stocks` join products on products.id=stocks.product_id $where GROUP BY stocks.lot_no,stocks.brand");
        return view('reports.stockReport',compact('stock'));
    }

   

    public function purchaseReport(Request $request)
    {
        // dd($request->all());
        $suppliers = Supplier::where(company())->get();

        $query = Purchase_details::join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
            ->groupBy('purchase_details.purchase_id')
            ->select('purchases.*', 'purchase_details.*')->where(company());

        if ($request->supplier) {
            $query->where('purchases.supplier_id', $request->supplier);
            // dd($query->toSql());
        }

        if ($request->fdate && $request->tdate) {
            $fdate = Carbon::parse($request->fdate)->toDateString();
            $tdate = Carbon::parse($request->tdate)->toDateString();
    
            $query->whereBetween(DB::raw('DATE(purchases.purchase_date)'), [$fdate, $tdate]);
            //  dd($query->toSql());
        }

        $data = $query->get();
        
        return view('reports.pview', compact('data', 'suppliers'));
    }

    public function salesReport(Request $request)
    {
        // dd($request->all());
        $customers = Customer::where(company())->get();

        $query = Sales_details::join('sales', 'sales.id', '=', 'sales_details.sales_id')
            ->groupBy('sales_details.sales_id')
            ->select('sales.*', 'sales_details.*')->where(company());

        if ($request->customer) {
            $query->where('sales.customer_id', $request->customer);
            // dd($query->toSql());
        }

        if ($request->fdate && $request->tdate) {
            $fdate = Carbon::parse($request->fdate)->toDateString();
            $tdate = Carbon::parse($request->tdate)->toDateString();
    
            $query->whereBetween(DB::raw('DATE(sales.sales_date)'), [$fdate, $tdate]);
            //  dd($query->toSql());
        }

        $data = $query->get();
        
        return view('reports.salesview', compact('data', 'customers'));
    }

    public function srota(Request $request)
    {
        return view('reports.srota');
    }
    public function srotaView(Request $request)
    {
        // dd($request->all());
        
        $lotNumber = $request->input('lot');
        $purchase = Purchase_details::where('lot_no',$lotNumber)->get();
        $sales = Sales_details::where('lot_no',$lotNumber)->get();
        $purExpense = ExpenseOfPurchase::where('lot_no',$lotNumber)->where('status',0)->get();
        $salExpense = ExpenseOfSales::where('lot_no',$lotNumber)->where('status',0)->get();
        
        return view('reports.srotaView', compact('purchase', 'sales','purExpense','salExpense'));
    }
    // public function srotaView(Request $request)
    // {
    //     // dd($request->all());
        
    //     $lotNumber = $request->input('lot');
    //     $purchase = Purchase_details::where('lot_no',$lotNumber)->get();
    //     $purExpense = ExpenseOfPurchase::where('lot_no',$lotNumber)->where('status',0)->get();
    //     $sales = Sales_details::where('lot_no',$lotNumber)->get();
    //     $firstSalesId = $sales->first()->sales_id;
    //     $salExpense = ExpenseOfSales::where('sales_id',$firstSalesId)->where('status',0)->get();
        
    //     return view('reports.srotaView', compact('purchase', 'sales','purExpense','salExpense'));
    // }

}
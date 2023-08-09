<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;


use App\Models\Purchases\Purchase;
use App\Models\Sales\Sales_details;
use App\Models\Sales\Sales;
use App\Models\Stock\Stock;
use App\Models\Suppliers\Supplier;
use App\Models\Customers\Customer;
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

        $stock= DB::select("SELECT products.product_name,stocks.*,sum(stocks.quantity) as qty,sum(stocks.quantity_bag) as bagQty, AVG(stocks.unit_price) as avunitprice FROM `stocks` join products on products.id=stocks.product_id $where GROUP BY stocks.lot_no");
        return view('reports.stockReport',compact('stock'));
    }

   

    public function salesReport(Request $request)
    {

        if ($request->fdate) {
            $tdate = $request->tdate ? $request->tdate : $request->fdate;
            
            // Convert the input dates to Carbon instances and format them to compare only dates.
            $fdate = Carbon::parse($request->fdate)->toDateString();
            $tdate = Carbon::parse($tdate)->toDateString();
        
            $data = Sales_details::join('sales', 'sales.id', '=', 'sales_details.sales_id')
                ->whereBetween(DB::raw('DATE(sales_details.created_at)'), [$fdate, $tdate])
                ->groupBy('sales_details.lot_no')
                ->select('sales.*', 'sales_details.*')
                ->get();
        } else {
            $data = Sales_details::join('sales', 'sales.id', '=', 'sales_details.sales_id')
                ->groupBy('sales_details.lot_no')
                ->select('sales.*', 'sales_details.*')
                ->get();
        }
        return view('reports.salesview',compact('data'));
    }
}
<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Child_one;
use App\Models\Accounts\Child_two;
use App\Models\Purchases\Purchase;
use App\Models\Sales\Sales_details;
use App\Models\Sales\Sales;
use App\Models\Stock\Stock;
use App\Models\Suppliers\Supplier;
use App\Models\Customers\Customer;
use App\Models\Expenses\ExpenseOfPurchase;
use App\Models\Expenses\ExpenseOfSales;
use App\Models\Products\Category;
use App\Models\Products\Product;
use App\Models\Purchases\Purchase_details;
use App\Models\Vouchers\GeneralLedger;
use Illuminate\Http\Request;
use App\Models\Settings\Company;
use DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    // public function stockreport(Request $request)
    // {
    //     $company = company()['company_id'];
    //     $where = '';
    
    //     if ($request->fdate) {
    //         $tdate = $request->tdate ? $request->tdate : $request->fdate;
    //         $where = " AND date(stocks.`created_at`) BETWEEN '" . $request->fdate . "' AND '" . $tdate . "'";
    //     }
    //     // $stock= DB::select("SELECT products.product_name,stocks.*,sum(stocks.quantity) as qty,sum(stocks.quantity_bag) as bagQty, AVG(stocks.unit_price) as avunitprice FROM `stocks` join products on products.id=stocks.product_id $where GROUP BY stocks.lot_no,stocks.brand");
    
    //     $sql = "SELECT products.product_name, stocks.*, SUM(stocks.quantity) as qty, SUM(stocks.quantity_bag) as bagQty, AVG(stocks.unit_price) as avunitprice 
    //             FROM stocks 
    //             JOIN products ON products.id = stocks.product_id 
    //             WHERE stocks.company_id = ? $where 
    //             GROUP BY stocks.lot_no, stocks.brand";
    
    //     $stock = DB::select($sql, [$company]);
    
    //     return view('reports.stockReport', compact('stock'));
    // }


    public function stockreport(Request $request)
    {
        $company = company()['company_id'];
        $category = Category::where(company())->get();
        $product = Product::where(company())->get();
        $where = '';
    
        if ($request->fdate) {
            $tdate = $request->tdate ? $request->tdate : $request->fdate;
            $where = " AND date(stocks.`created_at`) BETWEEN '" . $request->fdate . "' AND '" . $tdate . "'";
        }

        if ($request->category) {
            $where .= " AND products.category_id = '" . $request->category . "'";
        }

        if ($request->product) {
            $where .= " AND products.id = '" . $request->product . "'";
        }
        
        if ($request->lot_no) {
            $where .= " AND stocks.lot_no = '" . $request->lot_no . "'";
        }
    
        $sql = "SELECT products.*, stocks.*, SUM(stocks.quantity) as qty, SUM(stocks.quantity_bag) as bagQty, AVG(stocks.unit_price) as avunitprice 
                FROM stocks 
                JOIN products ON products.id = stocks.product_id 
                WHERE stocks.company_id = ? $where 
                GROUP BY stocks.lot_no, stocks.brand";
    
        $stock = DB::select($sql, [$company]);
    
        return view('reports.stockReport', compact('stock','product','category'));
    }

    public function stockindividual($id)
    {
        $company = company()['company_id'];
        $where = '';       
        $salesItem = Sales_details::where('product_id', $id)->where('company_id', $company)->get();
        $stock = Stock::where('product_id',$id)->where(company())->get();
        $product = Product::where('id',$id)->first();

        return view('reports.stockReportIndividual', compact('stock', 'salesItem','product'));
    }

    public function stockindividualByLot($lot_no)
    {
        $company = company()['company_id'];
        $where = '';     
        $salesItem = Sales_details::where('lot_no', $lot_no)->where('company_id', $company)->get();
        $stock = Stock::where('lot_no',$lot_no)->where(company())->get();
        $first = $stock->first()->product_id;
        $product = Product::where('id',$first)->where(company())->first();

        return view('reports.stockReportIndividualByLot', compact('stock', 'salesItem','product'));
    }


    public function allPurchaseReport(Request $request)
    {
        $company = company()['company_id'];
        $category = Category::where(company())->get();
        $product = Product::where(company())->get();
        $where = '';
    
        if ($request->fdate) {
            $tdate = $request->tdate ? $request->tdate : $request->fdate;
            $where = " AND date(purchase_details.`created_at`) BETWEEN '" . $request->fdate . "' AND '" . $tdate . "'";
        }

        if ($request->category) {
            $where .= " AND products.category_id = '" . $request->category . "'";
        }

        if ($request->product) {
            $where .= " AND products.id = '" . $request->product . "'";
        }
        
        if ($request->lot_no) {
            $where .= " AND purchase_details.lot_no = '" . $request->lot_no . "'";
        }
    
        $sql = "SELECT products.*, purchase_details.* 
                FROM purchase_details 
                JOIN products ON products.id = purchase_details.product_id 
                WHERE purchase_details.company_id = ? $where 
                GROUP BY purchase_details.id";
    
        $data = DB::select($sql, [$company]);
    
        return view('reports.allPview', compact('data','product','category'));
    }
   
    public function purchaseReport(Request $request)
    {
        // dd($request->all());
        $suppliers = Supplier::where(company())->get();

        $query = Purchase_details::join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
            ->groupBy('purchase_details.purchase_id')
            ->select('purchases.*', 'purchase_details.*')
            ->where('purchases.company_id', company()['company_id']);

        if ($request->supplier) {
            $query->where('purchases.supplier_id', $request->supplier);
            // dd($query->toSql());
        }
        if ($request->lc_no) {
            $query->where('purchase_details.lot_no', $request->lc_no);
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

    public function beparianPurchaseReport(Request $request)
    {
        // dd($request->all());
        $suppliers = Supplier::where(company())->get();

        $query = Purchase_details::join('beparian_purchases', 'beparian_purchases.id', '=', 'purchase_details.beparian_purchase_id')
            ->groupBy('purchase_details.beparian_purchase_id')
            ->select('beparian_purchases.*', 'purchase_details.*')
            ->where('beparian_purchases.company_id', company()['company_id']);

        if ($request->supplier) {
            $query->where('beparian_purchases.supplier_id', $request->supplier);
            // dd($query->toSql());
        }
        if ($request->lc_no) {
            $query->where('purchase_details.lot_no', $request->lc_no);
            // dd($query->toSql());
        }

        if ($request->fdate && $request->tdate) {
            $fdate = Carbon::parse($request->fdate)->toDateString();
            $tdate = Carbon::parse($request->tdate)->toDateString();
    
            $query->whereBetween(DB::raw('DATE(beparian_purchases.purchase_date)'), [$fdate, $tdate]);
            //  dd($query->toSql());
        }

        $data = $query->get();
        
        return view('reports.bpview', compact('data', 'suppliers'));
    }

    public function regularPurchaseReport(Request $request)
    {
        // dd($request->all());
        $suppliers = Supplier::where(company())->get();

        $query = Purchase_details::join('regular_purchases', 'regular_purchases.id', '=', 'purchase_details.regular_purchase_id')
            ->groupBy('purchase_details.regular_purchase_id')
            ->select('regular_purchases.*', 'purchase_details.*')
            ->where('regular_purchases.company_id', company()['company_id']);

        if ($request->supplier) {
            $query->where('regular_purchases.supplier_id', $request->supplier);
            // dd($query->toSql());
        }
        if ($request->lc_no) {
            $query->where('purchase_details.lot_no', $request->lc_no);
            // dd($query->toSql());
        }

        if ($request->fdate && $request->tdate) {
            $fdate = Carbon::parse($request->fdate)->toDateString();
            $tdate = Carbon::parse($request->tdate)->toDateString();
    
            $query->whereBetween(DB::raw('DATE(regular_purchases.purchase_date)'), [$fdate, $tdate]);
            //  dd($query->toSql());
        }

        $data = $query->get();
        
        return view('reports.rpview', compact('data', 'suppliers'));
    }

    // public function salesReport(Request $request)
    // {
    //     // dd($request->all());
    //     $customers = Customer::where(company())->get();

    //     $query = Sales_details::join('sales', 'sales.id', '=', 'sales_details.sales_id')
    //         ->groupBy('sales_details.id')
    //         ->select('sales.*', 'sales_details.*')
    //         ->where('sales.company_id', company()['company_id']);

    //     if ($request->customer) {
    //         $query->where('sales.customer_id', $request->customer);
    //         // dd($query->toSql());
    //     }
    //     if ($request->lc_no) {
    //         $query->where('sales_details.lot_no', $request->lc_no);
    //         // dd($query->toSql());
    //     }

    //     if ($request->fdate && $request->tdate) {
    //         $fdate = Carbon::parse($request->fdate)->toDateString();
    //         $tdate = Carbon::parse($request->tdate)->toDateString();
    
    //         $query->whereBetween(DB::raw('DATE(sales.sales_date)'), [$fdate, $tdate]);
    //         //  dd($query->toSql());
    //     }

    //     $data = $query->get();
        
    //     return view('reports.salesview', compact('data', 'customers'));
    // }
    public function salesReport(Request $request)
    {
        // dd($request->all());
        $customers = Customer::where(company())->get();

        $query = Sales::where(company());

        if ($request->customer) {
            $query->where('customer_id', $request->customer);
            // dd($query->toSql());
        }
        if($request->lc_no){
            $lotno=$request->lc_no;
            $query->whereHas('sale_lot', function($q) use ($lotno){
                $q->where('lot_no', $lotno);
            });
        }
        

        if ($request->fdate && $request->tdate) {
            $fdate = Carbon::parse($request->fdate)->toDateString();
            $tdate = Carbon::parse($request->tdate)->toDateString();
    
            $query->whereBetween(DB::raw('DATE(sales_date)'), [$fdate, $tdate]);
            //  dd($query->toSql());
        }

        $data = $query->orderBy('sales_date','DESC')->get();
        
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
        $purchase = Purchase_details::where('lot_no',$lotNumber)->where(company())->get();
        $sales = Sales_details::where('lot_no',$lotNumber)->where(company())->get();
        $purExpense = ExpenseOfPurchase::where('lot_no',$lotNumber)->where(company())->where('status',0)->get();
        $salExpense = ExpenseOfSales::where('lot_no',$lotNumber)->where(company())->where('status',0)->get();
        
       
        
        return view('reports.srotaView', compact('purchase', 'sales','purExpense','salExpense','lotNumber'));
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

    public function lc_report(Request $request)
    {
        $lc_data = false;
        if ($request->lc_no) {
        $leexp=Company::where('id',company()['company_id'])->pluck('lc_expense');
            if($leexp[0]){
                $leexp=explode(',',$leexp[0]);
                $childOne= Child_one::whereIn('head_code',$leexp)->where(company())->pluck('id');
                $childTwo = Child_two::whereIn('head_code',$leexp)->where(company())->pluck('id');
                $lc_data = GeneralLedger::where(company())->where('lc_no',$request->lc_no)
                ->where(function($query) use ($childTwo,$childOne){
                    if($childOne){
                        $query->orWhere(function($query) use ($childOne){
                            $query->whereIn('child_one_id',$childOne);
                        });
                    }
                    if($childTwo){
                        $query->orWhere(function($query) use ($childTwo){
                            $query->whereIn('child_two_id',$childTwo);
                        });
                    }
                })->get();
            }
        }
    
        return view('reports.lc_report', compact('lc_data'));
    }

}
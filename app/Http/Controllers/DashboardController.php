<?php

namespace App\Http\Controllers;

use App\Models\Customers\Customer;
use App\Models\Purchases\Beparian_purchase;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\Regular_purchase;
use App\Models\Sales\Sales;
use App\Models\Suppliers\Supplier;
use DateTime;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /*
    * admin dashboard
    */
    public function adminDashboard(){
        return view('dasbhoard.admin');
    }

    /*
    * owner dashboard
    */
    public function ownerDashboard(){
        $ldate = new DateTime('today');

        $supplier = Supplier::where(company())->count();
        $customer = Customer::where('is_walking',0)->where(company())->count();
        $totalPurchase = Purchase::where(company())->count();
        $totalRegularPurchase = Regular_purchase::where(company())->count();
        $totalBeparianPurchase = Beparian_purchase::where(company())->count();
        $totalPurchaseAmount = Purchase::where(company())->sum('grand_total');
        $totalRegularPurchaseAmount = Regular_purchase::where(company())->sum('grand_total');
        $totalBeparianPurchaseAmount = Beparian_purchase::where(company())->sum('grand_total');
        $todayPurchaseAmount = Purchase::where('purchase_date',$ldate->format('Y-m-d'))->where(company())->sum('grand_total');
        $todayRegularPurchaseAmount = Regular_purchase::where('purchase_date',$ldate->format('Y-m-d'))->where(company())->sum('grand_total');
        $todayBeparianPurchaseAmount = Beparian_purchase::where('purchase_date',$ldate->format('Y-m-d'))->where(company())->sum('grand_total');
        $totalSale = Sales::where(company())->count();
        $totalSaleAmount = Sales::where(company())->sum('grand_total');
        $todayTotalSaleAmount = Sales::where('sales_date',$ldate->format('Y-m-d'))->where(company())->sum('grand_total');
        return view('dasbhoard.owner',compact('supplier','customer','totalPurchase','totalBeparianPurchase','totalRegularPurchase','totalSale','totalPurchaseAmount','totalRegularPurchaseAmount','totalBeparianPurchaseAmount','totalSaleAmount','todayTotalSaleAmount','todayPurchaseAmount','todayRegularPurchaseAmount','todayBeparianPurchaseAmount'));
    }
    
    /*
    * sales manager dashboard
    */
    public function salesmanagerDashboard(){
        return view('dasbhoard.salesmanager');
    }

    /*
    * sales man dashboard
    */
    public function salesmanDashboard(){
        return view('dasbhoard.salesman');
    }

    /*
    * sales man dashboard
    */
    public function executiveDashboard(){
        return view('dasbhoard.executive');
    }
}

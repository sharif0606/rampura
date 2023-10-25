<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController as auth;
use App\Http\Controllers\DashboardController as dash;
use App\Http\Controllers\Settings\CompanyController as company;
use App\Http\Controllers\Settings\UserController as user;
use App\Http\Controllers\Settings\ProfileController as profile;
use App\Http\Controllers\Settings\AdminUserController as admin;
use App\Http\Controllers\Settings\Location\CountryController as country;
use App\Http\Controllers\Settings\Location\DivisionController as division;
use App\Http\Controllers\Settings\Location\DistrictController as district;
use App\Http\Controllers\Settings\Location\UpazilaController as upazila;
use App\Http\Controllers\Settings\Location\ThanaController as thana;
use App\Http\Controllers\Products\CategoryController as category;
use App\Http\Controllers\Products\SubcategoryController as subcat;
use App\Http\Controllers\Products\ChildcategoryController as childcat;
use App\Http\Controllers\Products\BrandController as brand;
use App\Http\Controllers\Products\UnitController as unit;
use App\Http\Controllers\Products\ProductController as product;
use App\Http\Controllers\Suppliers\SupplierController as supplier;
use App\Http\Controllers\Customers\CustomerController as customer;
use App\Http\Controllers\Purchases\PurchaseController as purchase;
use App\Http\Controllers\Purchases\BeparianPurchaseController as bpurchase;
use App\Http\Controllers\Purchases\RegularPurchaseController as rpurchase;
use App\Http\Controllers\Purchases\PurchasePendingsController as purPending;
use App\Http\Controllers\Sales\SalesController as sales;
use App\Http\Controllers\Sales\SalesPendingsController as salPending;
use App\Http\Controllers\Settings\BranchController as branch;
use App\Http\Controllers\Settings\WarehouseController as warehouse;
use App\Http\Controllers\Reports\ReportController as report;
use App\Http\Controllers\Transfers\TransferController as transfer;
use App\Http\Controllers\Currency\CurrencyController as currency;


use App\Http\Controllers\Accounts\MasterAccountController as master;
use App\Http\Controllers\Accounts\SubHeadController as sub_head;
use App\Http\Controllers\Accounts\ChildOneController as child_one;
use App\Http\Controllers\Accounts\ChildTwoController as child_two;
use App\Http\Controllers\Accounts\NavigationHeadViewController as navigate;
use App\Http\Controllers\Accounts\IncomeStatementController as statement;
use App\Http\Controllers\Accounts\Report\HeadReportController as headreport;
use App\Http\Controllers\Accounts\Report\BalanceSheetController as balancesheet;
use App\Http\Controllers\Accounts\Report\ProfitLossController as profitloss;

use App\Http\Controllers\Vouchers\PurchaseVoucherController as PurchaseVoucher;
use App\Http\Controllers\Vouchers\SalesVoucherController as SalesVoucher;
use App\Http\Controllers\Vouchers\CreditVoucherController as credit;
use App\Http\Controllers\Vouchers\DebitVoucherController as debit;
use App\Http\Controllers\Vouchers\JournalVoucherController as journal;
/* Middleware */
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\isOwner;
use App\Http\Middleware\isSalesmanager;
use App\Http\Middleware\isSalesman;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/register', [auth::class,'signUpForm'])->name('register');
Route::post('/register', [auth::class,'signUpStore'])->name('register.store');
Route::get('/', [auth::class,'signInForm'])->name('signIn');
Route::get('/login', [auth::class,'signInForm'])->name('login');
Route::post('/login', [auth::class,'signInCheck'])->name('login.check');
Route::get('/logout', [auth::class,'singOut'])->name('logOut');


Route::group(['middleware'=>isAdmin::class],function(){
    Route::prefix('admin')->group(function(){
        Route::get('/dashboard', [dash::class,'adminDashboard'])->name('admin.dashboard');
        /* settings */
        Route::get('/admincompany',[company::class,'admindex'])->name('admin.admincompany');

        //Adnin profile
        Route::get('/profile', [profile::class,'adminProfile'])->name('admin.profile');
        Route::post('/profile', [profile::class,'adminProfile'])->name('admin.profile.update');
        Route::post('/profile-update', [profile::class,'aProfileUpdate'])->name('admin.profile.up');

       // Route::resource('/profile/update',profile::class,['as'=>'admin']);

        Route::resource('users',user::class,['as'=>'admin']);
        Route::resource('admin',admin::class,['as'=>'admin']);
        Route::resource('country',country::class,['as'=>'admin']);
        Route::resource('division',division::class,['as'=>'admin']);
        Route::resource('district',district::class,['as'=>'admin']);
        Route::resource('upazila',upazila::class,['as'=>'admin']);
        Route::resource('thana',thana::class,['as'=>'admin']);
        Route::resource('unit',unit::class,['as'=>'admin']);
        Route::resource('currency',currency::class,['as'=>'admin']);

    });
});

Route::group(['middleware'=>isOwner::class],function(){
    Route::prefix('owner')->group(function(){
        Route::get('/dashboard', [dash::class,'ownerDashboard'])->name('owner.dashboard');
        //settings
        Route::resource('company',company::class,['as'=>'owner']);
        Route::resource('users',user::class,['as'=>'owner']);
        Route::resource('brand',brand::class,['as'=>'owner']);
        Route::resource('branch',branch::class,['as'=>'owner']);
        Route::resource('warehouse',warehouse::class,['as'=>'owner']);
        Route::resource('country',country::class,['as'=>'owner']);
        Route::resource('division',division::class,['as'=>'owner']);
        Route::resource('district',district::class,['as'=>'owner']);
        Route::resource('upazila',upazila::class,['as'=>'owner']);

        //Owner profile
        Route::get('/profile', [profile::class,'ownerProfile'])->name('owner.profile');
        Route::post('/profile', [profile::class,'ownerProfile'])->name('owner.profile.update');


        //Supplier and Customer
        Route::resource('supplier',supplier::class,['as'=>'owner']);
        Route::resource('customer',customer::class,['as'=>'owner']);


        //report
        Route::get('/stock-report',[report::class,'stockreport'])->name('owner.sreport');
        Route::get('/stock-report-individual{id}',[report::class,'stockindividual'])->name('owner.stock.individual');
        Route::get('/salreport',[report::class,'salesReport'])->name('owner.salreport');
        Route::get('/purchase-report',[report::class,'purchaseReport'])->name('owner.purchase_report');
        Route::get('/beparian-report',[report::class,'beparianPurchaseReport'])->name('owner.beparian_report');
        Route::get('/regular-report',[report::class,'regularPurchaseReport'])->name('owner.regular_report');
        Route::get('/all-purchase-report',[report::class,'allPurchaseReport'])->name('owner.all_pur_report');
        Route::get('/srota',[report::class,'srota'])->name('owner.srota');
        Route::get('/srota-view',[report::class,'srotaView'])->name('owner.srota_view');

        //Product
        Route::resource('category',category::class,['as'=>'owner']);
        Route::resource('subcategory',subcat::class,['as'=>'owner']);
        Route::resource('childcategory',childcat::class,['as'=>'owner']);
        Route::resource('product',product::class,['as'=>'owner']);
        Route::get('/plabel',[product::class,'label'])->name('owner.plabel');
        Route::get('/qrcodepreview',[product::class,'qrcodepreview'])->name('owner.qrcodepreview');
        Route::get('/barcodepreview',[product::class,'barcodepreview'])->name('owner.barcodepreview');
        Route::get('/labelprint',[product::class,'labelprint'])->name('owner.labelprint');

        //Accounts
        Route::resource('master',master::class,['as'=>'owner']);
        Route::resource('sub_head',sub_head::class,['as'=>'owner']);
        Route::resource('child_one',child_one::class,['as'=>'owner']);
        Route::resource('child_two',child_two::class,['as'=>'owner']);
        Route::resource('navigate',navigate::class,['as'=>'owner']);

        Route::get('incomeStatement',[statement::class,'index'])->name('owner.incomeStatement');
        Route::get('incomeStatement_details',[statement::class,'details'])->name('owner.incomeStatement.details');
        Route::get('/profitloss', [profitloss::class, 'index'])->name('owner.profitloss');
        Route::get('/balancesheet', [balancesheet::class, 'index'])->name('owner.balancesheet');
        Route::get('/headreport', [headreport::class, 'index'])->name('owner.headreport');

        //Voucher
        Route::resource('purchase_voucher',PurchaseVoucher::class,['as'=>'owner']);
        Route::resource('sales_voucher',SalesVoucher::class,['as'=>'owner']);
        Route::resource('credit',credit::class,['as'=>'owner']);
        Route::resource('debit',debit::class,['as'=>'owner']);
        Route::get('get_head', [credit::class, 'get_head'])->name('owner.get_head');
        Route::resource('journal',journal::class,['as'=>'owner']);
        Route::get('journal_get_head', [journal::class, 'get_head'])->name('owner.journal_get_head');

        //Purchase
        Route::resource('purchase',purchase::class,['as'=>'owner']);
        Route::resource('bpurchase',bpurchase::class,['as'=>'owner']);
        Route::resource('rpurchase',rpurchase::class,['as'=>'owner']);
        Route::get('/purchase-pending-expense',[purPending::class,'purchase_pending_expense'])->name('owner.pur_pending_exp');
        Route::get('/purchase-payment',[purPending::class,'purchase_supplier_payment'])->name('owner.pur_pending_pay');
        Route::get('/product_search', [purchase::class,'product_search'])->name('owner.pur.product_search');
        Route::get('/product_search_data', [purchase::class,'product_search_data'])->name('owner.pur.product_search_data');

        //lc check
        Route::get('/check-lc', [purchase::class, 'checkLcNo'])->name('owner.checkLcNo');

        //Sale
        Route::resource('sales',sales::class,['as'=>'owner']);
        Route::get('/sale-view{id}', [sales::class,'saleView'])->name('owner.sales.view');
        Route::get('/sale-memo{id}', [sales::class,'saleMemo'])->name('owner.sales.memo');
        Route::get('/product_sc', [sales::class,'product_sc'])->name('owner.sales.product_sc');
        Route::get('/product_sc_d', [sales::class,'product_sc_d'])->name('owner.sales.product_sc_d');

        Route::get('/sales-pending-expense',[salPending::class,'sales_pending_expense'])->name('owner.sales_pending_exp');
        Route::get('/sales-payment',[salPending::class,'sales_customer_payment'])->name('owner.sales_pending_pay');

        //Transfer
        Route::resource('transfer',transfer::class,['as'=>'owner']);
        Route::get('/product_scr', [transfer::class,'product_scr'])->name('owner.transfer.product_scr');
        Route::get('/product_scr_d', [transfer::class,'product_scr_d'])->name('owner.transfer.product_scr_d');
    });
});

Route::group(['middleware'=>isSalesmanager::class],function(){
    Route::prefix('salesmanager')->group(function(){
        Route::get('/dashboard', [dash::class,'salesmanagerDashboard'])->name('salesmanager.dashboard');

    });
});

Route::group(['middleware'=>isSalesman::class],function(){
    Route::prefix('salesman')->group(function(){
        Route::get('/dashboard', [dash::class,'salesmanDashboard'])->name('salesman.dashboard');

    });
});



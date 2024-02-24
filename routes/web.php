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
use App\Http\Controllers\Return\PurchaseReturnController as purchaseReturn;
use App\Http\Controllers\Return\BeparianPurchaseReturnController as beparianReturn;
use App\Http\Controllers\Return\RegularPurchaseReturnController as regularReturn;
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
use App\Http\Controllers\Accounts\Report\IncomeStatementController as statement;
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
use App\Http\Middleware\isExecutive;

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
        Route::get('/delete-data-company-wise', [company::class,'allCompany'])->name('admin.get_all_company');
        Route::get('/data-deleted-by-company', [company::class,'deleteCompanyWise'])->name('admin.deleted_data_company_wise');

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
        Route::get('/delete-data-company-wise', [company::class,'allCompany'])->name('owner.get_all_company');
        Route::get('/data-deleted-by-company', [company::class,'deleteCompanyWise'])->name('owner.deleted_data_company_wise');
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
        Route::get('/stock-report-individual/{id}',[report::class,'stockindividual'])->name('owner.stock.individual');
        Route::get('/stock-report-individual-by-lot/{lot_no}',[report::class,'stockindividualByLot'])->name('owner.stock.individual_lot');
        Route::get('/salreport',[report::class,'salesReport'])->name('owner.salreport');
        Route::get('/salreport-account',[report::class,'salesReportAccount'])->name('owner.salreport_account');
        Route::get('/purchase-report',[report::class,'purchaseReport'])->name('owner.purchase_report');
        Route::get('/beparian-report',[report::class,'beparianPurchaseReport'])->name('owner.beparian_report');
        Route::get('/regular-report',[report::class,'regularPurchaseReport'])->name('owner.regular_report');
        Route::get('/all-purchase-report',[report::class,'allPurchaseReport'])->name('owner.all_pur_report');
        Route::get('/srota',[report::class,'srota'])->name('owner.srota');
        Route::get('/srota-view',[report::class,'srotaView'])->name('owner.srota_view');
        Route::get('/lc_report',[report::class,'lc_report'])->name('owner.lc_report');
        Route::get('/statement-report',[report::class,'statement'])->name('owner.statement_report');

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
        Route::get('incomeStatement_details_without_sales',[statement::class,'details_without_sales'])->name('owner.incomeStatement.details_without_sales');
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
        Route::get('journal_get_head', [journal::class, 'get_head_journal'])->name('owner.journal_get_head');

        //Purchase
        Route::resource('purchase',purchase::class,['as'=>'owner']);
        Route::resource('purchaseReturn',purchaseReturn::class,['as'=>'owner']);
        Route::get('/product_search_return', [purchaseReturn::class,'product_search'])->name('owner.pur.product_search_return');
        Route::get('/product_sc_d_return', [purchaseReturn::class,'product_sc_d'])->name('owner.pur.product_sc_d_return');
        Route::resource('beparianReturn',beparianReturn::class,['as'=>'owner']);
        Route::get('/product_search_beparian_return', [beparianReturn::class,'product_search'])->name('owner.pur.product_search_beparian_return');
        Route::get('/product_sc_d_beparian_return', [beparianReturn::class,'product_sc_d'])->name('owner.pur.product_sc_d_beparian_return');
        Route::resource('regularReturn',regularReturn::class,['as'=>'owner']);
        Route::get('/product_search_regular_return', [regularReturn::class,'product_search'])->name('owner.pur.product_search_regular_return');
        Route::resource('bpurchase',bpurchase::class,['as'=>'owner']);
        Route::resource('rpurchase',rpurchase::class,['as'=>'owner']);
        Route::get('/purchase-pending-expense',[purPending::class,'purchase_pending_expense'])->name('owner.pur_pending_exp');
        Route::get('/purchase-payment',[purPending::class,'purchase_supplier_payment'])->name('owner.pur_pending_pay');
        Route::get('/product_search', [purchase::class,'product_search'])->name('owner.pur.product_search');
        Route::get('/product_search_data', [purchase::class,'product_search_data'])->name('owner.pur.product_search_data');
        Route::get('/beparian_product_search_data', [bpurchase::class,'beparian_product_search_data'])->name('owner.pur.beparian_product_search_data');

        //lc check
        Route::get('/check-lc', [purchase::class, 'checkLcNo'])->name('owner.checkLcNo');

        //Sale
        Route::resource('sales',sales::class,['as'=>'owner']);
        Route::get('/sales-cash', [sales::class,'cashSale'])->name('owner.sales.cash');
        Route::get('/sales-cash-edit/{id}', [sales::class,'cashSaleEdit'])->name('owner.sales_cash_edit');
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
    Route::prefix('manager')->group(function(){
        Route::get('/dashboard', [dash::class,'salesmanagerDashboard'])->name('manager.dashboard');
        Route::get('/profile', [profile::class,'ownerProfile'])->name('manager.profile');
        Route::get('/profile-update', [profile::class,'ownerProfile'])->name('manager.profile.update');
        
        //settings
        Route::resource('users',user::class,['as'=>'manager']);
        Route::resource('company',company::class,['as'=>'manager']);
        Route::resource('brand',brand::class,['as'=>'manager']);
        Route::resource('branch',branch::class,['as'=>'manager']);
        Route::resource('warehouse',warehouse::class,['as'=>'manager']);
        Route::resource('country',country::class,['as'=>'manager']);
        Route::resource('division',division::class,['as'=>'manager']);
        Route::resource('district',district::class,['as'=>'manager']);
        Route::resource('upazila',upazila::class,['as'=>'manager']);


        //Supplier and Customer
        Route::resource('supplier',supplier::class,['as'=>'manager']);
        Route::resource('customer',customer::class,['as'=>'manager']);


        //report
        Route::get('/stock-report',[report::class,'stockreport'])->name('manager.sreport');
        Route::get('/stock-report-individual/{id}',[report::class,'stockindividual'])->name('manager.stock.individual');
        Route::get('/stock-report-individual-by-lot/{lot_no}',[report::class,'stockindividualByLot'])->name('manager.stock.individual_lot');
        Route::get('/salreport',[report::class,'salesReport'])->name('manager.salreport');
        Route::get('/purchase-report',[report::class,'purchaseReport'])->name('manager.purchase_report');
        Route::get('/beparian-report',[report::class,'beparianPurchaseReport'])->name('manager.beparian_report');
        Route::get('/regular-report',[report::class,'regularPurchaseReport'])->name('manager.regular_report');
        Route::get('/all-purchase-report',[report::class,'allPurchaseReport'])->name('manager.all_pur_report');
        Route::get('/srota',[report::class,'srota'])->name('manager.srota');
        Route::get('/srota-view',[report::class,'srotaView'])->name('manager.srota_view');
        Route::get('/lc_report',[report::class,'lc_report'])->name('manager.lc_report');

        //Product
        Route::resource('category',category::class,['as'=>'manager']);
        Route::resource('subcategory',subcat::class,['as'=>'manager']);
        Route::resource('childcategory',childcat::class,['as'=>'manager']);
        Route::resource('product',product::class,['as'=>'manager']);

        //Accounts
        Route::resource('master',master::class,['as'=>'manager']);
        Route::resource('sub_head',sub_head::class,['as'=>'manager']);
        Route::resource('child_one',child_one::class,['as'=>'manager']);
        Route::resource('child_two',child_two::class,['as'=>'manager']);
        Route::resource('navigate',navigate::class,['as'=>'manager']);

        Route::get('incomeStatement',[statement::class,'index'])->name('manager.incomeStatement');
        Route::get('incomeStatement_details',[statement::class,'details'])->name('manager.incomeStatement.details');
        Route::get('/profitloss', [profitloss::class, 'index'])->name('manager.profitloss');
        Route::get('/balancesheet', [balancesheet::class, 'index'])->name('manager.balancesheet');
        Route::get('/headreport', [headreport::class, 'index'])->name('manager.headreport');

        //Voucher
        Route::resource('purchase_voucher',PurchaseVoucher::class,['as'=>'manager']);
        Route::resource('sales_voucher',SalesVoucher::class,['as'=>'manager']);
        Route::resource('credit',credit::class,['as'=>'manager']);
        Route::resource('debit',debit::class,['as'=>'manager']);
        Route::get('get_head', [credit::class, 'get_head'])->name('manager.get_head');
        Route::resource('journal',journal::class,['as'=>'manager']);
        Route::get('journal_get_head', [journal::class, 'get_head_journal'])->name('manager.journal_get_head');

        //Purchase
        Route::resource('purchase',purchase::class,['as'=>'manager']);
        Route::resource('bpurchase',bpurchase::class,['as'=>'manager']);
        Route::resource('rpurchase',rpurchase::class,['as'=>'manager']);
        Route::get('/purchase-pending-expense',[purPending::class,'purchase_pending_expense'])->name('manager.pur_pending_exp');
        Route::get('/purchase-payment',[purPending::class,'purchase_supplier_payment'])->name('manager.pur_pending_pay');
        Route::get('/product_search', [purchase::class,'product_search'])->name('manager.pur.product_search');
        Route::get('/product_search_data', [purchase::class,'product_search_data'])->name('manager.pur.product_search_data');

        //lc check
        Route::get('/check-lc', [purchase::class, 'checkLcNo'])->name('manager.checkLcNo');

        //Sale
        Route::resource('sales',sales::class,['as'=>'manager']);
        Route::get('/sales-cash', [sales::class,'cashSale'])->name('manager.sales.cash');
        Route::get('/sale-view{id}', [sales::class,'saleView'])->name('manager.sales.view');
        Route::get('/sale-memo{id}', [sales::class,'saleMemo'])->name('manager.sales.memo');
        Route::get('/product_sc', [sales::class,'product_sc'])->name('manager.sales.product_sc');
        Route::get('/product_sc_d', [sales::class,'product_sc_d'])->name('manager.sales.product_sc_d');

        Route::get('/sales-pending-expense',[salPending::class,'sales_pending_expense'])->name('manager.sales_pending_exp');
        Route::get('/sales-payment',[salPending::class,'sales_customer_payment'])->name('manager.sales_pending_pay');

    });
});

Route::group(['middleware'=>isSalesman::class],function(){
    Route::prefix('accountsofficer')->group(function(){
        Route::get('/dashboard', [dash::class,'salesmanDashboard'])->name('accountsofficer.dashboard');
        Route::get('/profile', [profile::class,'ownerProfile'])->name('accountsofficer.profile');
        Route::get('/profile-update', [profile::class,'ownerProfile'])->name('accountsofficer.profile.update');
        
        //settings
        Route::resource('users',user::class,['as'=>'accountsofficer']);
        Route::resource('company',company::class,['as'=>'accountsofficer']);
        Route::resource('brand',brand::class,['as'=>'accountsofficer']);
        Route::resource('branch',branch::class,['as'=>'accountsofficer']);
        Route::resource('warehouse',warehouse::class,['as'=>'accountsofficer']);
        Route::resource('country',country::class,['as'=>'accountsofficer']);
        Route::resource('division',division::class,['as'=>'accountsofficer']);
        Route::resource('district',district::class,['as'=>'accountsofficer']);
        Route::resource('upazila',upazila::class,['as'=>'accountsofficer']);


        //Supplier and Customer
        Route::resource('supplier',supplier::class,['as'=>'accountsofficer']);
        Route::resource('customer',customer::class,['as'=>'accountsofficer']);


        //report
        Route::get('/stock-report',[report::class,'stockreport'])->name('accountsofficer.sreport');
        Route::get('/stock-report-individual/{id}',[report::class,'stockindividual'])->name('accountsofficer.stock.individual');
        Route::get('/stock-report-individual-by-lot/{lot_no}',[report::class,'stockindividualByLot'])->name('accountsofficer.stock.individual_lot');
        Route::get('/salreport',[report::class,'salesReport'])->name('accountsofficer.salreport');
        Route::get('/purchase-report',[report::class,'purchaseReport'])->name('accountsofficer.purchase_report');
        Route::get('/beparian-report',[report::class,'beparianPurchaseReport'])->name('accountsofficer.beparian_report');
        Route::get('/regular-report',[report::class,'regularPurchaseReport'])->name('accountsofficer.regular_report');
        Route::get('/all-purchase-report',[report::class,'allPurchaseReport'])->name('accountsofficer.all_pur_report');
        Route::get('/srota',[report::class,'srota'])->name('accountsofficer.srota');
        Route::get('/srota-view',[report::class,'srotaView'])->name('accountsofficer.srota_view');
        Route::get('/lc_report',[report::class,'lc_report'])->name('accountsofficer.lc_report');

        //Product
        Route::resource('category',category::class,['as'=>'accountsofficer']);
        Route::resource('subcategory',subcat::class,['as'=>'accountsofficer']);
        Route::resource('childcategory',childcat::class,['as'=>'accountsofficer']);
        Route::resource('product',product::class,['as'=>'accountsofficer']);

        //Accounts
        Route::resource('master',master::class,['as'=>'accountsofficer']);
        Route::resource('sub_head',sub_head::class,['as'=>'accountsofficer']);
        Route::resource('child_one',child_one::class,['as'=>'accountsofficer']);
        Route::resource('child_two',child_two::class,['as'=>'accountsofficer']);
        Route::resource('navigate',navigate::class,['as'=>'accountsofficer']);

        Route::get('incomeStatement',[statement::class,'index'])->name('accountsofficer.incomeStatement');
        Route::get('incomeStatement_details',[statement::class,'details'])->name('accountsofficer.incomeStatement.details');
        Route::get('/profitloss', [profitloss::class, 'index'])->name('accountsofficer.profitloss');
        Route::get('/balancesheet', [balancesheet::class, 'index'])->name('accountsofficer.balancesheet');
        Route::get('/headreport', [headreport::class, 'index'])->name('accountsofficer.headreport');

        //Voucher
        Route::resource('purchase_voucher',PurchaseVoucher::class,['as'=>'accountsofficer']);
        Route::resource('sales_voucher',SalesVoucher::class,['as'=>'accountsofficer']);
        Route::resource('credit',credit::class,['as'=>'accountsofficer']);
        Route::resource('debit',debit::class,['as'=>'accountsofficer']);
        Route::get('get_head', [credit::class, 'get_head'])->name('accountsofficer.get_head');
        Route::resource('journal',journal::class,['as'=>'accountsofficer']);
        Route::get('journal_get_head', [journal::class, 'get_head_journal'])->name('accountsofficer.journal_get_head');

        //Purchase
        Route::resource('purchase',purchase::class,['as'=>'accountsofficer']);
        Route::resource('bpurchase',bpurchase::class,['as'=>'accountsofficer']);
        Route::resource('rpurchase',rpurchase::class,['as'=>'accountsofficer']);
        Route::get('/purchase-pending-expense',[purPending::class,'purchase_pending_expense'])->name('accountsofficer.pur_pending_exp');
        Route::get('/purchase-payment',[purPending::class,'purchase_supplier_payment'])->name('accountsofficer.pur_pending_pay');
        Route::get('/product_search', [purchase::class,'product_search'])->name('accountsofficer.pur.product_search');
        Route::get('/product_search_data', [purchase::class,'product_search_data'])->name('accountsofficer.pur.product_search_data');

        //lc check
        Route::get('/check-lc', [purchase::class, 'checkLcNo'])->name('accountsofficer.checkLcNo');

        //Sale
        Route::resource('sales',sales::class,['as'=>'accountsofficer']);
        Route::get('/sales-cash', [sales::class,'cashSale'])->name('accountsofficer.sales.cash');
        Route::get('/sale-view{id}', [sales::class,'saleView'])->name('accountsofficer.sales.view');
        Route::get('/sale-memo{id}', [sales::class,'saleMemo'])->name('accountsofficer.sales.memo');
        Route::get('/product_sc', [sales::class,'product_sc'])->name('accountsofficer.sales.product_sc');
        Route::get('/product_sc_d', [sales::class,'product_sc_d'])->name('accountsofficer.sales.product_sc_d');

        Route::get('/sales-pending-expense',[salPending::class,'sales_pending_expense'])->name('accountsofficer.sales_pending_exp');
        Route::get('/sales-payment',[salPending::class,'sales_customer_payment'])->name('accountsofficer.sales_pending_pay');

    });
});

Route::group(['middleware'=>isExecutive::class],function(){
    Route::prefix('executiveofficer')->group(function(){
        Route::get('/dashboard', [dash::class,'executiveDashboard'])->name('executiveofficer.dashboard');
        Route::get('/profile', [profile::class,'ownerProfile'])->name('executiveofficer.profile');
        Route::get('/profile-update', [profile::class,'ownerProfile'])->name('executiveofficer.profile.update');
        
        //settings
        Route::resource('users',user::class,['as'=>'executiveofficer']);
        Route::resource('company',company::class,['as'=>'executiveofficer']);
        Route::resource('brand',brand::class,['as'=>'executiveofficer']);
        Route::resource('branch',branch::class,['as'=>'executiveofficer']);
        Route::resource('warehouse',warehouse::class,['as'=>'executiveofficer']);
        Route::resource('country',country::class,['as'=>'executiveofficer']);
        Route::resource('division',division::class,['as'=>'executiveofficer']);
        Route::resource('district',district::class,['as'=>'executiveofficer']);
        Route::resource('upazila',upazila::class,['as'=>'executiveofficer']);


        //Supplier and Customer
        Route::resource('supplier',supplier::class,['as'=>'executiveofficer']);
        Route::resource('customer',customer::class,['as'=>'executiveofficer']);


        //report
        Route::get('/stock-report',[report::class,'stockreport'])->name('executiveofficer.sreport');
        Route::get('/stock-report-individual/{id}',[report::class,'stockindividual'])->name('executiveofficer.stock.individual');
        Route::get('/stock-report-individual-by-lot/{lot_no}',[report::class,'stockindividualByLot'])->name('executiveofficer.stock.individual_lot');
        Route::get('/salreport',[report::class,'salesReport'])->name('executiveofficer.salreport');
        Route::get('/purchase-report',[report::class,'purchaseReport'])->name('executiveofficer.purchase_report');
        Route::get('/beparian-report',[report::class,'beparianPurchaseReport'])->name('executiveofficer.beparian_report');
        Route::get('/regular-report',[report::class,'regularPurchaseReport'])->name('executiveofficer.regular_report');
        Route::get('/all-purchase-report',[report::class,'allPurchaseReport'])->name('executiveofficer.all_pur_report');
        Route::get('/srota',[report::class,'srota'])->name('executiveofficer.srota');
        Route::get('/srota-view',[report::class,'srotaView'])->name('executiveofficer.srota_view');
        Route::get('/lc_report',[report::class,'lc_report'])->name('executiveofficer.lc_report');

        //Product
        Route::resource('category',category::class,['as'=>'executiveofficer']);
        Route::resource('subcategory',subcat::class,['as'=>'executiveofficer']);
        Route::resource('childcategory',childcat::class,['as'=>'executiveofficer']);
        Route::resource('product',product::class,['as'=>'executiveofficer']);

        //Accounts
        Route::resource('master',master::class,['as'=>'executiveofficer']);
        Route::resource('sub_head',sub_head::class,['as'=>'executiveofficer']);
        Route::resource('child_one',child_one::class,['as'=>'executiveofficer']);
        Route::resource('child_two',child_two::class,['as'=>'executiveofficer']);
        Route::resource('navigate',navigate::class,['as'=>'executiveofficer']);

        Route::get('incomeStatement',[statement::class,'index'])->name('executiveofficer.incomeStatement');
        Route::get('incomeStatement_details',[statement::class,'details'])->name('executiveofficer.incomeStatement.details');
        Route::get('/profitloss', [profitloss::class, 'index'])->name('executiveofficer.profitloss');
        Route::get('/balancesheet', [balancesheet::class, 'index'])->name('executiveofficer.balancesheet');
        Route::get('/headreport', [headreport::class, 'index'])->name('executiveofficer.headreport');

        //Voucher
        Route::resource('purchase_voucher',PurchaseVoucher::class,['as'=>'executiveofficer']);
        Route::resource('sales_voucher',SalesVoucher::class,['as'=>'executiveofficer']);
        Route::resource('credit',credit::class,['as'=>'executiveofficer']);
        Route::resource('debit',debit::class,['as'=>'executiveofficer']);
        Route::get('get_head', [credit::class, 'get_head'])->name('executiveofficer.get_head');
        Route::resource('journal',journal::class,['as'=>'executiveofficer']);
        Route::get('journal_get_head', [journal::class, 'get_head_journal'])->name('executiveofficer.journal_get_head');

        //Purchase
        Route::resource('purchase',purchase::class,['as'=>'executiveofficer']);
        Route::resource('bpurchase',bpurchase::class,['as'=>'executiveofficer']);
        Route::resource('rpurchase',rpurchase::class,['as'=>'executiveofficer']);
        Route::get('/purchase-pending-expense',[purPending::class,'purchase_pending_expense'])->name('executiveofficer.pur_pending_exp');
        Route::get('/purchase-payment',[purPending::class,'purchase_supplier_payment'])->name('executiveofficer.pur_pending_pay');
        Route::get('/product_search', [purchase::class,'product_search'])->name('executiveofficer.pur.product_search');
        Route::get('/product_search_data', [purchase::class,'product_search_data'])->name('executiveofficer.pur.product_search_data');

        //lc check
        Route::get('/check-lc', [purchase::class, 'checkLcNo'])->name('executiveofficer.checkLcNo');

        //Sale
        Route::resource('sales',sales::class,['as'=>'executiveofficer']);
        Route::get('/sales-cash', [sales::class,'cashSale'])->name('executiveofficer.sales.cash');
        Route::get('/sale-view{id}', [sales::class,'saleView'])->name('executiveofficer.sales.view');
        Route::get('/sale-memo{id}', [sales::class,'saleMemo'])->name('executiveofficer.sales.memo');
        Route::get('/product_sc', [sales::class,'product_sc'])->name('executiveofficer.sales.product_sc');
        Route::get('/product_sc_d', [sales::class,'product_sc_d'])->name('executiveofficer.sales.product_sc_d');

        Route::get('/sales-pending-expense',[salPending::class,'sales_pending_expense'])->name('executiveofficer.sales_pending_exp');
        Route::get('/sales-payment',[salPending::class,'sales_customer_payment'])->name('executiveofficer.sales_pending_pay');

    });
});



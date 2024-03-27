<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;

use App\Models\Settings\Company;

use App\Models\Settings\Location\Country;
use App\Models\Settings\Location\District;
use App\Models\Settings\Location\Division;
use App\Models\Settings\Location\Thana;
use App\Models\Settings\Location\Upazila;
use App\Models\Currency\Currency;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Models\Customers\CustomerPayment;
use App\Models\Customers\CustomerPaymentDetails;
use App\Models\Expenses\ExpenseOfPurchase;
use App\Models\Expenses\ExpenseOfSales;
use App\Models\Products\LcNumber;
use App\Models\Purchases\Beparian_purchase;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\Purchase_details;
use App\Models\Purchases\PurReceiveInformation;
use App\Models\Purchases\Regular_purchase;
use App\Models\AllReturn\Beparian_purchase_return;
use App\Models\AllReturn\Beparian_purchase_return_detail;
use App\Models\AllReturn\Purchase_return;
use App\Models\AllReturn\Purchase_return_details;
use App\Models\AllReturn\Regular_purchase_return;
use App\Models\AllReturn\Regular_purchase_return_detail;
use App\Models\AllReturn\Sale_return;
use App\Models\AllReturn\Sale_return_detail;
use App\Models\Sales\BagDetail;
use App\Models\Sales\Sales;
use App\Models\Sales\Sales_details;
use App\Models\Stock\InitialStock;
use App\Models\Stock\InitialStockDetail;
use App\Models\Stock\Stock;
use App\Models\Suppliers\SupplierPayment;
use App\Models\Suppliers\SupplierPaymentDetails;
use App\Models\Vouchers\CreditVoucher;
use App\Models\Vouchers\CreVoucherBkdn;
use App\Models\Vouchers\DebitVoucher;
use App\Models\Vouchers\DevoucherBkdn;
use App\Models\Vouchers\GeneralLedger;
use App\Models\Vouchers\GeneralVoucher;
use App\Models\Vouchers\JournalVoucher;
use App\Models\Vouchers\JournalVoucherBkdn;
use App\Models\Vouchers\PurchaseReturnVoucher;
use App\Models\Vouchers\PurchaseVoucher;
use App\Models\Vouchers\PurReturnVoucherBkdn;
use App\Models\Vouchers\PurVoucherBkdns;
use App\Models\Vouchers\SaleReturnVoucher;
use App\Models\Vouchers\SalesVoucher;
use App\Models\Vouchers\SalReturnVoucherBkdn;
use App\Models\Vouchers\SalVoucherBkdns;
use Exception;

class CompanyController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Company::where('id',company()['company_id'])->first();
        return view('company.index',compact('data'));

    }

    public function allCompany()
    {
        $data = Company::select('id','name','contact')->get();
        return view('company.delete',compact('data'));

    }

    public function deleteCompanyWise(Request  $request){
        try{
            if($request->company_id){
                $id = $request->company_id;
                Stock::where('company_id',$id)->delete();
                InitialStock::where('company_id',$id)->delete();
                InitialStockDetail::where('company_id',$id)->delete();
                Purchase::where('company_id',$id)->delete();
                Purchase_details::where('company_id',$id)->delete();
                Purchase_return::where('company_id',$id)->delete();
                Purchase_return_details::where('company_id',$id)->delete();
                Beparian_purchase::where('company_id',$id)->delete();
                Beparian_purchase_return::where('company_id',$id)->delete();
                Beparian_purchase_return_detail::where('company_id',$id)->delete();
                Regular_purchase::where('company_id',$id)->delete();
                Regular_purchase_return::where('company_id',$id)->delete();
                Regular_purchase_return_detail::where('company_id',$id)->delete();
                ExpenseOfPurchase::where('company_id',$id)->delete();
                PurReceiveInformation::where('company_id',$id)->delete();
                PurchaseVoucher::where('company_id',$id)->delete();
                PurVoucherBkdns::where('company_id',$id)->delete();
                PurchaseReturnVoucher::where('company_id',$id)->delete();
                PurReturnVoucherBkdn::where('company_id',$id)->delete();
                GeneralLedger::where('company_id',$id)->delete();
                GeneralVoucher::where('company_id',$id)->delete();

                Sales::where('company_id',$id)->delete();
                Sales_details::where('company_id',$id)->delete();
                Sale_return::where('company_id',$id)->delete();
                Sale_return_detail::where('company_id',$id)->delete();
                BagDetail::where('company_id',$id)->delete();
                ExpenseOfSales::where('company_id',$id)->delete();
                SalesVoucher::where('company_id',$id)->delete();
                SalVoucherBkdns::where('company_id',$id)->delete();
                SaleReturnVoucher::where('company_id',$id)->delete();
                SalReturnVoucherBkdn::where('company_id',$id)->delete();

                SupplierPayment::where('company_id',$id)->delete();
                SupplierPaymentDetails::where('company_id',$id)->delete();
                CustomerPayment::where('company_id',$id)->delete();
                CustomerPaymentDetails::where('company_id',$id)->delete();

                DebitVoucher::where('company_id',$id)->delete();
                DevoucherBkdn::where('company_id',$id)->delete();
                CreditVoucher::where('company_id',$id)->delete();
                CreVoucherBkdn::where('company_id',$id)->delete();
                JournalVoucher::where('company_id',$id)->delete();
                JournalVoucherBkdn::where('company_id',$id)->delete();
                LcNumber::where('company_id',$id)->delete();
                
                return redirect()->back()->with($this->resMessageHtml(true,null,'Successfully deleted'));
            }else
                return redirect()->back()->with($this->resMessageHtml(false,'error',' try again'));
        }catch(Exception $e){
             //dd($e);
            return redirect()->back()->with($this->resMessageHtml(false,'error','Please try again'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function admindex()
    {
        $data = Company::all();
        return view('company.admin',compact('data'));

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
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $currency= Currency::all();
        $country = Country::all();
        $division = Division::all();
        $district = District::all();
        $upazila = Upazila::all();
        $thana = Thana::all();
        $company=Company::findOrFail(encryptor('decrypt',$id));
        return view('company.edit',compact('company','country','division','district','upazila','thana','currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $com=Company::findOrFail(encryptor('decrypt',$id));
            $com->name=$request->name;
            $com->lc_expense=$request->lc_expense;
            $com->income_head=$request->income_head;
            $com->expense_head=$request->expense_head;
            $com->tax_head=$request->tax_head;
            $com->company_bn=$request->company_bn;
            $com->contact=$request->contact;
            $com->contact_bn=$request->contact_bn;
            $com->address_bn=$request->address_bn;
            $com->email=$request->email;
            $com->country_id=$request->country;
            $com->division_id=$request->division;
            $com->district_id=$request->district;
            $com->upazila_id=$request->upazila;
            $com->thana_id=$request->thana;
            $com->address=$request->address;
            $com->currency=$request->currency;


            if($com->save())
                return redirect()->route(currentUser().'.company.index')->with($this->resMessageHtml(true,null,'Successfully updated'));
            else
                return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }catch(Exception $e){
            // dd($e);
            return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        //
    }
}

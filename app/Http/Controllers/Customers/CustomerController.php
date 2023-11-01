<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;

use App\Models\Settings\Location\Country;
use App\Models\Settings\Location\Division;
use App\Models\Settings\Location\District;
use App\Models\Settings\Location\Upazila;
use App\Models\Customers\Customer;
use App\Models\Settings\Branch;
use Illuminate\Http\Request;
use App\Http\Requests\Customer\AddNewRequest;
use App\Http\Requests\Customer\UpdateRequest;
use App\Http\Traits\ResponseTrait;
use App\Models\Accounts\Child_two;
use Exception;

class CustomerController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( currentUser()=='owner')
            $customers = Customer::where(company());
        else
            $customers = Customer::where(company())->where(branch());

        if($request->name)
            $customers=$customers->where('customer_name','like','%'.$request->name.'%');

        $customers=$customers->paginate(15);

        return view('customer.index',compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        $divisions = Division::all();
        $districts = District::all();
        $upazilas = Upazila::all();
        $branches = Branch::where(company())->get();
        return view('customer.create',compact('countries','divisions','districts','branches','upazilas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $cus= new Customer;
            $cus->customer_name= $request->customerName;
            $cus->contact= $request->contact;
            $cus->email= $request->email;
            $cus->phone= $request->phone;
            $cus->tax_number= $request->taxNumber;
            $cus->gst_number= $request->gstNumber;
            $cus->opening_balance= $request->openingAmount;
            $cus->country_id= $request->countryName;
            $cus->division_id= $request->divisionName;
            $cus->district_id= $request->districtName;
            $cus->upazila_id= $request->upazilaName;
            $cus->post_code= $request->postCode;
            $cus->post_code= $request->postCode;
            $cus->address= $request->address;
            $cus->company_id=company()['company_id'];
            $cus->branch_id?branch()['branch_id']:null;
            if($cus->save()){
                $ach = new Child_two;
                $ach->child_one_id=3;
                $ach->company_id=company()['company_id'];
                $ach->head_name= $request->customerName;
                $ach->head_code = '1130'.$cus->id;
                $ach->opening_balance =0;
                $ach->save();

                $cus->account_id= $ach->id;
                $cus->save();
                return redirect()->route(currentUser().'.customer.index')->with($this->resMessageHtml(true,null,'Successfully created'));
            }else
                return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }catch(Exception $e){
            // dd($e);
            return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customers\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customers\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $countries = Country::all();
        $divisions = Division::all();
        $districts = District::all();
        $upazilas = Upazila::all();
        $branches = Branch::where(company())->get();
        $customer = Customer::findOrFail(encryptor('decrypt',$id));
        return view('customer.edit',compact('countries','divisions','districts','customer','branches','upazilas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customers\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        try{
            $sup= Customer::findOrFail(encryptor('decrypt',$id));
            $sup->customer_name= $request->customerName;
            $sup->contact= $request->contact;
            $sup->email= $request->email;
            $sup->phone= $request->phone;
            $sup->tax_number= $request->taxNumber;
            $sup->gst_number= $request->gstNumber;
            $sup->opening_balance= $request->openingAmount;
            $sup->country_id= $request->countryName;
            $sup->division_id= $request->divisionName;
            $sup->district_id= $request->districtName;
            $sup->upazila_id= $request->upazilaName;
            $sup->post_code= $request->postCode;
            $sup->post_code= $request->postCode;
            $sup->address= $request->address;
            if($sup->save()){
                $ach = Child_two::where('head_code', '1130' . $sup->id)->first();
                if($ach){
                    $ach->child_one_id=3;
                    $ach->company_id=company()['company_id'];
                    $ach->head_name= $request->customerName;
                    $ach->head_code = '1130'.$sup->id;
                    $ach->opening_balance =0;
                    $ach->save();
                }else{
                    $ach = new Child_two;
                    $ach->child_one_id=3;
                    $ach->company_id=company()['company_id'];
                    $ach->head_name= $request->customerName;
                    $ach->head_code = '1130'.$sup->id;
                    $ach->opening_balance =0;
                    $ach->save();
                }
                $sup->account_id= $ach->id;
                $sup->save();
                return redirect()->route(currentUser().'.customer.index')->with($this->resMessageHtml(true,null,'Successfully Updated'));
            }else
                return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }catch(Exception $e){
            //dd($e);
            return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customers\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat= Customer::findOrFail(encryptor('decrypt',$id));
        $cat->delete();
        return redirect()->back();
    }
}

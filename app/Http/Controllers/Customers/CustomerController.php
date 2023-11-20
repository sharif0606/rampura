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
use DB;

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

        $customers=$customers->orderBy('id', 'DESC')->paginate(15);

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
    public function store(AddNewRequest $request)
    {
        try{
            DB::beginTransaction();
            $cus= new Customer;
            $cus->customer_name= $request->customer_name;
            $cus->contact= $request->contact;
            $cus->email= $request->email;
            $cus->phone= $request->phone;
            $cus->tax_number= $request->taxNumber;
            $cus->gst_number= $request->gstNumber;
            $cus->opening_balance= $request->openingAmount ?? 0;
            $cus->country_id= $request->countryName;
            $cus->division_id= $request->divisionName;
            $cus->district_id= $request->districtName;
            $cus->upazila_id= $request->upazilaName;
            $cus->post_code= $request->postCode;
            $cus->post_code= $request->postCode;
            $cus->address= $request->address;
            $cus->created_by=currentUserId();
            $cus->company_id=company()['company_id'];
            //$cus->branch_id=branch()['branch_id'] ?? null;
            if($cus->save()){
                $ach = new Child_two;
                $ach->child_one_id=3;
                $ach->company_id=company()['company_id'];
                $ach->head_name= $request->customer_name;
                $ach->head_code = '1130'.$cus->id;
                $ach->created_by=currentUserId();
                $ach->opening_balance =$request->openingAmount ?? 0;
                if($ach->save()){
                    $cus->account_id= $ach->id;
                    $cus->save();
                    DB::commit();
                    return redirect()->route(currentUser().'.customer.index')->with($this->resMessageHtml(true,null,'Successfully created'));
                }
            }
            
        }catch(Exception $e){
            DB::rollback();
            dd($e);
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
    public function update(UpdateRequest $request,$id)
    {
        try{
            $sup= Customer::findOrFail(encryptor('decrypt',$id));
            $sup->customer_name= $request->customer_name;
            $sup->contact= $request->contact;
            $sup->email= $request->email;
            $sup->phone= $request->phone;
            $sup->tax_number= $request->taxNumber;
            $sup->gst_number= $request->gstNumber;
            $sup->opening_balance= $request->openingAmount ?? 0;
            $sup->country_id= $request->countryName;
            $sup->division_id= $request->divisionName;
            $sup->district_id= $request->districtName;
            $sup->upazila_id= $request->upazilaName;
            $sup->post_code= $request->postCode;
            $sup->post_code= $request->postCode;
            $sup->address= $request->address;
            $sup->updated_by=currentUserId();
            if($sup->save()){DB::enableQueryLog();
                $ach = Child_two::where('head_code', "1130$sup->id")->first();
                if($ach){
                    $ach->head_name= $request->customer_name;
                    $ach->opening_balance =$request->openingAmount ?? 0;
                    $ach->updated_by=currentUserId();
                    $ach->save();
                }else{
                    $ach = new Child_two;
                    $ach->child_one_id=3;
                    $ach->company_id=company()['company_id'];
                    $ach->head_name= $request->customer_name;
                    $ach->head_code = '1130'.$sup->id;
                    $ach->opening_balance =$request->openingAmount ?? 0;
                    $ach->created_by=currentUserId();
                    $ach->save();
                    $sup->account_id= $ach->id;
                    $sup->save();
                }
                return redirect()->route(currentUser().'.customer.index')->with($this->resMessageHtml(true,null,'Successfully Updated'));
            }else
                return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }catch(Exception $e){
            dd($e);
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
        $sup= Customer::findOrFail(encryptor('decrypt',$id));
        if($sup->sales->count() > 0){
            return redirect()->back()->with($this->resMessageHtml(false,'error','You cannot delete this customer because you have already sales under this customer'));
        }else{
            $account_id=$sup->account_id;
            if($sup->delete()){
                Child_two::destroy($account_id);
            }
            return redirect()->back()->with($this->resMessageHtml(true,null,'Successfully Deleted'));
        }
        
    }
}

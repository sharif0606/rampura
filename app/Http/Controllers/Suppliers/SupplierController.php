<?php

namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;

use App\Models\Settings\Location\Country;
use App\Models\Settings\Location\Division;
use App\Models\Settings\Location\District;
use App\Models\Settings\Location\Upazila;
use App\Models\Suppliers\Supplier;
use App\Models\Settings\Branch;
use Illuminate\Http\Request;
use App\Http\Requests\Supplier\AddNewRequest;
use App\Http\Requests\Supplier\UpdateRequest;
use App\Http\Traits\ResponseTrait;
use App\Models\Accounts\Child_one;
use App\Models\Accounts\Child_two;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $suppliers = Supplier::where(company());
        // if( currentUser()=='owner')
        //     $suppliers = Supplier::where(company());
        // else
        //     $suppliers = Supplier::where(company())->where(branch());
        
        if($request->name)
        $suppliers=$suppliers->where('supplier_name','like','%'.$request->name.'%');

        $suppliers=$suppliers->orderBy('id', 'DESC')->paginate(15);

        return view('supplier.index',compact('suppliers'));
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
        return view('supplier.create',compact('countries','divisions','districts','branches','upazilas'));
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
            $sup= new Supplier;
            $sup->supplier_name= $request->supplier_name;
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
            $sup->branch_id= $request->branch_id;
            $sup->company_id=company()['company_id'];
            $sup->created_by=currentUserId();
            if($sup->save()){
                $id_child_one = Child_one::where('head_code','2130')->where(company())->first();
                $ach = new Child_two;
                $ach->child_one_id= $id_child_one->id;
                $ach->company_id=company()['company_id'];
                $ach->head_name=$request->supplier_name;
                $ach->head_code = '2130'.$sup->id;
                $ach->opening_balance =$request->openingAmount ?? 0;
                if($request->openingAmount > 0){
                    $ach->opening_balance_date= $request->opening_balance_date;
                }
                $ach->created_by=currentUserId();
                if($ach->save()){
                    $sup->account_id= $ach->id;
                    $sup->save();
                    DB::commit();
                    return redirect()->route(currentUser().'.supplier.index')->with($this->resMessageHtml(true,null,'Successfully created'));
                }else
                    return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
            }else
                return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }catch(Exception $e){
            DB::rollback();
            //dd($e);
            return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Suppliers\supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Suppliers\supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $countries = Country::all();
        $divisions = Division::all();
        $districts = District::all();
        $upazilas = Upazila::all();
        $branches = Branch::where(company())->get();
        $supplier = Supplier::findOrFail(encryptor('decrypt',$id));
        return view('supplier.edit',compact('countries','divisions','districts','supplier','branches','upazilas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Suppliers\supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        try{
            $sup= Supplier::findOrFail(encryptor('decrypt',$id));
            $sup->supplier_name= $request->supplier_name;
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
            if($sup->save()){
                $ach = Child_two::where('head_code', '2130' . $sup->id)->where(company())->first();
                if($ach){
                    $ach->head_name=$request->supplier_name;
                    $ach->opening_balance =$request->openingAmount ?? 0;
                    $ach->updated_by=currentUserId();
                    $ach->save();
                }else{
                    $id_child_one = Child_one::where('head_code','2130')->where(company())->first();
                    $ach = new Child_two;
                    $ach->child_one_id=$id_child_one->id;
                    $ach->company_id=company()['company_id'];
                    $ach->head_name=$request->supplier_name;
                    $ach->head_code = '2130'.$sup->id;
                    $ach->opening_balance =$request->openingAmount ?? 0;
                    if($request->openingAmount > 0){
                        $ach->opening_balance_date= $request->opening_balance_date;
                    }
                    $ach->created_by=currentUserId();
                    $ach->save();
                    $sup->account_id= $ach->id;
                    $sup->save();
                }
                return redirect()->route(currentUser().'.supplier.index')->with($this->resMessageHtml(true,null,'Successfully Updated'));
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
     * @param  \App\Models\Suppliers\supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sup= Supplier::findOrFail(encryptor('decrypt',$id));
        if($sup->beparian_purchase->count() > 0 || $sup->purchase->count() > 0 || $sup->regular_purchase->count() > 0 ){
            return redirect()->back()->with($this->resMessageHtml(false,'error','You cannot delete this supplier because you have already purchased under this supplier'));
        }else{
            $account_id=$sup->account_id;
            if($sup->delete()){
                Child_two::destroy($account_id);
            }
            return redirect()->back()->with($this->resMessageHtml(true,null,'Successfully Deleted'));
        }
    }
}

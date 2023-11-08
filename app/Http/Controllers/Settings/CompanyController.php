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

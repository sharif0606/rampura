<?php

namespace App\Http\Controllers\Settings\Location;

use App\Http\Controllers\Controller;

use App\Models\Settings\Location\Country;
use App\Models\Settings\Location\Division;
use Illuminate\Http\Request;
use App\Http\Requests\Division\AddNewRequest;
use App\Http\Requests\Division\UpdateRequest;
use App\Http\Traits\ResponseTrait;
use Exception;

class DivisionController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $divisions=Division::all();
        return view('settings.location.division.index',compact('divisions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries= Country::all();
        return view('settings.location.division.create',compact('countries'));
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
            $division=new Division;
            $division->country_id=$request->country;
            $division->name=$request->divisionName;
            $division->name_bn=$request->divisionBn;
            if($division->save())
                return redirect()->route(currentUser().'.division.index')->with($this->resMessageHtml(true,null,'Successfully created'));
            else
                return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }catch(Exception $e){
            // dd($e);
            return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Settings\Location\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function show($division)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Settings\Location\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function edit($division)
    {
        $countries= Country::all();
        $division=Division::findOrFail(encryptor('decrypt',$division));
        return view('settings.location.division.edit',compact('division','countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Settings\Location\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request,$division)
    {
        try{
            $division=Division::findOrFail(encryptor('decrypt',$division));
            $division->country_id=$request->country;
            $division->name=$request->divisionName;
            $division->name_bn=$request->divisionBn;
            if($division->save())
                return redirect()->route(currentUser().'.division.index')->with($this->resMessageHtml(true,null,'Successfully updated'));
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
     * @param  \App\Models\Settings\Location\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function destroy(Division $division)
    {
        //
    }
}

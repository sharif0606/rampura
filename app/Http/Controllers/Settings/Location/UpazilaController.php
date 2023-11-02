<?php

namespace App\Http\Controllers\Settings\Location;

use App\Http\Controllers\Controller;

use App\Models\Settings\Location\District;
use App\Models\Settings\Location\Upazila;
use Illuminate\Http\Request;
use App\Http\Requests\Upazila\AddNewRequest;
use App\Http\Requests\Upazila\UpdateRequest;
use App\Http\Traits\ResponseTrait;
use Exception;

class UpazilaController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $upazilas=Upazila::all();
       return view('settings.location.upazila.index',compact('upazilas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $districts=District::all();
        return view('settings.location.upazila.create',compact('districts'));
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
            $upazila=new Upazila;
            $upazila->district_id=$request->district_id;
            $upazila->name=$request->upazilaName;
            $upazila->name_bn=$request->upazilaBn;
            $upazila->created_by=currentUserId();
            if($upazila->save())
                return redirect()->route(currentUser().'.upazila.index')->with($this->resMessageHtml(true,null,'Successfully created'));
            else
                return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','please try again'));    
        }catch(Exception $e){
            // dd($e);
            return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Settings\Location\Upazila  $upazila
     * @return \Illuminate\Http\Response
     */
    public function show(Upazila $upazila)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Settings\Location\Upazila  $upazila
     * @return \Illuminate\Http\Response
     */
    public function edit($upazila)
    {
        $districts=District::all();
        $upazila=Upazila::findOrFail(encryptor('decrypt',$upazila));
        return view('settings.location.upazila.edit',compact('upazila','districts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Settings\Location\Upazila  $upazila
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $upazila)
    {
        try{
            $upazila=Upazila::findOrFail(encryptor('decrypt',$upazila));
            $upazila->district_id=$request->district_id;
            $upazila->name=$request->upazilaName;
            $upazila->name_bn=$request->upazilaBn;
            $upazila->updated_by=currentUserId();
            if($upazila->save())
                return redirect()->route(currentUser().'.upazila.index')->with($this->resMessageHtml(true,null,'Successfully update'));
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
     * @param  \App\Models\Settings\Location\Upazila  $upazila
     * @return \Illuminate\Http\Response
     */
    public function destroy(Upazila $upazila)
    {
        //
    }
}

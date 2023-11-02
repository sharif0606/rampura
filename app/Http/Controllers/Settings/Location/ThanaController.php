<?php

namespace App\Http\Controllers\Settings\Location;

use App\Http\Controllers\Controller;

use App\Models\Settings\Location\Thana;
use App\Models\Settings\Location\Upazila;
use Illuminate\Http\Request;
use App\Http\Requests\Thana\AddNewRequest;
use App\Http\Requests\Thana\UpdateRequest;
use App\Http\Traits\ResponseTrait;
use Exception;

class ThanaController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $thanas=Thana::all();
        return view('settings.location.thana.index',compact('thanas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $upazilas=Upazila::all();
        return view('settings.location.thana.create',compact('upazilas'));
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
            $thana=new Thana;
            $thana->upazila_id=$request->upazila_id;
            $thana->name=$request->thanaName;
            $thana->name_bn=$request->thanaBn;
            $thana->created_by=currentUserId();
            if($thana->save())
                return redirect()->route(currentUser().'.thana.index')->with($this->resMessageHtml(true,null,'Successfully created'));
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
     * @param  \App\Models\Settings\Location\Thana  $thana
     * @return \Illuminate\Http\Response
     */
    public function show(Thana $thana)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Settings\Location\Thana  $thana
     * @return \Illuminate\Http\Response
     */
    public function edit($thana)
    {
        $upazilas=Upazila::all();
        $thana= Thana::findOrFail(encryptor('decrypt',$thana));
        return view('settings.location.thana.edit',compact('thana','upazilas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Settings\Location\Thana  $thana
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $thana)
    {
        try{
            $thana= Thana::findOrFail(encryptor('decrypt',$thana));
            $thana->upazila_id=$request->upazila_id;
            $thana->name=$request->thanaName;
            $thana->name_bn=$request->thanaBn;
            $thana->updated_by=currentUserId();
            if($thana->save())
                return redirect()->route(currentUser().'.thana.index')->with($this->resMessageHtml(true,null,'Successfully update'));
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
     * @param  \App\Models\Settings\Location\Thana  $thana
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thana $thana)
    {
        //
    }
}

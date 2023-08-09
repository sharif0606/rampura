<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings\Branch;
use App\Models\Currency\Currency;
use Illuminate\Http\Request;
use App\Http\Requests\Branch\AddNewRequest;
use App\Http\Requests\Branch\UpdateRequest;
use App\Http\Traits\ResponseTrait;
use Exception;


class BranchController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $branchs= Branch::where(company())->paginate(10);
        return view('branch.index',compact('branchs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currency = Currency::all();
        return view('branch.create',compact('currency'));
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
            $cat= new Branch;
            $cat->name=$request->name;
            $cat->currency=$request->currency;
            $cat->contact=$request->contact;
            $cat->binNumber=$request->binNumber;
            $cat->tradeNumber=$request->tradeNumber;
            $cat->country_id=$request->country_id;
            $cat->division_id=$request->division_id;
            $cat->district_id=$request->district_id;
            $cat->upazila_id=$request->upazila_id;
            $cat->thana_id=$request->thana_id;
            $cat->address=$request->address;
            $cat->status=1;


            $cat->company_id=company()['company_id'];
            
            
            if($cat->save())
                return redirect()->route(currentUser().'.branch.index')->with($this->resMessageHtml(true,null,'Successfully created'));
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
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $currency= Currency::all();
        $branch=Branch::findOrFail(encryptor('decrypt',$id));
        return view('branch.edit',compact('branch','currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        try{
            $cat= Branch::findOrFail(encryptor('decrypt',$id));
            $cat->name=$request->name;
            $cat->currency=$request->currency;
            $cat->contact=$request->contact;
            $cat->binNumber=$request->binNumber;
            $cat->tradeNumber=$request->tradeNumber;
            $cat->country_id=$request->country_id;
            $cat->division_id=$request->division_id;
            $cat->district_id=$request->district_id;
            $cat->upazila_id=$request->upazila_id;
            $cat->thana_id=$request->thana_id;
            $cat->address=$request->address;

                
            if($cat->save())
                return redirect()->route(currentUser().'.branch.index')->with($this->resMessageHtml(true,null,'Successfully created'));
            else
                return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }catch(Exception $e){
            //dd($e);
            return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat= Branch::findOrFail(encryptor('decrypt',$id));
        $cat->delete();
        return redirect()->back();
    
    }
}


<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings\Warehouse;
use App\Models\Settings\Company;
use Illuminate\Http\Request;
use App\Http\Requests\Warehouse\AddNewRequest;
use App\Http\Requests\Warehouse\UpdateRequest;
use App\Http\Traits\ResponseTrait;
use App\Models\Settings\Branch;
use Exception;

class WarehouseController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $warehouses= Warehouse::where(company())->paginate(10);
        return view('warehouse.index',compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branch = Branch::where(company())->get();
        return view('warehouse.create',compact('branch'));
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
            $war= new Warehouse;
            $war->company_id=company()['company_id'];
            $war->branch_id=$request->branch;
            $war->name=$request->name;
            $war->contact=$request->contact;
            $war->address=$request->address;

            if($war->save())
                return redirect()->route(currentUser().'.warehouse.index')->with($this->resMessageHtml(true,null,'Successfully created'));
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
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function show(Warehouse $warehouse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        $branch = Branch::where(company())->get();
        $warehouse=Warehouse::findOrFail(encryptor('decrypt',$id));
        return view('warehouse.edit',compact('warehouse','branch'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        try{
            $war= Warehouse::findOrFail(encryptor('decrypt',$id));
            $war->name=$request->name;
            $war->company_id=company()['company_id'];
            $war->branch_id=$request->branch;
            $war->address=$request->address;
            $war->contact=$request->contact;

            if($war->save())
                return redirect()->route(currentUser().'.warehouse.index')->with($this->resMessageHtml(true,null,'Successfully created'));
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
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $war= Warehouse::findOrFail(encryptor('decrypt',$id));
        $war->delete();
        return redirect()->back();
    
    }
}



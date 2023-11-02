<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;

use App\Models\Products\Childcategory;
use App\Models\Products\Subcategory;
use App\Http\Requests\Childcategory\AddNewRequest;
use App\Http\Requests\Childcategory\UpdateRequest;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Exception;

class ChildcategoryController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $childcategories = Childcategory::where(company())->paginate(10);
        return view('childcategory.index',compact('childcategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subcategories = Subcategory::where(company())->get();
        return view('childcategory.create',compact('subcategories'));
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
            $childcat= new Childcategory;
            $childcat->subcategory_id=$request->subcategory;
            $childcat->name=$request->childcat;
            $childcat->company_id=company()['company_id'];
            $childcat->created_by=currentUserId();
            if($childcat->save())
                return redirect()->route(currentUser().'.childcategory.index')->with($this->resMessageHtml(true,null,'Successfully created'));
            else
                return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }catch(Exception $e){
            //dd($e);
            return redirect()->back()->withInput()->with($this->resMessageHtml(false,'error','Please try again'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Products\Childcategory  $childcategory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Products\Childcategory  $childcategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subcategory = Subcategory::where(company())->get();
        $childcategory= Childcategory::findOrFail(encryptor('decrypt',$id));
        return view('childcategory.edit',compact('childcategory','subcategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Products\Childcategory  $childcategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        try{
            $childcat=Childcategory::findOrFail(encryptor('decrypt',$id));
            $childcat->subcategory_id=$request->subcategory;
            $childcat->name=$request->childcat;
            $childcat->updated_by=currentUserId();
            if($childcat->save())
                return redirect()->route(currentUser().'.childcategory.index')->with($this->resMessageHtml(true,null,'Successfully created'));
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
     * @param  \App\Models\Products\Childcategory  $childcategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Childcategory $childcategory)
    {
        //
    }
}

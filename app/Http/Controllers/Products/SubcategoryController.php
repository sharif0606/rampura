<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;

use App\Models\Products\Subcategory;
use App\Models\Products\Category;
use App\Http\Requests\Subcategory\AddNewRequest;
use App\Http\Requests\Subcategory\UpdateRequest;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Exception;

class SubcategoryController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subcategories=Subcategory::where(company());

        if($request->name)
        $subcategories=$subcategories->where('name','like','%'.$request->name.'%')
                                    ->orWhere('hs_code','like','%'.$request->name.'%')
                                    ->orWhere('custom_duty','like','%'.$request->name.'%');

        $subcategories=$subcategories->orderBy('id', 'DESC')->paginate(10);

        return view('subcategory.index',compact('subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where(company())->get();
        return view('subcategory.create',compact('categories'));
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
            $subcat= new Subcategory;
            $subcat->category_id=$request->category;
            $subcat->name=$request->subCat;
            $subcat->hs_code=$request->hs_code;
            $subcat->custom_duty=$request->custom_duty;
            $subcat->company_id=company()['company_id'];
            $subcat->created_by=currentUserId();
            if($subcat->save())
                return redirect()->route(currentUser().'.subcategory.index')->with($this->resMessageHtml(true,null,'Successfully created'));
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
     * @param  \App\Models\Products\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Products\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category=Category::where(company())->get();
        $subcategory= Subcategory::findOrFail(encryptor('decrypt',$id));
        return view('subcategory.edit',compact('subcategory','category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Products\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        try{
            $subcat=Subcategory::findOrFail(encryptor('decrypt',$id));
            $subcat->category_id=$request->category;
            $subcat->name=$request->subCat;
            $subcat->hs_code=$request->hs_code;
            $subcat->custom_duty=$request->custom_duty;
            $subcat->updated_by=currentUserId();
            if($subcat->save())
                return redirect()->route(currentUser().'.subcategory.index')->with($this->resMessageHtml(true,null,'Successfully created'));
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
     * @param  \App\Models\Products\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subcategory $subcategory)
    {
        //
    }
}

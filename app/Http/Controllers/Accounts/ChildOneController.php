<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;

use App\Models\Accounts\Child_one;
use App\Models\Accounts\Sub_head;
use Illuminate\Http\Request;
use App\Http\Requests\Accounts\ChildOne\AddNewRequest;
use App\Http\Requests\Accounts\ChildOne\UpdateRequest;
use App\Http\Traits\ResponseTrait;
use Exception;

class ChildOneController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data= Child_one::where(company())->paginate(10);
        return view('accounts.child_one.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data= Sub_head::where(company())->get();
        return view('accounts.child_one.create',compact('data'));
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
            $mac = new Child_one;
            $mac->company_id=company()['company_id'];
            $mac->sub_head_id= $request->sub_head;
            $mac->head_name= $request->head_name;
            $mac->head_code= $request->head_code;
            $mac->opening_balance= $request->opening_balance;
            $mac->created_by=currentUserId();

        if($mac->save())
                return redirect()->route(currentUser().'.child_one.index')->with($this->resMessageHtml(true,null,'Successfully created'));
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
     * @param  \App\Models\Accounts\child_one  $child_one
     * @return \Illuminate\Http\Response
     */
    public function show(child_one $child_one)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Accounts\child_one  $child_one
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data= Sub_head::where(company())->get();
        $child= Child_one::findOrFail(encryptor('decrypt',$id));
        return view('accounts.child_one.edit',compact('data','child'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Accounts\child_one  $child_one
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        try{
            $mac = Child_one::findOrFail(encryptor('decrypt',$id));
            $mac->sub_head_id= $request->sub_head;
            $mac->head_name= $request->head_name;
            $mac->head_code= $request->head_code;
            $mac->opening_balance= $request->opening_balance;
            $mac->updated_by=currentUserId();

        if($mac->save())
                return redirect()->route(currentUser().'.child_one.index')->with($this->resMessageHtml(true,null,'Successfully Updated'));
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
     * @param  \App\Models\Accounts\child_one  $child_one
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $child= Child_one::findOrFail(encryptor('decrypt',$id));
        $child->delete();
        return redirect()->back();
    }
}

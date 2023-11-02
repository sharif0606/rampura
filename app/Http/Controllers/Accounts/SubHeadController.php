<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;

use App\Models\Accounts\Sub_head;
use App\Models\Accounts\Master_account;
use Illuminate\Http\Request;
use App\Http\Requests\Accounts\Subhead\AddNewRequest;
use App\Http\Requests\Accounts\Subhead\UpdateRequest;
use App\Http\Traits\ResponseTrait;
use Exception;

class SubHeadController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data= Sub_head::where(company())->paginate(10);
        return view('accounts.sub_head.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data= Master_account::where(company())->get();
        return view('accounts.sub_head.create',compact('data'));
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
            $mac = new Sub_head;
            $mac->company_id=company()['company_id'];
            $mac->master_head_id= $request->master_head;
            $mac->head_name= $request->head_name;
            $mac->head_code= $request->head_code;
            $mac->opening_balance= $request->opening_balance;
            $mac->created_by=currentUserId();

        if($mac->save())
                return redirect()->route(currentUser().'.sub_head.index')->with($this->resMessageHtml(true,null,'Successfully created'));
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
     * @param  \App\Models\Accounts\sub_head  $sub_head
     * @return \Illuminate\Http\Response
     */
    public function show(sub_head $sub_head)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Accounts\sub_head  $sub_head
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data= Master_account::where(company())->get();
        $sub= Sub_head::findOrFail(encryptor('decrypt',$id));
        return view('accounts.sub_head.edit',compact('data','sub'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Accounts\sub_head  $sub_head
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $mac = Sub_head::findOrFail(encryptor('decrypt',$id));
            $mac->master_head_id= $request->master_head;
            $mac->head_name= $request->head_name;
            $mac->head_code= $request->head_code;
            $mac->opening_balance= $request->opening_balance;
            $mac->updated_by=currentUserId();

        if($mac->save())
                return redirect()->route(currentUser().'.sub_head.index')->with($this->resMessageHtml(true,null,'Successfully Updated'));
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
     * @param  \App\Models\Accounts\sub_head  $sub_head
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sub= Sub_head::findOrFail(encryptor('decrypt',$id));
        $sub->delete();
        return redirect()->back();
    }
}

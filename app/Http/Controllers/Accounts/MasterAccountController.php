<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;

use App\Models\Accounts\Master_account;
use App\Models\Settings\Company;
use Illuminate\Http\Request;
use App\Http\Requests\Accounts\Master\AddNewRequest;
use App\Http\Requests\Accounts\Master\UpdateRequest;
use App\Http\Traits\ResponseTrait;
use Exception;


class MasterAccountController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data= Master_account::where(company())->paginate(10);
        return view('accounts.master.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('accounts.master.create');
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
            $mac = new Master_account;
            $mac->company_id=company()['company_id'];
            $mac->head_name= $request->head_name;
            $mac->head_code= $request->head_code;
            $mac->opening_balance= $request->opening_balance;

        if($mac->save())
                return redirect()->route(currentUser().'.master.index')->with($this->resMessageHtml(true,null,'Successfully created'));
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
     * @param  \App\Models\Accounts\master_account  $master_account
     * @return \Illuminate\Http\Response
     */
    public function show(master_account $master_account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Accounts\master_account  $master_account
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $mac = Master_account::findOrFail(encryptor('decrypt',$id));
        return view('accounts.master.edit',compact('mac'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Accounts\master_account  $master_account
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        try{
            $mac = Master_account::findOrFail(encryptor('decrypt',$id));;
            $mac->head_name= $request->head_name;
            $mac->head_code= $request->head_code;
            $mac->opening_balance= $request->opening_balance;

        if($mac->save())
                return redirect()->route(currentUser().'.master.index')->with($this->resMessageHtml(true,null,'Successfully Updated'));
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
     * @param  \App\Models\Accounts\master_account  $master_account
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mac= Master_account::findOrFail(encryptor('decrypt',$id));
        $mac->delete();
        return redirect()->back();
    }
}

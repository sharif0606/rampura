<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;

use App\Models\Accounts\Child_two;
use App\Models\Accounts\Child_one;
use Illuminate\Http\Request;
use App\Http\Requests\Accounts\ChildTwo\AddNewRequest;
use App\Http\Requests\Accounts\ChildTwo\UpdateRequest;
use App\Http\Traits\ResponseTrait;
use Exception;

class ChildTwoController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data= Child_two::where(company());

        if($request->name)
        $data=$data->where('head_name','like','%'.$request->name.'%')
                                    ->orWhere('head_code','like','%'.$request->name.'%');

        $data=$data->orderBy('id', 'DESC')->paginate(10);
        return view('accounts.child_two.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data= Child_one::where(company())->get();
        return view('accounts.child_two.create',compact('data'));
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
            $mac = new Child_two;
            $mac->company_id=company()['company_id'];
            $mac->child_one_id= $request->child_one;
            $mac->head_name= $request->head_name;
            $mac->head_code= $request->head_code;
            $mac->opening_balance= $request->opening_balance;
            $mac->created_by=currentUserId();

        if($mac->save())
                return redirect()->route(currentUser().'.child_two.index')->with($this->resMessageHtml(true,null,'Successfully created'));
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
     * @param  \App\Models\Accounts\child_two  $child_two
     * @return \Illuminate\Http\Response
     */
    public function show(child_two $child_two)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Accounts\child_two  $child_two
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data= Child_one::where(company())->get();
        $child= Child_two::findOrFail(encryptor('decrypt',$id));
        return view('accounts.child_two.edit',compact('data','child'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Accounts\child_two  $child_two
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        try{
            $mac = Child_two::findOrFail(encryptor('decrypt',$id));
            $mac->child_one_id= $request->child_one;
            $mac->head_name= $request->head_name;
            $mac->head_code= $request->head_code;
            $mac->opening_balance= $request->opening_balance;
            $mac->updated_by=currentUserId();

        if($mac->save())
                return redirect()->route(currentUser().'.child_two.index')->with($this->resMessageHtml(true,null,'Successfully Updated'));
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
     * @param  \App\Models\Accounts\child_two  $child_two
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $child= Child_two::findOrFail(encryptor('decrypt',$id));
        $child->delete();
        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\Settings\Branch;
use App\Http\Traits\ResponseTrait;
use App\Http\Traits\ImageHandleTraits;
use App\Http\Requests\User\AddNewRequest;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserController extends Controller
{
    use ResponseTrait,ImageHandleTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users=User::where(company())->whereNot('id',currentUserId())->get();
        return view('settings.users.index',compact('users'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(currentUser() == 'owner'){
            $branches=Branch::where(company())->get();
            $roles=Role::whereIn('id',[3,4,5])->get();
        }else{
            $branches=Branch::where(company())->get();
            $roles=Role::where('id',[4,5])->get();
        }
       
        return view('settings.users.create',compact('roles','branches'));
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
            $user=new User;
            $user->name=$request->userName;
            $user->contact_no=$request->contactNumber;
            $user->email=$request->userEmail;
            $user->password=Hash::make($request->password);
            $user->company_id=company()['company_id'];
            $user->branch_id=$request->branch_id;
            $user->role_id=$request->role_id;
            $user->created_by=currentUserId();
            if($request->has('image'))
                $user->image=$this->resizeImage($request->image,'images/users/'.company()['company_id'],true,200,200,false);
            if($user->save())
                return redirect()->route(currentUser().'.users.index')->with($this->resMessageHtml(true,null,'Successfully Registred'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(currentUser() == 'owner'){
            $branches=Branch::where(company())->get();
            $roles=Role::whereIn('id',[3,4,5])->get();
        }else{
            $branches=Branch::where(company())->get();
            $roles=Role::whereIn('id',[4,5])->get();
        }
        $user=User::findOrFail(encryptor('decrypt',$id));
       
        return view('settings.users.edit',compact('roles','branches','user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $user=User::findOrFail(encryptor('decrypt',$id));
            $user->name=$request->userName;
            $user->contact_no=$request->contactNumber;
            $user->email=$request->userEmail;
            if($request->has('password') && $request->password)
                $user->password=Hash::make($request->password);

            $user->branch_id=$request->branch_id;
            $user->role_id=$request->role_id;
            $user->updated_by=currentUserId();
            $path='images/users/'.company()['company_id'];
            if($request->has('image') && $request->image)
                if($this->deleteImage($user->image,$path))
                    $user->image=$this->resizeImage($request->image,$path,true,200,200,false);

            if($user->save())
                if($user->id == currentUserId()){
                    request()->session()->put(
                        [
                            'image'=>$user->image?$user->image:$user->image,
                            'userName'=>encryptor('encrypt',$user->name),
                        ]);
                    return redirect()->route(currentUser().'.profile.update')->with($this->resMessageHtml(true,null,'Successfully updated'));
                }else{
                    return redirect()->route(currentUser().'.users.index')->with($this->resMessageHtml(true,null,'Successfully updated'));
                }
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

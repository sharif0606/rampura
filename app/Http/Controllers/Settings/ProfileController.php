<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Traits\ResponseTrait;
use App\Http\Traits\ImageHandleTraits;
use Illuminate\Support\Facades\Hash;
use Exception;

class ProfileController extends Controller
{
    use ResponseTrait,ImageHandleTraits;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ownerProfile()
    {
        
        $users=User::where(company(currentUserId()))->first();
        // dd(currentUserId());
           return view('settings.users.profile',compact('users'));
          
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminProfile()
    {
        
        $users=User::where(company(currentUserId()))->first();
        // dd(currentUserId());
        return view('settings.adminusers.profiles',compact('users'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function aProfileUpdate(Request $request)
    {
        try{
            $user=User::findOrFail(currentUserId());
            $user->name=$request->userName;
            $user->contact_no=$request->contactNumber;
            $user->email=$request->userEmail;
            $user->language=$request->language;
            if($request->has('password') && $request->password)
                $user->password=Hash::make($request->password);
                
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

}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User; // custome
use Session; // custome
use App\Http\Traits\ResponseTrait; // custome

class isSalesman
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Session::has('userId') || Session::has('userId')==null || !Session::has('roleIdentity')){
            return redirect()->route('logOut');
        }else{
            $user=User::find(currentUserId());
            app()->setLocale($user->language); // language
            if(!$user){
                return redirect()->route('logOut');
            }else if(currentUser() != 'accountsofficer'){
                return redirect()->back()->with($this->resMessageHtml(false,'error','Access Denied'));
            }else{
                return $next($request);
            }
        }
        return redirect()->route('logOut');
    }
}

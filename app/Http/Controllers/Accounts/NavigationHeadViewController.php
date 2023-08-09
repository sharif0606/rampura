<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;

use App\Models\Accounts\Master_account;
use Illuminate\Http\Request;


class NavigationHeadViewController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data= Master_account::where(company())->get();
        return view('accounts.navigate.index',compact('data'));
    }

    
}

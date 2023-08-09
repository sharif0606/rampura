<?php

namespace App\Http\Controllers\Currency;

use App\Http\Controllers\Controller;

use App\Models\Currency\Currency;
use Illuminate\Http\Request;
use App\Http\Requests\Currency\AddNewRequest;
use App\Http\Requests\Currency\UpdateRequest;
use App\Http\Traits\ResponseTrait;
use Exception;

class CurrencyController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currency=Currency::paginate(10);
        return view('currency.index',compact('currency'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('currency.create');
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
            $cur= new Currency;
            $cur->currency_name=$request->currency;
            $cur->currency_symbol=$request->symbol;
            $cur->currency_port=$request->port;
            $cur->currency_rate=$request->rate;
            if($cur->save())
                return redirect()->route(currentUser().'.currency.index')->with($this->resMessageHtml(true,null,'Successfully created'));
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
     * @param  \App\Models\Currency\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Currency\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $currency=Currency::findOrFail(encryptor('decrypt',$id));
        return view('currency.edit',compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Currency\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request,$id)
    {
        try{
            $cur= Currency::findOrFail(encryptor('decrypt',$id));
            $cur->currency_name=$request->currency;
            $cur->currency_symbol=$request->symbol;
            $cur->currency_port=$request->port;
            $cur->currency_rate=$request->rate;
            if($cur->save())
                return redirect()->route(currentUser().'.currency.index')->with($this->resMessageHtml(true,null,'Successfully updated'));
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
     * @param  \App\Models\Currency\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat= Currency::findOrFail(encryptor('decrypt',$id));
        $cat->delete();
        return redirect()->back();
    }
}

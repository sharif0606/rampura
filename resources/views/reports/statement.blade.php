@extends('layout.app')

@section('pageTitle',trans('Statement'))
@section('pageSubTitle',trans('Reports'))

@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="text-center no_print"><h4>STATEMENT</h4></div>
                    <div class="card-body">
                        <form class="form" method="get" action="">
                            @csrf
                            <div class="row no_print">
                                <div class="col-md-2 mt-2">
                                    <label for="fdate" class="float-end"><h6>{{__('From Date')}}</h6></label>
                                </div>
                                <div class="col-md-4">
                                    <input type="date" id="fdate" class="form-control" value="{{isset($_GET['fdate'])?$_GET['fdate']:''}}" name="fdate">
                                </div>


                                <div class="col-md-2 mt-2">
                                    <label for="tdate" class="float-end"><h6>{{__('To Date')}}</h6></label>
                                </div>
                                <div class="col-md-4">
                                    <input type="date" id="tdate" class="form-control" value="{{isset($_GET['tdate'])?$_GET['tdate']:''}}" name="tdate">
                                </div>
                            </div>
                            <div class="row m-4 no_print">
                                <div class="col-6 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-success me-1 mb-1 ps-5 pe-5">{{__('Show')}}</button>
                                </div>
                                <div class="col-6 d-flex justify-content-Start">
                                    <a href="{{route(currentUser().'.statement_report')}}" class="btn pbtn btn-sm btn-warning me-1 mb-1 ps-5 pe-5">{{__('Reset')}}</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table mb-5">
                                            <tbody>
                                                <tr class="text-center">
                                                    <th width="48%"><h5>{{__('Revieved')}}</h5></th>
                                                    <th width="4%"></th>
                                                    <th width="48%"><h5>{{__('Payment')}}</h5></th>
                                                </tr>
                                                {{-- sales and purchase payment --}}
                                                <tr>
                                                    @php
                                                        $drTotal = 0;
                                                        $crTotal = 0;
                                                        $grandTotal = 0;
                                                        $purGrandTotal = 0;
                                                    @endphp
                                                    <td style="vertical-align: top; height: 0;">
                                                        <table class="table mb-5">
                                                            <tbody>
                                                                <tr >
                                                                    <th class=" text-start">{{__('Account Title')}}</th>
                                                                    <th class=" text-end">{{__('Amount')}}</th>
                                                                </tr>
                                                                @foreach ($customerPayment as $cp)
                                                                <tr>
                                                                    <td class=" text-start">{{$cp->customer?->customer_name}}</td>
                                                                    <td class=" text-end">{{money_format($cp->dr)}}</td>
                                                                </tr>
                                                                @php
                                                                $drTotal += $cp->dr;
                                                                @endphp
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th class="text-start">Total</th>
                                                                    <th class="text-end">{{money_format($drTotal)}}</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </td>
                                                    <td widtd="4%"></td>
                                                    <td style="vertical-align: top; height: 0;">
                                                        <table class="table mb-5">
                                                            <tbody>
                                                                <tr >
                                                                    <th class=" text-start">{{__('Account Title')}}</th>
                                                                    <th class=" text-end">{{__('Amount')}}</th>
                                                                </tr>
                                                                @foreach ($supplierayment as $sp)
                                                                <tr>
                                                                    <td class=" text-start">{{$sp->supplier?->supplier_name}}</td>
                                                                    <td class=" text-end">{{money_format($sp->cr)}}</td>
                                                                </tr>
                                                                @php
                                                                $crTotal += $sp->cr;
                                                                @endphp
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th class="text-start">Total</th>
                                                                    <th class="text-end">{{money_format($crTotal)}}</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </td>
                                                </tr>
                                                {{-- sales and purchase payment --}}
                                                {{-- sales and purchase --}}
                                                <tr>
                                                    <td style="vertical-align: top; height: 0;" class="text-start">
                                                        <table class="table mb-5">
                                                            <tbody>
                                                                <tr >
                                                                    <th class=" text-start">Description</th>
                                                                    <th class=" text-end">{{__('Amount')}}</th>
                                                                </tr>
                                                                @foreach ($findSales as $sale)
                                                                <tr>
                                                                    <th class=" text-start">{{$sale->customer?->customer_name}}</th>
                                                                    <th class=" text-end">{{money_format($sale->grand_total)}}</th>
                                                                </tr>
                                                                @php
                                                                $grandTotal += $sale->grand_total;
                                                                @endphp
                                                                <tr>
                                                                    <td width="100%" class="text-start">
                                                                        @if($sale->sale_lot)
                                                                            @foreach ($sale->sale_lot as $sd)
                                                                                <table width="90%">
                                                                                    <tr>
                                                                                        <td class="text-start">{{$sd->product?->product_name}}</td>
                                                                                        <td class="text-end">({{money_format($sd->actual_quantity)}} * {{money_format($sd->rate_kg)}}) = {{money_format($sd->amount)}}</td>
                                                                                    </tr>
                                                                                    @if($sale->expense)
                                                                                        @foreach ($sale->expense as $ex)
                                                                                            <tr>
                                                                                                <td class="text-start">{{$ex->expense?->head_name}}</td>
                                                                                                <td class="text-end">{{money_format($ex->cost_amount)}}</td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    @endif
                                                                                </table>
                                                                            @endforeach
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th class="text-start">Total</th>
                                                                    <th class="text-end">{{money_format($grandTotal)}}</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </td>
                                                    <td widtd="4%"></td>
                                                    <td style="vertical-align: top; height: 0;" class="text-start">
                                                        <table class="table mb-5">
                                                            <tbody>
                                                                <tr >
                                                                    <th class=" text-start">Description</th>
                                                                    <th class=" text-end">{{__('Amount')}}</th>
                                                                </tr>
                                                                @foreach ($findPurchase as $pr)
                                                                    <tr>
                                                                        <th class=" text-start">{{$pr->supplier?->supplier_name}}</th>
                                                                        <th class=" text-end">{{money_format($pr->grand_total)}}</th>
                                                                    </tr>
                                                                    @php
                                                                    $purGrandTotal += $pr->grand_total;
                                                                    @endphp
                                                                    <tr>
                                                                        <td width="100%" class="text-start">
                                                                            @if($pr->purchase_lot)
                                                                                @foreach ($pr->purchase_lot as $pd)
                                                                                    <table width="90%">
                                                                                        <tr>
                                                                                            <td class="text-start">{{$pd->product?->product_name}}</td>
                                                                                            <td class="text-end">({{money_format($pd->actual_quantity)}} * {{money_format($pd->rate_kg)}}) = {{money_format($pd->amount)}}</td>
                                                                                        </tr>
                                                                                        @if($pr->expense)
                                                                                            @foreach ($pr->expense as $ex)
                                                                                                <tr>
                                                                                                    <td class="text-start">{{$ex->expense?->head_name}}</td>
                                                                                                    <td class="text-end">{{money_format($ex->cost_amount)}}</td>
                                                                                                </tr>
                                                                                            @endforeach
                                                                                        @endif
                                                                                    </table>
                                                                                @endforeach
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                @foreach ($findBeparianPurchase as $pr)
                                                                    <tr>
                                                                        <th class=" text-start">{{$pr->supplier?->supplier_name}}</th>
                                                                        <th class=" text-end">{{money_format($pr->grand_total)}}</th>
                                                                    </tr>
                                                                    @php
                                                                    $purGrandTotal += $pr->grand_total;
                                                                    @endphp
                                                                    <tr>
                                                                        <td width="100%" class="text-start">
                                                                            @if($pr->purchase_lot)
                                                                                @foreach ($pr->purchase_lot as $pd)
                                                                                    <table width="90%">
                                                                                        <tr>
                                                                                            <td class="text-start">{{$pd->product?->product_name}}</td>
                                                                                            <td class="text-end">({{money_format($pd->actual_quantity)}} * {{money_format($pd->rate_kg)}}) = {{money_format($pd->amount)}}</td>
                                                                                        </tr>
                                                                                        @if($pr->expense)
                                                                                            @foreach ($pr->expense as $ex)
                                                                                                <tr>
                                                                                                    <td class="text-start">{{$ex->expense?->head_name}}</td>
                                                                                                    <td class="text-end">{{money_format($ex->cost_amount)}}</td>
                                                                                                </tr>
                                                                                            @endforeach
                                                                                        @endif
                                                                                    </table>
                                                                                @endforeach
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                @foreach ($findRegularPurchase as $pr)
                                                                    <tr>
                                                                        <th class=" text-start">{{$pr->supplier?->supplier_name}}</th>
                                                                        <th class=" text-end">{{money_format($pr->grand_total)}}</th>
                                                                    </tr>
                                                                    @php
                                                                    $purGrandTotal += $pr->grand_total;
                                                                    @endphp
                                                                    <tr>
                                                                        <td width="100%" class="text-start">
                                                                            @if($pr->purchase_lot)
                                                                                @foreach ($pr->purchase_lot as $pd)
                                                                                    <table width="90%">
                                                                                        <tr>
                                                                                            <td class="text-start">{{$pd->product?->product_name}}</td>
                                                                                            <td class="text-end">({{money_format($pd->actual_quantity)}} * {{money_format($pd->rate_kg)}}) = {{money_format($pd->amount)}}</td>
                                                                                        </tr>
                                                                                        @if($pr->expense)
                                                                                            @foreach ($pr->expense as $ex)
                                                                                                <tr>
                                                                                                    <td class="text-start">{{$ex->expense?->head_name}}</td>
                                                                                                    <td class="text-end">{{money_format($ex->cost_amount)}}</td>
                                                                                                </tr>
                                                                                            @endforeach
                                                                                        @endif
                                                                                    </table>
                                                                                @endforeach
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th class="text-start">Total</th>
                                                                    <th class="text-end">{{money_format($purGrandTotal)}}</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </td>
                                                </tr>
                                                {{-- sales and purchase --}}
                                                {{-- summary --}}
                                                <tr>
                                                    <td></td>
                                                    <td widtd="4%"></td>
                                                    <td>
                                                        <table class="table mb-5">
                                                            <tbody>
                                                                <tr >
                                                                    <th class=" text-start"></th>
                                                                    <th class=" text-end">{{__('Amount')}}</th>
                                                                    <th width="5%"></th>
                                                                    <th class=" text-start"></th>
                                                                    <th class=" text-end">{{__('Amount')}}</th>
                                                                </tr>
                                                                <tr>
                                                                    <td class=" text-start">Sale receive</td>
                                                                    <td class=" text-end">{{money_format($drTotal)}}</td>
                                                                    <td width="5%"></td>
                                                                    <td class=" text-start">Purchase Payment</td>
                                                                    <td class=" text-end">{{money_format($crTotal)}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class=" text-start">Sale</td>
                                                                    <td class=" text-end">{{money_format($grandTotal)}}</td>
                                                                    <td width="5%"></td>
                                                                    <td class=" text-start">Purchase</td>
                                                                    <td class=" text-end">{{money_format($purGrandTotal)}}</td>
                                                                </tr>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th class="text-start">Total</th>
                                                                    <th class="text-end">{{money_format($drTotal + $grandTotal)}}</th>
                                                                    <th width="5%"></th>
                                                                    <th class="text-start">Total</th>
                                                                    <th class="text-end">{{money_format($crTotal + $purGrandTotal)}}</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </td>
                                                </tr>
                                                {{-- summary --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
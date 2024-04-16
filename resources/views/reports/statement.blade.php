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
                            <div class="text-end">
                                <a href="#" class="no_print float-end" title="print" id="addCompanyDescription" onclick="printReport('result_show')"><svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 16 16"><g fill="currentColor"><path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/><path d="M4.603 12.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102c.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645a19.701 19.701 0 0 0 1.062-2.227a7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136c.075-.354.274-.672.65-.823c.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538c.007.187-.012.395-.047.614c-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686a5.753 5.753 0 0 1 1.334.05c.364.065.734.195.96.465c.12.144.193.32.2.518c.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416a.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.716 5.716 0 0 1-.911-.95a11.642 11.642 0 0 0-1.997.406a11.311 11.311 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238c-.328.194-.541.383-.647.547c-.094.145-.096.25-.04.361c.01.022.02.036.026.044a.27.27 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.647 12.647 0 0 1 1.01-.193a11.666 11.666 0 0 1-.51-.858a20.741 20.741 0 0 1-.5 1.05zm2.446.45c.15.162.296.3.435.41c.24.19.407.253.498.256a.107.107 0 0 0 .07-.015a.307.307 0 0 0 .094-.125a.436.436 0 0 0 .059-.2a.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.881 3.881 0 0 0-.612-.053zM8.078 5.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198a.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283c-.04.192-.03.469.046.822c.024.111.054.227.09.346z"/></g></svg></a>
                            </div>
                            <div class="row" id="result_show">
                                <div class="col-12">
                                    <div class="d-none only_print">
                                        <table style="width: 100%" id="companyDescript">
                                            <tr style="text-align: center;">
                                                <th colspan="2">
                                                    <h4>{{encryptor('decrypt', request()->session()->get('companyName'))}}</h4>
                                                    <p>{{encryptor('decrypt', request()->session()->get('companyAddress'))}}</p>
                                                    <p>IMPORT, EXPORTER, WHOLESALER, RETAILSALER & COMMISSION AGENT</p>
                                                    <p>E-MAIL: <a href="#" style="border-bottom: solid 1px; border-color:blue;">{{encryptor('decrypt', request()->session()->get('companyEmail'))}}</a> Contact: {{encryptor('decrypt', request()->session()->get('companyContact'))}}</p>
                                                    <h4 style="padding-bottom: 2.5rem;">Account Statement</h4>
                                                </th>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="table-responsive">
                                        @php
                                            $sale_cash_income=$purchase_cash_exp=$sale_due_income=$purchase_due_exp=$other_exp=$other_income=0;
                                        @endphp
                                        <table class="table mb-5">
                                            <tbody>
                                                <tr class="text-center">
                                                    <th width="48%"><h5>{{__('Revieved')}}</h5></th>
                                                    <th width="4%"></th>
                                                    <th width="48%"><h5>{{__('Payment')}}</h5></th>
                                                </tr>
                                                {{-- sales and purchase payment --}}
                                                <tr>
                                                    <td style="vertical-align: top; height: 0;">
                                                        <table class="table mb-5">
                                                            <tbody>
                                                                <tr >
                                                                    <th class=" text-start">{{__('Account Title')}}</th>
                                                                    <th class=" text-end">{{__('Amount')}}</th>
                                                                </tr>
                                                                @foreach ($customerPayment as $cp)
                                                                <tr>
                                                                    <td class=" text-start">{{$cp->customer?$cp->customer->customer_name:$cp->journal_title}}</td>
                                                                    <td class=" text-end">{{money_format($cp->dr)}}</td>
                                                                </tr>
                                                                @php
                                                                $sale_cash_income += $cp->dr;
                                                                @endphp
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th class="text-start">Total</th>
                                                                    <th class="text-end">{{money_format($sale_cash_income)}} <br>{{$customerPaymentOld}}</th>
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
                                                                    <td class=" text-start">{{$sp->supplier?$sp->supplier->supplier_name:$sp->journal_title}}</td>
                                                                    <td class=" text-end">{{money_format($sp->cr)}}</td>
                                                                </tr>
                                                                @php
                                                                $purchase_cash_exp += $sp->cr;
                                                                @endphp
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th class="text-start">Total</th>
                                                                    <th class="text-end">{{money_format($purchase_cash_exp)}}</th>
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
                                                                $sale_due_income += $sale->grand_total;
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
                                                                    <th class="text-end">{{money_format($sale_due_income)}} {{$findSalesOld}}</th>
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
                                                                    $purchase_due_exp += $pr->grand_total;
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
                                                                    $purchase_due_exp += $pr->grand_total;
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
                                                                    $purchase_due_exp += $pr->grand_total;
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
                                                                    <th class="text-end">{{money_format($purchase_due_exp)}} {{$findPurchaseOld+$findBeparianPurchaseOld+$findRegularPurchaseOld}}</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </td>
                                                </tr>
                                                {{-- sales and purchase --}}
                                                {{-- Cash and bank --}}
                                                <tr>
                                                    <td style="vertical-align: top; height: 0;" class="text-start">
                                                        <table class="table mb-5">
                                                            <tbody>
                                                                <tr >
                                                                    <th class=" text-start">Description</th>
                                                                    <th class=" text-end">{{__('Amount')}}</th>
                                                                </tr>
                                                                @foreach ($otherExpInc as $inc)
                                                                    @if($inc->dr > "0")
                                                                        <tr>
                                                                            <th class=" text-start">{{$inc->account_title}}</th>
                                                                            <th class=" text-end">{{money_format($inc->dr)}}</th>
                                                                        </tr>
                                                                        @php
                                                                        $other_income += $inc->dr;
                                                                        @endphp
                                                                    @endif
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th class="text-start">Total</th>
                                                                    <th class="text-end">{{money_format($other_income)}}</th>
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
                                                                @foreach ($otherExpInc as $exp)
                                                                    @if($exp->cr > "0")
                                                                        <tr>
                                                                            <th class=" text-start">{{$exp->account_title}}</th>
                                                                            <th class=" text-end">{{money_format($exp->cr)}}</th>
                                                                        </tr>
                                                                        @php
                                                                        $other_exp += $exp->cr;
                                                                        @endphp
                                                                    @endif
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th class="text-start">Total</th>
                                                                    <th class="text-end">{{money_format($other_exp)}}</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </td>
                                                </tr>
                                                {{-- sales and purchase --}}
                                                {{-- summary --}}
                                                <tr>
                                                    <td>

                                                        @php $balance=$deb=$cre=0; @endphp
                                                        @php $sumdr=$accOldData->sum('dr');$sumcr=$accOldData->sum('cr'); @endphp
                                                        @php if($openingBalance>0) $sumdr+=$openingBalance; else $sumcr+=$openingBalance; @endphp
                                                    </td>
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
                                                                
                                                                @php $oldtoho=500000; $lldhao=50000; @endphp
                                                                
                                                                
                                                                <tr>
                                                                    <td class=" text-start">সাবেক তহ: </td>
                                                                    <td class=" text-end">{{money_format($oldtoho)}}</td>
                                                                    <td width="5%"></td>
                                                                    <td class=" text-start">সাবেক হাও: </td>
                                                                    <td class=" text-end">{{money_format($lldhao)}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class=" text-start">অদ্য তহ:</td>
                                                                    <td class=" text-end">{{money_format($sale_due_income)}}</td>
                                                                    <td width="5%"></td>
                                                                    <td class=" text-start">অদ্য হাও:</td>
                                                                    <td class=" text-end">{{money_format($purchase_cash_exp)}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class=" text-start"></td>
                                                                    <td class=" text-end" style="border-top:4px double">{{money_format($oldtoho + $sale_due_income)}}</td>
                                                                    <td width="5%"></td>
                                                                    <td class=" text-start"></td>
                                                                    <td class=" text-end" style="border-top:4px double">{{money_format($lldhao+$purchase_cash_exp)}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class=" text-start">খরচ বাদ: </td>
                                                                    <td class=" text-end">{{money_format($purchase_due_exp + $other_exp)}}</td>
                                                                    <td width="5%"></td>
                                                                    <td class=" text-start">হাও বাদ:</td>
                                                                    <td class=" text-end">{{money_format($sale_cash_income)}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class=" text-start"></td>
                                                                    <td class=" text-end" style="border-top:4px double">{{money_format(($oldtoho + $sale_due_income) - ($purchase_due_exp + $other_exp))}}</td>
                                                                    <td width="5%"></td>
                                                                    <td class=" text-start"></td>
                                                                    <td class=" text-end" style="border-top:4px double">{{money_format($lldhao+$purchase_cash_exp - $sale_cash_income)}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class=" text-start"></td>
                                                                    <td class=" text-end"></td>
                                                                    <td width="5%"></td>
                                                                    <td class=" text-start">নগদ</td>
                                                                    <td class=" text-end">{{$sumdr > 0 ? money_format($sumdr) : money_format($sumcr)}}</td>
                                                                </tr>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th class="text-start"></th>
                                                                    <th class="text-end"></th>
                                                                    <th width="5%"></th>
                                                                    <th class="text-start"></th>
                                                                    <th class="text-end">{{money_format(($lldhao+$purchase_cash_exp - $sale_cash_income)+($sumdr > 0 ? $sumdr : $sumcr))}}</th>
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


@push('scripts')
<script>
    function printReport(divName) {
        var printContentDiv = document.getElementById('print-content');
        
        var prtContent = document.getElementById(divName);

        var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
        WinPrint.document.write('<link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}" type="text/css"/>');

        var style = '<style media="print">.no_print{ display:none}body{color:#000 !important;background-color:#FFF; font-size:14px; padding-top:50px}.only_print{ display:block !important;} .tbl_border{ border: 1px solid; border-collapse: collapse;}</style>';
        WinPrint.document.write(style);
                        
        //WinPrint.document.write(printContentDiv.innerHTML);
        WinPrint.document.write(prtContent.innerHTML); // Include the rest of the content
        WinPrint.document.close();
        WinPrint.onload = function () {
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        }
    }
</script>
@endpush
                                
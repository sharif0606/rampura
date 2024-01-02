@extends('layout.app')

@section('pageTitle',trans('Sales Reports'))
@section('pageSubTitle',trans('Reports'))

@section('content')
<style>
    .tbl_border{
    border: 1px solid;
    border-collapse: collapse;
    }
</style>
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="text-center"><h4>DAY/ YERALY SALES STATEMENT  (report)</h4></div>
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


                                <div class="col-md-2 mt-4">
                                    <label for="supplierName" class="float-end mt-2"><h6>{{__('Party Name')}}</h6></label>
                                </div>
                                <div class="col-md-4 mt-4">
                                    <select class="form-control form-select" name="customer" id="customer">
                                        <option value="">Select Party</option>
                                        @forelse($customers as $c)
                                            <option value="{{$c->id}}" {{isset($_GET['customer'])&& $_GET['customer']==$c->id?'selected':''}}> {{ $c->customer_name}}</option>
                                        @empty
                                            <option value="">No data found</option>
                                        @endforelse
                                    </select>
                                </div>
                                
                                <div class="col-md-2 mt-4">
                                    <label for="lc" class="float-end mt-2"><h6 class="m-0">{{__('LC NO')}}</h6></label>
                                </div>
                                <div class="col-md-4 mt-4">
                                    <input type="text"class="form-control" value="{{isset($_GET['lc_no'])?$_GET['lc_no']:''}}" name="lc_no" placeholder="Lc number">
                                </div>
                            </div>
                            <div class="row m-4 no_print">
                                <div class="col-6 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-success me-1 mb-1 ps-5 pe-5">{{__('Show')}}</button>
                                </div>
                                <div class="col-6 d-flex justify-content-Start">
                                    <a href="{{route(currentUser().'.salreport')}}" class="btn pbtn btn-sm btn-warning me-1 mb-1 ps-5 pe-5">{{__('Reset')}}</a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                {{-- <a href="#" class="no_print float-end" title="print" onclick="printInfo('multiple-column-form')"><svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 16 16"><g fill="currentColor"><path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/><path d="M4.603 12.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102c.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645a19.701 19.701 0 0 0 1.062-2.227a7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136c.075-.354.274-.672.65-.823c.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538c.007.187-.012.395-.047.614c-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686a5.753 5.753 0 0 1 1.334.05c.364.065.734.195.96.465c.12.144.193.32.2.518c.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416a.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.716 5.716 0 0 1-.911-.95a11.642 11.642 0 0 0-1.997.406a11.311 11.311 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238c-.328.194-.541.383-.647.547c-.094.145-.096.25-.04.361c.01.022.02.036.026.044a.27.27 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.647 12.647 0 0 1 1.01-.193a11.666 11.666 0 0 1-.51-.858a20.741 20.741 0 0 1-.5 1.05zm2.446.45c.15.162.296.3.435.41c.24.19.407.253.498.256a.107.107 0 0 0 .07-.015a.307.307 0 0 0 .094-.125a.436.436 0 0 0 .059-.2a.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.881 3.881 0 0 0-.612-.053zM8.078 5.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198a.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283c-.04.192-.03.469.046.822c.024.111.054.227.09.346z"/></g></svg></a> --}}
                                <table class="table mb-5">
                                    <tbody>
                                        <tr class="tbl_border bg-primary text-white  text-center">
                                            <th class="p-2 tbl_border">{{__('Date')}}</th>
                                            <th class="p-2 tbl_border">{{__('Particulars')}}</th>
                                            <th class="p-2 tbl_border">{{__('Voucher Type')}}</th>
                                            <th class="p-2 tbl_border">{{__('Quantity')}}</th>
                                            <th class="p-2 tbl_border">{{__('Alt.Unints')}}</th>
                                            <th class="p-2 tbl_border">{{__('Rate')}}</th>
                                            <th class="p-2 tbl_border">{{__('Gross Total')}}</th>
                                            <th class="p-2 tbl_border"> Sales- {{encryptor('decrypt', request()->session()->get('companyName'))}}</th>
                                            <th class="p-2 tbl_border">{{__('Commission')}}</th>
                                            <th class="p-2 tbl_border">{{__('Loading Charge')}}</th>
                                            <th class="p-2 tbl_border">{{__('Shopi Mazi')}}</th>
                                        </tr>
                                    
                                        @php
                                            $sumCommission = 0;
                                            $sumLoading = 0;
                                            $totalkg = 0;
                                            $totalBag = 0;
                                            $totalGross = 0;
                                            $totalAmount = 0;
                                            $totalCommission = 0;
                                            $totalLoading = 0;
                                        @endphp
                                        @forelse($data as $d)
                                        <tr class="tbl_border text-center">
                                            <td class="tbl_border" scope="row">{{ date('d-M-y', strtotime($d->sales_date)) }}</td>
                                            <td class="tbl_border">{{$d->customer?->customer_name}}</td>
                                            <td class="tbl_border">{{$d->voucher_note}}</td>
                                            <td class="tbl_border"><b>{{money_format($d->quantity_kg)}} কেজি</b></td>
                                            <td class="tbl_border">{{money_format($d->quantity_bag)}} বস্তা</td>
                                            <td class="tbl_border"></td>
                                            <td class="tbl_border">
                                                @if ($d->grand_total != '')
                                                    {{money_format($d->grand_total)}} Dr
                                                @else
                                                @endif
                                            </td>
                                            <td class="tbl_border">
                                                @if ($d->amount != '')
                                                    {{money_format($d->amount)}} Cr
                                                @else
                                                @endif
                                            </td>
                                            <td class="tbl_border">
                                                @if ($d->sales?->expense)
                                                    @foreach ($d->sales?->expense as $com)
                                                        @if ($com->child_two_id == '14' || $com->child_two_id == '57' || $com->child_two_id == '76' || $com->child_two_id == '94' || $com->child_two_id == '130')
                                                            @php
                                                                $sumCommission = $com->cost_amount;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                    {{money_format($sumCommission)}} Cr
                                                @endif
                                            </td>
                                            <td class="tbl_border">
                                                @if ($d->sales?->expense)
                                                    @foreach ($d->sales?->expense as $com)
                                                        @if ($com->child_two_id == '13' || $com->child_two_id == '56' || $com->child_two_id == '75' || $com->child_two_id == '93' || $com->child_two_id == '129')
                                                            @php
                                                                $sumLoading = $com->cost_amount;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                    {{money_format($sumLoading)}} Cr
                                                @endif
                                            </td>
                                            <td class="tbl_border"></td>
                                        </tr>
                                        <tr class="tbl_border text-center">
                                            <td class="tbl_border"></td>
                                            <td class="tbl_border">{{$d->product?->product_name}}--{{$d->lot_no}}--{{$d->brand}}</td>
                                            <td class="tbl_border"></td>
                                            <td class="tbl_border">{{money_format($d->quantity_kg)}} কেজি</td>
                                            <td class="tbl_border">{{money_format($d->quantity_bag)}} বস্তা</td>
                                            <td class="tbl_border">{{$d->rate_kg}}</td>
                                            <td class="tbl_border"></td>
                                            <td class="tbl_border"></td>
                                            <td class="tbl_border"></td>
                                            <td class="tbl_border"></td>
                                            <td class="tbl_border"></td>
                                        </tr>
                                        @php
                                        $totalkg += $d->quantity_kg;
                                        $totalBag += $d->quantity_bag;
                                        $totalGross += $d->grand_total;
                                        $totalAmount += $d->amount;
                                        $totalCommission += $sumCommission;
                                        $totalLoading += $sumLoading;
                                        @endphp
                                        @empty
                                        <tr>
                                            <th colspan="11" class="text-center">No data Found</th>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr class="text-center">
                                            <th colspan="3" class="tbl_border">Total</th>
                                            <th class="tbl_border">{{money_format($totalkg)}} কেজি</th>
                                            <th class="tbl_border">{{money_format($totalBag)}} বস্তা</th>
                                            <th class="tbl_border"></th>
                                            <th class="tbl_border">{{money_format($totalGross)}} Dr</th>
                                            <th class="tbl_border">{{money_format($totalAmount)}} Cr</th>
                                            <th class="tbl_border">{{money_format($totalCommission)}} Cr</th>
                                            <th class="tbl_border">{{money_format($totalLoading)}} Cr</th>
                                            <th class="tbl_border"></th>
                                        </tr>
                                    </tfoot>
                                </table>
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
    function printInfo(divName) {
        var prtContent = document.getElementById(divName);
        var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
            WinPrint.document.write('<link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}" type="text/css"/>');
            WinPrint.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-print-css/css/bootstrap-print.min.css" media="print">');
            WinPrint.document.write(`<style media="print">
                                        .no_print{ display:none}.only_print{ display:block !important;}
                                         body{color:#000 !important;background-color:#FFF; font-size:14px; padding-top:50px}
                                         .card-body{color:#000 !important; font-size:18px;}
                                          .dataTable-table, .table{color:#000 !important;}
                                          .tbl_border{border: 1px solid; border-collapse: collapse;}
                                          .bg-primary{background-color:red;}
                                          .form-group label {font-weight: bold;}</style>`);
            WinPrint.document.write(prtContent.innerHTML);
            WinPrint.document.close();
            WinPrint.onload =function(){
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        }
    }
</script>
@endpush
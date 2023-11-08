@extends('layout.app')

@section('pageTitle',trans('Show Debit/Payment Voucher'))
@section('pageSubTitle',trans('Show'))
@section('content')
  <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="d-none only_print text-center">
                                <table style="width: 100%" id="companyDescript">
                                    <tr style="text-align: center;">
                                        <th colspan="2">
                                            <h4>{{encryptor('decrypt', request()->session()->get('companyName'))}}</h4>
                                            <p>{{encryptor('decrypt', request()->session()->get('companyAddress'))}}</p>
                                            <p>IMPORT, EXPORTER, WHOLESALER, RETAILSALER & COMMISSION AGENT</p>
                                            <p>E-MAIL: <a href="#" style="border-bottom: solid 1px; border-color:blue;">{{encryptor('decrypt', request()->session()->get('companyEmail'))}}</a> Contact: {{encryptor('decrypt', request()->session()->get('companyContact'))}}</p>
                                            <h3 style="padding-bottom: 2rem;">Payment Voucher</h3>
                                        </th>
                                    </tr>
                                </table>
                            </div>
                            {{-- <h1 class="d-none only_print text-center mb-3">Payment Voucher</h1> --}}
                            <a href="#" class="no_print float-end" title="print" onclick="printInfo('multiple-column-form')"><svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 16 16"><g fill="currentColor"><path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/><path d="M4.603 12.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102c.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645a19.701 19.701 0 0 0 1.062-2.227a7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136c.075-.354.274-.672.65-.823c.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538c.007.187-.012.395-.047.614c-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686a5.753 5.753 0 0 1 1.334.05c.364.065.734.195.96.465c.12.144.193.32.2.518c.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416a.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.716 5.716 0 0 1-.911-.95a11.642 11.642 0 0 0-1.997.406a11.311 11.311 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238c-.328.194-.541.383-.647.547c-.094.145-.096.25-.04.361c.01.022.02.036.026.044a.27.27 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.647 12.647 0 0 1 1.01-.193a11.666 11.666 0 0 1-.51-.858a20.741 20.741 0 0 1-.5 1.05zm2.446.45c.15.162.296.3.435.41c.24.19.407.253.498.256a.107.107 0 0 0 .07-.015a.307.307 0 0 0 .094-.125a.436.436 0 0 0 .059-.2a.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.881 3.881 0 0 0-.612-.053zM8.078 5.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198a.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283c-.04.192-.03.469.046.822c.024.111.054.227.09.346z"/></g></svg></a>
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="countryName">{{__('Voucher No')}}: </label> <span>{{$dvoucher->voucher_no}}</span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="countryName">{{__('Date')}}: </label> <span>{{$dvoucher->current_date}}</span>
                                    </div>
                                </div>
                            
                                
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="name">{{__('Name')}}: </label>{{$dvoucher->pay_name}}
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="Purpose">{{__('Purpose')}}: </label> {{$dvoucher->purpose}}
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="payment">{{__('Payment from Account')}}: 
                                            @if($dvoucherbkdn)
                                                @foreach($dvoucherbkdn as $bk)
                                                    @if($bk->particulars=="Payment by")
                                                    {{$bk->account_code}} ({{$bk->credit}})
                                                    @endif
                                                @endforeach
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id='account' cellspacing="0" width="100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th>{{__('SN#')}}</th>
                                            <th>{{__('A/C Head')}}</th>
                                            <th>{{__('Amount')}}</th>
                                            <th>{{__('Remarks')}}</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th style="text-align:right;" colspan="2">{{__('Total Amount Tk.')}}</th>
                                            <th style="text-align:center;">{{$dvoucher->debit_sum}}</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    <tbody style="background:#eee;">
                                        @if($dvoucherbkdn)
                                            @foreach($dvoucherbkdn as $bk)
                                                @if($bk->particulars!="Payment by")
                                                <tr class="text-center">
                                                    <td style="width:5%;">1</td>
                                                    <td style="width:67%;">{{$bk->account_code}}</td>
                                                    <td style="width:14%;">{{$bk->debit}}</td>
                                                    <td style="width:14%;">{{$bk->particulars}}</td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group">
                                <div class="row mt-4">
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group @if($errors->has('name')) has-error @endif">
                                            <label>{{__('Cheque No')}}: </label>{{$dvoucher->cheque_no}}
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group">
                                        <label>{{__('Bank Name')}}: </label>{{$dvoucher->bank}}
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group">
                                        <label>{{__('Cheque Date')}}: </label>{{$dvoucher->cheque_dt}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-none only_print text-center">
                                <table style="width: 100%; margin-top: 4rem;">
                                    <tr style="padding-top: 5rem;">
                                        <th style="text-align: center;"><h6>PREPARED BY</h6></th>
                                        <th style="text-align: center;"><h6>CHECKED BY</h6></th>
                                        <th style="text-align: center;"><h6>VERIFIED BY</h6></th>
                                    </tr>
                                </table>
                            </div>
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
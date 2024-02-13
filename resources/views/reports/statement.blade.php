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
                                    <a href="" class="btn pbtn btn-sm btn-warning me-1 mb-1 ps-5 pe-5">{{__('Reset')}}</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table mb-5">
                                            <tbody>
                                                <tr class="text-center">
                                                    <th width="50%">{{__('Revieved')}}</th>
                                                    <th width="50%">{{__('Payment')}}</th>
                                                </tr>
                                                <tr>
                                                    @php
                                                        $drTotal = 0;
                                                        $crTotal = 0;
                                                    @endphp
                                                    <td style="vertical-align: top; height: 0;">
                                                        <table class="table mb-5">
                                                            <tbody>
                                                                <tr >
                                                                    <th class="p-2">{{__('Account Title')}}</th>
                                                                    <th class="p-2">{{__('Amount')}}</th>
                                                                </tr>
                                                                @foreach ($customerPayment as $cp)
                                                                <tr>
                                                                    <td class="p-2">{{$cp->customer?->customer_name}}</td>
                                                                    <td class="p-2">{{money_format($cp->dr)}}</td>
                                                                </tr>
                                                                @php
                                                                $drTotal += $cp->dr;
                                                                @endphp
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th>Total</th>
                                                                    <th>{{money_format($drTotal)}}</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </td>
                                                    <td style="vertical-align: top; height: 0;">
                                                        <table class="table mb-5">
                                                            <tbody>
                                                                <tr >
                                                                    <th class="p-2">{{__('Account Title')}}</th>
                                                                    <th class="p-2">{{__('Amount')}}</th>
                                                                </tr>
                                                                @foreach ($supplierayment as $sp)
                                                                <tr>
                                                                    <td class="p-2">{{$sp->supplier?->supplier_name}}</td>
                                                                    <td class="p-2">{{money_format($sp->cr)}}</td>
                                                                </tr>
                                                                @php
                                                                $crTotal += $sp->cr;
                                                                @endphp
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th>Total</th>
                                                                    <th>{{money_format($crTotal)}}</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top; height: 0;">
                                                        <table class="table mb-5">
                                                            <tbody>
                                                                <tr >
                                                                    <th class="p-2"></th>
                                                                    <th class="p-2">{{__('Amount')}}</th>
                                                                </tr>
                                                                @foreach ($allreceive as $cp)
                                                                <tr>
                                                                    <td class="p-2">{{$cp->journal_title}}n{{$cp->account_title}}</td>
                                                                    <td class="p-2">{{$cp->dr}}</td>
                                                                </tr>
                                                                @php
                                                                $drTotal += $cp->dr;
                                                                @endphp
                                                                @endforeach
                                                            </tbody>
                                                            {{-- <tfoot>
                                                                <tr>
                                                                    <th>Total</th>
                                                                    <th>{{money_format($drTotal)}}</th>
                                                                </tr>
                                                            </tfoot> --}}
                                                        </table>
                                                    </td>
                                                </tr>
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
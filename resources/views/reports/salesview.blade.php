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
                            <div class="row">
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
                            <div class="row m-4">
                                <div class="col-6 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-success me-1 mb-1 ps-5 pe-5">{{__('Show')}}</button>
                                </div>
                                <div class="col-6 d-flex justify-content-Start">
                                    <a href="{{route(currentUser().'.salreport')}}" class="btn pbtn btn-sm btn-warning me-1 mb-1 ps-5 pe-5">{{__('Reset')}}</a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-5">
                                    <thead>
                                        <tr class="tbl_border bg-primary text-white text-center">
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
                                    </thead>
                                    <tbody>
                                        @php
                                            $sumComission = 0;
                                            $sumLoading = 0;
                                        @endphp
                                        @forelse($data as $d)
                                        <tr class="tbl_border text-center">
                                            <td class="tbl_border" scope="row">{{ $d->sales_date }}</td>
                                            <td class="tbl_border">{{$d->customer?->customer_name}}</td>
                                            <td class="tbl_border">Sales Voucher</td>
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
                                                                $sumComission += $com->cost_amount;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                    {{money_format($sumComission)}} Cr
                                                @endif
                                            </td>
                                            <td class="tbl_border">
                                                @if ($d->sales?->expense)
                                                    @foreach ($d->sales?->expense as $com)
                                                        @if ($com->child_two_id == '13' || $com->child_two_id == '56' || $com->child_two_id == '75' || $com->child_two_id == '93' || $com->child_two_id == '129')
                                                            @php
                                                                $sumLoading += $com->cost_amount;
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
                                        @empty
                                        <tr>
                                            <th colspan="11" class="text-center">No data Found</th>
                                        </tr>
                                        @endforelse
                                    </tbody>
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
@extends('layout.app')

@section('pageTitle',trans('Sales Reports'))
@section('pageSubTitle',trans('Reports'))

@section('content')
<style>
    @media screen and (max-width: 800px) {
  .tbl_scroll {
    overflow: scroll;
  }
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
                                <div class="tbl_scroll">
                                    <table class="table mb-5">
                                        <thead>
                                            <tr class="bg-primary text-white text-center">
                                                <th class="p-2">{{__('#SL')}}</th>
                                                <th class="p-2" data-title="Party Name">{{__('Party Name')}}</th>
                                                <th class="p-2" data-title="Description of Goods">{{__('Des.of Goods')}}</th>
                                                <th class="p-2" data-title="LC / LOT NO">{{__('Lc/Lot no')}}</th>
                                                <th class="p-2" data-title="TRADEMARKE/BRAND NAME">{{__('Trade/Brand')}}</th>
                                                <th class="p-2" data-title="Quantity Bag">{{__('Q.bag')}}</th>
                                                <th class="p-2" data-title="Total Quantity in Kg">{{__('Total Kg')}}</th>
                                                <th class="p-2" data-title="Rate in Kg">{{__('Rate in kg')}}</th>
                                                <th class="p-2" data-title="Amount">{{__('Amount')}}</th>
                                                {{-- <th class="p-2" data-title="Sales Commission Income">{{__('S.Com')}}</th>
                                                <th class="p-2" data-title="Total Amount">{{__('Total Amount')}}</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalBagQty = 0;
                                                $totalQty = 0;
                                                $amount = 0;
                                                // $saleCommission = 0;
                                                // $totalAmount = 0;
                                            @endphp
                                            @forelse($data as $d)
                                            <tr class="text-center">
                                                <th scope="row">{{ ++$loop->index }}</th>
                                                <td>{{$d->customer?->customer_name}}</td>
                                                <td>{{$d->product?->product_name}}</td>
                                                <td>{{$d->lot_no}}</td>
                                                <td>{{$d->brand}}</td>
                                                <td>{{money_format($d->quantity_bag)}}</td>
                                                <td>{{money_format($d->quantity_kg)}}</td>
                                                <td>{{$d->rate_kg}}</td>
                                                <td>{{money_format($d->amount)}}</td>
                                                {{-- <td>{{$d->sale_commission}}</td>
                                                <td>{{$d->total_amount}}</td> --}}
                                            </tr>
                                            @php
                                                $totalBagQty += $d->quantity_bag;
                                                $totalQty += $d->quantity_kg;
                                                $amount += $d->amount;
                                                // $saleCommission += $d->sale_commission;
                                                // $totalAmount += $d->total_amount;
                                            @endphp
                                            @empty
                                            <tr>
                                                <th colspan="9" class="text-center">No data Found</th>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr class="text-center">
                                                <th colspan="5" class="p-2">Total</th>
                                                <th class="p-2">{{money_format($totalBagQty)}}</th>
                                                <th class="p-2">{{money_format($totalQty)}}</th>
                                                <th class="p-2"></th>
                                                <th class="p-2">{{money_format($amount)}}</th>
                                                {{-- <th class="p-2">{{$saleCommission}}</th>
                                                <th class="p-2">{{$totalAmount}}</th> --}}
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
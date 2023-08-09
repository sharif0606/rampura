@extends('layout.app')

@section('pageTitle',trans('Sales Reports'))
@section('pageSubTitle',trans('Reports'))

@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="text-center"><h4>SALES STATEMENT  (Rport)</h4></div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-12 d-flex justify-content-between">
                                <div>
                                    <label for="">Party Name:</label>
                                    <b>{{$show_data->customer?->customer_name}}</b>
                                </div>
                                <div>
                                    <label for="">Invoice No:</label>
                                    <b></b>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <div>
                                    <label for="">Address:</label>
                                    <b>{{$show_data->customer?->address}}</b>
                                </div>
                                <div>
                                    <label for="">Sales Date:</label>
                                    <b>{{$show_data->sales_date}}</b>
                                </div>
                            </div>
                        </div>
                        <table class="table mb-5">
                            <thead>
                                <tr class="bg-primary text-white text-center">
                                    <th class="p-2">{{__('#SL')}}</th>
                                    <th class="p-2" data-title="Description of Goods">{{__('Des.of Goods')}}</th>
                                    <th class="p-2" data-title="LC / LOT NO">{{__('Lc/Lot no')}}</th>
                                    <th class="p-2" data-title="TRADEMARKE/BRAND NAME">{{__('Trade/Brand')}}</th>
                                    <th class="p-2" data-title="Quantity Bag">{{__('Q.Bag')}}</th>
                                    <th class="p-2" data-title="Total Quantity in Kg">{{__('T.Q.KG')}}</th>
                                    <th class="p-2" data-title="Rate in Kg">{{__('R.KG')}}</th>
                                    <th class="p-2" data-title="Amount">{{__('Amount')}}</th>
                                    <th class="p-2" data-title="Sales Commission Commission">{{__('S.COM')}}</th>
                                    <th class="p-2" data-title="Transport Charge">{{__('T.Charge')}}</th>
                                    <th class="p-2" data-title="Labor Charge">{{__('L.Charge')}}</th>
                                    <th class="p-2" data-title="Total Amount">{{__('Total Amount')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalBagQty = 0;
                                    $totalQty = 0;
                                    $amount = 0;
                                    $saleCommission = 0;
                                    $transport = 0;
                                    $labour = 0;
                                    $totalAmount = 0;
                                @endphp

                                @forelse($salesDetail as $s)
                                <tr class="text-center">
                                    <th scope="row">{{ ++$loop->index }}</th>
                                    <td>{{$s->product?->product_name}}</td>
                                    <td>{{$s->lot_no}}</td>
                                    <td>{{$s->brand}}</td>
                                    <td>{{$s->quantity_bag}}</td>
                                    <td>{{$s->quantity_kg}}</td>
                                    <td>{{$s->rate_kg}}</td>
                                    <td>{{$s->amount}}</td>
                                    <td>{{$s->sale_commission}}</td>
                                    <td>{{$s->transport_cost}}</td>
                                    <td>{{$s->unloading_cost}}</td>
                                    <td>{{$s->total_amount}}</td>
                                </tr>
                                @php
                                    $totalBagQty += $s->quantity_bag;
                                    $totalQty += $s->quantity_kg;
                                    $amount += $s->amount;
                                    $saleCommission += $s->sale_commission;
                                    $transport += $s->transport_cost;
                                    $labour += $s->unloading_cost;
                                    $totalAmount += $s->total_amount;
                                @endphp
                                @empty
                                <tr>
                                    <th colspan="12" class="text-center">No data Found</th>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="text-center">
                                    <th colspan="4" class="p-2">Total</th>
                                    <th class="p-2">{{$totalBagQty}}</th>
                                    <th class="p-2">{{$totalQty}}</th>
                                    <th class="p-2"></th>
                                    <th class="p-2">{{$amount}}</th>
                                    <th class="p-2">{{$saleCommission}}</th>
                                    <th class="p-2">{{$transport}}</th>
                                    <th class="p-2">{{$labour}}</th>
                                    <th class="p-2">{{$totalAmount}}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
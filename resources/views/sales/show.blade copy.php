@extends('layout.app')

@section('pageTitle',trans('Sales Reports'))
@section('pageSubTitle',trans('Reports'))
@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="text-end print-section">
                    <button class="btn-danger btn btn-selected" id="amountHideButton">Hide Amount</button>
                    <button type="button" class="btn btn-info" onclick="printDiv('result_show')">Print</button>
                </div>
                <div class="card-content" id="result_show">
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
                                    <b>{{$show_data->voucher_no}}</b>
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
                                    <th class="p-2" >{{__('Description of Goods')}}</th>
                                    <th class="p-2" >{{__('Lc/Lot no')}}</th>
                                    <th class="p-2" >{{__('Brand/Trade Mark')}}</th>
                                    <th class="p-2" >{{__('Quantity Bag')}}</th>
                                    <th class="p-2" >{{__('Total Quantity in Kg')}}</th>
                                    <th class="p-2 td_hide" >{{__('Rate in Kg')}}</th>
                                    <th class="p-2 td_hide" >{{__('Amount')}}</th>
                                    <th class="p-2 td_hide" >{{__('Sales Commission Commission')}}</th>
                                    <th class="p-2 td_hide" >{{__('Transport Charge')}}</th>
                                    <th class="p-2 td_hide" >{{__('Labor Charge')}}</th>
                                    <th class="p-2 td_hide" >{{__('Total Amount')}}</th>
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
                                    <td class="td_hide">{{$s->rate_kg}}</td>
                                    <td class="td_hide">{{$s->amount}}</td>
                                    <td class="td_hide">{{$s->sale_commission}}</td>
                                    <td class="td_hide">{{$s->transport_cost}}</td>
                                    <td class="td_hide">{{$s->unloading_cost}}</td>
                                    <td class="td_hide">{{$s->total_amount}}</td>
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
                                    <th class="p-2 td_hide"></th>
                                    <th class="p-2 td_hide">{{$amount}}</th>
                                    <th class="p-2 td_hide">{{$saleCommission}}</th>
                                    <th class="p-2 td_hide">{{$transport}}</th>
                                    <th class="p-2 td_hide">{{$labour}}</th>
                                    <th class="p-2 td_hide">{{$totalAmount}}</th>
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
@push('scripts')
<script>
$(document).ready(function() {
    $('#amountHideButton').click(function(){
        if ($(this).hasClass('btn-selected')){
            $('.td_hide').addClass('d-none');
            $(this).removeClass('btn-danger').addClass('btn-success').removeClass('btn-selected').text('Show Amount');
        }else{
            $('.td_hide').removeClass('d-none');
            $(this).addClass('btn-danger').removeClass('btn-success').addClass('btn-selected').text('Hide Amount');
        }
    });
});
</script>
@endpush
@extends('layout.app')

@section('pageTitle',trans('Regular Purchase Reports'))
@section('pageSubTitle',trans('Reports'))
@push("styles")
<link rel="stylesheet" href="{{ asset('assets/css/main/full-screen.css') }}">
@endpush
@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="text-end">
                    <button type="button" class="btn btn-info" onclick="printDiv('result_show')">Print</button>
                </div>
                <div class="card-content" id="result_show">
                    <div class="text-center"><h4>REGULAR PURCHASES</h4></div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-12 d-flex justify-content-between">
                                <div>
                                    <label for="">Party Name:</label>
                                    <b>{{$show_data->supplier?->supplier_name}}</b>
                                </div>
                                <div>
                                    <label for="">Invoice No:</label>
                                    <b>{{$show_data->voucher_no}}</b>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <div>
                                    <label for="">Address:</label>
                                    <b>{{$show_data->supplier?->address}}</b>
                                </div>
                                <div>
                                    <label for="">Purchases Date:</label>
                                    <b>{{$show_data->purchase_date}}</b>
                                </div>
                            </div>
                        </div>
                        <table class="table mb-5">
                            <thead>
                                <tr class="bg-primary text-white text-center">
                                    <th class="p-2">{{__('#SL')}}</th>
                                    <th class="p-2" data-title="Description of Goods">Des.of.goods</th>
                                    <th class="p-2" data-title="Lot no/ Lc no">Lot/Lc No</th>
                                    <th class="p-2" data-title="Trade Marek/ Brand">Brand</th>
                                    <th class="p-2" data-title="Quantity Bag">Qty Bag</th>
                                    <th class="p-2" data-title="Quantity kg">Qty Kg</th>
                                    <th class="p-2" data-title="Less Quantity kg">L.Qty Kg</th>
                                    <th class="p-2" data-title="Actual Quantity">A.Quantity</th>
                                    <th class="p-2" data-title="Discount in Kg" >Dis.kg</th>
                                    <th class="p-2" data-title="Rate in kg">Rate Kg</th>
                                    <th class="p-2" >Amount</th>
                                    <th class="p-2" data-title="Purchase Commission">P.Com</th>
                                    <th class="p-2" data-title="Transport Cost">Tr.Cost</th>
                                    <th class="p-2" data-title="Unloading Cost">Un.Cost</th>
                                    <th class="p-2" data-title="Sales income per bag(2tk)">S.income.per.bag</th>
                                    <th class="p-2" data-title="Total Amount">Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalBagQty = 0;
                                    $totalQty = 0;
                                    $amount = 0;
                                    $purCommission = 0;
                                    $transport = 0;
                                    $labour = 0;
                                    $totalLessQty = 0;
                                    $totalActualQty = 0;
                                    $totalDiscount = 0;
                                    $totalAmount = 0;
                                    $totalSaleIncome = 0;
                                @endphp

                                @forelse($purDetail as $s)
                                <tr class="text-center">
                                    <th scope="row">{{ ++$loop->index }}</th>
                                    <td>{{$s->product?->product_name}}</td>
                                    <td>{{$s->lot_no}}</td>
                                    <td>{{$s->brand}}</td>
                                    <td>{{$s->quantity_bag}}</td>
                                    <td>{{$s->quantity_kg}}</td>
                                    <td>{{$s->less_quantity_kg}}</td>
                                    <td>{{$s->actual_quantity}}</td>
                                    <td>{{$s->discount}}</td>
                                    <td>{{$s->rate_kg}}</td>
                                    <td>{{$s->amount}}</td>
                                    <td>{{$s->purchase_commission}}</td>
                                    <td>{{$s->transport_cost}}</td>
                                    <td>{{$s->unloading_cost}}</td>
                                    <td>{{$s->sale_income_per_bag}}</td>
                                    <td>{{$s->total_amount}}</td>
                                </tr>
                                @php
                                    $totalBagQty += $s->quantity_bag;
                                    $totalQty += $s->quantity_kg;
                                    $totalLessQty += $s->less_quantity_kg;
                                    $totalActualQty += $s->actual_quantity;
                                    $totalDiscount += $s->discount;
                                    $amount += $s->amount;
                                    $purCommission += $s->purchase_commission;
                                    $transport += $s->transport_cost;
                                    $labour += $s->unloading_cost;
                                    $totalSaleIncome += $s->sale_income_per_bag;
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
                                    <th class="p-2">{{$totalLessQty}}</th>
                                    <th class="p-2">{{$totalActualQty}}</th>
                                    <th class="p-2">{{$totalDiscount}}</th>
                                    <th class="p-2"></th>
                                    <th class="p-2">{{$amount}}</th>
                                    <th class="p-2">{{$purCommission}}</th>
                                    <th class="p-2">{{$transport}}</th>
                                    <th class="p-2">{{$labour}}</th>
                                    <th class="p-2">{{$totalSaleIncome}}</th>
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
@push('scripts')
<script>
    function printDiv(divName) {
		var printContents = document.getElementById(divName).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
	}
</script>
<script src="{{ asset('/assets/js/full_screen.js') }}"></script>
@endpush
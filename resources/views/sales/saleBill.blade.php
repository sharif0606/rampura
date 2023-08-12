@extends('layout.app')

@section('pageTitle',trans('Sales Reports'))
@section('pageSubTitle',trans('Reports'))

@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="text-end">
                    <button type="button" class="btn btn-info" onclick="printDiv('result_show')">Print</button>
                </div>
                <div class="card-content" id="result_show">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="text-center">
                                <h4>RAMPUR SYNDICATED/ M.A TRADING</h4>
                                <strong><p>193, R.S Tower, Khatungonj, Chattogram, Bangladesh.</p></strong>
                                <strong><p>Mobile No: +8801707-377372, E-mail: Rampursyndicate</p></strong>
                                <h6>SALES/ বিক্রয় পত্র</h6>
                                <p><b>Party</b> Reporting System</p>
                            </div>
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
                                    <th class="p-2" >{{__('PARTICULER/ বিবরণ')}}</th>
                                    <th class="p-2" >{{__('TRADE MARK/ BRAND')}}</th>
                                    <th class="p-2" >{{__('QUANTATY BAG /বস্তার পরিমান')}}</th>
                                    <th class="p-2" >{{__('QUANTATIY CARTON/কেজির পরিমাণ')}}</th>
                                    <th class="p-2" >{{__('RATE IN PER KG / প্রতি কেজি')}}</th>
                                    <th class="p-2" >{{__('TOTAL/ সর্বমোট')}}</th>
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
                                    <td>{{$s->brand}}</td>
                                    <td>{{$s->quantity_bag}}</td>
                                    <td>{{$s->quantity_kg}}</td>
                                    <td>{{$s->rate_kg}}</td>
                                    <td>{{$s->amount}}</td>
                                    {{-- <td>{{$s->sale_commission}}</td>
                                    <td>{{$s->transport_cost}}</td>
                                    <td>{{$s->unloading_cost}}</td>
                                    <td>{{$s->total_amount}}</td> --}}
                                </tr>
                                @php
                                    $totalBagQty += $s->quantity_bag;
                                    $totalQty += $s->quantity_kg;
                                    $amount += $s->amount;
                                    $saleCommission += $s->sale_commission;
                                    $transport += $s->transport_cost;
                                    $labour += $s->unloading_cost;
                                    $totalAmount= $amount+$saleCommission+$labour;
                                @endphp
                                @empty
                                <tr>
                                    <th colspan="12" class="text-center">No data Found</th>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="text-center">
                                    <th colspan="3" class="p-2">Total</th>
                                    <th class="p-2">{{$totalBagQty}}</th>
                                    <th class="p-2">{{$totalQty}}</th>
                                    <th class="p-2"></th>
                                    <th class="p-2">{{$amount}}</th>
                                </tr>
                                <tr class="text-center">
                                    <th colspan="6" class="p-2">COMMISSION CHARGE / বাহির কমিশন</th>
                                    <th class="p-2">{{$saleCommission}}</th>
                                </tr>
                                <tr class="text-center">
                                    <th colspan="6" class="p-2">LODING CHARGE/ ধোলাই চার্জ</th>
                                    <th class="p-2">{{$labour}}</th>
                                </tr>
                                <tr class="text-center">
                                    <th colspan="6" class="p-2">GRAND TOTAL/ সর্বমোট টাকা</th>
                                    <th class="p-2">{{$totalAmount}}</th>
                                </tr>
                            </tfoot>
                        </table>
                        <div>
                            <h6>Buyer's signature /ক্রেতার স্বাক্ষর</h6>
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
    function printDiv(divName) {
		var printContents = document.getElementById(divName).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
	}
</script>
@endpush
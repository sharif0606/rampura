
@extends('layout.app')

@section('pageTitle',trans('Sales Reports'))
@section('pageSubTitle',trans('Reports'))
@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="text-end print-section">
                    {{--  <button class="btn-danger btn btn-selected" id="amountHideButton">Hide Amount</button>  --}}
                    <button type="button" class="btn btn-info" onclick="printDiv('result_show')">Print</button>
                </div>
                <div class="card-content" id="result_show">
                    <style>
                        .tbl_expense{
                            border: 1px solid;
                            border-collapse: collapse;
                        }
                    </style>
                <table width="100%">
                    <tr>
                        <th class="text-center" style="font-size: 40px; font-weight: 900;">
                            মেসার্স রামপুর সিন্ডিকেট
                        </th>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <th width="41%"></th>
                        <th width="18%"
                            style="font-size: 20px; background-color: rgb(70, 70, 230); color: white;  border-radius: 5px;">
                            কমিশন এজেন্ট
                        </th>
                        <th></th>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td style="font-size: 18px; text-align: center;">
                            ১৯৩, খাতুনগঞ্জ, চট্টগ্রাম, বাংলাদেশ।
                        </td>

                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td style="font-size: 18px; text-align: center;">
                            মোবাইলঃ ০১৮১৯-৩৭৭৩৭২, ০১৬৭২-৯৮১৬১৪,০১৭৫৮-৯৮২৬৬১
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td colspan="3" style="width: 28%; "> নং </td>
                        <td style="width: 44%;"></td>
                        <td>তারিখ_________{{ date('d-M-Y', strtotime($show_data->sales_date)) }}_____________________</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td>
                            নাম______________{{$show_data->customer?->customer_name}}_____________________________________________________________________________________
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td>
                            ঠিকানা_____________{{$show_data->customer?->address}}____________________________________________________________________________________
                        </td>
                    </tr>
                </table>
                <br>
                <table width="100%" border="1" cellspacing="0"
                    style="background-color: rgb(223, 196, 248);border-color:rgb(187, 134, 237) ;">
                    <tr class="text-center" style="color: rgb(126, 126, 240); position: relative;">
                        <th width="70%" style="background-color: rgb(223, 196, 248)">বিবরণ</th>
                        <th width="10%">দর</th>
                        <th width="20%">টাকা</th>
                    </tr>
                    @php
                    $totalAmount = 0;
                    @endphp
                    @forelse($salesDetail as $s)
                    <tr class="text-center">
                        <td width="70%" style="background-color: aliceblue;">{{$s->product?->product_name}}</td>
                        <td width="10%">{{$s->rate_kg}}</td>
                        <td width="20%">{{$s->amount}}</td>
                    </tr>
                    @php
                    $totalAmount += $s->amount;
                    @endphp
                    <tr style="height: 10px">
                        <td width="70%" style="background-color: aliceblue;"></td>
                        <td width="10%"></td>
                        <td width="20%"></td>
                    </tr>
                    @empty
                    <tr>
                        <th colspan="10">No data Found</th>
                    </tr>
                    @endforelse
                    <tr class="text-center">
                        <td style="text-align: right;">মোট </td>
                        <td></td>
                        <td>{{ $totalAmount }}</td>
                    </tr>
                </table>
                <div
                    {{--  style="transform: rotate(-35deg); position: absolute; margin-top: -280px; font-size: 35px; opacity: 0.5; padding-left: 300px;">
                    কমিশন
                    ভিত্তিক</div>  --}}
                <div style="text-align: center; font-size: 20px;"><b> ধন্যবাদ আবার আসবেন।</b></div>

                <div style="text-align: right; padding-right: 100px;">স্বাক্ষর</div>
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

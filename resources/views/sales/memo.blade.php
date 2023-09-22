<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.maateen.me/adorsho-lipi/font.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <style>
        @media print
        {    
            .no-print, .no-print *
            {
                display: none !important;
            }
        }
        .tinput {
            width: 100%;
            outline: 0;
            border-style: solid;
            border-width: 1px 0 0;
            border-color: #4F709C;
            background-color: transparent;
        }
        .sinput {
            width: 60%;
            outline: 0;
            border-style: solid;
            border-width: 1px 0 0;
            border-color: #4F709C;
            background-color: transparent;
        }
        input:focus {
            border-color: #4F709C;
            font-family: Montserrat !important;
        }


        
        .tbl_table{
            border: solid 1px;
            border-color: rgb(9, 95, 9);
            border-collapse: collapse;
        }
        .tbl_table_border_right{
            border-right: solid 1px;
            border-color: rgb(9, 95, 9);
            border-collapse: collapse;
        }
        body{
            font-family: 'AdorshoLipi', sans-serif;
        }
       
        .btn {
    --bs-btn-padding-x: 0.75rem;
    --bs-btn-padding-y: 0.375rem;
    --bs-btn-font-family: ;
    --bs-btn-font-size: 1rem;
    --bs-btn-font-weight: 400;
    --bs-btn-line-height: 1.5;
    --bs-btn-color: #607080;
    --bs-btn-bg: transparent;
    --bs-btn-border-width: 1px;
    --bs-btn-border-color: transparent;
    --bs-btn-border-radius: 0.25rem;
    --bs-btn-box-shadow: inset 0 1px 0 hsla(0,0%,100%,.15),0 1px 1px rgba(0,0,0,.075);
    --bs-btn-disabled-opacity: 0.65;
    --bs-btn-focus-box-shadow: 0 0 0 0.25rem rgba(var(--bs-btn-focus-shadow-rgb),.5);
    background-color: var(--bs-btn-bg);
    border: var(--bs-btn-border-width) solid var(--bs-btn-border-color);
    border-radius: var(--bs-btn-border-radius);
    color: var(--bs-btn-color);
    cursor: pointer;
    display: inline-block;
    font-family: var(--bs-btn-font-family);
    font-size: var(--bs-btn-font-size);
    font-weight: var(--bs-btn-font-weight);
    line-height: var(--bs-btn-line-height);
    padding: var(--bs-btn-padding-y) var(--bs-btn-padding-x);
    text-align: center;
    text-decoration: none;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
    vertical-align: middle;
    --bs-btn-color: #fff;
    --bs-btn-bg: #435ebe;
    --bs-btn-border-color: #435ebe;
    --bs-btn-hover-color: #fff;
    --bs-btn-hover-bg: #3950a2;
    --bs-btn-hover-border-color: #364b98;
    --bs-btn-focus-shadow-rgb: 95,118,200;
    --bs-btn-active-color: #fff;
    --bs-btn-active-bg: #364b98;
    --bs-btn-active-border-color: #32478f;
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0,0,0,.125);
    --bs-btn-disabled-color: #fff;
    --bs-btn-disabled-bg: #435ebe;
    --bs-btn-disabled-border-color: #435ebe;
}
    </style>
</head>
<body >
    
    <div>
        <a href="{{route(currentUser().'.dashboard')}}" class="btn no-print"> Go To Dashboard</a>
        <button class="no-print btn" type="button" onclick="window.print()" style="float:right"> 
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
              </svg>
            Print
        </button>
    </div>
    <div class="bg1"  style="width:800px; margin:0 auto;">
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th colspan="4">
                        <h1 style="color: #4F709C; margin: 0px 0px 6px 0px"> মেসার্স রামপুর সিন্ডিকেট</h1>
                        <span style="background-color: #4F709C; color:white; padding: 3px 7px 1px 7px; border: transparent; border-radius: 5px;">কমিশন এজেন্ট</span><br>
                        <span style="color: #4F709C;"><p style="margin: 6px 0px 0px 0px;">১৯৩, খাতুনগঞ্জ, চট্টগ্রাম, বাংলাদেশ।</p></span>
                        <p style="margin: 2px; color: #4F709C;">মোবাইলঃ ০১৮১৯-৩৭৭৩৭২, ০১৬৭২-৯৮১৬১৪,০১৭৫৮-৯৮২৬৬১</p>
                    </th>
                </tr>
                <tr>
                    <th style="text-align: left; color: #4F709C; padding: 7px 0 7px 0;">নং</th>
                    <td></td>
                    <th style="text-align: right; color: #4F709C; padding: 7px 2px 7px 0;">তারিখঃ</th>
                    <td style="width: 20%; padding: 7px 0 7px 0;"><input type="text" value="{{ date('d-M-Y', strtotime($show_data->sales_date)) }}" class="tinput"></td>
                </tr>
                <tr>
                    <th style="text-align: left; color: #4F709C; padding: 7px 0 7px 0;">নাম</th>
                    <td colspan="3" style="padding: 7px 0 7px 0;"><input type="text" value="{{$show_data->customer?->customer_name}}" class="tinput"></td>
                </tr>
                <tr>
                    <th style="text-align: left; color: #4F709C; padding: 7px 0 7px 0;">ঠিকানা</th>
                    <td colspan="3" style="padding: 7px 0 7px 0;"><input type="text" value="{{$show_data->customer?->address}}" class="tinput"></td>
                </tr>
            </thead>
        </table>
        <div style=" height: 830px;">
            <table class="tbl_table" style="width: 100%;">
                <thead>
                    <tr class="tbl_table" style="background-color: #cdddf1; text-align: center;">
                        <th class="tbl_table" style="color: #4F709C; width: 60%;">বিবরণ</th>
                        <th class="tbl_table" style="color: #4F709C; width: 13%;">দর</th>
                        <th class="tbl_table" style="color: #4F709C; width: 27%;">টাকা</th>
                    </tr>
                </thead>
                @php
                    $totalAmount = 0;
                    $totalBag = 0;
                    $totalQty = 0;
                @endphp
                <tbody style="height: 400px;">
                    @forelse ($salesDetail as $key => $s)
                        <tr style="vertical-align: top; {{$key !== count($salesDetail) - 1 ? 'height: 0;' : ''}}">
                            <th class="tbl_table_border_right" style="color: #4F709C; text-align: left; padding-left: 5px;">{{$s->product?->product_name}}, {{$s->quantity_bag}} ব্যাগ, {{$s->actual_quantity}} কেজি</th>
                            <th class="tbl_table_border_right" style="color: #4F709C; background-color: #cdddf1; text-align: center;">{{$s->rate_kg}}</th>
                            <th style="color: #4F709C; background-color: #cdddf1; text-align: right; padding-right: 5px;">
                                @php 
                                    $totalAmount += $s->amount;
                                    $totalBag += $s->quantity_bag;
                                    $totalQty += $s->actual_quantity;
                                @endphp
                                {{$formattedAmount = number_format((round($s->amount)), 0, '.', ',');}}
                            </th>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No data found</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot >
                    <tr class="">
                        <th class="tbl_table_border_right" style="color: #4F709C; padding-left: 5px;">
                            মোট:
                            {{$formattedAmount = number_format((round($totalBag)), 0, '.', ',');}} ব্যাগ, 
                            {{$formattedAmount = number_format((round($totalQty)), 0, '.', ',');}} কেজি
                        </th>
                        <th class="tbl_table tbl_table_border_right" style="color: #4F709C; background-color: #cdddf1;">মোট</th>
                        <th class="tbl_table" style="color: #4F709C; background-color: #cdddf1; text-align: right; padding-right: 5px;">{{$formattedAmount = number_format((round($totalAmount)), 0, '.', ',');}}</th>
                    </tr>
                </tfoot>
            </table>
            <div style="transform: rotate(-35deg); position: absolute; margin-top: -190px; font-size: 35px; opacity: 0.19; padding-left: 111px;">
                কমিশন ভিত্তিক
            </div>
            <div style="text-align: center; font-size: 20px; color: #4F709C; margin-bottom: 1.5rem;"><b> ধন্যবাদ আবার আসবেন।</b></div>
            <div style="text-align: right; padding-right: 100px; color: #4F709C;">স্বাক্ষর</div>
        </div>
    </div>
</body>
</html>
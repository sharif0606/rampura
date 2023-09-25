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
            border-color: green;
            background-color: transparent;
        }
        .sinput {
            width: 60%;
            outline: 0;
            border-style: solid;
            border-width: 1px 0 0;
            border-color: green;
            background-color: transparent;
        }
        input:focus {
            border-color: green;
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
<body>
    
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
        <div style="position: absolute; padding-left: 38rem;">
            <p>
                <label for="" style="color: green;">মোবাইল:</label> <span style="color: green;">০১৭০৭-৩৭৭৩৭২</span><br>
                <span style="color: green; padding-left: 68px;">০১৬৭২-৯৮১৬১৪</span>
            </p>
        </div>
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th colspan="4">
                        <p style="margin-bottom: 5px; color: green;">বিসমিল্লাহির রাহমানির রহিম</p>
                        <span style="background-color: green; color:white; padding: 5px; border: transparent; border-radius: 5px;">বেপারীর চৌথা</span>
                        <p style="margin-top: 6px; margin-bottom: 0px; color: green;">কমিশন এজেন্ট</p>
                        <h1 style="color: green; margin: 0px;"> মেসার্স রামপুর সিন্ডিকেট</h1>
                        <span style="color: green; border-bottom: solid 1.5px; border-top: solid 1.5px; margin-top: 4px; margin-bottom: 4px;">সুপারী, মরিচ ও ভূষা মালের আড়ৎ</span>
                        <p style="margin: 2px; color: green;">১৯৩, খাতুনগঞ্জ, চট্টগ্রাম।</p>
                    </th>
                </tr>
                @php
                    $id = '';
                    $supplier = '';
                    $address = '';
                @endphp
                
                @if($purchase->first()?->purchase)
                    @php
                        $id = optional($purchase->first()->purchase)->id;
                        $supplier = optional($purchase->first()->purchase->supplier)->supplier_name;
                        $address = optional($purchase->first()->purchase->supplier)->address;
                    @endphp
                @endif
                @if($purchase->first()?->beparian_purchase)
                    @php
                        $id = optional($purchase->first()->beparian_purchase)->id;
                        $supplier = optional($purchase->first()->beparian_purchase->supplier)->supplier_name;
                        $address = optional($purchase->first()->beparian_purchase->supplier)->address;
                    @endphp
                @endif
                @if($purchase->first()?->regular_purchase)
                    @php
                        $id = optional($purchase->first()->regular_purchase)->id;
                        $supplier = optional($purchase->first()->regular_purchase->supplier)->supplier_name;
                        $address = optional($purchase->first()->regular_purchase->supplier)->address;
                    @endphp
                @endif
                <tr>
                    <th style="text-align: left; color: green; padding: 7px 0 7px 0;">নং</th>
                    <td><span>{{$id}}</span></td>
                    <th style="text-align: right; color: green; padding: 7px 2px 7px 0;">তারিখঃ</th>
                    <td style="width: 20%; padding: 7px 0 7px 0;"><input type="text" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" class="tinput"></td>
                </tr>
                <tr>
                    <th style="text-align: left; color: green; padding: 7px 0 7px 0;">নাম</th>
                    <td colspan="3" style="padding: 7px 0 7px 0;"><input type="text" value="{{$supplier}}" class="tinput"></td>
                </tr>
                <tr>
                    <th style="text-align: left; color: green; padding: 7px 0 7px 0;">ঠিকানা</th>
                    <td colspan="3" style="padding: 7px 0 7px 0;"><input type="text" value="{{$address}}" class="tinput"></td>
                </tr>
            </thead>
        </table>
        <div>
            <table class="tbl_table" style="width: 100%;">
                <tbody>
                    @php
                        $subSalesAmount = 0;
                        $subSalesActualQty = 0;
                        $subSalesBag = 0;

                        $totalPayment = 0;
                        $bagLeft = 0;
                        $qtyLeft = 0;

                        $subPurchaseAmount = 0;
                        $subPurchaseExpense = 0;
                        $subPurActualQty = 0;
                        $subPurBag = 0;
                        $rate_per_kg = 0;
                        $totalPurchaseAmount = 0;
                        $totalDue = 0;
                        $salesFromPurchase = 0;
                        $totalSalesAmount = 0;
                        $formattedAmount = 0;
                        $profit = 0;
                    @endphp
                    <tr class="tbl_table">
                        <th class="tbl_table" style="color: green; text-align: center;">জমা</th>
                        <th class="tbl_table" style="color: green; text-align: center;">খরচ</th>
                    </tr>
                    <tr style="height: 600px;">
                        <th class="tbl_table_border_right" style="color: green; vertical-align: top;">
                            @forelse ($sales as $s)
                                <table style="width: 100%;">
                                        <tr>
                                            <th colspan="2" style="text-align: left;">তারিখঃ <span>{{date('d-m-Y', strtotime($s->sales?->sales_date))}}</span></th>
                                            {{-- <th style="text-align: right;"></th> --}}
                                        </tr>
                                        <tr>
                                            <th colspan="2" style="text-align: left;"><span style="border-bottom: solid 1px;">{{$s->product?->product_name}}, Lot No: {{$s->lot_no}}, Trade: {{$s->brand}}</span></th>
                                        </tr>
                                        <tr>
                                            <th style="text-align: left; padding-bottom: 1rem;">{{$s->quantity_bag}} ব্যাগ, {{$s->actual_quantity}} কেজি *{{$s->rate_kg}}/- </th>
                                            <th style="text-align: right; padding-bottom: 1rem;">{{ number_format($s->amount,'0', '.', ',')}}</th>
                                        </tr>
                                        @php
                                            $subSalesAmount += $s->amount;
                                            $subSalesActualQty += $s->actual_quantity;
                                            $subSalesBag += $s->quantity_bag;
                                        @endphp
                                        {{-- @if($s->sales?->expense)
                                            @foreach ($s->sales?->expense as $ex)
                                                @if($ex->cost_amount != null)
                                                    <tr>
                                                        <th style="text-align: left;">{{$ex->expense?->head_name}}</th>
                                                        <th style="text-align: right;">{{ number_format($ex->cost_amount,'0', '.', ',')}}</th>
                                                    </tr>
                                                    @php
                                                        $subsalesExpense += $ex->cost_amount;
                                                    @endphp
                                                @endif
                                            @endforeach
                                        @endif --}}
                                        {{-- <tr>
                                            <th colspan="2" style="text-align: right;"><span style="border-top: double;">{{$s->sales?->grand_total}}</span></th>
                                        </tr> --}}
                                </table>
                            @empty
                            @endforelse
                        </th>
                        <th style="color: green; vertical-align: top;">
                            @forelse ($purchase as $pur)
                                <table style="width: 100%; padding-bottom: 1.5rem;">
                                    <tr>
                                        <th colspan="2" style="text-align: left;"><span style="border-bottom: solid 1px;">{{$pur->product?->product_name}}, Lot No: {{$pur->lot_no}}, Trade: {{$pur->brand}}</span></th>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left;">{{$pur->quantity_bag}} ব্যাগ , {{$pur->actual_quantity}} কেজি *{{$pur->rate_kg}}/- </th>
                                        <th style="text-align: right;">{{ number_format($pur->amount,'0', '.', ',')}}</th>
                                    </tr>
                                    
                                    @php
                                        $subPurchaseAmount += $pur->amount;
                                        $subPurActualQty += $pur->actual_quantity;
                                        $subPurBag += $pur->quantity_bag;
                                    @endphp

                                    @if($pur->purchase?->expense)
                                        @foreach ($pur->purchase?->expense as $ex)
                                            @if($ex->cost_amount != null && $ex->lot_no == $pur->lot_no)
                                            <tr>
                                                <th style="text-align: left;">{{$ex->expense?->head_name}}</th>
                                                <th style="text-align: right;">{{ number_format($ex->cost_amount,'0', '.', ',')}}</th>
                                            </tr>
                                            @php
                                                $subPurchaseExpense += $ex->cost_amount;
                                            @endphp
                                            @endif
                                        @endforeach
                                    @endif

                                    @if($pur->beparian_purchase?->expense)
                                        @foreach ($pur->beparian_purchase?->expense as $ex)
                                            @if($ex->cost_amount != null && $ex->lot_no == $pur->lot_no)
                                            <tr>
                                                <th style="text-align: left;">{{$ex->expense?->head_name}}</th>
                                                <th style="text-align: right;">{{ number_format($ex->cost_amount,'0', '.', ',')}}</th>
                                            </tr>
                                            @php
                                                $subPurchaseExpense += $ex->cost_amount;
                                            @endphp
                                            @endif
                                        @endforeach
                                    @endif

                                    @if($pur->regular_purchase?->expense)
                                        @foreach ($pur->regular_purchase?->expense as $ex)
                                            @if($ex->cost_amount != null && $ex->lot_no == $pur->lot_no)
                                            <tr>
                                                <th style="text-align: left;">{{$ex->expense?->head_name}}</th>
                                                <th style="text-align: right;">{{ number_format($ex->cost_amount,'0', '.', ',')}}</th>
                                            </tr>
                                            @php
                                                $subPurchaseExpense += $ex->cost_amount;
                                            @endphp
                                            @endif
                                        @endforeach
                                    @endif

                                    @if($pur->purchase?->payment)
                                        @foreach ($pur->purchase?->payment as $pm)
                                            @if($pm->lc_no != null && $pm->lc_no == $pur->lot_no)
                                            @php
                                                $totalPayment += $pm->amount;
                                            @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                    @if($pur->beparian_purchase?->payment)
                                        @foreach ($pur->beparian_purchase?->payment as $pm)
                                            @if($pm->lc_no != null && $pm->lc_no == $pur->lot_no)
                                            @php
                                                $totalPayment += $pm->amount;
                                            @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                    @if($pur->regular_purchase?->payment)
                                        @foreach ($pur->regular_purchase?->payment as $pm)
                                            @if($pm->lc_no != null && $pm->lc_no == $pur->lot_no)
                                            @php
                                                $totalPayment += $pm->amount;
                                            @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                    {{-- @if($pur->purchase?->grand_total)
                                    <tr>
                                        <th colspan="2" style="text-align: right;"><span style="border-top: double;">{{$pur->purchase?->grand_total}}</span></th>
                                    </tr>
                                    @endif
                                    @if($pur->beparian_purchase?->grand_total)
                                    <tr>
                                        <th colspan="2" style="text-align: right;"><span style="border-top: double;">{{$pur->beparian_purchase?->grand_total}}</span></th>
                                    </tr>
                                    @endif
                                    @if($pur->regular_purchase?->grand_total)
                                    <tr>
                                        <th colspan="2" style="text-align: right;"><span style="border-top: double;">{{$pur->regular_purchase?->grand_total}}</span></th>
                                    </tr>
                                    @endif --}}
                                </table>
                            @empty
                            @endforelse
                        </th>
                        
                    </tr>
                    
                </tbody>
                <tfoot class="tbl_table">
                    <tr style="vertical-align: top;">
                        <th class="tbl_table_border_right">
                            <table style="width: 100%;">
                                <tr>
                                    <th style="text-align: left;"><span style="color: green;">মোট {{$subSalesBag}} ব্যাগ, {{$subSalesActualQty}} কেজি</span></th>
                                    <td style="text-align: right;">
                                        @php
                                            $totalSalesAmount= $subSalesAmount;
                                        @endphp
                                        <span style="color: green;">{{$formattedAmount = number_format($totalSalesAmount, 0, '.', ',');}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2" style="text-align: left;">
                                        @php
                                            $bagLeft = $subPurBag - $subSalesBag;
                                            $qtyLeft = $subPurActualQty - $subSalesActualQty;
                                        @endphp
                                        <span style="color: green;">বর্তমান স্টক {{$bagLeft}} ব্যাগ, {{$qtyLeft}} কেজি</span>
                                    </th>
                                </tr>
                                
                            </table>
                        </th>
                        <th class="tbl_table_border_right">
                            <table style="width: 100%;">
                                <tr>
                                    
                                    <th style="text-align: left;"><span style="color: green;">মোট {{$subPurBag}} ব্যাগ, {{$subPurActualQty}} কেজি</span></th>
                                    <td style="text-align: right;">
                                        @php
                                            $totalPurchaseAmount= $subPurchaseAmount + $subPurchaseExpense;
                                        @endphp
                                        <span style="color: green;">{{number_format($totalPurchaseAmount, 0, '.', ','); }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="text-align: left;"><span style="color: green;">নগদ পরিশোধ</span></th>
                                    <td style="text-align: right;">
                                        <span style="color: green;">{{number_format(round($totalPayment), 0, '.', ','); }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="text-align: left;"><span style="color: green;">পরিশোধযোগ্য</span></th>
                                    <td style="text-align: right;">
                                        @php
                                            $totalDue= $totalPurchaseAmount - $totalPayment;
                                        @endphp
                                        <span style="color: green; border-top: double;">{{number_format(round($totalDue), 0, '.', ','); }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    
                                    <th style="text-align: left;"><span style="color: green;">প্রাপ্য</span></th>
                                    <td style="text-align: right;">
                                        @php
                                            $profit = $totalSalesAmount - $totalDue
                                        @endphp
                                        <span style="color: green;">{{number_format($profit, 0, '.', ','); }}</span>
                                    </td>
                                </tr>
                                
                            </table>
                        </th>
                        
                    </tr>
                    <tr>
                        <th class="tbl_table_border_right " style="padding-top: 1rem;">
                            <label for="" style="color: green;">নগদ</label>
                            <input type="text" class="sinput">
                        </th>
                        <th style="padding-top: 1rem;">
                            <label for="" style="color: green;">স্বাক্ষর</label>
                            <input type="text" class="sinput">
                        </th>
                    </tr>
                    <tr>
                        <th class="tbl_table_border_right" style="padding-top: 1rem;">
                            <label for="" style="color: green;">চেক</label>
                            <input type="text" class="sinput">
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
</html>
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
                        <span style="color: green; border-bottom: solid 2px; border-top: solid 2px; margin-top: 4px; margin-bottom: 4px;">সুপারী, মরিচ ও ভূষা মালের আড়ৎ</span>
                        <p style="margin: 2px; color: green;">১৯৩, খাতুনগঞ্জ, চট্টগ্রাম।</p>
                    </th>
                </tr>
                <tr>
                    {{-- <th style="text-align: left; color: green; padding: 7px 0 7px 0;">নং</th>
                    <td></td> --}}
                    <th style="text-align: right; color: green; padding: 7px 0 7px 0;">তারিখঃ</th>
                    <td style="width: 20%; padding: 7px 0 7px 0;"><input type="text" class="tinput"></td>
                </tr>
                {{-- <tr>
                    <th style="text-align: left; color: green; padding: 7px 0 7px 0;">নাম</th>
                    <td colspan="3" style="padding: 7px 0 7px 0;"><input type="text" class="tinput"></td>
                </tr>
                <tr>
                    <th style="text-align: left; color: green; padding: 7px 0 7px 0;">ঠিকানা</th>
                    <td colspan="3" style="padding: 7px 0 7px 0;"><input type="text" class="tinput"></td>
                </tr> --}}
            </thead>
        </table>
        <div style="background-color: rgb(219, 239, 219); height: 830px;">
            <table class="tbl_table" style="width: 100%;">
                <tbody >
                    @php
                        $subSalesAmount = 0;
                        $subsalesExpense = 0;
                        $subSalesActualQty = 0;

                        $subPurchaseAmount = 0;
                        $subPurchaseExpense = 0;
                        $subPurActualQty = 0;
                    @endphp
                    <tr class="tbl_table">
                        <th class="tbl_table" style="color: green; text-align: center;">জমা</th>
                        <th class="tbl_table" style="color: green; text-align: center;">খরচ</th>
                    </tr>
                    <tr >
                        <th class="tbl_table_border_right" style="color: green; text-align: center;">
                            <table style="width: 100%;">
                                @forelse ($sales as $s)
                                    <tr>
                                        <th style="text-align: left;">বিক্রয়:</th>
                                        <th style="text-align: right;">{{$s->amount}}</th>
                                    </tr>
                                    @php
                                        $subSalesAmount += $s->amount;
                                        $subSalesActualQty += $s->actual_quantity;
                                    @endphp
                                @empty
                                    <tr>
                                        <th colspan="2" style="text-align: center;">No data found</th>
                                    </tr>
                                @endforelse
                                @forelse ($salExpense as $ex)
                                    @if($ex->cost_amount != null)
                                        <tr>
                                            <th style="text-align: left;">{{$ex->expense?->head_name}}</th>
                                            <th style="text-align: right;">{{$ex->cost_amount}}</th>
                                        </tr>
                                        @php
                                            $subsalesExpense += $ex->cost_amount;
                                        @endphp
                                    @endif
                                @empty
                                @endforelse
                            </table>
                        </th>
                        <th  style="color: green; text-align: center;">
                            <table style="width: 100%;">
                                @forelse ($purchase as $pur)
                                    <tr>
                                        <th style="text-align: left;">ক্রয়:</th>
                                        <th style="text-align: right;">{{$pur->amount}}</th>
                                    </tr>
                                    @php
                                        $subPurchaseAmount += $pur->amount;
                                        $subPurActualQty += $pur->actual_quantity;
                                    @endphp
                                @empty
                                    <tr>
                                        <th colspan="2" style="text-align: center;">No data found</th>
                                    </tr>
                                @endforelse
                                @forelse ($purExpense as $ex)
                                    @if($ex->cost_amount != null)
                                        <tr>
                                            <th style="text-align: left;">{{$ex->expense?->head_name}}</th>
                                            <th style="text-align: right;">{{$ex->cost_amount}}</th>
                                        </tr>
                                        @php
                                            $subPurchaseExpense += $ex->cost_amount;
                                        @endphp
                                    @endif
                                @empty
                                @endforelse
                            </table>
                        </th>
                    </tr>
                    
                </tbody>
                <tfoot class="tbl_table">
                    <tr>
                        <th class="tbl_table_border_right">
                            <table style="width: 100%;">
                                <tr>
                                    <th style="text-align: left;"><span style="color: green;">মোট</span></th>
                                    <td style="text-align: right;">
                                        @php
                                            $totalSalesAmount= $subSalesAmount + $subsalesExpense;
                                        @endphp
                                        <span style="color: green;">{{$totalSalesAmount}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="text-align: left;"><span style="color: green;">বিক্রীত পণ্যের ক্রয়মূল্য</span></th>
                                    <td style="text-align: right;">
                                        @php
                                            $totalSalesAmount= $subSalesAmount + $subsalesExpense;

                                            $totalPurchaseAmount= $subPurchaseAmount + $subPurchaseExpense;
                                            $rate_per_kg = $totalPurchaseAmount / $subPurActualQty;
                                            $salesFromPurchase = $subSalesActualQty * $rate_per_kg;
                                            $formattedAmount = number_format($salesFromPurchase, 2, '.', '');
                                        @endphp
                                        <span style="color: green;">{{$formattedAmount}}</span>
                                    </td>
                                </tr>
                            </table>
                        </th>
                        <th class="tbl_table_border_right">
                            <table style="width: 100%;">
                                <tr>
                                    <th style="text-align: left;"><span style="color: green;">মোট</span></th>
                                    <td style="text-align: right;">
                                        @php
                                            $totalPurchaseAmount= $subPurchaseAmount + $subPurchaseExpense;
                                        @endphp
                                        <span style="color: green;">{{$totalPurchaseAmount}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="text-align: left;"><span style="color: green;">মোট আয়</span></th>
                                    <td style="text-align: right;">
                                        @php
                                            $totalSalesAmount= $subSalesAmount + $subsalesExpense;

                                            $totalPurchaseAmount= $subPurchaseAmount + $subPurchaseExpense;
                                            $rate_per_kg = $totalPurchaseAmount / $subPurActualQty;
                                            $salesFromPurchase = $subSalesActualQty * $rate_per_kg;
                                            $formattedAmount = number_format($salesFromPurchase, 2, '.', '');
                                            $profit = $totalSalesAmount - $formattedAmount
                                        @endphp
                                        <span style="color: green;">{{$profit}}</span>
                                    </td>
                                </tr>
                            </table>
                        </th>
                    </tr>
                    <tr>
                        <th class="tbl_table_border_right " style="padding-top: 3rem;">
                            <label for="" style="color: green;">নগদ</label>
                            <input type="text" class="sinput">
                        </th>
                        <th style="padding-top: 3rem;">
                            <label for="" style="color: green;">স্বাক্ষর</label>
                            <input type="text" class="sinput">
                        </th>
                    </tr>
                    <tr>
                        <th class="tbl_table_border_right" style="padding-top: 3rem;">
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
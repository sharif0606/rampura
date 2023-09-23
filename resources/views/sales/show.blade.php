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
                    <style>
                        .tbl_expense{
                            border: 1px solid;
                            border-collapse: collapse;
                        }
                    </style>
                    <div class="card-body">
                        <table style="width: 100%">
                            <tr style="text-align: center;">
                                <th colspan="2">
                                    <h4>M/S. RAMPURA SYNDICATE</h4>
                                    <p>R.S TOWER 193, KHATUNGONJ, CHATTOGRAM</p>
                                    <p>IMPORT, EXPORTER, WHOLESALER, RETAILSALER & COMMISSION AGENT</p>
                                    <p>E-MAIL: <a href="#" style="border-bottom: solid 1px; border-color:blue;">rampursyndicate@yahoo.com</a> Contact: +88 01707-377372 & +88 01758-982661</p>
                                    <h6 style="padding-bottom: 3.5rem;">SALES INVOICE</h6>
                                </th>
                            </tr>
                        </table>
                        <table style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="text-align: left; width: 70%;">
                                        <table style="width: 100%">
                                            <tr>
                                                <th style="text-align: left; width: 25%;">PARTY NAME:</th>
                                                <td style="text-align: left;">{{$show_data->customer?->customer_name}}</td>
                                            </tr>
                                            <tr>
                                                <th style="text-align: left; width: 25%;">ADDRESS:</th>
                                                <td style="text-align: left;">{{$show_data->customer?->address}}</p></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="text-align: end;">
                                        <table style="width: 100%">
                                            <tr>
                                                <th style="text-align: left; width: 30%;">INVOICE:</th>
                                                <td style="text-align: left;">{{$show_data->voucher_no}}</td>
                                            </tr>
                                            <tr>
                                                <th style="text-align: left; width: 30%;">DATE:</th>
                                                <td style="text-align: left;">{{ date('d-M-Y', strtotime($show_data->sales_date)) }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="tbl_expense" style="width:100%">
                            <tbody>
                                <tr class="tbl_expense">
                                    <th class="tbl_expense" style="text-align: center; padding: 5px;">{{__('SL NO')}}</th>
                                    <th class="tbl_expense" style="text-align: center; padding: 5px;">DESCRIPTION OF GOODS</th>
                                    <th class="tbl_expense" style="text-align: center; padding: 5px;">LOT/ LC NO</th>
                                    <th class="tbl_expense" style="text-align: center; padding: 5px;">TRADE MARK</th>
                                    <th class="tbl_expense" style="text-align: center; padding: 5px;">QUANTITY BAG</th>
                                    <th class="tbl_expense" style="text-align: center; padding: 5px;">QUANTITY KG</th>
                                    <th class="tbl_expense" style="text-align: center; padding: 5px;">LESS/ DISCOUNT KG</th>
                                    <th class="tbl_expense" style="text-align: center; padding: 5px;">ACTUAL QUANTITY IN KG</th>
                                    <th class="tbl_expense td_hide" style="text-align: center; padding: 5px;">RATE IN PER KG</th>
                                    <th class="tbl_expense td_hide" style="text-align: center; padding: 5px;">TOTAL AMOUNT</th>
                                </tr>
                                @forelse($salesDetail as $s)
                                <tr class="tbl_expense">
                                    <th class="tbl_expense" scope="row" style="text-align: center; padding: 5px;">{{ ++$loop->index }}</th>
                                    <td class="tbl_expense" style="text-align: center; padding: 5px;">{{$s->product?->product_name}}</td>
                                    <td class="tbl_expense" style="text-align: center; padding: 5px;">{{$s->lot_no}}</td>
                                    <td class="tbl_expense" style="text-align: center; padding: 5px;">{{$s->brand}}</td>
                                    <td class="tbl_expense" style="text-align: center; padding: 5px;">{{$formattedAmount = number_format((round($s->quantity_bag)), 0, '.', ',');}}</td>
                                    <td class="tbl_expense" style="text-align: center; padding: 5px;">{{$formattedAmount = number_format((round($s->quantity_kg)), 0, '.', ',');}}</td>
                                    <td class="tbl_expense" style="text-align: center; padding: 5px;">{{$formattedAmount = number_format((round($s->less_quantity_kg)), 0, '.', ',');}}</td>
                                    <td class="tbl_expense" style="text-align: center; padding: 5px;">{{$formattedAmount = number_format((round($s->actual_quantity)), 0, '.', ',');}}</td>
                                    <td class="tbl_expense td_hide" style="text-align: center; padding: 5px;">{{$formattedAmount = number_format((round($s->rate_kg)), 0, '.', ',');}}</td>
                                    <td class="tbl_expense td_hide" style="text-align: center; padding: 5px;">{{$formattedAmount = number_format((round($s->amount)), 0, '.', ',');}}</td>
                                </tr>
                                @empty
                                <tr>
                                    <th colspan="10">No data Found</th>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="td_hide" style="margin-top: 3rem;"><h5>ADD FOR SALES:</h5></div>
                        <table class="tbl_expense td_hide" style="width:100%;">
                            <tbody>
                                @forelse ($expense as $ex)
                                    @if($ex->cost_amount != null)
                                        <tr class="tbl_expense">
                                            <th class="tbl_expense" style="padding-left: 8px;">{{$ex->expense?->head_name}}</th>
                                            <td class="tbl_expense" style="text-align: end; padding-right: 8px;">{{$formattedAmount = number_format((round($ex->cost_amount)), 0, '.', ',');}}</td>
                                        </tr>
                                    @endif
                                @empty
                                    
                                @endforelse
                                    <tr class="tbl_expense">
                                        <th class="tbl_expense"  style="text-align: end; padding-right: 8px;"><h5>TOTAL RECEIVABLE AMOUNT</h5></th>
                                        <td class="tbl_expense" style="text-align: end; padding-right: 8px;"><h6>{{$formattedAmount = number_format((round($show_data->grand_total)), 0, '.', ',');}}</h6></td>
                                    </tr>
                            </tbody>
                        </table>
                        <div class="td_hide" style="margin-top: 5px; margin-bottom: 5px;"><h6>Note: Merchandise sold is non - refundable (Call for Delivery +01837-772467)</h6></div>
                        <div class="td_hide" style="text-align: end; padding-right: 100px; margin-top:5rem; margin-bottom: 4rem;">
                            <h6>AUTHORISED SIGNTORY</h6>
                        </div>
                        <table class="td_hide" style="width: 100%; margin-top: 2rem;">
                            <tr style="padding-top: 5rem;">
                                <th style="text-align: center;"><h6>PREPARED BY</h6></th>
                                <th style="text-align: center;"><h6>CHECKED BY</h6></th>
                                <th style="text-align: center;"><h6>VERIFIED BY</h6></th>
                            </tr>
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
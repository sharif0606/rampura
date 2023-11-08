@extends('layout.app')

@section('pageTitle',trans('Stock Individual Lot Wise Reports'))
@section('pageSubTitle',trans('Reports'))
@push("styles")
<link rel="stylesheet" href="{{ asset('assets/css/main/full-screen.css') }}">
@endpush
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
                <div class="text-end">
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
                                    <p>Mobile No 01707-377372, 01758-982661 E-mail: <a href="#" style="border-bottom: solid 1px; border-color:blue;">rampursyndicate@yahoo.com</a></p>
                                    <h6><span style="border-bottom: solid 1px;">{{$product->product_name}}</span></h6>
                                    <p>Stock Item Register</p>
                                </th>
                            </tr>
                        </table>
                        <div class="tbl_scroll">
                            <table class="tbl_expense" style="width:100%">
                                <tbody>
                                    <tr class="tbl_expense bg-secondary text-white">
                                        <th colspan="3" class="tbl_expense" style="text-align: center; padding: 5px;">PARTICULARS</th>
                                        <th colspan="4" class="tbl_expense" style="text-align: center; padding: 5px;">INWARDS</th>
                                        <th colspan="4" class="tbl_expense" style="text-align: center; padding: 5px;">OUTWARDS</th>
                                        <th colspan="2" class="tbl_expense" style="text-align: center; padding: 5px;">COLSING STOCK</th>
                                    </tr>
                                    <tr class="tbl_expense bg-secondary text-white">
                                        <th class="tbl_expense" style="text-align: center; padding: 5px;">Particulars</th>
                                        <th class="tbl_expense" style="text-align: center; padding: 5px;">Voh Type</th>
                                        <th class="tbl_expense" style="text-align: center; padding: 5px;">Vch No</th>
                                        <th class="tbl_expense" style="text-align: center; padding: 5px;">Quantity Bag</th>
                                        <th class="tbl_expense" style="text-align: center; padding: 5px;">Quantity Kg</th>
                                        <th class="tbl_expense" style="text-align: center; padding: 5px;">Rate In Kg</th>
                                        <th class="tbl_expense" style="text-align: center; padding: 5px;">Value</th>
                                        <th class="tbl_expense" style="text-align: center; padding: 5px;">Quantity Bag</th>
                                        <th class="tbl_expense" style="text-align: center; padding: 5px;">Quantity Kg</th>
                                        <th class="tbl_expense" style="text-align: center; padding: 5px;">Rate In Kg</th>
                                        <th class="tbl_expense" style="text-align: center; padding: 5px;">Value</th>
                                        <th class="tbl_expense" style="text-align: center; padding: 5px;">Quantity Bag</th>
                                        <th class="tbl_expense" style="text-align: center; padding: 5px;">Quantity Kg</th>
                                    </tr>
                                    @php
                                        $actualQtyTotalkg = 0;
                                        $actualQtyTotalbag = 0;
                                    @endphp
                                    @forelse($stock as $s)
                                    <tr class="tbl_expense">
                                        <td class="tbl_expense" style="text-align: center; padding: 5px;">
                                            {{$s->purchase?->supplier?->supplier_name}}
                                            {{$s->beparian_purchase?->supplier?->supplier_name}}
                                            {{$s->regular_purchase?->supplier?->supplier_name}}
                                            {{$s->sales?->customer?->customer_name}}
                                        </td>
                                        <td class="tbl_expense" style="text-align: center; padding: 5px;">
                                           @if($s->sales_id) Sales @else Purchase  @endif
                                        </td>
                                        <td class="tbl_expense" style="text-align: center; padding: 5px;">
                                            {{$s->purchase?->voucher_no}}
                                            {{$s->beparian_purchase?->voucher_no}}
                                            {{$s->regular_purchase?->voucher_no}}
                                            {{$s->sales?->voucher_no}}
                                        </td>
                                        @if($s->sales_id)
                                        <td class="tbl_expense" style="text-align: center; padding: 5px;"></td>
                                        <td class="tbl_expense" style="text-align: center; padding: 5px;"></td>
                                        <td class="tbl_expense" style="text-align: center; padding: 5px;"></td>
                                        <td class="tbl_expense" style="text-align: center; padding: 5px;"></td>
                                        <td class="tbl_expense" style="text-align: center; padding: 5px;">{{$s->quantity_bag}}</td>
                                        <td class="tbl_expense" style="text-align: center; padding: 5px;">{{$s->quantity}}</td>
                                        <td class="tbl_expense" style="text-align: center; padding: 5px;">{{$s->unit_price}}</td>
                                        <td class="tbl_expense" style="text-align: center; padding: 5px;">{{$s->total_amount}}</td>
                                        @else
                                        <td class="tbl_expense" style="text-align: center; padding: 5px;">{{$s->quantity_bag}}</td>
                                        <td class="tbl_expense" style="text-align: center; padding: 5px;">{{$s->quantity}}</td>
                                        <td class="tbl_expense" style="text-align: center; padding: 5px;">{{$s->unit_price}}</td>
                                        <td class="tbl_expense" style="text-align: center; padding: 5px;">{{$s->total_amount}}</td>
                                        <td class="tbl_expense" style="text-align: center; padding: 5px;"></td>
                                        <td class="tbl_expense" style="text-align: center; padding: 5px;"></td>
                                        <td class="tbl_expense" style="text-align: center; padding: 5px;"></td>
                                        <td class="tbl_expense" style="text-align: center; padding: 5px;"></td>
                                        @endif
                                        
                                        <td class="tbl_expense" style="text-align: center; padding: 5px;">
                                             @php echo $actualQtyTotalbag += $s->quantity_bag; @endphp
                                            
                                        </td>
                                        <td class="tbl_expense" style="text-align: center; padding: 5px;">
                                           @php echo $actualQtyTotalkg += $s->quantity; @endphp
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <th colspan="10">No data Found</th>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <table style="width: 100%; margin-top: 5rem;">
                            <tr style="padding-top: 5rem;">
                                <th style="text-align: center;"><h6>CHECKED BY</h6></th>
                                <th style="text-align: center;"><h6>VERIFIED BY</h6></th>
                                <th style="text-align: center;"><h6>Authoraised Signatory</h6></th>
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

<script src="{{ asset('/assets/js/full_screen.js') }}"></script>
@endpush
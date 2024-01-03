@extends('layout.app')

@section('pageTitle',trans('Stock Individual Product Wise Reports'))
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

                        .tbl_border{
                        border: 1px solid;
                        border-collapse: collapse;
                        }
                        </style>
                    <div class="card-body">
                        <table style="width: 100%">
                            <tr style="text-align: center;">
                                <th colspan="2">
                                    <h4>{{encryptor('decrypt', request()->session()->get('companyName'))}}</h4>
                                    <p>{{encryptor('decrypt', request()->session()->get('companyAddress'))}}</p>
                                    <p>IMPORT, EXPORTER, WHOLESALER, RETAILSALER & COMMISSION AGENT</p>
                                    <p>E-MAIL: <a href="#" style="border-bottom: solid 1px; border-color:blue;">{{encryptor('decrypt', request()->session()->get('companyEmail'))}}</a> Contact: {{encryptor('decrypt', request()->session()->get('companyContact'))}}</p>
                                    <h6><span style="border-bottom: solid 1px;">{{$product->product_name}}</span></h6>
                                    <p>Stock Item Register</p>
                                </th>
                            </tr>
                        </table>
                        <div class="tbl_scroll">
                            <table class="tbl_border" style="width:100%">
                                <tbody>
                                    <tr class="tbl_border bg-secondary text-white">
                                        <th colspan="3" class="tbl_border" style="text-align: center; padding: 5px;">PARTICULARS</th>
                                        <th colspan="4" class="tbl_border" style="text-align: center; padding: 5px;">INWARDS</th>
                                        <th colspan="4" class="tbl_border" style="text-align: center; padding: 5px;">OUTWARDS</th>
                                        <th colspan="2" class="tbl_border" style="text-align: center; padding: 5px;">COLSING STOCK</th>
                                    </tr>
                                    <tr class="tbl_border bg-secondary text-white">
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Particulars</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Voh Type</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Vch No</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Quantity Bag</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Quantity Kg</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Rate In Kg</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Value</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Quantity Bag</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Quantity Kg</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Rate In Kg</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Value</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Quantity Bag</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">Quantity Kg</th>
                                    </tr>
                                    @php
                                        $actualQtyTotalkg = 0;
                                        $actualQtyTotalbag = 0;
                                        $totalPurBag = 0;
                                        $totalPurKg = 0;
                                        $totalPurAmount = 0;
                                        $totalSalBag = 0;
                                        $totalSalKg = 0;
                                        $totalSalAmount = 0;
                                    @endphp
                                    @forelse($stock as $s)
                                    <tr class="tbl_border">
                                        <td class="tbl_border" style="text-align: center; padding: 5px;">
                                            {{$s->purchase?->supplier?->supplier_name}}
                                            {{$s->beparian_purchase?->supplier?->supplier_name}}
                                            {{$s->regular_purchase?->supplier?->supplier_name}}
                                            {{$s->sales?->customer?->customer_name}}
                                        </td>
                                        <td class="tbl_border" style="text-align: center; padding: 5px;">
                                           @if($s->sales_id) Sales @else Purchase  @endif
                                        </td>
                                        <td class="tbl_border" style="text-align: center; padding: 5px;">
                                            {{$s->purchase?->voucher_no}}
                                            {{$s->beparian_purchase?->voucher_no}}
                                            {{$s->regular_purchase?->voucher_no}}
                                            {{$s->sales?->voucher_no}}
                                        </td>
                                        @if($s->sales_id)
                                        <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                        <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                        <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                        <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                        <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->quantity_bag}}</td>
                                        <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->quantity}}</td>
                                        <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->unit_price}}</td>
                                        <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->total_amount}}</td>
                                        @php
                                            $totalSalBag += $s->quantity_bag;
                                            $totalSalKg += $s->quantity;
                                            $totalSalAmount += $s->total_amount;
                                        @endphp
                                        @else
                                        <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->quantity_bag}}</td>
                                        <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->quantity}}</td>
                                        <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->unit_price}}</td>
                                        <td class="tbl_border" style="text-align: center; padding: 5px;">{{$s->total_amount}}</td>
                                        <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                        <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                        <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                        <td class="tbl_border" style="text-align: center; padding: 5px;"></td>
                                        @php
                                            $totalPurBag += $s->quantity_bag;
                                            $totalPurKg += $s->quantity;
                                            $totalPurAmount += $s->total_amount;
                                        @endphp
                                        @endif
                                        
                                        <td class="tbl_border" style="text-align: center; padding: 5px;">
                                             @php echo $actualQtyTotalbag += $s->quantity_bag; @endphp
                                            
                                        </td>
                                        <td class="tbl_border" style="text-align: center; padding: 5px;">
                                           @php echo $actualQtyTotalkg += $s->quantity; @endphp
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <th colspan="10">No data Found</th>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="tbl_border" colspan="3" style="text-align: center; padding: 5px;">Total</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">{{$totalPurBag}}</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">{{$totalPurKg}}</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;"></th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">{{$totalPurAmount}}</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">{{$totalSalBag}}</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">{{$totalSalKg}}</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;"></th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;">{{$totalSalAmount}}</th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;"></th>
                                        <th class="tbl_border" style="text-align: center; padding: 5px;"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <table style="width: 100%; margin-top: 5rem;">
                            <tr style="padding-top: 5rem;">
                                <td style="text-align: center;"><span style="border-bottom: solid 1px;">{{encryptor('decrypt', request()->session()->get('userName'))}}</span></td>
                                <th style="text-align: center;"></th>
                                <th style="text-align: center;"></th>
                            </tr>
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
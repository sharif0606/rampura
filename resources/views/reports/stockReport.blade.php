@extends('layout.app')

@section('pageTitle',trans('Stock Reports'))
@section('pageSubTitle',trans('Reports'))

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
                <div class="card-content">
                    <div class="text-center"><h4>STOCK STATEMENT (Report)</h4></div>
                    <div class="card-body">
                        <form class="form" method="get" action="">
                            <div class="row">
                                {{-- <div class="col-md-2 mt-2">
                                    <label for="fdate" class="float-end"><h6>{{__('From Date')}}</h6></label>
                                </div>
                                <div class="col-md-4">
                                    <input type="date" id="fdate" class="form-control" value="{{ old('fdate')}}" name="fdate">
                                </div>
                                <div class="col-md-2 mt-2">
                                    <label for="tdate" class="float-end"><h6>{{__('To Date')}}</h6></label>
                                </div>
                                <div class="col-md-4">
                                    <input type="date" id="tdate" class="form-control" value="{{ old('tdate')}}" name="tdate">
                                </div> --}}
                                <div class="col-4 py-1">
                                    <label for="fdate">{{__('From Date')}}</label>
                                    <input type="date" id="fdate" class="form-control" value="{{ old('fdate')}}" name="fdate">
                                </div>
                                <div class="col-4 py-1">
                                    <label for="fdate">{{__('To Date')}}</label>
                                    <input type="date" id="tdate" class="form-control" value="{{ old('tdate')}}" name="tdate">
                                </div>
                                <div class="col-4 py-1">
                                    <label for="fdate">{{__('Category')}}</label>
                                    <select name="category" class="choices form-select">
                                        <option value="">Select</option>
                                        @forelse ($category as $cat)
                                            <option value="{{$cat->id}}" {{ old('category')==$cat->id?"selected":""}}>{{$cat->category}}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-4 py-1">
                                    <label for="product">{{__('Product Name')}}</label>
                                    <select name="product" class="choices form-select">
                                        <option value="">Select</option>
                                        @forelse ($product as $p)
                                            <option value="{{$p->id}}" {{ old('product')==$p->id?"selected":""}}>{{$p->product_name}}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-4 py-1">
                                    <label for="lcNo">{{__('LC Number')}}</label>
                                    <input type="text" class="form-control" value="{{ old('lot_no')}}" name="lot_no" placeholder="lc number">
                                </div>
                            </div>
                            <div class="row m-4">
                                <div class="col-6 d-flex justify-content-end">
                                    <button type="#" class="btn btn-sm btn-success me-1 mb-1 ps-5 pe-5">{{__('Show')}}</button>
                                    
                                </div>
                                <div class="col-6 d-flex justify-content-Start">
                                    <button type="#" class="btn pbtn btn-sm btn-warning me-1 mb-1 ps-5 pe-5">{{__('Reset')}}</button>
                                    
                                </div>
                            </div>
                            <div class="tbl_scroll">
                                <table class="table mb-5">
                                    <thead>
                                        <tr class="bg-primary text-white text-center">
                                            <th class="p-2">{{__('#SL')}}</th>
                                            <th class="p-2" >{{__('Category')}}</th>
                                            <th class="p-2" data-title="Description of Goods">{{__('Des.of Goods')}}</th>
                                            <th class="p-2" data-title="LC / LOT NO">{{__('Lc/Lot no')}}</th>
                                            <th class="p-2" data-title="TRADEMARKE/BRAND NAME">{{__('Trade/Brand')}}</th>
                                            <th class="p-2" data-title="TOTAL BAG">{{__('Total Bag')}}</th>
                                            <th class="p-2" data-title="TOTAL KG">{{__('Total Kg')}}</th>
                                            <th class="p-2" data-title="Warehouse Name">{{__('Werehouse')}}</th>
                                            <th class="p-2" data-title="Approximate Costing Per Kg (Pucrchases Costing)">{{__('Rate in kg')}}</th>
                                            <th class="p-2" data-title="Total Amount">{{__('Total Amount')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalBagQty = 0;
                                            $totalQty = 0;
                                            $totalAmount = 0;
                                        @endphp
    
                                        @forelse($stock as $s)
                                        <tr class="text-center">
                                            <th scope="row">{{ ++$loop->index }}</th>
                                            <td>
                                                @foreach (App\Models\Products\Category::where('id',$s->category_id)->where('company_id', $s->company_id)->get() as $cat)
                                                {{$cat->category}}
                                                @endforeach
                                            </td>
                                            <td><a href="{{route(currentUser().'.stock.individual',$s->product_id)}}">{{$s->product_name}}</a></td>
                                            <td><a href="{{route(currentUser().'.stock.individual_lot',$s->lot_no)}}">{{$s->lot_no}}</a></td>
                                            <td>{{$s->brand}}</td>
                                            <td>{{money_format($s->bagQty)}}</td>
                                            <td>{{money_format($s->qty)}}</td>
                                            <td>
                                                @php
                                                    $wh= App\Models\Settings\Warehouse::where('id',$s->warehouse_id)->where('company_id', $s->company_id)->first();
                                                @endphp
                                                {{$wh->name}}
                                            </td>
                                            <td>{{$s->unit_price}}</td>
                                            <td>{{money_format($s->qty*$s->avunitprice)}}</td>
                                        </tr>
                                        @php
                                            $totalBagQty += $s->bagQty;
                                            $totalQty += $s->qty;
                                            $totalAmount += ($s->qty * $s->avunitprice);
                                        @endphp
                                        @empty
                                        <tr>
                                            <th colspan="10" class="text-center">No data Found</th>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr class="text-center">
                                            <th colspan="5" class="p-2">Total</th>
                                            <th class="p-2">{{money_format($totalBagQty)}}</th>
                                            <th class="p-2">{{money_format($totalQty)}}</th>
                                            <th class="p-2" colspan="2"></th>
                                            <th class="p-2">{{money_format($totalAmount)}}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
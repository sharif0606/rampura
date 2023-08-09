@extends('layout.app')

@section('pageTitle',trans('Purchase Reports'))
@section('pageSubTitle',trans('Reports'))

@section('content')
  <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" method="get" action="">
                                <div class="row">
                                    <div class="col-md-2 mt-2">
                                        <label for="fdate" class="float-end"><h6>{{__('From Date')}}</h6></label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="date" id="fdate" class="form-control" value="{{isset($_GET['fdate'])?$_GET['fdate']:''}}" name="fdate">
                                    </div>


                                    <div class="col-md-2 mt-2">
                                        <label for="tdate" class="float-end"><h6>{{__('To Date')}}</h6></label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="date" id="tdate" class="form-control" value="{{isset($_GET['tdate'])?$_GET['tdate']:''}}" name="tdate">
                                    </div>


                                    <div class="col-md-2 mt-4">
                                        <label for="sup" class="float-end"><h6>{{__('Supplier')}}</h6></label>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        
                                        <select class="form-control form-select" name="sup" id="sup">
                                            <option value="">Select Supplier</option>
                                            @forelse($suppliers as $d)
                                                <option value="{{$d->id}}" {{isset($_GET['sup'])&& $_GET['sup']==$d->id?'selected':''}}> {{ $d->supplier_name}}</option>
                                            @empty
                                                <option value="">No Supplier found</option>
                                            @endforelse
                                        </select>
                                    </div>


                                </div>
                                <div class="row m-4">
                                    <div class="col-6 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-sm btn-success me-1 mb-1 ps-5 pe-5">{{__('Show')}}</button>
                                        
                                    </div>
                                    <div class="col-6 d-flex justify-content-Start">
                                        <a href="{{route(currentUser().'.preport')}}" class="btn pbtn btn-sm btn-warning me-1 mb-1 ps-5 pe-5">{{__('Reset')}}</a>
                                        
                                    </div>
                                </div>
                                <table class="table mb-5">
                                    <thead>
                                        <tr class="bg-primary text-white text-center">
                                            <th class="p-2">{{__('Purchase Date')}}</th>
                                            <th class="p-2">{{__('Supplier')}}</th>
                                            <th class="p-2">{{__('Reference Number')}}</th>
                                            <th class="p-2">{{__('Quantity')}}</th>
                                            <th class="p-2">{{__('Sub Amount')}}</th>
                                            <th class="p-2">{{__('Tax')}}</th>
                                            <th class="p-2">{{__('Discount')}}</th>
                                            <th class="p-2">{{__('Total Amount')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $d)
                                        <tr class="text-center">
                                            <td>{{$d->purchase_date}}</td>
                                            <td>{{$d->supplier?->supplier_name}}</td>
                                            <td>{{$d->reference_no}}</td>
                                            <td>{{$d->total_quantity}}</td>
                                            <td>{{$d->sub_amount}}</td>
                                            <td>{{$d->tax}}</td>
                                            <td>
                                                @if($d->discount)
                                                    @if($d->discount_type==2)
                                                        %{{$d->discount}}
                                                    @else
                                                        {{$d->discount}}
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{$d->grand_total}}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <th colspan="7" class="text-center">No data Found</th>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- // Basic multiple Column Form section end -->
</div>
@endsection
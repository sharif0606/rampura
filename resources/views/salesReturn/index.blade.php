@extends('layout.app')
@section('pageTitle',trans('Sales Return List'))
@section('pageSubTitle',trans('List'))

@section('content')
<style>
    @media (min-width: 1192px){
        .choices__inner{
            width: 438px !important;
        }
    }
</style>

<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                @if(Session::has('response'))
                    {!!Session::get('response')['message']!!}
                @endif
                <div class="row pb-1">
                    <div class="col-10">
                        <form action="" method="get">
                            <div class="row">
                                <div class="input-group input-group-sm d-flex justify-content-between">
                                    <div class="col-3 pe-1">
                                        <label for=""> Lot Number</label>
                                        <input type="text" class="form-control" name="lot_no" value="{{request('lot_no')}}" placeholder="Lot Number">
                                    </div>
                                    <div class="col-3 pe-1">
                                        <label for=""> Return Date</label>
                                        <input type="date" id="datepicker" class="form-control hasDatepicker" name="return_date" value="{{request('return_date')}}" placeholder="dd-mm-yyyy"/>
                                    </div>
                                    {{-- <div class="col-3">
                                        <label>Customer</label><br>
                                        <select class="form-control choices" name="nane">
                                            <option value="">Select Customer</option>
                                            @forelse($customers as $d)
                                                <option value="{{$d->id}}" {{ (request('nane') == $d->id ? 'selected' : '') }}> {{ $d->customer_name}}-[{{ $d->upazila?->name}}]</option>
                                            @empty
                                                <option value="">No Data Found</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-3 mt-1">
                                        <button type="submit" class="btn btn-info ms-2 mt-3">
                                            <i class="bi bi-search"></i>
                                        </button>
                                        <a class="btn btn-warning ms-2 mt-3" href="{{route(currentUser().'.salesReturn.index')}}" title="Clear"><i class="bi bi-arrow-clockwise"></i></a>
                                    </div> --}}
                                    <div class="col-6">
                                        <label for=""> Customer</label>
                                        <div class="d-flex">
                                            <select class="form-control choices" name="nane">
                                                <option value="">Select Customer</option>
                                                @forelse($customers as $d)
                                                    <option value="{{$d->id}}" {{ (request('nane') == $d->id ? 'selected' : '') }}> {{ $d->customer_name}}-[{{ $d->upazila?->name}}]</option>
                                                @empty
                                                    <option value="">No Data Found</option>
                                                @endforelse
                                            </select>
                        
                                            <div class="input-group-append" style="margin-left: 6px;">
                                                <button type="submit" class="btn btn-info">
                                                    <i class="bi bi-search"></i>
                                                </button>
                                            </div>
                                            <div class="input-group-append" style="margin-left: -2px;">
                                                <a class="btn btn-warning ms-2" href="{{route(currentUser().'.salesReturn.index')}}" title="Clear"><i class="bi bi-arrow-clockwise"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="col-2">
                        <a class=" float-end" href="{{route(currentUser().'.salesReturn.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                    </div>
                </div>
                <!-- table bordered -->
                <div class="table-responsive">
                    @php $st=array("","Sales","Return","Partial Return","Cancel"); @endphp
                    @php $pst=array("Unpaid","Paid","Parital Paid"); @endphp
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th scope="col">{{__('#SL')}}</th>
                                <th scope="col">{{__('Customer')}}</th>
                                <th scope="col">{{__('Return Date')}}</th>
                                <th scope="col">{{__('GrandTotal')}}</th>
                                <th scope="col">{{__('Branch')}}</th>
                                <th scope="col">{{__('Warehouse')}}</th>
                                <th scope="col">{{__('Lot Number')}}</th>
                                <th scope="col">{{__('Created By')}}</th>
                                <th scope="col">{{__('Updated By')}}</th>
                                <th class="white-space-nowrap">{{__('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $s)
                            <tr>
                                <th scope="row">{{ ++$loop->index }}</th>
                                <td>{{$s->customer?->customer_name}}</td>
                                <td>{{ date('d-M-y', strtotime($s->return_date)) }}</td>
                                <td>{{$s->grand_total}}</td>
                                <td>{{$s->branch?->name}}</td>
                                <td>{{$s->warehouse?->name}}</td>
                                <td>
                                    @if($s->sale_lot)
                                        @foreach ($s->sale_lot as $lot)
                                            {{$lot->lot_no}},
                                        @endforeach
                                    @endif
                                </td>
                                <td>{{$s->createdBy?->name}}</td>
                                <td>{{$s->updatedBy?->name}}</td>
                                <td class="white-space-nowrap">
                                    @if(currentUser() == 'admin' || currentUser() == 'owner')
                                        <a href="{{route(currentUser().'.salesReturn.edit',encryptor('encrypt',$s->id))}}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <th colspan="10" class="text-center">No Data Found</th>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="my-3">
                        {!! $sales->withQueryString()->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

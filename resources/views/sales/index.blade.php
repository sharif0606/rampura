@extends('layout.app')
@section('pageTitle',trans('Sales List'))
@section('pageSubTitle',trans('List'))

@section('content')
<style>
    @media (min-width: 1192px){
        .choices__inner{
            width: 450px !important;
        }
    }
</style>

<!-- Bordered table start -->
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">

                @if(Session::has('response'))
                    {!!Session::get('response')['message']!!}
                @endif
                {{-- <div>
                    <a class="float-end" href="{{route(currentUser().'.sales.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                </div> --}}
                <div class="row pb-1">
                    <div class="col-10">
                        <form action="" method="get">
                            <div class="row">
                                <div class="input-group input-group-sm d-flex justify-content-between">
                                    <div class="d-flex">
                                        <select class="form-control choices" name="nane">
                                            <option value="">Select Supplier</option>
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
                                            <a class="btn btn-warning ms-2" href="{{route(currentUser().'.sales.index')}}" title="Clear"><i class="bi bi-arrow-clockwise"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="col-2">
                        <a class=" float-end" href="{{route(currentUser().'.sales.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
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
                                <th scope="col">{{__('Date')}}</th>
                                <th scope="col">{{__('GrandTotal')}}</th>
                                <th scope="col">{{__('Branch')}}</th>
                                <th scope="col">{{__('Warehouse')}}</th>
                                <th scope="col">{{__('Status')}}</th>
                                {{-- <th scope="col">{{__('Payment Status')}}</th> --}}
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
                                <td>{{$s->sales_date}}</td>
                                <td>{{$s->grand_total}}</td>
                                <td>{{$s->branch?->name}}</td>
                                <td>{{$s->warehouse?->name}}</td>
                                <td>{{$st[$s->status]}}</td>
                                {{-- <td>{{$pst[$s->payment_status]}}</td> --}}
                                <td>{{$s->createdBy?->name}}</td>
                                <td>{{$s->updatedBy?->name}}</td>
                                <td class="white-space-nowrap">
                                    <a href="{{route(currentUser().'.sales.show',encryptor('encrypt',$s->id))}}">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>&nbsp;
                                    <a href="{{route(currentUser().'.sales.memo',encryptor('encrypt',$s->id))}}">
                                        <i class="bi bi-receipt"></i>
                                    </a>&nbsp;
                                    <a href="{{route(currentUser().'.sales.edit',encryptor('encrypt',$s->id))}}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    {{--<a href="javascript:void()" onclick="$('#form{{$s->id}}').submit()">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    <form id="form{{$s->id}}" action="{{route(currentUser().'.purchase.destroy',encryptor('encrypt',$pur->id))}}" method="post">
                                        @csrf
                                        @method('delete')
                                    </form>--}}
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
                        {!! $sales->links()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

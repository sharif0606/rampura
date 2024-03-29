@extends('layout.app')
@section('pageTitle',trans('Purchase Return List'))
@section('pageSubTitle',trans('List'))

@section('content')
<style>
    @media (min-width: 1192px){
        .choices__inner{
            width: 526px !important;
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
                {{-- <div>
                    <a class="float-end" href="{{route(currentUser().'.purchase.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                </div> --}}
                <div class="row pb-1">
                    <div class="col-10">
                        <form action="" method="get">
                            <div class="row">
                                <div class="input-group input-group-sm d-flex justify-content-between">
                                    <div class="col-5 pe-1">
                                        <input type="text" class="form-control" name="lot_no" value="{{request('lot_no')}}" placeholder="Lot Number">
                                    </div>
                                    <div class="col-7">
                                        <div class="d-flex">
                                            <select class="form-control choices" name="nane">
                                                <option value="">Select Supplier</option>
                                                @forelse($suppliers as $d)
                                                    <option value="{{$d->id}}" {{ (request('nane') == $d->id ? 'selected' : '') }}> {{ $d->supplier_name}}-[{{ $d->upazila?->name}}]</option>
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
                                                <a class="btn btn-warning ms-2" href="{{route(currentUser().'.purchaseReturn.index')}}" title="Clear"><i class="bi bi-arrow-clockwise"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="col-2">
                        <a class=" float-end" href="{{route(currentUser().'.purchaseReturn.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                    </div>
                </div>
                <!-- table bordered -->
                <div class="table-responsive">
                    @php $st=array("","Purchase","Return","Partial Return","Cancel"); @endphp
                    @php $pst=array("Unpaid","Paid","Parital Paid"); @endphp

                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th scope="col">{{__('#SL')}}</th>
                                <th scope="col">{{__('Supplier')}}</th>
                                <th scope="col">{{__('Date')}}</th>
                                <th scope="col">{{__('GrandTotal')}}</th>
                                <th scope="col">{{__('Warehouse')}}</th>
                                {{-- <th scope="col">{{__('Status')}}</th>
                                <th scope="col">{{__('Payment Status')}}</th> --}}
                                <th scope="col">{{__('Lot Number')}}</th>
                                <th scope="col">{{__('Created By')}}</th>
                                <th scope="col">{{__('Updated By')}}</th>
                                <th class="white-space-nowrap">{{__('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchases as $pur)
                            <tr>
                                <th scope="row">{{ ++$loop->index }}</th>
                                <td>{{$pur->supplier?->supplier_name}}</td>
                                <td>{{$pur->return_date}}</td>
                                <td>{{$pur->grand_total}}</td>
                                <td>{{$pur->warehouse?->name}}</td>
                                {{-- <td>{{$st[$pur->status]}}</td>
                                <td>{{$pst[$pur->payment_status]}}</td> --}}
                                <td>
                                    @if($pur->purchase_lot)
                                        @foreach ($pur->purchase_lot as $lot)
                                            {{$lot->lot_no}},
                                        @endforeach
                                    @endif
                                </td>
                                <td>{{$pur->createdBy?->name}}</td>
                                <td>{{$pur->updatedBy?->name}}</td>
                                <td class="white-space-nowrap">
                                    {{-- <a href="{{route(currentUser().'.purchase.show',encryptor('encrypt',$pur->id))}}">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>&nbsp; --}}
                                    @if(currentUser() == 'admin' || currentUser() == 'owner')
                                        <a href="{{route(currentUser().'.purchaseReturn.edit',encryptor('encrypt',$pur->id))}}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        {{-- <a class="text-danger" href="javascript:void()" onclick="$('#form{{$pur->id}}').submit()">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        <form id="form{{$pur->id}}" onsubmit="return confirm('Are you sure?')" action="{{route(currentUser().'.purchase.destroy',encryptor('encrypt',$pur->id))}}" method="post">
                                            @csrf
                                            @method('delete')
                                        </form> --}}
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
                        {!! $purchases->links()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
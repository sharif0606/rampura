@extends('layout.app')
@section('pageTitle',trans('Sales Voucher List'))
@section('pageSubTitle',trans('List'))

@section('content')
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
                                <div class="input-group input-group-sm d-flex justify-content-between" >
                                    <div class="d-flex" style="width: 150px;">
                                        <input type="text" name="name" value="{{isset($_GET['name'])?$_GET['name']:''}}" class="form-control float-start" placeholder="head name, head code" style="width: 200px;">
                                    
                                        <div class="input-group-append" style="margin-left: 6px;">
                                            <button type="submit" class="btn btn-info">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                        <div class="input-group-append" style="margin-left: -2px;">
                                            <a class="btn btn-warning ms-2" href="{{route(currentUser().'.sales_voucher.index')}}" title="Clear"><i class="bi bi-arrow-clockwise"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-2">
                        <a class=" float-end" href="{{route(currentUser().'.sales_voucher.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                    </div>
                </div>
                <!-- table bordered -->
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th scope="col">{{__('#SL')}}</th>
                                <th scope="col">{{__('Voucher No')}}</th>
                                <th scope="col">{{__('Lc No')}}</th>
                                <th scope="col">{{__('Customer')}}</th>
                                <th scope="col">{{__('Date')}}</th>
                                <th scope="col">{{__('Pay Name')}}</th>
                                <th scope="col">{{__('Purpose')}}</th>
                                <th scope="col">{{__('Amount')}}</th>
                                <th scope="col">{{__('Created By')}}</th>
                                <th scope="col">{{__('Updated By')}}</th>
                                <th class="white-space-nowrap">{{__('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $cr)
                            <tr>
                                <th scope="row">{{ ++$loop->index }}</th>
                                <td>{{$cr->voucher_no}}</td>
                                <td>{{$cr->lc_no}}</td>
                                <td>{{$cr->customer}}</td>
                                <td>{{date('d/m,Y',strtotime($cr->current_date))}}</td>
                                <td>{{$cr->pay_name}}</td>
                                <td>{{$cr->purpose}}</td>
                                <td>{{$cr->debit_sum}}</td>
                                <td>{{$cr->createdBy?->name}}</td>
                                <td>{{$cr->updatedBy?->name}}</td>
                                <td class="white-space-nowrap">
                                    @if(currentUser() == 'admin' || currentUser() == 'owner')
                                        <a href="{{route(currentUser().'.sales_voucher.edit',encryptor('encrypt',$cr->id))}}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <th colspan="11" class="text-center">No Data Found</th>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="my-3">
                        {!! $data->links()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
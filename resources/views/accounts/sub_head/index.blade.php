@extends('layout.app')
@section('pageTitle',trans('Sub Head List'))
@section('pageSubTitle',trans('List'))

@section('content')

<!-- Bordered table start -->
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
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
                                            <a class="btn btn-warning ms-2" href="{{route(currentUser().'.sub_head.index')}}" title="Clear"><i class="bi bi-arrow-clockwise"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-2">
                        <a class=" float-end" href="{{route(currentUser().'.sub_head.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                    </div>
                </div>
                <!-- table bordered -->
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th scope="col">{{__('#SL')}}</th>
                                <th scope="col">{{__('Master Head')}}</th>
                                <th scope="col">{{__('Sub Head')}}</th>
                                
                                <th scope="col">{{__('Opening Balance')}}</th>
                                {{-- <th class="white-space-nowrap">{{__('ACTION')}}</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $d)
                            <tr>
                            <th scope="row">{{ ++$loop->index }}</th>
                                <td>{{$d->master_account?->head_name}} - {{$d->master_account?->head_code}}</td>
                                <td>{{$d->head_name}} - {{$d->head_code}}</td>
                        
                                <td>{{$d->opening_balance}}</td>
                                @if(currentUser() == 'admin' || currentUser() == 'owner')
                                    {{-- <td class="white-space-nowrap">
                                        <a href="{{route(currentUser().'.sub_head.edit',encryptor('encrypt',$d->id))}}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="javascript:void()" onclick="$('#form{{$d->id}}').submit()">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        <form id="form{{$d->id}}" action="{{route(currentUser().'.sub_head.destroy',encryptor('encrypt',$d->id))}}" method="post">
                                            @csrf
                                            @method('delete')
                                            
                                        </form>
                                    </td> --}}
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <th colspan="5" class="text-center">No data Found</th>
                            </tr>
                            @endforelse
                        </tbody>
                        
                    </table>
                    <div class="d-flex justify-content-end my-3">
                        {!! $data->links()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Bordered table end -->


@endsection
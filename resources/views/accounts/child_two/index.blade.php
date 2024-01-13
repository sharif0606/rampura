@extends('layout.app')
@section('pageTitle',trans('Child Two List'))
@section('pageSubTitle',trans('List'))

@section('content')
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
                                            <a class="btn btn-warning ms-2" href="{{route(currentUser().'.child_two.index')}}" title="Clear"><i class="bi bi-arrow-clockwise"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-2">
                        <a class=" float-end" href="{{route(currentUser().'.child_two.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                    </div>
                </div>
                <!-- table bordered -->
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        {{-- <a class="float-end" href="{{route(currentUser().'.child_two.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a> --}}
                        <thead>
                            <tr>
                                <th scope="col">{{__('#SL')}}</th>
                                <th scope="col">{{__('Child One')}}</th>
                                <th scope="col">{{__('Child Two')}}</th>
                            
                                <th scope="col">{{__('Opening Balance')}}</th>
                                {{-- <th class="white-space-nowrap">{{__('ACTION')}}</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $d)
                            <tr>
                            <th scope="row">{{ ++$loop->index }}</th>
                                <td>{{$d->child_one?->head_name}} - {{$d->child_one?->head_code}}</td>
                                <td>{{$d->head_name}} - {{$d->head_code}}</td>
                            
                                <td>{{$d->opening_balance}}</td>
                                <td class="white-space-nowrap">
                                    @if(currentUser() == 'admin' || currentUser() == 'owner')
                                        <a href="{{route(currentUser().'.child_two.edit',encryptor('encrypt',$d->id))}}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        {{-- <a class="text-danger" href="javascript:void()" onclick="$('#form{{$d->id}}').submit()">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        <form id="form{{$d->id}}" onsubmit="return confirm('Are you sure?')" action="{{route(currentUser().'.child_two.destroy',encryptor('encrypt',$d->id))}}" method="post">
                                            @csrf
                                            @method('delete')
                                            
                                        </form> --}}
                                    @endif
                                </td>
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
@endsection
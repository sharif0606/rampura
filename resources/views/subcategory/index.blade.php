@extends('layout.app')
@section('pageTitle',trans('Subcategory List'))
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
                                        <input type="text" name="name" value="{{isset($_GET['name'])?$_GET['name']:''}}" class="form-control float-start" placeholder="name,hs-code,duty" style="width: 200px;">
                                    
                                        <div class="input-group-append" style="margin-left: 6px;">
                                            <button type="submit" class="btn btn-info">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                        <div class="input-group-append" style="margin-left: -2px;">
                                            <a class="btn btn-warning ms-2" href="{{route(currentUser().'.subcategory.index')}}" title="Clear"><i class="bi bi-arrow-clockwise"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-2">
                        <a class=" float-end" href="{{route(currentUser().'.subcategory.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">{{__('#SL')}}</th>
                                <th scope="col">{{__('Category')}}</th>
                                <th scope="col">{{__('Name')}}</th>
                                <th scope="col">{{__('HS-Code')}}</th>
                                <th scope="col">{{__('Custom Duty')}}</th>
                                <th class="white-space-nowrap">{{__('ACTION')}}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($subcategories as $sub)
                            <tr class="text-center">
                                <th scope="row">{{ ++$loop->index }}</th>
                                <td>{{$sub->category?->category}}</td>
                                <td>{{$sub->name}}</td>
                                <td>{{$sub->hs_code}}</td>
                                <td>{{$sub->custom_duty}}</td>
                                <td class="white-space-nowrap">
                                    @if(currentUser() == 'admin' || currentUser() == 'owner')
                                        <a href="{{route(currentUser().'.subcategory.edit',encryptor('encrypt',$sub->id))}}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        
                                        <form id="form{{$sub->id}}" action="{{route(currentUser().'.subcategory.destroy',encryptor('encrypt',$sub->id))}}" method="post">
                                            @csrf
                                            @method('delete')
                                            
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <th colspan="6" class="text-center">No Data Found</th>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
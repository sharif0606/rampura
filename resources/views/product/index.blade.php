@extends('layout.app')
@section('pageTitle',trans('Product List'))
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
                                        <input type="text" name="name" value="{{isset($_GET['name'])?$_GET['name']:''}}" class="form-control float-start" placeholder="Search by product" style="width: 200px;">
                                    
                                        <div class="input-group-append" style="margin-left: 6px;">
                                            <button type="submit" class="btn btn-info">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                        <div class="input-group-append" style="margin-left: -2px;">
                                            <a class="btn btn-warning ms-2" href="{{route(currentUser().'.product.index')}}" title="Clear"><i class="bi bi-arrow-clockwise"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-2">
                        <a class="float-end" href="{{route(currentUser().'.product.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th scope="col">{{__('#SL')}}</th>
                                {{-- <th scope="col">{{__('Bar Code')}}</th> --}}
                                {{-- <th scope="col">{{__('Brand')}}</th> --}}
                                <th scope="col">{{__('Category')}}</th>
                                <th scope="col">{{__('Sub Category')}}</th>
                                {{-- <th scope="col">{{__('Child Category')}}</th> --}}
                                <th scope="col">{{__('Name')}}</th>
                                {{-- <th scope="col">{{__('Units')}}</th> --}}
                                {{-- <th scope="col">{{__('Sales Price')}}</th> --}}
                                {{-- <th scope="col">{{__('Image')}}</th> --}}
                                {{-- <th scope="col">{{__('Status')}}</th> --}}
                                <th class="white-space-nowrap">{{__('ACTION')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $p)
                            <tr>
                            <th scope="row">{{ ++$loop->index }}</th>
                                {{-- <td>{{$p->bar_code}}</td> --}}
                                {{-- <td>{{$p->brand?->name}}</td> --}}
                                <td>{{$p->category?->category}}</td>
                                <td>{{$p->subcategory?->name}}</td>
                                {{-- <td>{{$p->childcategory?->name}}</td> --}}
                                <td>{{$p->product_name}}</td>
                                {{-- <td>{{$p->unit?->name}}</td> --}}
                                {{-- <td>{{$p->price}}</td> --}}
                                    {{-- <td><img width="80px" height="40px" class="float-first" src="{{asset('images/product/'.company()['company_id'].'/'.$p->image)}}" alt=""></td> --}}
                                {{-- <td>@if($p->status == 1) Active @else Inactive @endif</td> --}}
                                <!-- or <td>{{ $p->status == 1?"Active":"Inactive" }}</td>-->
                                <td class="white-space-nowrap">
                                    @if(currentUser() == 'admin' || currentUser() == 'owner')
                                        <a href="{{route(currentUser().'.product.edit',encryptor('encrypt',$p->id))}}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        {{-- <a class="text-danger" href="javascript:void()" onclick="$('#form{{$p->id}}').submit()">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        <form id="form{{$p->id}}" action="{{route(currentUser().'.product.destroy',encryptor('encrypt',$p->id))}}" method="post">
                                            @csrf
                                            @method('delete')
                                            
                                        </form> --}}
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <th colspan="7" class="text-center">No Pruduct Found</th>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="my-3">
                        {!! $products->links()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
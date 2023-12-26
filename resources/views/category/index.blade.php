@extends('layout.app')

@section('pageTitle',trans('Category List'))
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
                                            <input type="text" name="name" value="{{isset($_GET['name'])?$_GET['name']:''}}" class="form-control float-start" placeholder="Search by name" style="width: 200px;">
                                        
                                            <div class="input-group-append" style="margin-left: 6px;">
                                                <button type="submit" class="btn btn-info">
                                                    <i class="bi bi-search"></i>
                                                </button>
                                            </div>
                                            <div class="input-group-append" style="margin-left: -2px;">
                                                <a class="btn btn-warning ms-2" href="{{route(currentUser().'.category.index')}}" title="Clear"><i class="bi bi-arrow-clockwise"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-2">
                            <a class=" float-end" href="{{route(currentUser().'.category.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">{{__('#SL')}}</th>
                                    <th scope="col">{{__('Name')}}</th>
                                    {{-- <th scope="col">{{__('Image')}}</th> --}}
                                    <th class="white-space-nowrap">{{__('ACTION')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $cat)
                                <tr>
                                <th scope="row">{{ ++$loop->index }}</th>
                                    <td>{{$cat->category}} ({{$cat->products->count()}})</td>
                                    {{-- <td><img width="80px" height="40px" class="float-first" src="{{asset('images/category/'.company()['company_id'].'/'.$cat->image)}}" alt=""></td> --}}
                                    <td class="white-space-nowrap">
                                        @if(currentUser() == 'admin' || currentUser() == 'owner')
                                            <a href="{{route(currentUser().'.category.edit',encryptor('encrypt',$cat->id))}}">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <!-- <a href="javascript:void()" onclick="$('#form{{$cat->id}}').submit()">
                                                <i class="bi bi-trash"></i>
                                            </a> -->
                                            <form id="form{{$cat->id}}" action="{{route(currentUser().'.category.destroy',encryptor('encrypt',$cat->id))}}" method="post">
                                                @csrf
                                                @method('delete')
                                                
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <th colspan="3" class="text-center">No Data Found</th>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="my-3">
                            {!! $categories->links()!!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


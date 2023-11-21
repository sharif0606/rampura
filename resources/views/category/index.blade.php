@extends('layout.app')

@section('pageTitle',trans('Category List'))
@section('pageSubTitle',trans('List'))

@section('content')
    <section class="section">
        <div class="row" id="table-bordered">
            <div class="col-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                        <a class="float-end" href="{{route(currentUser().'.category.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
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
                                    <th colspan="4" class="text-center">No Category Found</th>
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


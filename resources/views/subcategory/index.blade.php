@extends('layout.app')
@section('pageTitle',trans('Subcategory List'))
@section('pageSubTitle',trans('List'))

@section('content')
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <a class="float-end" href="{{route(currentUser().'.subcategory.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                        <thead>
                            <tr>
                                <th scope="col">{{__('#SL')}}</th>
                                <th scope="col">{{__('Category')}}</th>
                                <th scope="col">{{__('Name')}}</th>
                                <th class="white-space-nowrap">{{__('ACTION')}}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($subcategories as $sub)
                            <tr>
                            <th scope="row">{{ ++$loop->index }}</th>

                                <td>{{$sub->category?->category}}</td>
                                <td>{{$sub->name}}</td>
                                <td class="white-space-nowrap">
                                    <a href="{{route(currentUser().'.subcategory.edit',encryptor('encrypt',$sub->id))}}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    
                                    <form id="form{{$sub->id}}" action="{{route(currentUser().'.subcategory.destroy',encryptor('encrypt',$sub->id))}}" method="post">
                                        @csrf
                                        @method('delete')
                                        
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <th colspan="4" class="text-center">No Data Found</th>
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
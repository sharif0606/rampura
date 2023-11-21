@extends('layout.app')

@section('pageTitle',trans('Warehouse List'))
@section('pageSubTitle',trans('List'))

@section('content')
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                    <a class="float-end" href="{{route(currentUser().'.warehouse.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                        <thead>
                            <tr>
                                <th scope="col">{{__('#SL')}}</th>
                                {{-- <th scope="col">{{__('Branch')}}</th> --}}
                                <th scope="col">{{__('Name')}}</th>
                                <th scope="col">{{__('Contact')}}</th>
                                <th scope="col">{{__('Address')}}</th>
                                <th class="white-space-nowrap">{{__('ACTION')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($warehouses as $war)
                            <tr>
                            <th scope="row">{{ ++$loop->index }}</th>
                                {{-- <td>{{$war->branch->name}}</td>  --}}
                                <td>{{$war->name}}</td>
                                <td>{{$war->contact}}</td>
                                <td>{{$war->address}}</td>
                                
                                <td class="white-space-nowrap">
                                    @if(currentUser() == 'admin' || currentUser() == 'owner')
                                        <a href="{{route(currentUser().'.warehouse.edit',encryptor('encrypt',$war->id))}}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        
                                        {{-- <form id="form{{$war->id}}" action="{{route(currentUser().'.warehouse.destroy',encryptor('encrypt',$war->id))}}" method="post">
                                            @csrf
                                            @method('delete')
                                        </form> --}}
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <th colspan="5" class="text-center">No Data Found</th>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="my-3">
                        {!! $warehouses->links()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


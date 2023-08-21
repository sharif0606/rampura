@extends('layout.app')
@section('pageTitle',trans('Child One List'))
@section('pageSubTitle',trans('List'))

@section('content')
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                    <!-- table bordered -->
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <a class="float-end" href="{{route(currentUser().'.child_one.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                            <thead>
                                <tr>
                                    <th scope="col">{{__('#SL')}}</th>
                                    <th scope="col">{{__('Sub Head')}}</th>
                                    <th scope="col">{{__('Child One')}}</th>
                                   
                                    <th scope="col">{{__('Opening Balance')}}</th>
                                    <th class="white-space-nowrap">{{__('ACTION')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $d)
                                <tr>
                                <th scope="row">{{ ++$loop->index }}</th>
                                    <td>{{$d->sub_head?->head_name}} - {{$d->sub_head?->head_code}}</td>
                                    <td>{{$d->head_name}} - {{$d->head_code}}</td>
                                   
                                    <td>{{$d->opening_balance}}</td>
                                    <td class="white-space-nowrap">
                                        <a href="{{route(currentUser().'.child_one.edit',encryptor('encrypt',$d->id))}}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a class="text-danger" href="javascript:void()" onclick="$('#form{{$d->id}}').submit()">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        <form id="form{{$d->id}}" action="{{route(currentUser().'.child_one.destroy',encryptor('encrypt',$d->id))}}" method="post">
                                            @csrf
                                            @method('delete')
                                            
                                        </form>
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
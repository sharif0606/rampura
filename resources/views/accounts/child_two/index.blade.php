@extends('layout.app')
@section('pageTitle',trans('Child Two List'))
@section('pageSubTitle',trans('List'))

@section('content')
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                    <!-- table bordered -->
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <a class="float-end" href="{{route(currentUser().'.child_two.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                            <thead>
                                <tr>
                                    <th scope="col">{{__('#SL')}}</th>
                                    <th scope="col">{{__('Child One')}}</th>
                                    <th scope="col">{{__('Child Two')}}</th>
                                
                                    <th scope="col">{{__('Opening Balance')}}</th>
                                    <th class="white-space-nowrap">{{__('ACTION')}}</th>
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
                                        <a href="{{route(currentUser().'.child_two.edit',encryptor('encrypt',$d->id))}}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="javascript:void()" onclick="$('#form{{$d->id}}').submit()">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        <form id="form{{$d->id}}" action="{{route(currentUser().'.child_two.destroy',encryptor('encrypt',$d->id))}}" method="post">
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
                    </div>
                </div>
            </div>
    </div>
</section>
@endsection
@extends('layout.app')
@section('pageTitle',trans('Currency List'))
@section('pageSubTitle',trans('List'))

@section('content')

<!-- Bordered table start -->
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                    <!-- table bordered -->
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <a class="float-end" href="{{route(currentUser().'.currency.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                            <thead>
                                <tr>
                                    <th scope="col">{{__('#SL')}}</th>
                                    <th scope="col">{{__('Currency')}}</th>
                                    <th scope="col">{{__('Symbol')}}</th>
                                    <th scope="col">{{__('Port')}}</th>
                                    <th scope="col">{{__('Rate')}}</th>
                                    <th class="white-space-nowrap">{{__('ACTION')}}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($currency as $p)
                                <tr>
                                <th scope="row">{{ ++$loop->index }}</th>
                                    <td>{{$p->currency_name}}</td>
                                    <td>{{$p->currency_symbol}}</td>
                                    <td>{{$p->currency_port}}</td>
                                    <td>{{$p->currency_rate}}</td>
                                    
                                    <td class="white-space-nowrap">
                                        <a href="{{route(currentUser().'.currency.edit',encryptor('encrypt',$p->id))}}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="javascript:void()" onclick="$('#form{{$p->id}}').submit()">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        <form id="form{{$p->id}}" action="{{route(currentUser().'.currency.destroy',encryptor('encrypt',$p->id))}}" method="post">
                                            @csrf
                                            @method('delete')
                                            
                                        </form>
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
<!-- Bordered table end -->


@endsection
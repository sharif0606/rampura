@extends('layout.app')
@section('pageTitle',trans('Transfer List'))
@section('pageSubTitle',trans('List'))

@section('content')

<!-- Bordered table start -->
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                
                @if(Session::has('response'))
                    {!!Session::get('response')['message']!!}
                @endif
                <div>
                    <a class="float-end" href="{{route(currentUser().'.transfer.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                </div>
                <!-- table bordered -->
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th scope="col">{{__('#SL')}}</th>
                                <th scope="col">{{__('From Branch')}}</th>
                                <th scope="col">{{__('From Warehouse')}}</th>
                                <th scope="col">{{__('To Warehouse')}}</th>
                                <th scope="col">{{__('Trasfer Date')}}</th>
                                <th scope="col">{{__('Quantity')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transfers as $s)
                            <tr>
                                <th scope="row">{{ ++$loop->index }}</th>
                                <td>{{$s->branch?->name}}</td>
                                <td>{{$s->warehousef?->name}}</td>
                                <td>{{$s->warehouset?->name}}</td>
                                <td>{{$s->transfer_date}}</td>
                                <td>{{$s->quantity}}</td>
                            </tr>
                            @empty
                            <tr>
                                <th colspan="8" class="text-center">No Data Found</th>
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
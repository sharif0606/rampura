@extends('layout.app')

@section('pageTitle',trans('Payment'))
@section('pageSubTitle',trans('payment'))

@section('content')
<style>
    @media screen and (max-width: 800px) {
  .tbl_scroll {
    overflow: scroll;
  }
}
</style>
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="text-center"><h4>Purchase Payment</h4></div>
                        <div class="card-body">
                            <form class="form" method="get" action="">
                                @csrf
                                <div class="tbl_scroll">
                                    <table class="table mb-5">
                                        <thead>
                                            <tr class="bg-primary text-white text-center">
                                                <th class="p-2">{{__('#SL')}}</th>
                                                <th class="p-2">{{__('Date')}}</th>
                                                <th class="p-2">{{__('Type of Payment')}}</th>
                                                <th class="p-2">{{__('Amount')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($payment as $p)
                                                <tr class="text-center">
                                                    <th scope="row">{{ ++$loop->index }}</th>
                                                    <td>{{ date('d-M-Y', strtotime($p->created_at)) }}</td>
                                                    <td>{{$p->p_head_name}}</td>
                                                    <td>{{$p->amount}}</td>
                                                </tr>
                                            @empty
                                                <tr  class="text-center">
                                                    <td colspan="4">No Data Found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
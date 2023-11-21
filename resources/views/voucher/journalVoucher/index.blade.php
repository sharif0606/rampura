@extends('layout.app')
@section('pageTitle',trans('Journal Voucher List'))
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
                    <a class="float-end" href="{{route(currentUser().'.journal.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                </div>
                <!-- table bordered -->
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th scope="col">{{__('#SL')}}</th>
                                <th scope="col">{{__('Voucher No')}}</th>
                                <th scope="col">{{__('LC No')}}</th>
                                <th scope="col">{{__('Date')}}</th>
                                <th scope="col">{{__('Pay Name')}}</th>
                                <th scope="col">{{__('Purpose')}}</th>
                                <th scope="col">{{__('Amount')}}</th>
                                <th scope="col">{{__('Created By')}}</th>
                                <th scope="col">{{__('Updated By')}}</th>
                                <th class="white-space-nowrap">{{__('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($journalVoucher as $cr)
                            <tr>
                                <th scope="row">{{ ++$loop->index }}</th>
                                <td>{{$cr->voucher_no}}</td>
                                <td>
                                    @foreach($cr->generalLedgers as $key => $generalLedger)
                                        {{$generalLedger->lc_no}}
                                        @if($key === 0)
                                            @break
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{date('d/m,Y',strtotime($cr->current_date))}}</td>
                                <td>{{$cr->pay_name}}</td>
                                <td>{{$cr->purpose}}</td>
                                <td>{{$cr->debit_sum}}</td>
                                <td>{{$cr->createdBy?->name}}</td>
                                <td>{{$cr->updatedBy?->name}}</td>
                                <td class="white-space-nowrap">
                                    <a href="{{route(currentUser().'.journal.show',encryptor('encrypt',$cr->id))}}">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(currentUser() == 'admin' || currentUser() == 'owner')
                                        <a href="{{route(currentUser().'.journal.edit',encryptor('encrypt',$cr->id))}}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <th colspan="10" class="text-center">No Data Found</th>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="my-3">
                        {!! $journalVoucher->links()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Bordered table end -->


@endsection
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
                        <div class="text-center"><h4>Sales Payment</h4></div>
                        <div class="card-body">
                            <form class="form" method="get" action="">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <label for="fdate"><h6>{{__('From Date')}}</h6></label>
                                        <input type="date" class="form-control" value="{{isset($_GET['fdate'])?$_GET['fdate']:''}}" name="fdate">
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <label for="tdate"><h6>{{__('To Date')}}</h6></label>
                                        <input type="date" class="form-control" value="{{isset($_GET['tdate'])?$_GET['tdate']:''}}" name="tdate">
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <label for="head_name"><h6>{{__('Pay Method')}}</h6></label>
                                        <select name="head_name[]" class="choices form-select multiple-remove" multiple>
                                            <option value="">Select</option>
                                            @if($paymethod)
                                                @foreach($paymethod as $d)
                                                    <option value="{{$d['id']}}">{{$d['head_name']}}-{{$d['head_code']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <label for="lcNo"><h6>{{__('LC Number')}}</h6></label>
                                        <input type="text" class="form-control" value="{{isset($_GET['lc_no'])?$_GET['lc_no']:''}}" name="lc_no" placeholder="lc number">
                                    </div>
                                </div>
                                
                                <div class="row m-1">
                                    <div class="col-6 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-sm btn-success me-1 mb-1 ps-5 pe-5">{{__('Show')}}</button>
                                    </div>
                                    <div class="col-6 d-flex justify-content-Start">
                                        <a href="{{route(currentUser().'.sales_pending_pay')}}" class="btn pbtn btn-sm btn-warning me-1 mb-1 ps-5 pe-5">{{__('Reset')}}</a>
                                    </div>
                                </div>
                            </form>
                            <form class="form" method="get" action="">
                                <div class="tbl_scroll">
                                    <button type="submit" class="btn btn-primary mb-1 float-end">Create Voucher</button>
                                    <table class="table mb-5">
                                        <thead>
                                            <tr class="bg-primary text-white text-center">
                                                <th class="p-2">
                                                    <input type="checkbox" class="exp_id_all">
                                                </th>
                                                <th class="p-2">{{__('LC/Lot No')}}</th>
                                                <th class="p-2">{{__('Date')}}</th>
                                                <th class="p-2">{{__('Type of Expense')}}</th>
                                                <th class="p-2">{{__('Amount')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($payment as $p)
                                                <tr class="text-center">
                                                    <td>
                                                        <input type="checkbox" name="exp_id[]" class="exp_id" value="{{$p->id}}">
                                                    </td>
                                                    <td>{{$p->lc_no}}</td>
                                                    <td>{{ date('d-M-Y', strtotime($p->created_at)) }}</td>
                                                    <td>{{$p->p_head_name}}</td>
                                                    <td>{{$p->amount}}</td>
                                                </tr>
                                            @empty
                                                <tr  class="text-center">
                                                    <td colspan="5">No Data Found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="my-3">
                                        {!! $payment->links()!!}
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
<script>
    $('.exp_id_all').click(function(){
        if($(this).is(":checked"))
            $('.exp_id').attr('checked',true);
        else
            $('.exp_id').removeAttr('checked');
    });
    
</script>
@endpush
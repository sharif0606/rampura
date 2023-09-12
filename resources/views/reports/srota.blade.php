@extends('layout.app')

@section('pageTitle',trans('Srota'))
@section('pageSubTitle',trans('srota'))

@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="text-center"><h4>FIND SROTA LOT WISE</h4></div>
                    <div class="card-body">
                        <form class="form" method="get" action="{{route(currentUser().'.srota_view')}}">
                            <div class="row">
                                <div class="col-lg-8 offset-2">
                                    <label for="">Lot Number</label>
                                    <input type="text" class="form-control" value="{{ old('lot')}}" name="lot" placeholder="Enter lot number">
                                </div>
                            </div>
                            <div class="row m-4">
                                <div class="col-6 d-flex justify-content-end">
                                    <button type="#" class="btn btn-sm btn-success me-1 mb-1 ps-5 pe-5">{{__('Show')}}</button>
                                </div>
                                <div class="col-6 d-flex justify-content-Start">
                                    <button type="#" class="btn pbtn btn-sm btn-warning me-1 mb-1 ps-5 pe-5">{{__('Close')}}</button>
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
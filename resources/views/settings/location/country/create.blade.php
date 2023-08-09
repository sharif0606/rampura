@extends('layout.app')

@section('pageTitle',trans('Create Country'))
@section('pageSubTitle',trans('Create'))

@section('content')
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" method="post" action="{{route(currentUser().'.country.store')}}">
                                @csrf
                                <div class="row">
                                    
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="countryName">{{__('Country Name')}}<span class="text-danger">*</span></label>
                                            <input type="text" id="countryName" class="form-control" value="{{ old('countryName')}}" name="countryName">
                                            @if($errors->has('countryName'))
                                                <span class="text-danger"> {{ $errors->first('countryName') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="countryCode">{{__('Country Code')}}<span class="text-danger">*</span></label>
                                            <input type="text" id="countryCode" class="form-control" value="{{ old('countryCode')}}" name="countryCode">
                                            @if($errors->has('countryCode'))
                                                <span class="text-danger"> {{ $errors->first('countryCode') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="countryBn">{{__('Country Bangla')}}</label>
                                            <input type="text" id="countryBn" class="form-control" value="{{ old('countryBn')}}" name="countryBn">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Save</button>
                                        
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

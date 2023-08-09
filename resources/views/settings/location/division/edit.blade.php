@extends('layout.app')

@section('pageTitle',trans('Update Division'))
@section('pageSubTitle',trans('Update'))

@section('content')
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" method="post" action="{{route(currentUser().'.division.update',encryptor('encrypt',$division->id))}}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="uptoken" value="{{encryptor('encrypt',$division->id)}}">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="country_id">{{__('Country')}}<span class="text-danger">*</span></label>
                                            <select class="form-control form-select" name="country" id="country">
                                                <option value="">Select Country</option>
                                                @forelse($countries as $d)
                                                    <option value="{{$d->id}}" {{ old('country',$division->country_id)==$d->id?"selected":""}}> {{ $d->name}}</option>
                                                @empty
                                                    <option value="">No Country found</option>
                                                @endforelse
                                            </select>
                                            @if($errors->has('country'))
                                            <span class="text-danger"> {{ $errors->first('country') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="divisionName">{{__('Division Name')}}<span class="text-danger">*</span></label>
                                            <input type="text" id="divisionName" class="form-control" value="{{ old('divisionName',$division->name)}}" name="divisionName">
                                            @if($errors->has('divisionName'))
                                                <span class="text-danger"> {{ $errors->first('divisionName') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="divisionBn">{{__('Division Bangla')}}</label>
                                            <input type="text" id="divisionBn" class="form-control" value="{{ old('divisionBn',$division->name_bn)}}" name="divisionBn">
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

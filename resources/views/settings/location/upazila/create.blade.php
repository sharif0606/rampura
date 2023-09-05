@extends('layout.app')

@section('pageTitle',trans('Create Area'))
@section('pageSubTitle',trans('Create'))

@section('content')
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" method="post" action="{{route(currentUser().'.upazila.store')}}">
                                @csrf
                                <div class="row">
                                    
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="district_id">District<span class="text-danger">*</span></label>
                                            <select class="form-control form-select" name="district_id" id="district_id">
                                                <option value="">Select District</option>
                                                @forelse($districts as $d)
                                                    <option value="{{$d->id}}" {{ old('district_id')==$d->id?"selected":""}}> {{ $d->name}}</option>
                                                @empty
                                                    <option value="">No data found</option>
                                                @endforelse
                                            </select>
                                            @if($errors->has('district_id'))
                                                <span class="text-danger"> {{ $errors->first('district_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="upazilaName">Area<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" value="{{ old('upazilaName')}}" name="upazilaName">
                                            @if($errors->has('upazilaName'))
                                                <span class="text-danger"> {{ $errors->first('upazilaName') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="upazilaBn">Area Bangla</label>
                                            <input type="text" id="upazilaBn" class="form-control" value="{{ old('upazilaBn')}}" name="upazilaBn">
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

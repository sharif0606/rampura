@extends('layout.app')

@section('pageTitle',trans('Create Thana'))
@section('pageSubTitle',trans('Create'))

@section('content')
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" method="post" action="{{route(currentUser().'.thana.store')}}">
                                @csrf
                                <div class="row">
                                    
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="upazila_id">Upazila<span class="text-danger">*</span></label>
                                            <select class="form-control form-select" name="upazila_id" id="upazila_id">
                                                <option value="">Select Upazila</option>
                                                @forelse($upazilas as $d)
                                                    <option value="{{$d->id}}" {{ old('upazila_id')==$d->id?"selected":""}}> {{ $d->name}}</option>
                                                @empty
                                                    <option value="">No Upazila found</option>
                                                @endforelse
                                            </select>
                                            @if($errors->has('upazila_id'))
                                                <span class="text-danger"> {{ $errors->first('upazila_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="thanaName">Thana Name<span class="text-danger">*</span></label>
                                            <input type="text" id="thanaName" class="form-control" value="{{ old('thanaName')}}" name="thanaName">
                                            @if($errors->has('thanaName'))
                                                <span class="text-danger"> {{ $errors->first('thanaName') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="thanaBn">Thana Bangla</label>
                                            <input type="text" id="thanaBn" class="form-control" value="{{ old('thanaBn')}}" name="thanaBn">
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

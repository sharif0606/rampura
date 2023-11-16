@extends('layout.app')

@section('pageTitle',trans('Create Users'))
@section('pageSubTitle',trans('Create'))

@section('content')
  <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" method="post" enctype="multipart/form-data" action="{{route(currentUser().'.users.store')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="role_id">{{__('Role')}}<span class="text-danger">*</span></label>
                                            <select class="form-control" name="role_id" id="role_id">
                                                <option value="">Select Role</option>
                                                @forelse($roles as $r)
                                                    <option value="{{$r->id}}" {{ old('role_id')==$r->id?"selected":""}}> {{ $r->type}}</option>
                                                @empty
                                                    <option value="">No Role found</option>
                                                @endforelse
                                            </select>
                                            @if($errors->has('role_id'))
                                                <span class="text-danger"> {{ $errors->first('role_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="userName">{{__('Name')}}<span class="text-danger">*</span></label>
                                            <input type="text" id="userName" class="form-control" value="{{ old('userName')}}" name="userName">
                                            @if($errors->has('userName'))
                                                <span class="text-danger"> {{ $errors->first('userName') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="userEmail">{{__('Email')}}</label>
                                            <input type="email" id="userEmail" class="form-control" value="{{ old('userEmail')}}" name="userEmail" placeholder="example@gmail.com">
                                            @if($errors->has('userEmail'))
                                                <span class="text-danger"> {{ $errors->first('userEmail') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="contactNumber">{{__('Contact Number')}}<span class="text-danger">*</span></label>
                                            <input type="text" id="contactNumber" class="form-control" value="{{ old('contactNumber')}}" name="contactNumber">
                                            @if($errors->has('contactNumber'))
                                                <span class="text-danger"> {{ $errors->first('contactNumber') }}</span>
                                            @endif
                                        </div>
                                    </div> 
                                    <div class="col-lg-4 col-md-6 col-sm-12 d-none">
                                        <div class="form-group">
                                            <label for="branch_id">{{__('Branch')}}</label>
                                            <select class="form-control" name="branch_id" id="branch_id">
                                                @forelse($branches as $r)
                                                    <option value="{{$r->id}}" {{ old('branch_id')==$r->id?"selected":""}}> {{ $r->name}} - {{ $r->contact_no}}</option>
                                                @empty
                                                    <option value="">No Branch found</option>
                                                @endforelse
                                            </select>
                                            @if($errors->has('branch_id'))
                                                <span class="text-danger"> {{ $errors->first('branch_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="password">{{__('Password')}}<span class="text-danger">*</span></label>
                                            <input type="password" id="password" class="form-control" name="password">
                                                @if($errors->has('password'))
                                                    <span class="text-danger"> {{ $errors->first('password') }}</span>
                                                @endif
                                        </div>
                                    </div>
                                    {{-- <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="image">{{__('Image')}}</label>
                                            <input type="file" id="image" class="form-control"
                                                placeholder="Image" name="image">
                                                @if($errors->has('image'))
                                                    <span class="text-danger"> {{ $errors->first('image') }}</span>
                                                @endif
                                        </div>
                                    </div> --}}
                                </div>
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">{{__('Save')}}</button>
                                        
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

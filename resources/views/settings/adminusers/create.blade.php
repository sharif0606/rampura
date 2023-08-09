@extends('layout.app')

@section('pageTitle',trans('Create Users'))
@section('pageSubTitle',trans('Create'))

@section('content')
  <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    @if(Session::has('response'))
                        {!!Session::get('response')['message']!!}
                    @endif
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" method="post" enctype="multipart/form-data" action="{{route(currentUser().'.admin.store')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="userName">{{__('Name')}}<span class="text-danger">*</span></label>
                                            <input type="text" id="userName" class="form-control" value="{{ old('userName')}}" name="userName">
                                            @if($errors->has('userName'))
                                                <span class="text-danger"> {{ $errors->first('userName') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="userEmail">{{__('Email')}}</label>
                                            <input type="text" id="userEmail" class="form-control" value="{{ old('userEmail')}}" name="userEmail">
                                            @if($errors->has('userEmail'))
                                                <span class="text-danger"> {{ $errors->first('userEmail') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="contactNumber">{{__('Contact Number')}}<span class="text-danger">*</span></label>
                                            <input type="text" id="contactNumber" class="form-control" value="{{ old('contactNumber')}}" name="contactNumber">
                                            @if($errors->has('contactNumber'))
                                                <span class="text-danger"> {{ $errors->first('contactNumber') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                 
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="password">{{__('Password')}}<span class="text-danger">*</span></label>
                                            <input type="password" id="password" class="form-control" name="password">
                                                @if($errors->has('password'))
                                                    <span class="text-danger"> {{ $errors->first('password') }}</span>
                                                @endif
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6 col-12">
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

@push('scripts')
<script>
    function showBranch(e){
        $('#branch_id .branchs').hide();
        $('#branch_id .branchs'+e.value).show();
    }
    function hideCompany(e){
        if(e=="1" || e=="2"){
            $('.company_row').hide();
        }else{
            $('.company_row').show();
        }
    }
    if($('#role_id').val()=="1" || $('#role_id').val()=="2"){
        $('.company_row').hide();
    }else{
        $('.company_row').show();
    }
</script>
@endpush
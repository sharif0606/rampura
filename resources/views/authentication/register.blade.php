@extends('layout.auth')

@section('content')
<!-- <h1 class="auth-title">Sign Up</h1> -->
@if(Session::has('response'))
    {!!Session::get('response')['message']!!}
@endif
{{-- <form action="{{route('register.store')}}" method="post">
    @csrf
    <div class="form-group position-relative has-icon-left mb-3">
        <input name="FullName" value="{{old('FullName')}}" type="text" class="form-control form-control-xl" placeholder="Full Name">
        <div class="form-control-icon">
            <i class="bi bi-person"></i>
        </div>
        @if($errors->has('FullName'))
            <small class="d-block text-danger">
                {{$errors->first('FullName')}}
            </small>
        @endif
    </div>
    <div class="form-group position-relative has-icon-left mb-3">
        <input name="PhoneNumber" value="{{old('PhoneNumber')}}" type="text" class="form-control form-control-xl" placeholder="Phone Number">
        <div class="form-control-icon">
            <i class="bi bi-phone"></i>
        </div>
        @if($errors->has('PhoneNumber'))
            <small class="d-block text-danger">
                {{$errors->first('PhoneNumber')}}
            </small>
        @endif
    </div>
    <div class="form-group position-relative has-icon-left mb-3">
        <input name="EmailAddress" value="{{old('EmailAddress')}}" type="email" class="form-control form-control-xl" placeholder="Email">
        <div class="form-control-icon">
            <i class="bi bi-envelope"></i>
        </div>
        @if($errors->has('EmailAddress'))
            <small class="d-block text-danger">
                {{$errors->first('EmailAddress')}}
            </small>
        @endif
    </div>
    <div class="form-group position-relative has-icon-left mb-3">
        <input type="password" name="password" class="form-control form-control-xl" placeholder="Password">
        <div class="form-control-icon">
            <i class="bi bi-shield-lock"></i>
        </div>
        @if($errors->has('password'))
            <small class="d-block text-danger">
                {{$errors->first('password')}}
            </small>
        @endif
    </div>
    <div class="form-group position-relative has-icon-left mb-3">
        <input type="password" name="password_confirmation" class="form-control form-control-xl" placeholder="Confirm Password">
        <div class="form-control-icon">
            <i class="bi bi-shield-lock"></i>
        </div>
        @if($errors->has('password_confirmation'))
            <small class="d-block text-danger">
                {{$errors->first('password_confirmation')}}
            </small>
        @endif
    </div>
    <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-3">Sign Up</button>
</form>
<div class="text-center mt-3 text-lg fs-4">
    <p class='text-gray-600'>Already have an account? <a href="{{route('login')}}" class="font-bold">Log
            in</a>.</p>
</div> --}}

@endsection
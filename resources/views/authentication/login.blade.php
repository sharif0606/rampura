@extends('layout.auth')

@section('content')
<!-- <h1 class="auth-title">Login</h1> -->
@if(Session::has('response'))
    {!!Session::get('response')['message']!!}
@endif
<form action="{{route('login.check')}}" method="post">
    @csrf
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
    <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-2">{{__('Log in')}}</button>
</form>
<div class="text-center mt-3 text-lg fs-4">
    <p class="text-gray-600 m-0">Don't have an account? <a href="{{route('register')}}" class="font-bold">{{__('Sign
            up')}}</a>.</p>
    <p><a class="font-bold" href="auth-forgot-password.html">Forgot password?</a>.</p>
</div>


@endsection
@extends('layout.app')
@section('pageTitle',trans('Profile'))
@section('pageSubTitle',trans('Profile'))

@section('content')

<!-- Bordered table start -->
<section id="multiple-column-form">
    <div class="card p-4">
        <div class="row match-height">
            <div class="col-md-4">
                <div class="text-center position-relative">
                    <img class="rounded-circle" src="{{asset('images/users/'.company()['company_id'].'/'.$users->image)}}" alt="" style="width: 200px; height: 220px;">
                    <h5 class="mt-2">{{$users->name}}</h5>
                    <p>{{$users->role?->type}}</p>
                </div>
            </div>
            <div class="col-md-8">
                @if(Session::has('response'))
                    {!!Session::get('response')['message']!!}
                @endif
                <form class="form" method="post" enctype="multipart/form-data" action="{{route(currentUser().'.users.update',encryptor('encrypt',$users->id))}}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" value="{{$users->role_id}}" name="role_id">
                    <div class="row mb-3">
                        <label for="userName" class="col-sm-2 col-form-label"><b>{{__('Name')}}</b></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{$users->name}}" name="userName">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="userEmail" class="col-sm-2 col-form-label">{{__('Email')}}</label>
                        <div class="col-sm-10">
                            <input type="email" name="userEmail" class="form-control" value="{{$users->email}}">
                            
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="contactNumber" class="col-sm-2 col-form-label">{{__('Contact')}}</label>
                        <div class="col-sm-10">
                            <input type="text" id="contactNumber" name="contactNumber" class="form-control" value="{{$users->contact_no}}">
                            
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="password" class="col-sm-2 col-form-label">{{__('Password')}}</label>
                        <div class="col-sm-10">
                            <input type="text" id="password" name="password" class="form-control">
                        
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="photo" class="col-sm-2 col-form-label">Photo</label>
                        <div class="col-sm-10">
                            
                            <input type="file" id="photo" name="image" class="form-control" onchange="pview(this)">
                            <img src="{{asset('images/users/'.company()['company_id'].'/'.$users->image)}}" id="photo_p" class="my-1" width="100px" alt="">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
	function pview(e){
		document.getElementById('photo_p').src=window.URL.createObjectURL(e.files[0]);
	}
</script>
@endpush
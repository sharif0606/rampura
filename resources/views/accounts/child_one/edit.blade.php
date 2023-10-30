@extends('layout.app')

@section('pageTitle',trans('Update Child One'))
@section('pageSubTitle',trans('Update'))

@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" method="post" action="{{route(currentUser().'.child_one.update',encryptor('encrypt',$child->id))}}">
                            @csrf
                            @method('patch')
                            <input type="hidden" name="uptoken" value="{{encryptor('encrypt',$child->id)}}">
                            <div class="row">
                                

                                
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="sub_head">{{__('Sub Head')}}</label>
                                        <select class="form-control form-select" name="sub_head" id="sub_head">
                                            <option value="">Select Sub Head</option>
                                            @forelse($data as $d)
                                                <option value="{{$d->id}}" {{ old('sub_head',$child->sub_head_id)==$d->id?"selected":""}}> {{ $d->head_name}}-{{ $d->head_code}}</option>
                                            @empty
                                                <option value="">No data found</option>
                                            @endforelse
                                        </select>
                                        @if($errors->has('sub_head'))
                                        <span class="text-danger"> {{ $errors->first('sub_head') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="head_name">{{__('Head Name')}}</label>
                                        <input type="text" id="head_name" class="form-control"
                                            placeholder="Head Name" value="{{ old('head_name',$child->head_name)}}" name="head_name">
                                    </div>
                                    @if($errors->has('head_name'))
                                    <span class="text-danger"> {{ $errors->first('head_name') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="head_code">{{__('Head Code')}}</label>
                                        <input type="text" id="head_code" class="form-control"
                                            placeholder="Head Code" value="{{ old('head_code',$child->head_code)}}" name="head_code">
                                    </div>
                                    @if($errors->has('head_code'))
                                    <span class="text-danger"> {{ $errors->first('head_code') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="opening_balance">{{__('Opening Balance')}}</label>
                                        <input type="text" id="opening_balance" class="form-control"
                                            placeholder="Opening Balance" value="{{ old('opening_balance',$child->opening_balance)}}" name="opening_balance">
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-start">
                                    <button type="submit" class="btn btn-info me-1 mb-1">{{__('Update')}}</button>
                                    
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
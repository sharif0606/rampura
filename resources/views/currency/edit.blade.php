@extends('layout.app')

@section('pageTitle',trans('Update Currency'))
@section('pageSubTitle',trans('Update'))

@section('content')
  <section id="multiple-column-form">
      <div class="row match-height">
          <div class="col-12">
              <div class="card">
                  <div class="card-content">
                      <div class="card-body">
                          <form class="form" method="post" enctype="multipart/form-data" action="{{route(currentUser().'.currency.update',encryptor('encrypt',$currency->id))}}">
                              @csrf
                              @method('patch')
                              <input type="hidden" name="uptoken" value="{{encryptor('encrypt',$currency->id)}}">
                              <div class="row"> 
                                  <div class="col-md-6 col-12">
                                      <div class="form-group">
                                          <label for="name">{{__('Currency')}}<span class="text-danger">*</span></label>
                                          <input type="text" id="name" value="{{ $currency->currency_name }}" class="form-control"
                                              placeholder="Currency Name" name="currency">
                                      </div>
                                      @if($errors->has('currency'))
                                      <span class="text-danger"> {{ $errors->first('currency') }}</span>
                                      @endif
                                  </div>
                                  <div class="col-md-6 col-12">
                                      <div class="form-group">
                                          <label for="name">{{__('Symbol')}}<span class="text-danger">*</span></label>
                                          <input type="text" id="Symbol" value="{{ $currency->currency_symbol }}" class="form-control"
                                              placeholder="Currency Name" name="symbol">
                                      </div>
                                      @if($errors->has('currency'))
                                      <span class="text-danger"> {{ $errors->first('currency') }}</span>
                                      @endif
                                  </div>
                                  <div class="col-md-6 col-12">
                                      <div class="form-group">
                                          <label for="name">{{__('Port')}}<span class="text-danger">*</span></label>
                                          <input type="text" id="port" value="{{ $currency->currency_port }}" class="form-control"
                                              placeholder="Currency Name" name="port">
                                      </div>
                                      @if($errors->has('currency'))
                                      <span class="text-danger"> {{ $errors->first('currency') }}</span>
                                      @endif
                                  </div>
                                  <div class="col-md-6 col-12">
                                      <div class="form-group">
                                          <label for="name">{{__('Rate')}}<span class="text-danger">*</span></label>
                                          <input type="text" id="rate" value="{{ $currency->currency_rate }}" class="form-control"
                                              placeholder="Currency Name" name="rate">
                                      </div>
                                      @if($errors->has('currency'))
                                      <span class="text-danger"> {{ $errors->first('currency') }}</span>
                                      @endif
                                  </div>
                               
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
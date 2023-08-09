@extends('layout.app')

@section('pageTitle',trans('Update Company Details'))
@section('pageSubTitle',trans('Update'))
@section('content')
  <section id="multiple-column-form">
      <div class="row match-height">
          <div class="col-12">
              <div class="card">
                  <div class="card-content">
                      <div class="card-body">
                          <form class="form" method="post" enctype="multipart/form-data" action="{{route(currentUser().'.company.update',encryptor('encrypt',$company->id))}}">
                              @csrf
                              @method('patch')
                              <input type="hidden" name="uptoken" value="{{encryptor('encrypt',$company->id)}}">
                              <div class="row">
                                  <div class="col-md-6 col-12">
                                      <div class="form-group">
                                          <label for="name">{{__('Company Name')}}</label>
                                          <input type="text" class="form-control" value="{{ old('name',$company->name)}}" name="name"  placeholder="Company Name" >
                                      </div>
                                  </div>
                                  <div class="col-md-6 col-12">
                                      <div class="form-group">
                                          <label for="contact">{{__('Contact')}}</label>
                                          <input type="text" class="form-control" value="{{ old('contact',$company->contact)}}" name="contact" >
                                      </div>
                                  </div>
                                  <div class="col-md-6 col-12 d-none">
                                        <div class="form-group">
                                            <label for="country">{{__('Country')}}</label>
                                            <select class="form-control form-select" name="country">
                                                <option value="">Select Country</option>
                                                @forelse($country as $d)
                                                    <option value="{{$d->id}}" {{ old('country',$company->country_id)==$d->id?"selected":""}}> {{ $d->name}}</option>
                                                @empty
                                                    <option value="">No Country found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 d-none">
                                        <div class="form-group">
                                            <label for="division_id">{{__('Division')}}</label>
                                            <select class="form-control form-select" name="division">
                                                <option value="">Select Division</option>
                                                @forelse($division as $d)
                                                    <option value="{{$d->id}}" {{ old('division',$company->division_id)==$d->id?"selected":""}}> {{ $d->name}}</option>
                                                @empty
                                                    <option value="">No Division found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 d-none">
                                        <div class="form-group">
                                            <label for="district">{{__('District')}}</label>
                                            <select class="form-control form-select" name="district">
                                                <option value="">Select District</option>
                                                @forelse($district as $d)
                                                    <option value="{{$d->id}}" {{ old('district',$company->district_id)==$d->id?"selected":""}}> {{ $d->name}}</option>
                                                @empty
                                                    <option value="">No District found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 d-none">
                                        <div class="form-group">
                                            <label for="upazila">{{__('Upazila')}}</label>
                                            <select class="form-control form-select" name="upazila">
                                                <option value="">Select Upazila</option>
                                                @forelse($upazila as $d)
                                                    <option value="{{$d->id}}" {{ old('upazila',$company->upazila_id)==$d->id?"selected":""}}> {{ $d->name}}</option>
                                                @empty
                                                    <option value="">No Upazila found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 d-none">
                                        <div class="form-group">
                                            <label for="thana">{{__('Thana')}}</label>
                                            <select class="form-control form-select" name="thana">
                                                <option value="">Select Thana</option>
                                                @forelse($thana as $d)
                                                    <option value="{{$d->id}}" {{ old('thana',$company->thana_id)==$d->id?"selected":""}}> {{ $d->name}}</option>
                                                @empty
                                                    <option value="">No Thana found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 d-none">
                                        <div class="form-group">
                                            <label for="thana">{{__('Currency')}}</label>
                                            <select class="form-control form-select" name="currency">
                                                <option value="">Select Currency</option>
                                                @forelse($currency as $d)
                                                    <option value="{{$d->id}}" {{ old('currency',$company->currency)==$d->id?"selected":""}}> {{ $d->currency_name}}</option>
                                                @empty
                                                    <option value="">No Currency found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="description">{{__('Address')}}</label>
                                            <textarea  class="form-control" name="address">{{ old('address',$company->address)}}</textarea>
                                        </div>
                                    </div>
                                  <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary mb-1">{{__('Save')}}</button>
                                  </div>
                              </div>
                          </form>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </section>
  <!-- // Basic multiple Column Form section end -->
</div>
@endsection
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
                                  <div class="col-lg-4 col-md-6 col-sm-12">
                                      <div class="form-group">
                                          <label for="name">{{__('Company Name')}}</label>
                                          <input type="text" class="form-control" value="{{ old('name',$company->name)}}" name="name"  placeholder="Company Name" >
                                      </div>
                                  </div>
                                  <div class="col-lg-4 col-md-6 col-sm-12">
                                      <div class="form-group">
                                          <label for="contact">{{__('Contact')}}</label>
                                          <input type="text" class="form-control" value="{{ old('contact',$company->contact)}}" name="contact" >
                                      </div>
                                  </div>
                                  <div class="col-lg-4 col-md-6 col-sm-12">
                                      <div class="form-group">
                                          <label for="contact">{{__('Email')}}</label>
                                          <input type="email" class="form-control" value="{{ old('email',$company->email)}}" name="email" >
                                      </div>
                                  </div>
                                  <div class="col-lg-4 col-md-6 col-sm-12 d-none">
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
                                    <div class="col-lg-4 col-md-6 col-sm-12 d-none">
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
                                    <div class="col-lg-4 col-md-6 col-sm-12 d-none">
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
                                    <div class="col-lg-4 col-md-6 col-sm-12 d-none">
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
                                    <div class="col-lg-4 col-md-6 col-sm-12 d-none">
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
                                    <div class="col-lg-4 col-md-6 col-sm-12 d-none">
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
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="description">{{__('Address')}}</label>
                                            <textarea  class="form-control" name="address">{{ old('address',$company->address)}}</textarea>
                                        </div>
                                    </div>
                              </div>
                              <div class="row">
                                    <hr>
                                    <div class="text-center"><h4>For Srota & Memu</h4></div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="name">কোম্পানি নাম</label>
                                            <input type="text" class="form-control" value="{{ old('company_bn',$company->company_bn)}}" name="company_bn">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="name">ঠিকানা</label>
                                            <input type="text" class="form-control" value="{{ old('address_bn',$company->address_bn)}}" name="address_bn">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="name">মোবাইল</label>
                                            <input type="text" class="form-control" value="{{ old('contact_bn',$company->contact_bn)}}" name="contact_bn">
                                        </div>
                                    </div>
                              </div>
                              <div class="row">
                                    <hr>
                                    <div class="text-center"><h4>For LC Expense</h4></div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="name">LC Expense Head Code</label>
                                            <textarea class="form-control" name="lc_expense" rows="2">{{ old('lc_expense',$company->lc_expense)}}</textarea>
                                            <span class="text-danger">Value must be comma separated!</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="name">Income Head Code</label>
                                            <textarea class="form-control" name="income_head" rows="2">{{ old('income_head',$company->income_head)}}</textarea>
                                            <span class="text-danger">Value must be comma separated!</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="name">Expense Head Code</label>
                                            <textarea class="form-control" name="expense_head" rows="2">{{ old('expense_head',$company->expense_head)}}</textarea>
                                            <span class="text-danger">Value must be comma separated!</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="name">Tax Head Code</label>
                                            <textarea class="form-control" name="tax_head" rows="2">{{ old('tax_head',$company->tax_head)}}</textarea>
                                            <span class="text-danger">Value must be comma separated!</span>
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
@endsection
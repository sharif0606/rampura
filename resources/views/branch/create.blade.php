  @extends('layout.app')

  @section('pageTitle',trans('Create Branch'))
@section('pageSubTitle',trans('Create'))

  @section('content')
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" method="post" enctype="multipart/form-data" action="{{route(currentUser().'.branch.store')}}">
                                @csrf
                                <div class="row">

                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="name">{{__('Branch')}}<span class="text-danger">*</span></label>
                                            <input type="text" id="name" value="{{ old('name')}}" class="form-control"
                                                placeholder="Branch Name" name="name">

                                            @if($errors->has('name'))
                                            <span class="text-danger"> {{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 d-none">
                                        <div class="form-group">
                                            <label for="thana">{{__('Currency')}}</label>
                                            <select class="form-control form-select" name="currency">
                                                <option value="">Select Currency</option>
                                                @forelse($currency as $d)
                                                    <option value="{{$d->id}}" {{ old('currency')==$d->id?"selected":""}}> {{ $d->currency_name}}</option>
                                                @empty
                                                    <option value="">No Currency found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="contact">{{__('Contact')}}<span class="text-danger">*</span></label>
                                            <input type="text" id="contact" value="{{ old('contact')}}" class="form-control"
                                                placeholder="Branch contact" name="contact">

                                            @if($errors->has('contact'))
                                            <span class="text-danger"> {{ $errors->first('contact') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 d-none">
                                        <div class="form-group">
                                            <label for="binNumber">{{__('Bin Number')}}</label>
                                            <input type="text" id="binNumber" value="{{ old('binNumber')}}" class="form-control"
                                                placeholder="Branch Bin Number" name="binNumber">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 d-none">
                                        <div class="form-group">
                                            <label for="tradeNumber">{{__('Trade Number')}}</label>
                                            <input type="text" id="binNumber" value="{{ old('tradeNumber')}}" class="form-control"
                                                placeholder="Branch Trade Number" name="tradeNumber">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="address">{{__('Address')}}</label>
                                           <textarea class="form-control" name="address" id="address" rows="2">{{ old('address')}}</textarea>
                                        </div>
                                       
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
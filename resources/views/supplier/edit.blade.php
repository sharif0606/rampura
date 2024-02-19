@extends('layout.app')

@section('pageTitle',trans('Update Supplier'))
@section('pageSubTitle',trans('Update'))

@section('content')
  <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" method="post" action="{{route(currentUser().'.supplier.update',encryptor('encrypt',$supplier->id))}}">
                                @csrf
                                @method('patch')
                                <input type="hidden" name="uptoken" value="{{encryptor('encrypt',$supplier->id)}}">
                                <div class="row">

                                        {{-- @if( currentUser()=='owner')
                                            <div class="col-md-4 col-12 d-none">
                                                <div class="form-group">
                                                    <label for="branch_id">{{__('Branches Name')}}<span class="text-danger">*</span></label>
                                                    <select class="form-control form-select" name="branch_id" id="branch_id">
                                                        @forelse($branches as $b)
                                                            <option value="{{ $b->id }}" {{old('branch_id',$supplier->branch_id)==$b->id?'selected':''}}>{{ $b->name }}</option>
                                                        @empty
                                                            <option value="">No branch found</option>
                                                        @endforelse
                                                    </select>
                                                    @if($errors->has('supplierName'))
                                                    <span class="text-danger"> {{ $errors->first('supplierName') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <input type="hidden" value="{{ branch()['branch_id']}}" name="branch_id">
                                        @endif --}}

                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="supplier_name">{{__('Supplier Name')}}<span class="text-danger">*</span></label>
                                            <input type="text" onkeyup="removeCharacter(this)" id="supplier_name" class="form-control" value="{{ old('supplier_name',$supplier->supplier_name)}}" name="supplier_name" required>
                                            @if($errors->has('supplier_name'))
                                            <span class="text-danger"> {{ $errors->first('supplier_name') }}</span>
                                            @endif
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="contact">{{__('Contact')}}<span class="text-danger">*</span></label>
                                            <input type="text" id="contact" class="form-control" value="{{ old('contact',$supplier->contact)}}" name="contact">
                                            @if($errors->has('contact'))
                                            <span class="text-danger"> {{ $errors->first('contact') }}</span>
                                            @endif
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="email">{{__('Email')}}</label>
                                            <input type="email" id="email" class="form-control" value="{{ old('email',$supplier->email)}}" name="email">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="phone">{{__('Phone')}}</label>
                                            <input type="text" id="phone" class="form-control" value="{{ old('phone',$supplier->phone)}}" name="phone">
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-4 col-12 d-none">
                                        <div class="form-group">
                                            <label for="taxNumber">{{__('TAX Number')}}</label>
                                            <input type="text" id="taxNumber" class="form-control" value="{{ old('taxNumber',$supplier->tax_number)}}" name="taxNumber">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12 d-none">
                                        <div class="form-group">
                                            <label for="gstNumber">{{__('GST Number')}}</label>
                                            <input type="text" id="gstNumber" class="form-control" value="{{ old('gstNumber',$supplier->gst_number)}}" name="gstNumber">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="openingAmount">{{__('Opening Balance')}}</label>
                                            <input type="number" onkeyup="check_opb()" id="openingAmount" class="form-control op_balance" value="{{ old('openingAmount',$supplier->opening_balance)}}" name="openingAmount">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="opening-balance-date">{{__('Opening Balance Date')}}</label>
                                            <input type="date" class="form-control" id="opbDate" value="{{ old('opening_balance_date')}}" name="opening_balance_date">
                                            <span id="reqMessage" class="text-danger text-start"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="countryName">{{__('Country')}}<span class="text-danger">*</span></label>
                                            <select onchange="show_division(this.value)" class="form-control form-select" name="countryName" id="countryName">
                                                <option value="">Select Country</option>
                                                @forelse($countries as $d)
                                                    <option value="{{$d->id}}" {{ old('countryName',$supplier->country_id)==$d->id?"selected":""}}> {{ $d->name}}</option>
                                                @empty
                                                    <option value="">No Country found</option>
                                                @endforelse
                                            </select>
                                            @if($errors->has('countryName'))
                                            <span class="text-danger"> {{ $errors->first('countryName') }}</span>
                                            @endif
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="divisionName">{{__('Division')}}</label>
                                            <select onchange="show_district(this.value)" class="form-control form-select" name="divisionName" id="divisionName">
                                                <option value="">Select Division</option>
                                                @forelse($divisions as $d)
                                                    <option class="div div{{$d->country_id}}" value="{{$d->id}}" {{ old('divisionName',$supplier->division_id)==$d->id?"selected":""}}> {{ $d->name}}</option>
                                                @empty
                                                    <option value="">No Division found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="districtName">{{__('District')}}</label>
                                            <select onchange="show_upazila(this.value)" class="form-control form-select" name="districtName" id="districtName">
                                                <option value="">Select District</option>
                                                @forelse($districts as $d)
                                                    <option class="dist dist{{$d->division_id}}" value="{{$d->id}}" {{ old('districtName',$supplier->district_id)==$d->id?"selected":""}}> {{ $d->name}}</option>
                                                @empty
                                                    <option value="">No District found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="districtName">{{__('Area')}}</label>
                                            <select class="form-control form-select" name="upazilaName">
                                                <option value="">Select Area</option>
                                                @forelse($upazilas as $d)
                                                    <option class="upa upa{{$d->district_id}}" value="{{$d->id}}" {{ old('upazilaName',$supplier->upazila_id)==$d->id?"selected":""}}> {{ $d->name}}</option>
                                                @empty
                                                    <option value="">No data found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="postCode">{{__('Post Code')}}</label>
                                            <input type="text" id="postCode" class="form-control" value="{{ old('postCode',$supplier->post_code)}}" name="postCode">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="address" class="form-label">{{__('Address')}}</label>
                                            <textarea class="form-control" name="address" id="address" rows="2">{{ old('address',$supplier->address)}}</textarea>
                                            
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
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

@push('scripts')
<script>
    /* call on load page */
    $(document).ready(function(){
        $('.div').hide();
        $('.dist').hide();
        $('.upa').hide();
    })

    function show_division(e){
         $('.div').hide();
         $('.div'+e).show()
    }
    function show_district(e){
        $('.dist').hide();
        $('.dist'+e).show();
    }
    function show_upazila(e){
        $('.upa').hide();
        $('.upa'+e).show();
    }

    function check_opb(){
        var opb=(isNaN(parseFloat($('.op_balance').val().trim()))) ? 0 :parseFloat($('.op_balance').val().trim());
        if(opb > 0){
            $('#opbDate').prop('required', true);
            $('#reqMessage').text("This Field is required")
        }else{
            $('#opbDate').removeAttr('required');
            $('#reqMessage').text("")
        }
    }
</script>
<script>
    function removeCharacter(e) {
        newString = e.value.replace("-", " ");
        e.value= newString;
    }
</script>
@endpush
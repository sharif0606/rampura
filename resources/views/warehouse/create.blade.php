  @extends('layout.app')

  @section('pageTitle',trans('Create Warehouse'))
@section('pageSubTitle',trans('Create'))_

  @section('content')
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" method="post" enctype="multipart/form-data" action="{{route(currentUser().'.warehouse.store')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                                <label for="branch_id">{{__('Branch')}}<span class="text-danger">*</span></label>
                                                <select class="form-control form-select" name="branch" id="branch">
                                                    {{-- <option value="">Select Branch</option> --}}
                                                    @forelse($branch as $b)
                                                        <option value="{{ $b->id }}" {{old('branch')==$b->id?'selected':''}}>
                                                            {{ $b->name }}
                                                        </option>
                                                    @empty
                                                        <option value="">No branch found</option>
                                                    @endforelse
                                                </select>
                                                @if($errors->has('branch'))
                                                <span class="text-danger"> {{ $errors->first('branch') }}</span>
                                                @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="name">{{__('Warehouse')}}<span class="text-danger">*</span></label>
                                            <input type="text" id="name" value="{{ old('name')}}" class="form-control" placeholder="Warehouse Name" name="name">
                                            @if($errors->has('name'))
                                            <span class="text-danger"> {{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="contact">{{__('Contact')}}<span class="text-danger">*</span></label>
                                            <input type="text" id="name" value="{{ old('contact')}}" class="form-control" placeholder="Warehouse contact" name="contact">
                                            @if($errors->has('contact'))
                                            <span class="text-danger"> {{ $errors->first('contact') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="address">{{__('Address Details')}}</label>
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
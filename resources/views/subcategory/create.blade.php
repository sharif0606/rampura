@extends('layout.app')

@section('pageTitle',trans('Create Subcategory'))
@section('pageSubTitle',trans('Create'))

@section('content')
<!-- // Basic multiple Column Form section start -->
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" method="post" action="{{route(currentUser().'.subcategory.store')}}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="Category">{{__('Category')}}<span class="text-danger">*</span></label>
                                        <select class="form-control form-select" name="category" id="category">
                                            <option value="">Select Category</option>
                                            @forelse($categories as $cat)
                                                <option value="{{$cat->id}}" {{ old('category')==$cat->id?"selected":""}}> {{ $cat->category}}</option>
                                            @empty
                                                <option value="">No Category found</option>
                                            @endforelse
                                            
                                        </select>
                                        @if($errors->has('category'))
                                        <span class="text-danger"> {{ $errors->first('category') }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="Sub Category">{{__('Sub Category')}}<span class="text-danger">*</span></label>
                                        <input type="text" id="subCat" class="form-control"
                                            placeholder="Subcategory Name" value="{{ old('subCat')}}" name="subCat">
                                            @if($errors->has('subCat'))
                                            <span class="text-danger"> {{ $errors->first('subCat') }}</span>
                                            @endif
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
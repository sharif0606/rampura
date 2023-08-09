@extends('layout.app')

@section('pageTitle',trans('update Subcategory'))
@section('pageSubTitle',trans('update'))

@section('content')
<!-- // Basic multiple Column Form section start -->
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" method="post" action="{{route(currentUser().'.subcategory.update',encryptor('encrypt',$subcategory->id))}}">
                            @csrf
                            @method('patch')
                            <input type="hidden" name="uptoken" value="{{encryptor('encrypt',$subcategory->id)}}">
                            <div class="row">

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="Category">{{__('Category')}}<span class="text-danger">*</span></label>
                                        <select class="form-control form-select" name="category" id="category">
                                            <option value="">Select Category</option>
                                            @forelse($category as $cat)
                                                <option value="{{$cat->id}}" {{ old('category',$subcategory->category_id)==$cat->id?"selected":""}}> {{ $cat->category}}</option>
                                            @empty
                                                <option value="">No Category found</option>
                                            @endforelse
                                        </select>
                                        @if($errors->has('Category'))
                                        <span class="text-danger"> {{ $errors->first('Category') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="Sub Category">{{__('Sub Category')}}<span class="text-danger">*</span></label>
                                        <input type="text" id="subCat" class="form-control"
                                            placeholder="Subcategory Name" value="{{ old('subCat',$subcategory->name)}}" name="subCat">
                                            @if($errors->has('Sub Category'))
                                            <span class="text-danger"> {{ $errors->first('Sub Category') }}</span>
                                            @endif
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
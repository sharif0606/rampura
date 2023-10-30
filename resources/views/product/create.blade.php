@extends('layout.app')

@section('pageTitle',trans('Create Product'))
@section('pageSubTitle',trans('Create'))

@section('content')
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" method="post" enctype="multipart/form-data" action="{{route(currentUser().'.product.store')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="Category">{{__('Category')}}<span class="text-danger">*</span></label>
                                            <select onchange="show_subcat(this.value)" class="form-control form-select" name="category" id="category">
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
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="subcategory">{{__('Sub Category')}}</label>
                                            <select onchange="show_childcat(this.value)" class="form-control form-select" name="subcategory" id="subcategory">
                                                <option value="">Select Sub Category</option>
                                                @forelse($subcategories as $sub)
                                                    <option class="subcat subcat{{$sub->category_id}}" value="{{$sub->id}}" {{ old('subcategory')==$sub->id?"selected":""}}> {{ $sub->name}}</option>
                                                @empty
                                                    <option value="">No Sub Category found</option>
                                                @endforelse

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 d-none">
                                        <div class="form-group">
                                            <label for="childcategory Name">{{__('Child Category')}}</label>
                                            <select class="form-control form-select" name="childcategory" id="childcategory">
                                                <option value="">Select Child Category</option>
                                                @forelse($childcategories as $child)
                                                    <option class="childcat childcat{{$child->subcategory_id}}" value="{{$child->id}}" {{ old('childcategory')==$child->id?"selected":""}}> {{ $child->name}}</option>
                                                @empty
                                                    <option value="">No Child Category found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 d-none">
                                        <div class="form-group">
                                            <label for="unit_id">{{__('Unit')}}</label>
                                            <select class="form-control form-select" name="unit_id" id="unit_id">
                                                <option value="">Select</option>
                                                @forelse($units as $u)
                                                    <option value="{{$u->id}}" {{ old('unit_id')==$u->id?"selected":""}}> {{ $u->name}}</option>
                                                @empty
                                                    <option value="">No Unit found</option>
                                                @endforelse
                                                @if($errors->has('name'))
                                                    <span class="text-danger"> {{ $errors->first('name') }}</span>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 d-none">
                                        <div class="form-group">
                                            <label for="brand_id">{{__('Brand')}}</label>
                                            <select class="form-control" name="brand_id" id="brand_id">
                                                <option value="">Select Brand</option>
                                                @forelse($brands as $b)
                                                    <option value="{{$b->id}}" {{ old('brand_id')==$b->id?"selected":""}}> {{ $b->name}}</option>
                                                @empty
                                                    <option value="">No Brand found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="Product Name">{{__('Product Name')}}<span class="text-danger">*</span></label>
                                            <input type="text" id="productName" class="form-control"
                                                placeholder="Product Name" value="{{ old('productName')}}" name="productName">
                                                @if($errors->has('productName'))
                                                <span class="text-danger"> {{ $errors->first('productName') }}</span>
                                                @endif
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4 col-md-6 col-sm-12 d-none">
                                        <div class="form-group">
                                            <label for="price">{{__('Sell Price')}}<span class="text-danger">*</span></label>
                                            <input type="text" id="price" class="form-control"
                                                placeholder="Price" value="{{ old('price')}}" name="price">
                                                @if($errors->has('price'))
                                                    <span class="text-danger"> {{ $errors->first('price') }}</span>
                                                @endif
                                                
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6 col-sm-12 d-none">
                                        <div class="form-group">
                                            <label for="purchase_price">{{__('Purchase Price')}}</label>
                                            <input type="text" id="purchase_price" class="form-control" 
                                                placeholder="Purchase Price" value="{{ old('purchase_price')}}" name="purchase_price">
                                                @if($errors->has('purchase_price'))
                                                    <span class="text-danger"> {{ $errors->first('purchase_price') }}</span>
                                                @endif
                                                
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 d-none">
                                        <div class="form-group">
                                            <label for="image">{{__('Image')}}</label>
                                            <input type="file" id="image" class="form-control"
                                                placeholder="Image" name="image">
                                                @if($errors->has('image'))
                                                    <span class="text-danger"> {{ $errors->first('image') }}</span>
                                                @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="description">{{__('Description')}}</label>
                                            <textarea  class="form-control" id="description"
                                                placeholder="Product description" name="description">{{ old('description')}}</textarea>
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

@push('scripts')
<script>
    /* call on load page */
    $(document).ready(function(){
        $('.subcat').hide();
        $('.childcat').hide();
    })

    function show_subcat(e){
         $('.subcat').hide();
         $('.subcat'+e).show()
    }
    function show_childcat(e){
        $('.childcat').hide();
        $('.childcat'+e).show();
    }

    
   
    
</script>
@endpush
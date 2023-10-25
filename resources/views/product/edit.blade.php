@extends('layout.app')

@section('pageTitle',trans('Update Product'))
@section('pageSubTitle',trans('Update'))

@section('content')
  <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" method="post" enctype="multipart/form-data" action="{{route(currentUser().'.product.update',encryptor('encrypt',$product->id))}}">
                                @csrf
                                @method('patch')
                                <input type="hidden" name="uptoken" value="{{encryptor('encrypt',$product->id)}}">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="Category">{{__('Category')}}<span class="text-danger">*</span></label>
                                            <select onchange="show_subcat(this.value)" class="form-control form-select" name="category" id="category">
                                                <option value="">Select Category</option>
                                                @forelse($categories as $cat)
                                                    <option value="{{$cat->id}}" {{ old('category',$product->category_id)==$cat->id?"selected":""}}> {{ $cat->category}}</option>
                                                @empty
                                                    <option value="">No Category found</option>
                                                @endforelse
                                                
                                            </select>
                                           
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 d-none">
                                        <div class="form-group">
                                            <label for="subcategory">{{__('Sub Category')}}</label>
                                            <select onchange="show_childcat(this.value)" class="form-control form-select" name="subcategory" id="subcategory">
                                                <option value="">Select Sub Category</option>
                                                @forelse($subcategories as $sub)
                                                    <option class="subcat subcat{{$sub->category_id}}" value="{{$sub->id}}" {{ old('subcategory',$product->subcategory_id)==$sub->id?"selected":""}}> {{ $sub->name}}</option>
                                                @empty
                                                    <option value="">No Sub Category found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 d-none">
                                        <div class="form-group">
                                            <label for="childcategory">{{__('Child Category')}}</label>
                                            <select class="form-control form-select" name="childcategory" id="childcategory">
                                                <option value="">Select Child Category</option>
                                                @forelse($childcategories as $child)
                                                    <option class="childcat childcat{{$child->subcategory_id}}" value="{{$child->id}}" {{ old('childcategory',$product->childcategory_id)==$child->id?"selected":""}}> {{ $child->name}}</option>
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
                                                <option value="">Select Unit</option>
                                                @forelse($units as $u)
                                                    <option value="{{$u->id}}" {{ old('name',$product->unit_id)==$u->id?"selected":""}}> {{ $u->name}}</option>
                                                @empty
                                                    <option value="">No Unit found</option>
                                                @endforelse
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 d-none">
                                        <div class="form-group">
                                            <label for="brand_id">{{__('Brand')}}</label>
                                            <select class="form-control form-select" name="brand_id" id="brand_id">
                                                <option value="">Select Brand</option>
                                                @forelse($brands as $b)
                                                    <option value="{{$b->id}}" {{ old('name',$product->brand_id)==$b->id?"selected":""}}> {{ $b->name}}</option>
                                                @empty
                                                    <option value="">No Brand found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="Product Name">{{__('Name')}}<span class="text-danger">*</span></label>
                                            <input type="text" id="productName" class="form-control"
                                                placeholder="Product Name" value="{{ old('productName',$product->product_name)}}" name="productName">
                                              
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4 col-md-6 col-sm-12 d-none">
                                        <div class="form-group">
                                            <label for="price">{{__('Sell Price')}}<span class="text-danger">*</span></label>
                                            <input type="text" id="price" class="form-control" placeholder="Price" value="{{ old('price',$product->price)}}" name="price">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6 col-sm-12 d-none">
                                        <div class="form-group">
                                            <label for="purchase_price">{{__('Purchase Price')}}</label>
                                            <input type="text" id="purchase_price" class="form-control"
                                                placeholder="Purchase Price" value="{{ old('purchase_price',$product->purchase_price)}}" name="purchase_price">
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
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="description">{{__('Description')}}</label>
                                            <textarea  class="form-control" id="description"
                                                placeholder="Product description" name="description">{{ old('description',$product->description)}}</textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 d-flex justify-content-end">
                                    {{-- <img width="80px" height="40px" class="float-first" src="{{asset('images/product/'.company()['company_id'].'/'.$product->image)}}" alt=""> --}}
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
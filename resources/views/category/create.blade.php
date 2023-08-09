  @extends('layout.app')

  @section('pageTitle',trans('Create Category'))
@section('pageSubTitle',trans('Create'))

  @section('content')
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" method="post" enctype="multipart/form-data" action="{{route(currentUser().'.category.store')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="name">{{__('Category')}}<span class="text-danger">*</span></label>
                                            <input type="text" id="name" class="form-control"
                                                placeholder="Category Name" name="category">
                                                @if($errors->has('category'))
                                                <span class="text-danger"> {{ $errors->first('category') }}</span>
                                                @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 d-none">
                                        <div class="form-group">
                                            <label for="image">{{__('Image')}}</label>
                                            <input type="file" id="image" class="form-control"
                                                placeholder="Image" name="image">
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
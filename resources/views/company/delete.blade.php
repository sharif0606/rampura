@extends('layout.app')

@section('pageTitle',trans('Data Delete Company Wise'))
@section('pageSubTitle',trans('Delete'))

@section('content')
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                @if(Session::has('response'))
                    {!!Session::get('response')['message']!!}
                @endif
                <form method="get" action="{{route(currentUser().'.deleted_data_company_wise')}}">
                    @csrf
                    @method('delete')
                    <div class="row">
                        <div class="col-6 offset-3">
                            <label for=""></label>
                            <select name="company_id" class="form-control form-select">
                                <option value="">Select Company</option>
                                @forelse ($data as $d)
                                    @if($d->name != '')
                                        <option value="{{$d->id}}">{{$d->name}}-{{$d->contact}}</option>
                                    @endif
                                @empty
                                    <option value="">No Data Found</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-6 offset-3 py-2 text-end">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
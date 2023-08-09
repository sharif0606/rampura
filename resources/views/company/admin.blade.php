@extends('layout.app')

@section('pageTitle',trans('Company Details'))
@section('pageSubTitle',trans('details'))

@section('content')
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th scope="col">{{__('Company Name')}}</th>
                                <th scope="col">{{__('Contact')}}</th>
                                {{-- <th scope="col">{{__('Country')}}</th>
                                <th scope="col">{{__('Division')}}</th>
                                <th scope="col">{{__('District')}}</th>
                                <th scope="col">{{__('Upazila')}}</th>
                                <th scope="col">{{__('Thana')}}</th> --}}
                                <th scope="col">{{__('Address')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $company)
                            <tr>
                                <td>{{$company->name}}</td>
                                <td>{{$company->contact}}</td>
                                {{-- <td>{{$company->country?->name}}</td>
                                <td>{{$company->division?->name}}</td>
                                <td>{{$company->district?->name}}</td>
                                <td>{{$company->upazila?->name}}</td>
                                <td>{{$company->thana?->name}}</td> --}}
                                <td>{{$company->address}}</td>
                            </tr> 
                            @empty
                            <tr>
                                <td colspan="3">No Data Found</td>
                            </tr>

                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


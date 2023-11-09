@extends('layout.app')

@section('pageTitle',trans('Company Details'))
@section('pageSubTitle',trans('Details'))

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
                                <th scope="col">{{__('Company Bn')}}</th>
                                <th scope="col">{{__('Contact')}}</th>
                                <th scope="col">{{__('Contact Bn')}}</th>
                                <th scope="col">{{__('Email')}}</th>
                                {{-- <th scope="col">{{__('Country')}}</th>
                                <th scope="col">{{__('Division')}}</th>
                                <th scope="col">{{__('District')}}</th>
                                <th scope="col">{{__('Upazila')}}</th>
                                <th scope="col">{{__('Thana')}}</th>
                                <th scope="col">{{__('Currency')}}</th> --}}
                                <th scope="col">{{__('Address')}}</th>
                                <th scope="col">{{__('Address Bn')}}</th>
                                <th class="white-space-nowrap">{{__('ACTION')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <tr>
                                <td>{{$data->name}}</td>
                                <td>{{$data->company_bn}}</td>
                                <td>{{$data->contact}}</td>
                                <td>{{$data->contact_bn}}</td>
                                <td>{{$data->email}}</td>
                                {{-- <td>{{$data->country?->name}}</td>
                                <td>{{$data->division?->name}}</td>
                                <td>{{$data->district?->name}}</td>
                                <td>{{$data->upazila?->name}}</td>
                                <td>{{$data->thana?->name}}</td>
                                <td>{{$data->Currency?->currency_name}}</td> --}}
                                <td>{{$data->address}}</td>
                                <td>{{$data->address_bn}}</td>
                                <td class="white-space-nowrap">
                                    <a href="{{route(currentUser().'.company.edit',encryptor('encrypt',$data->id))}}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


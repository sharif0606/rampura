@extends('layout.app')
@section('pageTitle',trans('Users List'))
@section('pageSubTitle',trans('List'))

@section('content')

<!-- Bordered table start -->
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                @if(Session::has('response'))
                    {!!Session::get('response')['message']!!}
                @endif
                <div>
                    <a class=" float-end" href="{{route(currentUser().'.users.create')}}"style="font-size:1.7rem"><i class="bi bi-plus-square-fill"></i></a>
                </div>
                <!-- table bordered -->
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">{{__('#SL')}}</th>
                                <th scope="col">{{__('Name')}}</th>
                                <th scope="col">{{__('Email')}}</th>
                                <th scope="col">{{__('Contact')}}</th>
                                {{-- <th scope="col">{{__('Image')}}</th> --}}
                                <th scope="col">{{__('Role')}}</th>
                                
                                <th class="white-space-nowrap">{{__('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $p)
                            <tr class="text-center">
                                <th scope="row">{{ ++$loop->index }}</th>
                                <td>{{$p->name}}</td>
                                <td>{{$p->email}}</td>
                                <td>{{$p->contact_no}}</td>
                                {{-- <td><img width="40px" height="55px" class="float-first" src="{{asset('images/users/'.company()['company_id'].'/'.$p->image)}}" alt=""></td> --}}
                                <!-- <td>@if($p->status == 1) {{__('Active') }} @else {{__('Inactive') }} @endif</td> -->
                                <!-- or <td>{{ $p->status == 1?"Active":"Inactive" }}</td>-->
                                <td>{{$p->role?->type}}</td>
                                <td class="white-space-nowrap">
                                    <a href="{{route(currentUser().'.users.edit',encryptor('encrypt',$p->id))}}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    {{--<a href="javascript:void()" onclick="$('#form{{$p->id}}').submit()">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    <form id="form{{$p->id}}" action="{{route(currentUser().'.users.destroy',encryptor('encrypt',$p->id))}}" method="post">
                                        @csrf
                                        @method('delete')
                                    </form>--}}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <th colspan="8" class="text-center">No Data Found</th>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Bordered table end -->


@endsection
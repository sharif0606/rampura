@extends('layout.app')
@section('pageTitle',trans('Navigate master view List'))
@section('pageSubTitle',trans('List'))

@section('content')

<!-- Bordered table start -->
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                    <!-- table bordered -->
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">{{__('Master Head')}}</th>
                                    <th scope="col">{{__('Sub Head')}}</th>
                                    <th scope="col">{{__('Child One')}}</th>
                                    <th scope="col">{{__('Child Two')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $d) <!-- master head loop -->
                                    @if($d->sub_head)
                                        <tr>
                                            <td>{{$d->head_name}} - {{$d->head_code}}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @foreach($d->sub_head as $subhead) <!-- sub head loop -->
                                            @if($subhead->child_one)
                                                <tr>
                                                    <td></td>
                                                    <td>{{$subhead->head_name}} - {{$subhead->head_code}}</td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                @foreach($subhead->child_one as $childOne) <!-- child one head loop -->
                                                    @if($childOne->child_two)
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td>{{$childOne->head_name}} - {{$childOne->head_code}}</td>
                                                            <td></td>
                                                        </tr>
                                                        @foreach($childOne->child_two as $childTwo) <!-- child two head loop -->
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>{{$childTwo->head_name}} - {{$childTwo->head_code}}</td>
                                                        </tr>
                                                        @endforeach <!-- /child two head loop -->
                                                    @endif
                                                @endforeach <!-- /child one head loop -->
                                            @endif
                                        @endforeach <!-- /sub head loop -->
                                    @endif

                                @empty
                                    <tr>
                                        <td colspan="4">No data found</td>
                                    </tr>
                                @endforelse <!-- /master head loop -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</section>
<!-- Bordered table end -->


@endsection
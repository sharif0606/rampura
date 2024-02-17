@extends('layout.app')

@section('pageTitle',trans('Create Child_One'))
@section('pageSubTitle',trans('Create'))

@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" method="post" action="{{route(currentUser().'.child_one.store')}}">
                            @csrf
                            <div class="row">

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="sub_head">{{__('Sub Head')}}</label>
                                        <select class="form-control form-select" name="sub_head" id="sub_head">
                                            <option value="">Select Sub Head</option>
                                            @forelse($data as $d)
                                                <option value="{{$d->id}}" {{ old('sub_head')==$d->id?"selected":""}}> {{ $d->head_name}}-{{ $d->head_code}}</option>
                                            @empty
                                                <option value="">No data found</option>
                                            @endforelse
                                        </select>
                                        @if($errors->has('sub_head'))
                                        <span class="text-danger"> {{ $errors->first('sub_head') }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="head_name">{{__('Head Name')}}</label>
                                        <input onkeyup="removeCharacter(this)" type="text" id="head_name" class="form-control"
                                            placeholder="Head Name" value="{{ old('head_name')}}" name="head_name" required>
                                    </div>
                                    @if($errors->has('head_name'))
                                    <span class="text-danger"> {{ $errors->first('head_name') }}</span>
                                    @endif
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="head_code">{{__('Head Code')}}</label>
                                        <input onkeyup="removeCharacter(this)" type="text" id="head_code" class="form-control"
                                            placeholder="Head Code" value="{{ old('head_code')}}" name="head_code" required>
                                    </div>
                                    @if($errors->has('head_code'))
                                    <span class="text-danger"> {{ $errors->first('head_code') }}</span>
                                    @endif
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="opening_balance">{{__('Opening Balance')}}</label>
                                        <input type="number" step="any" onkeyup="check_opb()" id="opening_balance" class="form-control op_balance"
                                            placeholder="Opening Balance" value="{{ old('opening_balance')}}" name="opening_balance" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="opening-balance-date">{{__('Opening Balance Date')}}</label>
                                        <input type="date" class="form-control" id="opbDate" value="{{ old('opening_balance_date')}}" name="opening_balance_date">
                                        <span id="reqMessage" class="text-danger text-start"></span>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-start">
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
    function removeCharacter(e) {
        newString = e.value.replace("-", " ");
        e.value= newString;
    }
    function check_opb(){
        var opb=(isNaN(parseFloat($('.op_balance').val().trim()))) ? 0 :parseFloat($('.op_balance').val().trim());
        if(opb > 0){
            $('#opbDate').prop('required', true);
            $('#reqMessage').text("This Field is required")
        }else{
            $('#opbDate').removeAttr('required');
            $('#reqMessage').text("")
        }
    }
</script>
@endpush
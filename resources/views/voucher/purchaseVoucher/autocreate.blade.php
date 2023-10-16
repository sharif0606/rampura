@extends('layout.app')

@section('pageTitle',trans('Create Purchase Voucher'))
@section('pageSubTitle',trans('Create'))

@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <h4 class="card-title text-center">{{__('Purchase Voucher Entry')}}</h4>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" enctype="multipart/form-data" method="post" action="{{route(currentUser().'.purchase_voucher.store')}}">
                            @csrf
                            <div class="row">
                                
                                <input type="hidden" id="voucher_no" class="form-control" value="" name="voucher_no" readonly>
                            
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="date">{{__('Date')}}</label>
                                        <input type="date" id="current_date" class="form-control" value="{{ old('current_date')}}" name="current_date" required>
                                        @if($errors->has('current_date'))
                                            <span class="text-danger"> {{ $errors->first('current_date') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="name">{{__('Pay to Name')}}</label>
                                        <input type="text" id="pay_name" class="form-control" value="{{ old('pay_name')}}" name="pay_name">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="Purpose">{{__('Purpose Note')}}</label>
                                        <input type="text" id="purpose" class="form-control" value="{{ old('purpose')}}" name="purpose">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="Category">{{__('Received Account')}}</label>
                                        <select  class="form-control form-select" name="credit">
                                            @if($paymethod)
                                                @foreach($paymethod as $d)
                                                    <option value="{{$d['table_name']}}~{{$d['id']}}~{{$d['head_name']}}-{{$d['head_code']}}">{{$d['head_name']}}-{{$d['head_code']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="table-responsive">
                                <table class="table table-bordered" id='account' cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>{{__('SN#')}}</th>
                                            <th>{{__('A/C Head')}}</th>
                                            <th>{{__('Amount')}}</th>
                                            <th>{{__('Lc Number')}}</th>
                                            <th>{{__('Supplier')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody style="background:#eee;">
                                        @forelse ($expense as $i=>$ex)
                                            <tr>
                                                <td style='text-align:center;'>{{++$i}}</td>
                                                <td style='text-align:left;'>
                                                    <div style='width:100%;position:relative;'>
                                                        <input type='text' readonly name='account_code[]' class=' form-control' value='{{$ex->expense?->head_code}}-{{$ex->expense?->head_name}}' style='border:none;' maxlength='100' autocomplete="off"/>
                                                    </div>
                                                        <input type='hidden' class='table_name' name='table_name[]' value='child_twos'>
                                                        <input type='hidden' class='table_id' name='table_id[]' value='{{$ex->child_two_id}}'>
                                                </td>
                                                <td style='text-align:left;'>
                                                    <input type='text' readonly name='debit[]' class=' form-control' value='{{$ex->cost_amount}}' style='text-align:center; border:none;' maxlength='15' autocomplete="off"/> 
                                                </td>
                                                <td style='text-align:left;'>
                                                    <input type='text' class=" form-control" name='lc_no[]' value='{{$ex->lot_no}}' maxlength='50' style='text-align:left;border:none;' />
                                                </td>
                                                <td style='text-align:left;'>
                                                    <input type='hidden' name='expense_id[]' value='{{$ex->id}}'>
                                                    @if($ex->purchase)
                                                        <input type='text' readonly class="form-control" style='text-align:center; border:none;' name='supplier_name[]' value='{{$ex->purchase->supplier?->supplier_name}} ({{$ex->purchase->supplier?->contact}})'/>
                                                        <input type='hidden' name='supplier_id[]' value='{{$ex->purchase->supplier_id}}'/>
                                                    @elseif($ex->regular_purchase)
                                                        <input type='text' readonly class="form-control" style='text-align:center; border:none;' name='supplier_name[]' value='{{$ex->regular_purchase->supplier?->supplier_name}} ({{$ex->regular_purchase->supplier?->contact}})'/>
                                                        <input type='hidden' name='supplier_id[]' value='{{$ex->regular_purchase->supplier_id}}'/>
                                                    @elseif($ex->beparian_purchase)
                                                        {{$ex->beparian_purchase->supplier?->supplier_name}} ({{$ex->beparian_purchase->supplier?->contact}})
                                                        <input type='text' readonly class="form-control" style='text-align:center; border:none;' name='supplier_name[]' value='{{$ex->beparian_purchase->supplier?->supplier_name}} ({{$ex->beparian_purchase->supplier?->contact}})'/>
                                                        <input type='hidden' name='supplier_id[]' value='{{$ex->beparian_purchase->supplier_id}}'/>
                                                    @endif
                                                    
                                                </td>
                                            </tr>
                                        @empty
                                            <tr  class="text-center">
                                                <td colspan="4">No Data Found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th style="text-align:right;" colspan="2">{{__('Total Amount Tk.')}}</th>
                                            <th><input type='text' class='form-control' name='debit_sum' id='debit_sum' value='{{$expense->sum('cost_amount')}}' style='text-align:center; border:none;' readonly autocomplete="off" /></th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>


                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group @if($errors->has('name')) has-error @endif">
                                        <label>{{__('Cheque No')}}</label>
                                        <span class="block input-icon input-icon-right">
                                            <input type="text" class="form-control" name="cheque_no" value="{{old('cheque_no')}}">
                                            @if($errors->has('cheque_no')) 
                                            <i class="ace-icon fa fa-times-circle"></i>
                                            @endif
                                        </span>
                                        @if($errors->has('cheque_no')) 
                                            <div class="help-block col-sm-reset">
                                            {{ $errors->first('cheque_no') }}
                                            </div>
                                        @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group">
                                        <label>{{__('Bank Name')}}</label>
                                        <input type="text" class="form-control" name="bank" value="{{old('bank')}}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group">
                                            <label>{{__('Cheque Date')}}</label>
                                            <input type="date" class="form-control" name="cheque_dt" >
                                            
                                            @if($errors->has('cheque_dt')) 
                                                <div class="help-block col-sm-reset">
                                                    {{ $errors->first('cheque_dt') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-sm-12 text-right">
                                        <input class="form-control" type="file" name="slip">
                                    </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">{{__('Submit')}}</button>
                                    
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
	
    function removeChar(item){ 
    	var val = item.value;
      	val = val.replace(/[^.0-9]/g, "");  
      	if (val == ' '){val = ''};   
      	item.value=val;
    }
    function sum_of_debit(){
    	$.total_debit=0;
    	
    	/* Debit SUM */
    	$(".cls_debit").each(function(){
    		var debit_amount=$(this).val();
    		$.total_debit+=Number(debit_amount);
    	});
    	/* Debit SUM */
    	
    	$("#debit_sum").val($.total_debit);	
    }
    
    function debit_entry(inc){
    	if($(inc).parents('tr').find('.cls_account_code').val()!=''){
    		var debit_amount = Number($(inc).val());
			$(inc).parents('tr').find('.cls_credit').val('');
			sum_of_debit();
    	}else {
    		alert("Please Enter Account Code");
    		$(inc).val('');
    		sum_of_debit();
    		$(inc).parents('tr').find('.cls_account_code').focus();
    	}
    }
</script>
@endpush
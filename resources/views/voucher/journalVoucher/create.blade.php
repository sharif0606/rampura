@extends('layout.app')

@section('pageTitle',trans('Create Journal Voucher'))
@section('pageSubTitle',trans('Create'))

@section('content')
  <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <h4 class="card-title text-center">{{__('Journal Voucher Entry')}}</h4>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="forms-sample" method="post" action="{{route(currentUser().'.journal.store')}}" enctype="multipart/form-data">
                                @csrf 
                                <div class="col-sm-12">
                                    <div class="widget" style="min-height:500px;">
                                        <div class="widget-content padding">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group ">
                                                            <label>Voucher No</label>
                                                            <span class="block input-icon input-icon-right">
                                                                <input type="text" class="form-control" name="voucher_no" value="" readonly>
                                                            </span>
                                                        </div>
                                                    </div>	
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Date</label>
                                                            <input type="date" class="form-control" name="current_date" value="{{old('current_date')}}" required>
                                                            @if($errors->has('current_date')) 
                                                                <div class="help-block col-sm-reset">
                                                            {{ $errors->first('current_date') }}
                                                                </div>
                                                        @endif
                                                        </div>
                                                    </div>	
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <input type="text" class="form-control" name="pay_name" value="{{old('pay_name')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Purpose</label>
                                                            <input type="text" class="form-control" name="purpose" value="{{old('purpose')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-sm-6">
                                                        <div class="form-group">
                                                            <label>LC Number</label>
                                                            <input type="text" class="form-control" name="lc_no" value="{{old('lc_no')}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-bordered" id='account'>
                                                    <thead>
                                                        <tr>
                                                            <th>SN#</th>
                                                            <th>A/C Head</th>
                                                            <th>Debit (Tk)</th>
                                                            <th>Credit (Tk)</th>
                                                            <th>Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th style="text-align:right;" colspan="2">Total Amount Tk.</th>
                                                            <th><input type='text' name='debit_sum' id='debit_sum' value='' style='text-align:center; border:none;' readonly autocomplete="off" /></th>
                                                            <th><input type='text' name='credit_sum' id='credit_sum' value='' style='text-align:center; border:none;' readonly autocomplete="off" /></th>
                                                            <th></th>
                                                        </tr>
                                                        <tr>
                                                            <th style="text-align:right;" colspan="5">
                                                                <input type='button' class='btn btn-primary' value='Add' onClick='add_row();'>
                                                                <input type='button' class='btn btn-danger' value='Remove' onClick='remove_row();'>
                                                            </th>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody style="background:#eee;">
                                                        <tr>
                                                            <td style='text-align:center;'>1</td>
                                                            <td style='text-align:left;'>
                                                                <div style='width:100%;position:relative;'>
                                                                    <input type='text' name='account_code[]' class='cls_account_code form-control' value='' style='border:none;' onkeyup="get_head(this);" maxlength='100' autocomplete="off"/>
                                                                    <div class="sugg" style='display:none;'>
                                                                        <div style='border:1px solid #aaa;'></div>
                                                                    </div>
                                                                </div>
                                                                    <input type='hidden' class='table_name' name='table_name[]' value=''>
                                                                    <input type='hidden' class='table_id' name='table_id[]' value=''>
                                                            </td>
                                                            <td style='text-align:left;'>
                                                                <input type='text' name='debit[]' class='cls_debit form-control' value='' style='text-align:center; border:none;' maxlength='15' onkeyup='removeChar(this)' onBlur='return debit_entry(this);' autocomplete="off"/> 
                                                            </td>
                                                            <td style='text-align:left;'>
                                                                <input type='text' name='credit[]' class='cls_credit form-control' value='' style='text-align:center; border:none;' maxlength='15' onkeyup='removeChar(this)' onBlur='return credit_entry(this);' autocomplete="off" /> 
                                                                <input type='hidden' name='jobinc[]' class='jobinc' value='1'>
                                                                <input type='hidden' name='bkdn_id[]' value='' />
                                                            </td>
                                                            <td style='text-align:left;'><input type='text' class=" form-control" name='remarks[]' value='' maxlength='50' style='text-align:left;border:none;' /></td>
                                                        </tr>
                                                        <tr>
                                                            <td style='text-align:center;'>2</td>
                                                            <td style='text-align:left;'>
                                                                <div style='width:100%;position:relative;'>
                                                                    <input type='text' name='account_code[]' class='cls_account_code form-control' value='' style='border:none;' onkeyup="get_head(this)" maxlength='100' autocomplete="off"/>
                                                                    <div class="sugg" style='display:none;'>
                                                                        <div style='border:1px solid #aaa;'></div>
                                                                    </div>
                                                                </div>
                                                                    <input type='hidden' class='table_name' name='table_name[]' value=''>
                                                                    <input type='hidden' class='table_id' name='table_id[]' value=''>
                                                            </td>
                                                            <td style='text-align:left;'>
                                                                <input type='text' name='debit[]' class='cls_debit form-control' value='' style='text-align:center; border:none;' maxlength='15' onkeyup='removeChar(this)' onBlur='return debit_entry(this);' autocomplete="off"/> 
                                                            </td>
                                                            <td style='text-align:left;'>
                                                                <input type='text' name='credit[]' class='cls_credit form-control' value='' style='text-align:center; border:none;' maxlength='15' onkeyup='removeChar(this)' onBlur='return credit_entry(this);' autocomplete="off" /> 
                                                                <input type='hidden' name='jobinc[]' class='jobinc' value='2'>
                                                                <input type='hidden' name='bkdn_id[]' value='' />
                                                            </td>
                                                            <td style='text-align:left;'><input type='text' name='remarks[]' class=' form-control' value='' maxlength='50' style='text-align:left;border:none;' /></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <br>
                                            <div class="form-group">
                                                <div class="row">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group @if($errors->has('name')) has-error @endif">
                                                    <label>Cheque No</label>
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
                                                    <label>Bank Name</label>
                                                    <input type="text" class="form-control" name="bank" value="{{old('bank')}}">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group">
                                                    <label>Cheque Date</label>
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
                                                    <input name="slip" type="file" name="slip">
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
		                            </div>
	                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- // Basic multiple Column Form section end -->
</div>
@endsection

@push('scripts')
<script>
	function add_row(){

		var row="<tr>\
					<td style='text-align:center;'>"+(parseInt($("#account tbody tr").length) + 1)+"</td>\
					<td style='text-align:left;'>\
						<div style='width:100%;position:relative;'>\
							<input type='text' name='account_code[]' class='cls_account_code form-control' value='' style='border:none;' onkeyup='get_head(this)' maxlength='100' autocomplete='off'/>\
							<div class='sugg' style='display:none;'>\
								<div style='border:1px solid #aaa;'></div>\
							</div>\
						</div>\
							<input type='hidden' class='table_name' name='table_name[]' value=''>\
							<input type='hidden' class='table_id' name='table_id[]' value=''>\
					</td>\
					<td style='text-align:left;'>\
						<input type='text' name='debit[]' class='cls_debit form-control' value='' style='text-align:center; border:none;' maxlength='15' onkeyup='removeChar(this)' onBlur='return debit_entry(this);' autocomplete='off'/> \
					</td>\
					<td style='text-align:left;'>\
						<input type='text' name='credit[]' class='cls_credit form-control' value='' style='text-align:center; border:none;' maxlength='15' onkeyup='removeChar(this)' onBlur='return credit_entry(this);' autocomplete='off' /> \
						<input type='hidden' name='jobinc[]' class='jobinc' value='2'>\
						<input type='hidden' name='bkdn_id[]' value='' />\
					</td>\
					<td style='text-align:left;'><input type='text' name='remarks[]' value='' class=' form-control' maxlength='50' style='text-align:left;border:none;' /></td>\
				</tr>";
		$('#account tbody').append(row);
	}

	function remove_row(){
		$('#account tbody tr').last().remove();
	}
    
    function get_head(code){
	    if($(code).val()!=""){
            $.getJSON( "{{route(currentUser().'.journal_get_head')}}",{'code':$(code).val()}, function(j){
	            if(j.length>0){
            		var data			= '';
            		var table_name 		= '';
            		var table_id 		= '';
            		var display_value 	= '';
		
            		for (var i = 0; i < j.length; i++) {
            			var table_name 		= j[i].table_name;
            			var table_id 		= j[i].table_id;
            			var display_value 	= j[i].display_value;
            			data += '<div style="cursor: pointer;padding:5px 10px;border-bottom:1px solid #aaa" class="item" align="left" onClick="account_code_fill(\''+display_value+'\',this,\''+table_name+'\','+table_id+');"><b>'+display_value+'</b></div>';
		
            		}
		
            		$(code).next().find('div').html(data);
            		$(code).next().find('div').css('background-color', '#FFFFE0');
            		$(code).next().fadeIn("slow");
	            }else{
            		$(code).parents('td').find('.table_name').val('');
            		$(code).parents('td').find('.table_id').val('');
            		$(code).val('');
            		$(code).css('background-color', '#D9A38A');
            		$(code).next().fadeOut();
            	}
            });		
        }else {
            $(code).parents('td').find('.table_name').val('');
            $(code).parents('td').find('.table_id').val('');
            $(code).val('');
            $(code).css('background-color', '#D9A38A');
            $(code).next().fadeOut();
        }
    }
    
    function account_code_fill(value,code,tablename,tableid) {
    	$(code).parents('td').find('.cls_account_code').css('background-color', '#FFFFE0');
    	$(code).parents('td').find('.cls_account_code').val(value);
    	$(code).parents('td').find('.table_name').val(tablename);
    	$(code).parents('td').find('.table_id').val(tableid);
    
    	$(code).parents('td').find('.sugg').fadeOut();
    	$(code).parents('td').find('.cls_account_code').focus();
    }
    function removeChar(item){ 
    	var val = item.value;
      	val = val.replace(/[^.0-9]/g, "");  
      	if (val == ' '){val = ''};   
      	item.value=val;
    }
    function sum_of_debit_credit(){
    	$.total_debit=0;
    	$.total_credit=0;
    	
    	/* Debit SUM */
    	$(".cls_debit").each(function(){
    		var debit_amount=$(this).val();
    		$.total_debit+=Number(debit_amount);
    	});
    	/* Debit SUM */
    	
    	/* Credit SUM */
    	$(".cls_credit").each(function(){
    		var credit_amount=$(this).val();
    		$.total_credit+=Number(credit_amount);
    	});
    	/* Credit SUM */
    	
    	$("#debit_sum").val($.total_debit);
    	$("#credit_sum").val($.total_credit);	
    }
    
    function debit_entry(inc){
    	if($(inc).parents('tr').find('.cls_account_code').val()!=''){
    		var debit_amount = Number($(inc).val());
			$(inc).parents('tr').find('.cls_credit').val('');
			sum_of_debit_credit();
    	}else {
    		alert("Please Enter Account Code");
    		$(inc).val('');
    		sum_of_debit_credit();
    		$(inc).parents('tr').find('.cls_account_code').focus();
    	}
    }
    
    function credit_entry(inc){
    	if($(inc).parents('tr').find('.cls_account_code').val()!=''){
    		var credit_amount = Number($(inc).val());
			$(inc).parents('tr').find('.cls_debit').val('');
			sum_of_debit_credit();
    		
    	}
    	else {
    		alert("Please Enter Account Code");
    		$(inc).val('');
    		sum_of_debit_credit();
    		$(inc).parents('tr').find('.cls_account_code').focus();
    	}
    }
</script>
@endpush
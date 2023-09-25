@extends('layout.app')
@section('title','Balance Sheet Report')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
@section('content')
<div class="page-header">
    <h1>
	    Head Wise Report
        <small>
            <i class="ace-icon fa fa-angle-double-left"></i>
            Report
        </small>
        <small>
            <i class="ace-icon fa fa-angle-double-left"></i>
            Account
        </small>
    </h1>
</div><!-- /.page-header -->
<div class="row">
	<div class="col-12">
    	@if(Session::has('response'))
            <div class="alert alert-{{Session::get('response')['class']}}">
                {{Session::get('response')['message']}}
            </div>
        @endif
        <!-- PAGE CONTENT BEGINS -->
        <div class="widget-box">
			<div class="widget-header widget-header-small">
				<h5 class="widget-title lighter">Head Wise Report Search</h5>
			</div>
			<div class="widget-body">
				<div class="widget-main">
				    <form method="get" action="">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label for="accDate">Acc Head</label>
									<select name="head_id" id="head_id" class="form-control">
									    @if($headlists)
									        @foreach($headlists as $h)
									            @if($h->masterhead_id)
									                <option value="{{$h->master_head->id}}-masterhead_id-{{$h->master_head->opening_balance}}" @if($head_id==$h->master_head->id."masterhead_id") selected @endif>{{$h->master_head->head_name}}</option>
									            @elseif($h->subhead_id && $h->sub_head)
									                <option value="{{$h->sub_head->id}}-subhead_id-{{$h->sub_head->opening_balance}}" @if($head_id==$h->sub_head->id."subhead_id") selected @endif>{{$h->sub_head->head_name}}</option>
									            @elseif($h->chieldheadone_id && $h->chield_one)
									                <option value="{{$h->chield_one->id}}-chieldheadone_id-{{$h->chield_one->opening_balance}}" @if($head_id==$h->chield_one->id."chieldheadone_id") selected @endif>{{$h->chield_one->head_name}}</option>
									            @elseif($h->chieldheadtwo_id && $h->chield_two)
									                <option value="{{$h->chield_two->id}}-chieldheadtwo_id-{{$h->chield_two->opening_balance}}" @if($head_id==$h->chield_two->id."chieldheadtwo_id") selected @endif>{{$h->chield_two->head_name}}</option>
									           @endif
									        @endforeach
									    @endif
								    </select>
								</div>
							</div>
							
							<div class="col-sm-4">
								<div class="form-group">
									<label for="accDate">Year</label>
									<div class="input-group">
									    <input class="form-control date-picker" name="current_date" type="text" data-date-format="dd-mm-yyyy" required />
									    <span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>
									</div>
								</div>
							</div>
							<div class="col-sm-4 pt-4">
								<button class="btn btn-primary btn-sm" type="submit"> Get Report </button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
    <div class="col-12">
        <!-- PAGE CONTENT BEGINS -->
        <table class="table table-bordered table-hover" width="100%">
			<thead>
				<tr>
					<td>Trans Date</td>
					<td>Entry Date</td>
					<td>Voucher No</td>
					<td>Particulars</td>
					<td>Debit</td>
					<td>Credit</td>
					<td>Balance</td>
					<td>Details</td>
				</tr>
			</thead>
			<tbody>
			    @php $balance=$deb=$cre=0; @endphp
			    @if($accData)
			        <tr>
						<td>{{date("d M, Y",strtotime($startDate))}}</td>
						<td></td>
						<td></td>
						<td>B/F</td>
						<td></td>
						<td></td>
						<td>
						    @if($accOldData->sum('dr')>$accOldData->sum('cr'))
						        {{ ($accOldData->sum('dr') - $accOldData->sum('cr')) }} DR
						        @php $balance+= ($accOldData->sum('dr') - $accOldData->sum('cr')); @endphp
						    @elseif($accOldData->sum('dr')<$accOldData->sum('cr'))
						        {{ ($accOldData->sum('cr') - $accOldData->sum('dr')) }} CR
						        @php $balance+= ($accOldData->sum('dr') - $accOldData->sum('cr')); @endphp
						    @else
						            0
						    @endif
						</td>
						<td></td>
					</tr>
					<?php
					    foreach($accData as $acc){
                            if($acc->dr>0){ $balance+=$acc->dr;}
                            if($acc->cr>0){ $balance-=$acc->cr;}
			        ?>
        				<tr>
        					<td>{{date("d M, Y",strtotime($acc->v_date))}}</td>
        					<td>{{date("d M, Y",strtotime($acc->created_at))}}</td>
        					<td>{{$acc->jv_id}}</td>
        					<td>{{$acc->journal_title}}</td>
        					<td>{{$acc->dr}} @php $deb+=$acc->dr; @endphp</td>
        					<td>{{$acc->cr}} @php $cre+=$acc->cr; @endphp</td>
        					<td>{{$balance>0?abs($balance)." DR":abs($balance)." CR"}}</td>
        					<td><a href="#"></a></td>
        				</tr>
				    <?php } ?>
				@endif
			</tbody>
			<tfoot>
				<tr>
					<th colspan="4" style="text-align:right">Total</th>
					<th><?= $deb ?></th>
					<th><?= $cre ?></th>
					<th>{{$balance>0?abs($balance)." DR":abs($balance)." CR"}}</th>
					<th></th>
				</tr>
			</tfoot>
		</table>
        
    </div>
</div>

@endsection

@push('script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    window.onload=function(){
        
        jQuery(function($) {
    		//to translate the daterange picker, please copy the "examples/daterange-fr.js" contents here before initialization
    		$('.date-picker').daterangepicker({
    		    'startDate': "{{date('d-m-Y',strtotime($startDate))}}",
                'endDate': "{{date('d-m-Y',strtotime($endDate))}}",
    			'applyClass' : 'btn-sm btn-success',
    			'cancelClass' : 'btn-sm btn-default',
    			locale: {applyLabel: 'Apply',cancelLabel: 'Cancel',format:'DD-MM-YYYY',separator: ' / '},
    			ranges: {
    			   'Today': [moment(), moment()],
    			   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    			   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
    			   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
    			   'This Month': [moment().startOf('month'), moment().endOf('month')],
    			   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    			}
    		}).prev().on(ace.click_event, function(){$(this).next().focus();});
            
    		setTimeout(function() {$('.alert').hide('slowly');},3000);
    	});
    }
</script>
@endpush

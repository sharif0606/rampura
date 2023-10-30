@extends('layout.app')
@section('pageTitle',trans('Head Wise Report'))
@section('pageSubTitle',trans('Reports'))

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
@section('content')
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
				<div class="text-end">
                    <button type="button" class="btn btn-info btn-selected" id="addCompanyDescription" onclick="printReport('result_show')">Print</button>
                </div>
                <div class="card-content">

					@if(Session::has('response'))
						<div class="alert alert-{{Session::get('response')['class']}}">
							{{Session::get('response')['message']}}
						</div>
					@endif
			
					{{-- <div class="text-center"><h4>Head Wise Report Search</h4></div> --}}
					<div class="card-body" id="result_show">
						<table style="width: 100%" id="companyDescript">
                            <tr style="text-align: center;">
                                <th colspan="2">
                                    <h4>M/S. RAMPURA SYNDICATE</h4>
                                    <p>R.S TOWER 193, KHATUNGONJ, CHATTOGRAM</p>
                                    <p>IMPORT, EXPORTER, WHOLESALER, RETAILSALER & COMMISSION AGENT</p>
                                    <p>E-MAIL: <a href="#" style="border-bottom: solid 1px; border-color:blue;">rampursyndicate@yahoo.com</a> Contact: +88 01707-377372 & +88 01758-982661</p>
                                    <h6 style="padding-bottom: 2.5rem;">Head Wise Report</h6>
                                </th>
                            </tr>
                        </table>
						<form method="get" action="" id="form-hide">
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<label for="accDate">Acc Head</label>
										<select name="head_id" id="head_id" class="form-control choices">
											@if($headlists)
												@foreach($headlists as $h)
													@if($h->master_account_id)
														<option value="{{$h->master_head->id}}-master_account_id-{{$h->master_head->opening_balance}}" @if($head_id==$h->master_head->id."master_account_id") selected @endif>{{$h->master_head->head_name}}</option>
													@elseif($h->sub_head_id && $h->sub_head)
														<option value="{{$h->sub_head->id}}-sub_head_id-{{$h->sub_head->opening_balance}}" @if($head_id==$h->sub_head->id."sub_head_id") selected @endif>{{$h->sub_head->head_name}}</option>
													@elseif($h->child_one_id && $h->chield_one)
														<option value="{{$h->chield_one->id}}-child_one_id-{{$h->chield_one->opening_balance}}" @if($head_id==$h->chield_one->id."child_one_id") selected @endif>{{$h->chield_one->head_name}}</option>
													@elseif($h->child_two_id && $h->chield_two)
														<option value="{{$h->chield_two->id}}-child_two_id-{{$h->chield_two->opening_balance}}" @if($head_id==$h->chield_two->id."child_two_id") selected @endif>{{$h->chield_two->head_name}}</option>
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
											<input class="form-control date-picker" name="current_date" id="inputDate" type="text" data-date-format="dd-mm-yyyy" required />
											<span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>
										</div>
									</div>
								</div>
								<div class="col-sm-4 pt-4">
									<button class="btn btn-primary btn-sm" type="submit"> Get Report </button>
								</div>
							</div>
						</form>
					
		
						<div class="tbl_scroll">
							<div class="d-flex justify-content-between mb-2" id="print-content"></div>
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
										@foreach($accData as $acc)
											@if($acc->dr>0)@php $balance+=$acc->dr; @endphp @endif
											@if($acc->cr>0)@php $balance-=$acc->cr; @endphp @endif
											<tr>
												<td>{{date("d M, Y",strtotime($acc->rec_date))}}</td>
												<td>{{date("d M, Y",strtotime($acc->created_at))}}</td>
												<td>{{$acc->jv_id}}</td>
												<td>{{$acc->journal_title}}</td>
												<td>{{$acc->dr}} @php $deb+=$acc->dr; @endphp</td>
												<td>{{$acc->cr}} @php $cre+=$acc->cr; @endphp</td>
												<td>{{$balance>0?abs($balance)." DR":abs($balance)." CR"}}</td>
												<td><a href="#"></a></td>
											</tr>
										@endforeach
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
				</div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
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
    		}).prev().on(click, function(){$(this).next().focus();});
            
    		setTimeout(function() {$('.alert').hide('slowly');},3000);
    	});
    }
</script>
<script>
    function printReport(divName) {
        $('.acc-head-report').removeClass('d-none');
        var selectedValue = $('#head_id option:selected').text();
        var inputDate = $('#inputDate').val();

		var printContentDiv = document.getElementById('print-content');
        printContentDiv.innerHTML = '<label>Head Name: ' + selectedValue + '</label><label>Year: ' + inputDate + '</label>';
        var prtContent = document.getElementById(divName);

        var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
        WinPrint.document.write('<link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}" type="text/css"/>');

		var style = '<style>#form-hide { display: none; }</style>';
        WinPrint.document.write(style);

		WinPrint.document.write(printContentDiv.innerHTML);
        WinPrint.document.write(prtContent.innerHTML); // Include the rest of the content
        WinPrint.document.close();
        WinPrint.onload = function () {
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        }
    }
</script>
@endpush

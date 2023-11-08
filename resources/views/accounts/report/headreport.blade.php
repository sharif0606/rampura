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
                    <a href="#" class="no_print float-end" title="print" id="addCompanyDescription" onclick="printReport('result_show')"><svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 16 16"><g fill="currentColor"><path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/><path d="M4.603 12.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102c.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645a19.701 19.701 0 0 0 1.062-2.227a7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136c.075-.354.274-.672.65-.823c.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538c.007.187-.012.395-.047.614c-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686a5.753 5.753 0 0 1 1.334.05c.364.065.734.195.96.465c.12.144.193.32.2.518c.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416a.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.716 5.716 0 0 1-.911-.95a11.642 11.642 0 0 0-1.997.406a11.311 11.311 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238c-.328.194-.541.383-.647.547c-.094.145-.096.25-.04.361c.01.022.02.036.026.044a.27.27 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.647 12.647 0 0 1 1.01-.193a11.666 11.666 0 0 1-.51-.858a20.741 20.741 0 0 1-.5 1.05zm2.446.45c.15.162.296.3.435.41c.24.19.407.253.498.256a.107.107 0 0 0 .07-.015a.307.307 0 0 0 .094-.125a.436.436 0 0 0 .059-.2a.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.881 3.881 0 0 0-.612-.053zM8.078 5.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198a.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283c-.04.192-.03.469.046.822c.024.111.054.227.09.346z"/></g></svg></a>
                </div>
                <div class="card-content">

					@if(Session::has('response'))
						<div class="alert alert-{{Session::get('response')['class']}}">
							{{Session::get('response')['message']}}
						</div>
					@endif
			
					{{-- <div class="text-center"><h4>Head Wise Report Search</h4></div> --}}
					<div class="card-body" id="result_show">
						<div class="d-none only_print">
							<table style="width: 100%" id="companyDescript">
								<tr style="text-align: center;">
									<th colspan="2">
										<h4>M/S. RAMPURA SYNDICATE</h4>
										<p>R.S TOWER 193, KHATUNGONJ, CHATTOGRAM</p>
										<p>IMPORT, EXPORTER, WHOLESALER, RETAILSALER & COMMISSION AGENT</p>
										<p>E-MAIL: <a href="#" style="border-bottom: solid 1px; border-color:blue;">rampursyndicate@yahoo.com</a> Contact: +88 01707-377372 & +88 01758-982661</p>
										<h4 style="padding-bottom: 2.5rem;">Head Wise Report</h4>
									</th>
								</tr>
							</table>
						</div>
						<form method="get" action="" id="form_hide" class="no_print">
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
									@php if($opening_bal>0) $deb=$opening_bal; else $cre=$opening_bal; @endphp
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
						<div class="d-none only_print text-center">
							<table style="width: 100%; margin-top: 4rem;">
								<tr style="padding-top: 5rem;">
									<th style="text-align: center;"><h6>PREPARED BY</h6></th>
									<th style="text-align: center;"><h6>CHECKED BY</h6></th>
									<th style="text-align: center;"><h6>VERIFIED BY</h6></th>
								</tr>
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

		var style = '<style media="print">.no_print{ display:none}body{color:#000 !important;background-color:#FFF; font-size:14px; padding-top:50px}.only_print{ display:block !important;}</style>';
        WinPrint.document.write(style);

		//WinPrint.document.write(printContentDiv.innerHTML);
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

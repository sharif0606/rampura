@extends('layout.app')
@section('title','Balance Sheet Report')
@section('content')
<div class="page-header">
    <h1>
	Balance Sheet Report
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            Balance Sheet Report
        </small>
    </h1>
</div><!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
	@if(Session::has('response'))
        <div class="alert alert-{{Session::get('response')['class']}}">
        {{Session::get('response')['message']}}
        </div>
    @endif
        <!-- PAGE CONTENT BEGINS -->
        <div class="widget-box">
			<div class="widget-header widget-header-small">
				<h5 class="widget-title lighter">Balance Sheet Report Search</h5>
			</div>
			<div class="widget-body">
				<div class="widget-main">
				    <form method="get" action>
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label for="accDate">Year</label>
                        			<select name="current_year" class="form-control">
                        				<?php for($y=2018;$y<= date('Y');$y++){ ?>
                        				<option value="<?= $y ?>" <?= $cy==$y?"selected":"" ?>><?= $y ?></option>
                        				<?php } ?>
                        			</select>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="accDate">Month</label>
                        			<select name="current_month" class="form-control">
                        				<?php  for($m=1; $m<=12; ++$m){ ?>
                        				<option value="<?= date('m', mktime(0, 0, 0, $m, 1)) ?>" <?= date('m', mktime(0, 0, 0, $m, 1))==$cm?"selected":"" ?>><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                        				<?php } ?>
                        			</select>
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
	
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <table class="table table-bordered table-hover" width="100%">
			<tbody>
				<tr>
					<td style="vertical-align:top;padding: 0px;border:0px">

						<table class="table table-bordered table-hover" width="100%" style="margin-bottom:0;border:0px">
							<thead>
								<tr>
									<th width="60%">&nbsp;&nbsp;Fund, Liablities & Owner Equity</th>
									<th width="20%">Previous Year</th>
									<th width="20%">Current Fiscal Year</th>
								</tr>
							</thead>				
<tbody>
@php 
	$subhead=\App\Models\Subhead::whereIn('masterhead_id',[2,3])->pluck('head_name','id')->toArray();
@endphp	
    @if($subhead)
	    @foreach($subhead as $shk=>$sh)
	        @php
			    $childone=\App\Models\Chieldheadone::where('subhead_id',$shk)->pluck('head_name','id')->toArray();
			@endphp
			@if($childone)
			    <tr>
					<td><b>{{$sh}}</b></td>
					<td></td>
					<td></td>
				</tr>
				
			    @foreach($childone as $cok=>$co)
					@php
					    $childtwo=\App\Models\Chieldheadtwo::where('chieldheadone_id',$cok)->pluck('head_name','id')->toArray();
					@endphp
					@if($childtwo)
    					<tr>
    						<td> &nbsp;&nbsp;&nbsp;&nbsp;<b>{{$co}}</b></td>
    						<td></td>
    						<td></td>
    					</tr>
					    @foreach($childtwo as $ctk=>$ct)
					        @php
                			    $datactqly=\DB::select("select sum(generalledgers.cr) as cr 
                                                from generalledgers where 
                                                chieldheadtwo_id = $ctk and $qly 
                                                group by chieldheadtwo_id");
                                $datactqy=\DB::select("select sum(generalledgers.cr)  as cr
                                                from generalledgers where 
                                                chieldheadtwo_id = $ctk and $qy 
                                                group by chieldheadtwo_id");
                            @endphp
							<tr>
								<td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$ct}}</td>
								<td>@if($datactqly) {{$datactqly[0]->cr}} @endif</td>
					            <td>@if($datactqy) {{$datactqy[0]->cr}} @endif</td>
							</tr>
					    @endforeach <!-- child two end -->
					@else <!-- child two not found -->
					    @php
            			    $datacoqly=\DB::select("select sum(generalledgers.cr) as cr 
                                            from generalledgers where 
                                            chieldheadone_id = $cok and $qly 
                                            group by chieldheadone_id");
                            $datacoqy=\DB::select("select sum(generalledgers.cr)  as cr
                                            from generalledgers where 
                                            chieldheadone_id = $cok and $qy 
                                            group by chieldheadone_id");
                        @endphp
                
					    <tr>
    						<td> &nbsp;&nbsp;&nbsp;&nbsp;{{$co}}</td>
    						<td>@if($datacoqly) {{$datacoqly[0]->cr}} @endif</td>
					        <td>@if($datacoqy) {{$datacoqy[0]->cr}} @endif</td>
    					</tr>
					@endif<!-- child two end -->
					
			    @endforeach<!-- child one end -->
			@else<!-- child one not found -->
			    @php
			    $dataqly=\DB::select("select sum(generalledgers.cr) as cr 
                                from generalledgers where 
                                subhead_id = $shk and $qly 
                                group by subhead_id");
                $dataqy=\DB::select("select sum(generalledgers.cr)  as cr
                                from generalledgers where 
                                subhead_id = $shk and $qy 
                                group by subhead_id");
                @endphp
				<tr>
					<td>{{$sh}}</td>
					<td>@if($dataqly) {{$dataqly[0]->cr}} @endif</td>
					<td>@if($dataqy){{$dataqy[0]->cr}} @endif</td>
				</tr>
			@endif<!-- child one end -->
			
	    @endforeach<!-- sub head end -->
	@endif<!-- sub head end -->
</tbody>
						</table>
						
					</td>
					<td style="vertical-align:top;padding: 0px;border:0;border-right:1px solid; width:50%">
					
						<table class="table table-bordered table-hover" width="100%" style="margin-bottom:0">
							<thead>
								<tr>
									<th width="60%">&nbsp;&nbsp;Assets</th>
									<th width="20%">Previous Year</th>
									<th width="20%">Current Fiscal Year</th>
								</tr>
							</thead>				
							
<tbody>
@php 
	$subhead=\App\Models\Subhead::whereIn('masterhead_id',[1])->pluck('head_name','id')->toArray();
@endphp	
    @if($subhead)
	    @foreach($subhead as $shk=>$sh)
	        @php
			    $childone=\App\Models\Chieldheadone::where('subhead_id',$shk)->pluck('head_name','id')->toArray();
			@endphp
			@if($childone)
			    <tr>
					<td><b>{{$sh}}</b></td>
					<td></td>
					<td></td>
				</tr>
				
			    @foreach($childone as $cok=>$co)
					@php
					    $childtwo=\App\Models\Chieldheadtwo::where('chieldheadone_id',$cok)->pluck('head_name','id')->toArray();
					@endphp
					@if($childtwo)
    					<tr>
    						<td> &nbsp;&nbsp;&nbsp;&nbsp;<b>{{$co}}</b></td>
    						<td></td>
    						<td></td>
    					</tr>
					    @foreach($childtwo as $ctk=>$ct)
					        @php
                			    $datactqly=\DB::select("select sum(generalledgers.dr) as dr 
                                                from generalledgers where 
                                                chieldheadtwo_id = $ctk and $qly 
                                                group by chieldheadtwo_id");
                                $datactqy=\DB::select("select sum(generalledgers.dr)  as dr
                                                from generalledgers where 
                                                chieldheadtwo_id = $ctk and $qy 
                                                group by chieldheadtwo_id");
                            @endphp
							<tr>
								<td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$ct}}</td>
								<td>@if($datactqly) {{$datactqly[0]->dr}} @endif</td>
					            <td>@if($datactqy) {{$datactqy[0]->dr}} @endif</td>
							</tr>
					    @endforeach <!-- child two end -->
					@else <!-- child two not found -->
					    @php
            			    $datacoqly=\DB::select("select sum(generalledgers.dr) as dr 
                                            from generalledgers where 
                                            chieldheadone_id = $cok and $qly 
                                            group by chieldheadone_id");
                            $datacoqy=\DB::select("select sum(generalledgers.dr)  as dr
                                            from generalledgers where 
                                            chieldheadone_id = $cok and $qy 
                                            group by chieldheadone_id");
                        @endphp
                
					    <tr>
    						<td> &nbsp;&nbsp;&nbsp;&nbsp;{{$co}}</td>
    						<td>@if($datacoqly) {{$datacoqly[0]->dr}} @endif</td>
					        <td>@if($datacoqy) {{$datacoqy[0]->dr}} @endif</td>
    					</tr>
					@endif<!-- child two end -->
					
			    @endforeach<!-- child one end -->
			@else<!-- child one not found -->
			    @php
			    $dataqly=\DB::select("select sum(generalledgers.dr) as dr 
                                from generalledgers where 
                                subhead_id = $shk and $qly 
                                group by subhead_id");
                $dataqy=\DB::select("select sum(generalledgers.dr)  as dr
                                from generalledgers where 
                                subhead_id = $shk and $qy 
                                group by subhead_id");
                @endphp
				<tr>
					<td>{{$sh}}</td>
					<td>@if($dataqly) {{$dataqly[0]->dr}} @endif</td>
					<td>@if($dataqy){{$dataqy[0]->dr}} @endif</td>
				</tr>
			@endif<!-- child one end -->
			
	    @endforeach<!-- sub head end -->
	@endif<!-- sub head end -->
</tbody>	
						
						</table>
					</td>
				</tr>
			</tbody>
			<!--
			<tfoot>
				<tr>
					<td style="vertical-align:top;padding: 0px;border:0px">
					
						<table class="table table-bordered table-hover" width="100%" style="margin-bottom:0;border:0px">
							<tr>
								<th width="60%"><b>Total Expenses</b></th>
								<th width="20%"><b> </b></th>
								<th width="20%"><b> </b></th>
							</tr>
							<tr>
								<th width="60%"><b>Net Profite</b></th>
								<th width="20%"><b> </b></th>
								<th width="20%"><b> </b></th>
							</tr>
						</table>
					</td>
					<td style="vertical-align:top;padding: 0px;border:0;border-right:1px solid; width:50%">
					
						<table class="table table-bordered table-hover" width="100%" style="margin-bottom:0">
							<tr>
								<th width="60%"><b>Total Income</b></th>
								<th width="20%"><b> </b></th>
								<th width="20%"><b> </b></th>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>Manager signature</td>
					<td><?= date('m/d/Y h:i:s a', time()); ?></td>
				</tr>
			</tfoot> -->
		</table>
        
    </div>

</div>

@endsection

@push('script')

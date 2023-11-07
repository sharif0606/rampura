@extends('layout.app')
@section('title','Profit Loss Report')
@section('content')
<div class="page-header">
    <h1>
	Profit Loss Report
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            Profit Loss Report
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
				<h5 class="widget-title lighter">Profit Loss Report Search</h5>
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
									<th width="60%">&nbsp;&nbsp;Expenses</th>
									<th width="20%">Current Month</th>
									<th width="20%">Current Year</th>
								</tr>
							</thead>				
							<tbody>
								<?php 
									$tey=0; // total expenses year
									$tem=0; // total expenses month
									$bal_e=0; // total balance from starting
									if($expDataYear){
										foreach($expDataYear as $edy){
											$tey+=$edy->cost+$bal_e;
								?>
								<tr>
									<td><?= $edy->head_name ?></td>
									<td>
										<?php
											if(isset($data['expDataMonth'][explode('-',$edy->head_name)[0]])){
												echo $data['expDataMonth'][explode('-',$edy->head_name)[0]];
												$tem+=$data['expDataMonth'][explode('-',$edy->head_name)[0]];
											}
										?>
									</td>
									<td><?= $edy->cost ?></td>
								</tr>
										<?php } ?>
								<?php } ?>
							</tbody>
						</table>
					</td>
					<td style="vertical-align:top;padding: 0px;border:0;border-right:1px solid; width:50%">
					
						<table class="table table-bordered table-hover" width="100%" style="margin-bottom:0">
							<thead>
								<tr>
									<th width="60%">&nbsp;&nbsp;Income</th>
									<th width="20%">Current Month</th>
									<th width="20%">Current Year</th>
								</tr>
							</thead>				
							<tbody>
								<?php 
									$tiy=0; // total income year
									$tim=0; // total income month
									$bal_i=0;
									if($incDataYear){
										foreach($incDataYear as $idy){
											$tiy+=$idy->income+$bal_i;
								?>
								<tr>
									<td><?= $idy->head_name ?></td>
									<td>
										<?php
											if(isset($data['incDataMonth'][explode('-',$idy->head_name)[0]])){ echo $data['incDataMonth'][explode('-',$idy->head_name)[0]];
											$tim+=$data['incDataMonth'][explode('-',$idy->head_name)[0]];
											}
										?>
									</td>
									<td><?= $idy->income ?></td>
								</tr>
										<?php } ?>
								<?php } ?>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td style="vertical-align:top;padding: 0px;border:0px">
					
						<table class="table table-bordered table-hover" width="100%" style="margin-bottom:0;border:0px">
							<tr>
								<th width="60%"><b>Total Expenses</b></th>
								<th width="20%"><b><?= $tem ?></b></th>
								<th width="20%"><b><?= $tey ?></b></th>
							</tr>
							<tr>
								<th width="60%"><b>Net Profite</b></th>
								<th width="20%"><b><?= $tim-$tem ?></b></th>
								<th width="20%"><b><?= $tiy-$tey ?></b></th>
							</tr>
						</table>
					</td>
					<td style="vertical-align:top;padding: 0px;border:0;border-right:1px solid; width:50%">
					
						<table class="table table-bordered table-hover" width="100%" style="margin-bottom:0">
							<tr>
								<th width="60%"><b>Total Income</b></th>
								<th width="20%"><b><?= $tim ?></b></th>
								<th width="20%"><b><?= $tiy ?></b></th>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>Manager signature</td>
					<td><?= date('m/d/Y h:i:s a', time()); ?></td>
				</tr>
			</tfoot>
		</table>
    </div>
</div>

@endsection

@push('script')

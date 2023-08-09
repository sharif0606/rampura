@extends('layout.app')
@section('pageTitle',trans('Income Statement'))
@section('pageSubTitle',trans('Statement'))

@section('content')

<!-- Bordered table start -->
<section class="section">
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="text-center">
                 <h3>Income Statement</h3>
                </div>
                <div class="row ps-2">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Month</label>
                            <select id="month" class="form-control">
                            <option value="">Select Month</option>
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-label">Year</label>
                            <select id="year" class="form-control">
                                <option value="">Select Year</option>
                                @for($i=2021; $i<= date('Y'); $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4 pt-3 mt-2">
                        <button class="btn btn-primary btn-block" type="button" onclick="get_details_report()">Get Report</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div id="details">

                        </div>

                    </div>
                </div>

                <br>

                    
            </div>
        </div>
    </div>
</section>
<!-- Bordered table end -->


@endsection

@push('scripts')
<script>
	function get_details_report() {
		var month = $('#month').val();
		var year = $('#year').val();
		if (year) {
			$.ajax({
				url: "{{route(currentUser().'.incomeStatement.details')}}",
				data: {
					'month': month,
					'year': year
				},
				dataType: 'json',
				success: function(data) {
                    console.log(data);
                    $('#details').html(data);
					result = '' + data['result'] + '';
					mainContent = '' + data['mainContent'] + '';

					if (result == 'success')
						$('#details').html(mainContent);

				},
				error: function(e) {
					console.log(e);
				}
			});
		} else {
			alert("Please select any Year");
			$('#year').focus();
		}
		return false; // keeps the page from not refreshing     
	}
</script>
@endpush
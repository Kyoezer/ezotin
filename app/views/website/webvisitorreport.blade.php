@extends('websiteadminmaster')
@section('main-content')

<h4 class="head-title">Visitor Report</h4>

<div class="row">
	<div class="col-md-4">
		@foreach($visitorYearList as $visitorYearDetails)
			<strong>{{ $visitorYearDetails->YearVisited }}</strong>
			<table class="table table-striped table-hover table-responsive">
				<thead>
					<tr class="success">
						<th>Month</th>
						<th>Number of Visits</th>
					</tr>
				</thead>

				<tbody>
					@foreach($visitorMonthList as $visitorMonthDetails)
						@if($visitorYearDetails->YearVisited == $visitorMonthDetails->YearVisited)
							<tr>
								<td>{{ $visitorMonthDetails->MonthName }}</td>
								<td>{{ $visitorMonthDetails->VisitorCount }}</td>
							</tr>
						@endif
					@endforeach
				</tbody>
			</table>
		@endforeach
	</div>
</div>

@stop
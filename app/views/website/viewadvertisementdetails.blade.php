@extends('websitemaster')
@section('main-content')
<h4 class="text-info"><strong>Advertisement Details</strong></h4>
<div class="row">
	<div class="col-md-12">
		<table class="table table-bordered table-striped table-condensed table-hover table-responsive">
			@foreach($details as $detail)
				<tr>
					<td><b>Title:</b></td>
					<td>{{ $detail->Title }}</td>
				</tr>
				<tr>
					<td><b>Content:</b></td>
					<td>{{HTML::decode($detail->Content)}}</td>
				</tr>
				<tr>
					<td><b>Image: </b></td>
					<td>
					@if(isset($detail->Image))
					<img src="{{asset($detail->Image)}}" alt="" width="400" height="400">
					@endif
					</td>
				</tr>
			@endforeach
		</table>
	</div>	
</div>
<!-- Modals Start -->

@stop
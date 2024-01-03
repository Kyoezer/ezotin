@extends('homepagemaster')
@section('content')
<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption font-green-seagreen">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject">Welcome Engineers</span>
				</div>
			</div>
			<div class="portlet-body">
				<h3>Coming Soon!</h3>
				{{--<blockquote>--}}
					{{--<p class="text-justify">--}}
						{{--Welcome to Engineer Registration--}}
					{{--</p>--}}
				{{--</blockquote>--}}
				{{--<div class="col-md-12">--}}
					{{--<h4 class="font-green-seagreen">Fee Structure for New Registration</h4>--}}
					{{--<table class="table table-bordered table-striped table-condensed">--}}
						{{--<thead>--}}
							{{--<tr>--}}
								{{--<th>Engineer Type</th>--}}
								{{--<th class="text-center" width="20%">Validity (yrs)</th>--}}
								{{--<th class="text-right">Amount (Nu.)</th>--}}
							{{--</tr>--}}
						{{--</thead>--}}
						{{--<tbody>--}}
							{{--<tr>--}}
								{{--<td>Goverment</td>--}}
								{{--<td class="text-center">--}}
									{{--{{$feeDetails[0]->EngineerGovtValidity}}--}}
								{{--</td>--}}
								{{--<td class="text-right">--}}
									{{--Free--}}
								{{--</td>--}}
							{{--</tr>--}}
							{{--<tr>--}}
								{{--<td>Private</td>--}}
								{{--<td class="text-center">--}}
									{{--{{$feeDetails[0]->EngineerPvtValidity}}--}}
								{{--</td>--}}
								{{--<td class="text-right">--}}
									{{--{{$feeDetails[0]->EngineerPvtAmount}}--}}
								{{--</td>--}}
							{{--</tr>--}}
						{{--</tbody>--}}
					{{--</table>--}}
				{{--</div>	--}}
				{{--<div class="form-actions">--}}
					{{--<div class="row">--}}
						{{--<div class="col-md-12">--}}
							{{--<a href="{{URL::to('engineer/registration')}}" class="btn green-seagreen btn-lg"><i class="fa fa-plus"></i> Proceed to Registration</a>--}}
							{{--<a href="{{URL::to('/')}}" class="btn grey-cascade btn-lg"><i class="fa fa-home"></i> Home</a>--}}
						{{--</div>--}}
					{{--</div>--}}
				{{--</div>--}}
			</div>
		</div>
	</div>
</div>
@stop
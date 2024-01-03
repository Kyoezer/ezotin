@extends('homepagemaster')
@section('content')
<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption font-green-seagreen">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject">Welcome Specialized Trades</span>
				</div>
			</div>
			<div class="portlet-body">
				<blockquote>
					<p class="text-justify">
						Welcome to Specialized Trade Registration.
					</p>
				</blockquote>
				<div class="col-md-6">
				<h4 class="font-green-seagreen">Fee Structure for New Registration</h4>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Amount (Nu.)</th>
								<th>Validity (yrs)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									Free
								</td>
								<td>
									{{$feeDetails[0]->SpecializedTradeValidity}}
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-md-12">
					<p class="bold"><i>The validity of the registration certificate will be for 3 years and the registration fee is not applicable for first time applicant. However, the first renewal fee is  Nu. 1000/- and Nu. 500/- thereafter.</i></p>	
				</div>
				<div class="form-actions">
					<div class="row">
						<div class="col-md-12">
							<a href="{{URL::to('specializedtrade/registration')}}" class="btn green-seagreen btn-lg"><i class="fa fa-plus"></i> Proceed to Registration</a>
							<a href="{{URL::to('/')}}" class="btn grey-cascade btn-lg"><i class="fa fa-home"></i> Home</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>	
@stop
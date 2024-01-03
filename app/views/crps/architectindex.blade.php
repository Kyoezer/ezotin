@extends('homepagemaster')
@section('content')
<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption font-green-seagreen">
					<i class="fa fa-cogs"></i>
						Welcome Architects
				</div>
			</div>
			<div class="portlet-body">
				<blockquote>
					<p class="text-justify">
						Welcome to Architect Registration.
					</p>
				</blockquote>
				<div class="col-md-12">
					<h4 class="font-green-seagreen">Fee Structure for New Registration</h4>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Architect Type</th>
								<th class="text-center" width="20%">Validity (yrs)</th>
								<th class="text-right">Amount (Nu.)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Goverment</td>
								<td class="text-center">
									{{$feeDetails[0]->ArchitectGovtValidity}}
								</td>
								<td class="text-right">
									Free
								</td>
							</tr>
							<tr>
								<td>Private</td>
								<td class="text-center">
									{{$feeDetails[0]->ArchitectPvtValidity}}
								</td>
								<td class="text-right">
									{{number_format($feeDetails[0]->ArchitectPvtAmount,2)}}
								</td>
							</tr>
						</tbody>
					</table>
				</div>	
				<div class="form-actions">
					<div class="row">
						<div class="col-md-12">
							<a href="{{URL::to('architect/registration')}}" class="btn green-seagreen btn-lg"><i class="fa fa-plus"></i> Proceed to Registration</a>
							<a href="{{URL::to('/')}}" class="btn grey-cascade btn-lg"><i class="fa fa-home"></i> Home</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
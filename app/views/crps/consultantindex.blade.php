@extends('homepagemaster')
@section('content')
<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption font-green-seagreen">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject">Welcome Consultants</span>
				</div>
			</div>
			<div class="portlet-body">
				<blockquote>
					<p class="text-justify">
						Welcome to Consultant Registration.
					</p>
				</blockquote>
				<div class="col-md-12 table-responsive">
					<h4 class="font-green-seagreen">Fee Structure for New Registration</h4>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Service</th>
								<th class="text-center">Validity (years)</th>
								<th>Fee</th>
							</tr>
						</thead>
						<tbody>
							@foreach($feeStructures as $feeStructure)
							<tr>
								<td>{{$feeStructure->Name}}</td>
								<td class="text-center">
									{{$feeStructure->ConsultantValidity}}
								</td>
								<td>
									{{number_format($feeStructure->ConsultantAmount,2)}} per category service
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>	
				<div class="form-actions">
					<div class="row">
						<div class="col-md-12">
							<a href="{{URL::to('consultant/generalinforegistration')}}" class="btn green-seagreen btn-lg"><i class="fa fa-plus"></i> Proceed to Registration</a>
							<a href="{{URL::to('/')}}" class="btn grey-cascade btn-lg"><i class="fa fa-home"></i> Home</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
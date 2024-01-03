@extends('homepagemaster')
@section('content')
<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption font-green-seagreen">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject">Welcome Contractors</span>
				</div>
			</div>
			<div class="portlet-body">
				<blockquote>
					<p class="text-justify">
						Welcome to Contractor Registration.
					</p>
				</blockquote>
				<div class="col-md-12 table-responsive">
					<h4 class="font-green-seagreen">Fee Structure for New Registration</h4>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr class="info">
								<th colspan="3" class="text-right">
									<i>Fees (Nu) per Category</i>
								</th>
							</tr>
							<tr>
								<th>Classification</th>
								<th class="text-center" width="20%">Validity (Years)</th>
								<th class="text-right">Fee (Nu.)</th>
							</tr>
						</thead>
						<tbody>
							@foreach($feeStructures as $feeStructure)
							<tr>
								<td>
									{{$feeStructure->Name}}
								</td>
								<td class="text-center">
									{{$registrationValidityYears}}
								</td>
								<td class="text-right">
									{{number_format($feeStructure->RegistrationFee,2)}}
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>	
				<div class="form-actions">
					<div class="row">
						<div class="col-md-12">
							<a href="{{URL::to('contractor/generalinforegistration')}}" class="btn green-seagreen btn-lg"><i class="fa fa-plus"></i> Proceed to Registration</a>
							<a href="{{URL::to('/')}}" class="btn grey-cascade btn-lg"><i class="fa fa-home"></i> Home</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
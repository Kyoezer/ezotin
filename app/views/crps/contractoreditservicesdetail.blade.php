@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/sys.js') }}
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Edit Services for Application # 6353 (CDB No. 6276)</span>
		</div>
	</div>
	{{Form::open(array('url' => 'contractor/saveeditedservice','role'=>'form','id'=>'roleregistration'))}}
	<div class="portlet-body form">
		<div class="form-body">
			<div class="row">
				<div class="col-md-6">
					@foreach($applicationDetails as $application)
					<strong>Firm Name:</strong> {{$application->NameOfFirm.' ('.$application->CDBNo.')'}}<br>
					<strong>Application No:</strong> {{$application->ReferenceNo}} <br>
					<strong>Application Date:</strong> {{convertDateToClientFormat($application->ApplicationDate)}} <br>
					@endforeach
					{{Form::hidden('Id',$application->Id)}}
					<div class="table-responsive">
					<h5 class="font-blue-hoki bold">Services</h5>
						<table id="sysrolemenumap" class="table table-condensed table-striped table-bordered table-hover table-responsive">
							<thead>
								<tr>
									<th>Services Name</th>
									<th width="5%">Applied</th>
								</tr>
							</thead>
							<tbody>
								@foreach($services as $service)
									<?php $randomKey = randomString(); ?>
									<tr>
										<td>
											{{$service->Service}}
										</td>
										<td class="text-center">
											<input type="checkbox" name="detailtable[{{$randomKey}}][CmnServiceTypeId]" value="{{$service->Id}}" class="toggle-hidden" @if((bool)$service->Check)checked="checked"@endif/>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="form-actions">
			<div class="btn-set">
				<button type="submit" class="btn green">Update</button>
				<a href="{{URL::to('contractor/editservices')}}" class="btn red">Cancel</a>
			</div>
		</div>
	</div>
	{{Form::close()}}
</div>
@stop
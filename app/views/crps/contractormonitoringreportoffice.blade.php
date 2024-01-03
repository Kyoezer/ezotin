@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/sys.js') }}
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Monitoring Report</span>

		</div>
	</div>
	{{Form::open(array('url' => URL::to('monitoringreport/saveoffice'),'role'=>'form','id'=>'roleregistration'))}}
	<div class="portlet-body flip-scroll">
		<div class="form-body">
			<div class="row">
				<div class="col-md-12"><h4><strong>Office Establishment</strong></h4></div>
			</div>
		</div>
			<div class="table-responsive">
				<table class="table table-condensed table-bordered table-responsive flip-content" id="monitoring-office">
					<thead class="flip-content">
						<tr style="background: #f9f9f9;">
							<th>Year</th>
							<th style="width: 90px;">Office Establishment</th>
							<th style="width: 60px!important;">Sign Board</th>
							<th style="width: 80px!important;">Cannot be contacted</th>
							<th>Deceiving on location change</th>
							<th>Equipment Requirements <br>Please tick if present</th>
							<th>HR requirements <br>Please tick if present</th>
							<th>Remarks</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>{{date('Y')}}
								<input type="hidden" name="Year" value="{{date('Y')}}"/>
								<input type="hidden" name="CrpContractorFinalId" value="{{$contractorDetails[0]->Id}}"/>
							</td>
							<td>
								<select name="HasOfficeEstablishment" class="form-control input-sm input-small" style="width: 80px!important;">
									<option value="1">Yes</option>
									<option value="0">No</option>
								</select>
							</td>
							<td>
								<select name="HasSignBoard" class="form-control input-sm input-small" style="width: 70px!important;">
									<option value="1">Yes</option>
									<option value="0">No</option>
								</select>
							</td>
							<td>
								<select name="CannotBeContacted" class="form-control input-sm input-small" style="width: 70px!important;">
									<option value="1">Yes</option>
									<option value="0">No</option>
								</select>
							</td>
							<td>
								<select name="DeceivingOnLocationChange" class="form-control input-sm input-small" style="width: 80px!important;">
									<option value="1">Yes</option>
									<option value="0">No</option>
								</select>
							</td>
							<td class="hr-eq-details">
								<div class='checkbox-list'>
									@foreach($eqDetails as $eqDetail)
										<?php $randomKey = randomString(); ?>
										<input type="hidden" name="MonitoringOfficeEquipment[{{$randomKey}}][CmnEquipmentId]" value="{{$eqDetail->CmnEquipmentId}}">
										<input type="hidden" name="MonitoringOfficeEquipment[{{$randomKey}}][RegistrationNo]" value="{{$eqDetail->RegistrationNo}}">
										<label><input type='checkbox' name="MonitoringOfficeEquipment[{{$randomKey}}][Checked]" value="1"/>{{$eqDetail->Equipment}}@if((bool)$eqDetail->RegistrationNo){{" (".$eqDetail->RegistrationNo.")"}}@endif</label>
									@endforeach
								</div>
							</td>
							<td class="hr-eq-details">
								<div class='checkbox-list'>
									@foreach($hrDetails as $hrDetail)
										<?php $randomKey = randomString(); ?>
										<input type="hidden" name="MonitoringOfficeHR[{{$randomKey}}][CmnDesignationId]" value="{{$hrDetail->CmnDesignationId}}">
										<input type="hidden" name="MonitoringOfficeHR[{{$randomKey}}][CIDNo]" value="{{$hrDetail->CIDNo}}">
										<input type="hidden" name="MonitoringOfficeHR[{{$randomKey}}][Name]" value="{{$hrDetail->Name}}">
										<input type="hidden" name="MonitoringOfficeHR[{{$randomKey}}][Sex]" value="{{$hrDetail->Sex}}">
										<label><input type='checkbox' name="MonitoringOfficeHR[{{$randomKey}}][Checked]" value="1"/>{{$hrDetail->Designation." (".$hrDetail->Personnel.")"}}</label>
									@endforeach
								</div>
							</td>
							<td><textarea style="width: 160px!important;" rows="4" name="Remarks" class="form-control input-sm input-small"></textarea></td>
						</tr>
					</tbody>
				</table>
			</div>
		<br>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Monitored On</label>
					<input type="text" name="MonitoringDate" readonly="readonly" class="datepicker input-sm required form-control" value="{{date('d-m-Y')}}"/>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Status</label>
					<select name="MonitoringStatus" class="form-control required input-sm">
						<option value="1">Fulfilled</option>
						<option value="0">Not fulfilled</option>
					</select>
				</div>
			</div>
		</div><div class="clearfix"></div>
		<div class="form-actions">
			<div class="btn-set">
				<button type="submit" class="btn green">Save</button>
				<a href="{{URL::to('contractor/editservices')}}" class="btn red">Cancel</a>
			</div>
		</div>
	</div>
	{{Form::close()}}
</div>
@stop
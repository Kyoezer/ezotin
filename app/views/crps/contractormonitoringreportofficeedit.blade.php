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
							<th>Equipment Requirements</th>
							<th>HR requirements</th>
							<th>Remarks</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>{{isset($monitoringDetails[0]->Id)?$monitoringDetails[0]->Year:date('Y')}}
								<input type="hidden" name="Year" value="{{isset($monitoringDetails[0]->Id)?$monitoringDetails[0]->Year:date('Y')}}"/>
								<input type="hidden" name="Id" value="{{$monitoringDetails[0]->Id}}"/>
								<input type="hidden" name="CrpContractorFinalId" value="{{$monitoringDetails[0]->CrpContractorFinalId}}"/>
							</td>
							<td>
								{{Form::select('HasOfficeEstablishment',array('1'=>'Yes','0'=>'No'),$monitoringDetails[0]->HasOfficeEstablishment,array('class'=>'form-control input-sm input-small','style'=>'width: 80px!important;'))}}
							</td>
							<td>
								{{Form::select('HasSignBoard',array('1'=>'Yes','0'=>'No'),$monitoringDetails[0]->HasSignBoard,array('class'=>'form-control input-sm input-small','style'=>'width: 70px!important;'))}}
							</td>
							<td>
								{{Form::select('CannotBeContacted',array('1'=>'Yes','0'=>'No'),$monitoringDetails[0]->CannotBeContacted,array('class'=>'form-control input-sm input-small','style'=>'width: 70px!important;'))}}
							</td>
							<td>
								{{Form::select('DeceivingOnLocationChange',array('1'=>'Yes','0'=>'No'),$monitoringDetails[0]->DeceivingOnLocationChange,array('class'=>'form-control input-sm input-small','style'=>'width: 80px!important;'))}}
							</td>
							<td class="hr-eq-details">
								<div class='checkbox-list'>
									@foreach($monitoringEquipmentDetails as $eqDetail)
										<?php $randomKey = randomString(); ?>
										<input type="hidden" name="MonitoringOfficeEquipment[{{$randomKey}}][CmnEquipmentId]" value="{{$eqDetail->CmnEquipmentId}}">
										<input type="hidden" name="MonitoringOfficeEquipment[{{$randomKey}}][RegistrationNo]" value="{{$eqDetail->RegistrationNo}}">
										<label><input type='checkbox' name="MonitoringOfficeEquipment[{{$randomKey}}][Checked]" value="1" @if($eqDetail->Checked == 1)checked="checked"@endif/>{{$eqDetail->Equipment}}@if((bool)$eqDetail->RegistrationNo){{" (".$eqDetail->RegistrationNo.")"}}@endif</label>
									@endforeach
								</div>
							</td>
							<td class="hr-eq-details">
								<div class='checkbox-list'>
									@foreach($monitoringHRDetails as $hrDetail)
										<?php $randomKey = randomString(); ?>
										<input type="hidden" name="MonitoringOfficeHR[{{$randomKey}}][CmnDesignationId]" value="{{$hrDetail->CmnDesignationId}}">
										<input type="hidden" name="MonitoringOfficeHR[{{$randomKey}}][CIDNo]" value="{{$hrDetail->CIDNo}}">
										<input type="hidden" name="MonitoringOfficeHR[{{$randomKey}}][Name]" value="{{$hrDetail->Name}}">
										<input type="hidden" name="MonitoringOfficeHR[{{$randomKey}}][Sex]" value="{{$hrDetail->Sex}}">
										<label><input type='checkbox' name="MonitoringOfficeHR[{{$randomKey}}][Checked]" value="1" @if($hrDetail->Checked == 1)checked="checked"@endif/>{{$hrDetail->Designation." (".$hrDetail->Personnel.")"}}</label>
									@endforeach
								</div>
							</td>
							<td><textarea style="width: 160px!important;" rows="4" name="Remarks" class="form-control input-sm input-small">{{$monitoringDetails[0]->Remarks}}</textarea></td>
						</tr>
					</tbody>
				</table>
			</div>
		<br>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Monitored On</label>
					<input type="text" name="MonitoringDate" class="datepicker input-sm required form-control" value="{{convertDateToClientFormat($monitoringDetails[0]->MonitoringDate)}}"/>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Status</label>
					{{Form::select("MonitoringStatus",array('1'=>"Fulfilled",'0'=>"Not fulfilled"),$monitoringDetails[0]->MonitoringStatus,array('class'=>'form-control required input-sm'))}}
				</div>
			</div>
		</div><div class="clearfix"></div>
		<br>
		<div class="form-actions">
			<div class="btn-set">
				<button type="submit" class="btn green">Update</button>
				<a href="{{URL::to('monitoringreport/officelist')}}" class="btn red">Cancel</a>
			</div>
		</div>
	</div>
	{{Form::close()}}
</div>
@stop
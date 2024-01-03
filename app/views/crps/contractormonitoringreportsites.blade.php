@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/contractor.js') }}
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Monitoring Report</span>

		</div>
	</div>
	{{Form::open(array('url' => 'monitoringreport/savesite','role'=>'form','id'=>'roleregistration'))}}
	<div class="portlet-body flip-scroll">
		<div class="form-body">
			<div class="row">
				<div class="col-md-12"><h4><strong>Sites</strong></h4></div>
			</div>
		</div>
		@foreach($reportingSites as $site)
		<div class="panel-group select-less-padding" id="accordion">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#soe">General Information</a>
					</h4>
				</div>
				<div id="soe" class="panel-collapse collapse in">
					<div class="panel-body">
						<div class="col-md-12">
							{{Form::hidden("Year",((bool)$site->Year)?$site->Year:date('Y'))}}
							{{Form::hidden("CrpContractorFinalId",$contractorDetails[0]->Id)}}
							{{Form::hidden("Id",$site->Id)}}
							{{Form::hidden("WorkId",((bool)$site->WorkId)?$site->WorkId:$workDetails[0]->Id)}}
							{{Form::hidden("Type",((bool)$site->Type)?$site->Type:$type)}}
							<h5><strong>Contractor:</strong></h5>
							{{$contractorDetails[0]->NameOfFirm." (".$contractorDetails[0]->CDBNo.')'}} <br>
							<h5><strong>Name of Work:</strong></h5>
							{{strip_tags($workDetails[0]->NameOfWork)}} <br/>
							<h5><strong>Agency:</strong></h5>
							{{$workDetails[0]->Agency}}
							<br><br>
						</div>

					</div>
				</div>
			</div>
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title"><a href="#si" data-toggle="collapse" data-parent="#accordion">Inspection of
							Site</a></h3>
				</div>
				<div id="si" class="panel-collapse collapse">
					<div class="panel-body">
						<div class="col-md-4">
							<div class="form-group">
								<label for="ProjectEngineer" class="control-label">Name of Project Engineer</label>
								<input type="text" value="{{$site->ProjectEngineer}}" name="ProjectEngineer" id="ProjectEngineer" class="form-control required input-sm"/>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="ConstructionPhase" class="control-label">Construction Phase at time of Monitoring</label>
								<input type="text" value="{{$site->ConstructionPhase}}" name="ConstructionPhase" id="ConstructionPhase" class="form-control required input-sm"/>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="OfficeSetUp" class="control-label">Office Set Up?</label>
								{{Form::select('OfficeSetUp',array('1'=>'Yes','0'=>'No'),$site->OfficeSetUp,array('class'=>'form-control required input-sm','id'=>'OfficeSetUp'))}}
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="SignBoard" class="control-label">Sign board erected?</label>
								{{Form::select('SignBoard',array('1'=>'Yes','0'=>'No'),$site->SignBoard,array('class'=>'form-control required input-sm','id'=>'SignBoard'))}}
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="BillOfQuantities" class="control-label">Bill of quantities</label>
								{{Form::select('BillOfQuantities',array('1'=>'Yes','0'=>'No'),$site->BillOfQuantities,array('class'=>'form-control required input-sm','id'=>'BillOfQuantities'))}}
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="SBDAndApprovedDrawing" class="control-label">SBD and Approved Drawing</label>
								{{Form::select('SBDAndApprovedDrawing',array('1'=>'Yes','0'=>'No'),$site->SBDAndApprovedDrawing,array('class'=>'form-control required input-sm','id'=>'SBDAndApprovedDrawing'))}}
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="SiteOrderBook" class="control-label">Site Order Book</label>
								{{Form::select('SiteOrderBook',array('1'=>'Yes','0'=>'No'),$site->SiteOrderBook,array('class'=>'form-control required input-sm','id'=>'SiteOrderBook'))}}
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="SiteHindranceBook" class="control-label">Site hindrance Book</label>
								{{Form::select('SiteHindranceBook',array('1'=>'Yes','0'=>'No'),$site->SiteHindranceBook,array('class'=>'form-control required input-sm','id'=>'SiteHindranceBook'))}}
							</div>
						</div>
						<div class="col-md-1">
							<div class="form-group">
								<label for="Store" class="control-label">Store</label>
								{{Form::select('Store',array('1'=>'Yes','0'=>'No'),$site->Store,array('class'=>'form-control required input-sm','id'=>'Store'))}}
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="LabourCamp" class="control-label">Labour Camp</label>
								{{Form::select('LabourCamp',array('1'=>'Yes','0'=>'No'),$site->LabourCamp,array('class'=>'form-control required input-sm','id'=>'LabourCamp'))}}
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="Sanitation" class="control-label">Sanitation</label>
								{{Form::select('Sanitation',array('Good'=>'Good','Fair'=>'Fair','Poor'=>'Poor'),$site->Sanitation,array('class'=>'form-control required input-sm','id'=>'Sanitation'))}}
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="WorkPlan" class="control-label">Work Plan</label>
								{{Form::select('WorkPlan',array('1'=>'Yes','0'=>'No'),$site->WorkPlan,array('class'=>'form-control required input-sm','id'=>'WorkPlan'))}}
							</div>
						</div>
						<div class="col-md-1">
							<div class="form-group">
								<label for="MB" class="control-label">MB</label>
								{{Form::select('MB',array('1'=>'Yes','0'=>'No'),$site->MB,array('class'=>'form-control required input-sm','id'=>'MB'))}}
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="ConstructionJournal" class="control-label">Construction Journal</label>
								{{Form::select('ConstructionJournal',array('1'=>'Yes','0'=>'No'),$site->ConstructionJournal,array('class'=>'form-control required input-sm','id'=>'ConstructionJournal'))}}
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="QualityAssurance" class="control-label">Quality Assurance</label>
								{{Form::select('QualityAssurance',array('1'=>'Yes','0'=>'No'),$site->QualityAssurance,array('class'=>'form-control required input-sm','id'=>'QualityAssurance'))}}
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="TestReport" class="control-label">Test Report</label>
								{{Form::select('TestReport',array('1'=>'Yes','0'=>'No'),$site->TestReport,array('class'=>'form-control required input-sm','id'=>'TestReport'))}}
							</div>
						</div>
						<div class="col-md-1">
							<div class="form-group">
								<label for="APS" class="control-label">APS</label>
								{{Form::select('APS',array('1'=>'Yes','0'=>'No'),$site->APS,array('class'=>'form-control required input-sm','id'=>'APS'))}}
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="SiteInspectionRemarks" class="control-label">Remarks</label>
								<textarea name="SiteInspectionRemarks" rows="2" class="form-control">{{$site->SiteInspectionRemarks}}</textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-info">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#lm">Use of Local Materials</a>
					</h4>
				</div>
				<div id="lm" class="panel-collapse collapse">
					<div class="panel-body">
						<div class="col-md-2">
							<div class="form-group">
								<label for="UseOfLocalBricks" class="control-label">Local Bricks Used?</label>
								{{Form::select('UseOfLocalBricks',array('1'=>'Yes','0'=>'No'),$site->UseOfLocalBricks,array('class'=>'form-control required input-sm','id'=>'UseOfLocalBricks'))}}
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="LocalBricksBoughtFrom" class="control-label">Local Bricks Bought From</label>
								<input type="text" value="{{$site->LocalBricksBoughtFrom}}" name="LocalBricksBoughtFrom" id="LocalBricksBoughtFrom" class="form-control required input-sm"/>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="LocalMaterialsRemarks" class="control-label">Remarks</label>
								<textarea name="LocalMaterialsRemarks" rows="2" class="form-control">{{$site->LocalMaterialsRemarks}}</textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-info">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#ohs">OHS Facilities at Site</a>
					</h4>
				</div>
				<div id="ohs" class="panel-collapse collapse">
					<div class="panel-body">
						<div class="col-md-3">
							<div class="form-group">
								<label for="OHSFacilitiesPresent" class="control-label">OHS facilities Present?</label>
								{{Form::select('OHSFacilitiesPresent',array('1'=>'Yes','0'=>'No'),$site->OHSFacilitiesPresent,array('class'=>'form-control required input-sm','id'=>'OHSFacilitiesPresent'))}}
							</div>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<label for="OHSDetail" class="control-label">Types of OHS provided</label>
								<textarea class="form-control input-sm" rows="2" id="OHSDetail" name="OHSDetail">{{$site->OHSDetail}}</textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-info">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#hr">Types of Labourers at Site</a>
					</h4>
				</div>
				<div id="hr" class="panel-collapse collapse">
					<div class="panel-body">
						<div class="col-md-2">
							<div class="form-group">
								<label for="TotalBhutanese" class="control-label">Total (National)</label>
								<input type="number" class="form-control input-sm" id="TotalBhutanese"  value="{{$site->TotalBhutanese}}" name="TotalBhutanese"/>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="TotalNonBhutanese" class="control-label">Total (Non-National)</label>
								<input type="number" class="form-control input-sm" id="TotalNonBhutanese" value="{{$site->TotalNonBhutanese}}" name="TotalNonBhutanese"/>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="NoOfInterns" class="control-label">No. of Interns</label>
								<input type="number" class="form-control input-sm" id="NoOfInterns" value="{{$site->NoOfInterns}}" name="NoOfInterns"/>
							</div>
						</div><div class="clearfix"></div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="EmploymentOfVTI" class="control-label">Percentage of VTI/ local skill labours committed during evaluation</label>
								<select name="EmploymentOfVTI" id="" class="form-control input-sm required">
									<option value="">---SELECT ONE---</option>
									@foreach($vtiEmployment as $vtiEmp)
										<option value="{{$vtiEmp->Name}}" @if($edit && ($vtiEmp->Name == $site->EmploymentOfVTI))selected="selected"@else<?php if(!$edit && isset($commitmentDetails[0]) && $commitmentDetails[0]->EmploymentOfVTI == $vtiEmp->Points): ?>selected="selected"<?php endif; ?>@endif>{{$vtiEmp->Name}}</option>
									@endforeach
									<option value="0">None</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="CommitmentOfInternship" class="control-label">Number of interns committed during evaluation</label>
								<select name="CommitmentOfInternship" id="" class="form-control input-sm required">
									<option value="">---SELECT ONE---</option>
									@foreach($vtiInternship as $vtiInt)
										<option value="{{$vtiInt->Name}}" @if($edit && ($vtiInt->Name == $site->CommitmentOfInternship))selected="selected"@else<?php if(!$edit && isset($commitmentDetails[0]) && $commitmentDetails[0]->CommitmentOfInternship == $vtiInt->Points): ?>selected="selected"<?php endif; ?>@endif>{{$vtiInt->Name}}</option>
									@endforeach
									<option value="0">None</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
			@if($type == 3 && count($committedHR)>0)
				<div class="panel panel-info">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#committedhr">Committed HR</a>
						</h4>
					</div>
					<div id="committedhr" class="panel-collapse collapse">
						<div class="panel-body">
							<h4>Please tick if present</h4> <br>
							<div class='checkbox-list'>
								@foreach($committedHR as $hrDetail)
									<?php $randomKey = randomString(); ?>
									<input type="hidden" name="MonitoringSiteHR[{{$randomKey}}][CIDNo]" value="{{$hrDetail->CIDNo}}">
									<input type="hidden" name="MonitoringSiteHR[{{$randomKey}}][Name]" value="{{$hrDetail->Name}}">
									<input type="hidden" name="MonitoringSiteHR[{{$randomKey}}][CmnDesignationId]" value="{{$hrDetail->CmnDesignationId}}">
									<input type="hidden" name="MonitoringSiteHR[{{$randomKey}}][Qualification]" value="{{$hrDetail->Qualification}}">
									<label><input type='checkbox' name="MonitoringSiteHR[{{$randomKey}}][Checked]" value="1" @if(isset($hrDetail->Checked))@if($hrDetail->Checked == 1)checked="checked"@endif @endif/>{{"<strong>".$hrDetail->Designation."</strong><br/>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CID/Work Permit: ".$hrDetail->CIDNo}}{{((bool)$hrDetail->Name)?" <br/>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Name: ".$hrDetail->Name:''}}
										<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Qualification: {{$hrDetail->Qualification}}</label>
								@endforeach
							</div>
						</div>
					</div>
				</div>
			@endif
			@if($type == 3 && count($committedEq)>0)
				<div class="panel panel-info">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#committedeq">Committed Equipment</a>
						</h4>
					</div>
					<div id="committedeq" class="panel-collapse collapse">
						<div class="panel-body">
							<h4>Please tick if present</h4> <br>
							<div class='checkbox-list'>
								@foreach($committedEq as $eqDetail)
									<?php $randomKey = randomString(); ?>
									<input type="hidden" name="MonitoringSiteEQ[{{$randomKey}}][RegistrationNo]" value="{{$eqDetail->RegistrationNo}}">
									<input type="hidden" name="MonitoringSiteEQ[{{$randomKey}}][Quantity]" value="{{($eqDetail->Quantity==0)?1:$eqDetail->Quantity}}">
									<input type="hidden" name="MonitoringSiteEQ[{{$randomKey}}][CmnEquipmentId]" value="{{$eqDetail->CmnEquipmentId}}">
									<label><input type='checkbox' name="MonitoringSiteEQ[{{$randomKey}}][Checked]" value="1" @if(isset($eqDetail->Checked))@if($eqDetail->Checked == 1)checked="checked"@endif @endif/>{{"<strong>".$eqDetail->Equipment."</strong>"}}{{((bool)$eqDetail->RegistrationNo)?" <br/>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Registration No.: ".$eqDetail->RegistrationNo."":''}}
										<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Quantity: {{($eqDetail->Quantity==0)?1:$eqDetail->Quantity}}</label>
								@endforeach
							</div>
						</div>
					</div>
				</div>
			@endif
		</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label for="Remarks" class="control-label">Remarks</label>
						<textarea class="form-control input-sm" rows="2" id="Remarks" name="Remarks">{{$site->Remarks}}</textarea>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Monitored On</label>
						<input type="text" name="MonitoringDate" readonly="readonly" class="datepicker input-sm required form-control" value="{{((bool)$site->MonitoringDate)?$site->MonitoringDate:date('d-m-Y')}}"/>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Status</label>
						{{Form::select('MonitoringStatus',array('1'=>'Fulfilled','0'=>'Not fulfilled'),$site->MonitoringStatus,array('class'=>'form-control required input-sm'))}}
					</div>
				</div>
			</div>
		@endforeach
		<div class="clearfix"></div>
		<br>
		<div class="form-actions">
			<div class="btn-set">
				<button type="submit" class="btn green">Save</button>
				<a href="{{URL::to('monitoringreport/sitelist')}}" class="btn red">Cancel</a>
			</div>
		</div>
	</div>
	{{Form::close()}}
</div>
@stop
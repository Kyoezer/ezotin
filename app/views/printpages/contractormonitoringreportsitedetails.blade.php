@extends('printmaster')
@section('content')
<p style="font-size: 10pt; display: inline-block;">
	<table class="data-large table-smallfont" style="width: 100%; margin-top: -10px;">
		<tbody>
			<tr>
				<td width="12%"><strong>Firm Name</strong></td>
				<td width="35%">
					{{$contractorDetails[0]->NameOfFirm}}
				</td>
				<td width="15%"><strong>Name of Work</strong></td>
				<td class="text-center">
					{{$workDetails[0]->NameOfWork}}
				</td>
			</tr>
			<tr>
				<td width="12%"><strong>CDB No</strong></td>
				<td width="35%">{{$contractorDetails[0]->CDBNo}}</td>
				<td width="15%"><strong>Agency</strong></td>
				<td class="text-center">
					{{$workDetails[0]->Agency}}
				</td>
			</tr>
			<tr>
				<td width="12%"><strong>Monitored On</strong></td>
				<td width="35%">{{convertDateToClientFormat($reportingSites[0]->MonitoringDate)}}</td>
				<td width="15%"><strong>For Year</strong></td>
				<td class="text-center">
					{{$reportingSites[0]->Year}}
				</td>
			</tr>
		</tbody>
	</table>
</p>
<br>
<table class="data-large table-smallfont" style="width: 100%;">
	<tbody>
		<tr style="background: #ccc;"><td colspan="2"><center><strong>INSPECTION OF SITE</strong></center></td></tr>
		<tr>
			<td width="50%"><strong>Name of Project Engineer</strong></td>
			<td class="text-center">
				{{$reportingSites[0]->ProjectEngineer}}
			</td>
		</tr>
		<tr>
			<td width="50%"><strong>Construction Phase at time of Monitoring</strong></td>
			<td class="text-center">
				{{$reportingSites[0]->ConstructionPhase}}
			</td>
		</tr>
		<tr>
			<td width="50%"><strong>Office Set Up?</strong></td>
			<td class="text-center">
				{{($reportingSites[0]->OfficeSetUp==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
			</td>
		</tr>
		<tr>
			<td width="50%"><strong>Sign board Erected?</strong></td>
			<td class="text-center">
				{{($reportingSites[0]->SignBoard==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
			</td>
		</tr>
		<tr>
			<td width="50%"><strong>Bill of Quantities</strong></td>
			<td class="text-center">
				{{($reportingSites[0]->BillOfQuantities==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
			</td>
		</tr>
		<tr>
			<td width="50%"><strong>SBD and Approved Drawing</strong></td>
			<td class="text-center">
				{{($reportingSites[0]->SBDAndApprovedDrawing==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
			</td>
		</tr>
		<tr>
			<td width="50%"><strong>Site Order Book</strong></td>
			<td class="text-center">
				{{($reportingSites[0]->SiteOrderBook==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
			</td>
		</tr>
		<tr>
			<td width="50%"><strong>Site Hindrance Book</strong></td>
			<td class="text-center">
				{{($reportingSites[0]->SiteHindranceBook==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
			</td>
		</tr>
		<tr>
			<td width="50%"><strong>Store</strong></td>
			<td class="text-center">
				{{($reportingSites[0]->Store==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
			</td>
		</tr>
		<tr>
			<td width="50%"><strong>Labour Camp</strong></td>
			<td class="text-center">
				{{($reportingSites[0]->LabourCamp==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
			</td>
		</tr>
		<tr>
			<td width="50%"><strong>Sanitation</strong></td>
			<td class="text-center">
				{{($reportingSites[0]->Sanitation==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
			</td>
		</tr>
		<tr>
			<td width="50%"><strong>Work Plan</strong></td>
			<td class="text-center">
				{{($reportingSites[0]->WorkPlan==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
			</td>
		</tr>
		<tr>
			<td width="50%"><strong>MB</strong></td>
			<td class="text-center">
				{{($reportingSites[0]->MB==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
			</td>
		</tr>
		<tr>
			<td width="50%"><strong>Construction Journal</strong></td>
			<td class="text-center">
				{{($reportingSites[0]->ConstructionJournal==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
			</td>
		</tr>
		<tr>
			<td width="50%"><strong>Quality Assurance</strong></td>
			<td class="text-center">
				{{($reportingSites[0]->QualityAssurance==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
			</td>
		</tr>
		<tr>
			<td width="50%"><strong>Test Report</strong></td>
			<td class="text-center">
				{{($reportingSites[0]->TestReport==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
			</td>
		</tr>
		<tr>
			<td width="50%"><strong>APS</strong></td>
			<td class="text-center">
				{{($reportingSites[0]->APS==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
			</td>
		</tr>
		<tr>
			<td colspan="2"><strong>Remarks: {{$reportingSites[0]->SiteInspectionRemarks}}</strong></td>
		</tr>
	</tbody>
</table>
<br>
<table class="data-large table-smallfont" style="width: 100%;">
	<tbody>
	<tr style="background: #ccc;"><td colspan="2"><center><strong>LOCAL MATERIALS</strong></center></td></tr>
	<tr>
		<td width="30%"><strong>Local Bricks Used?</strong></td>
		<td class="text-center">
			{{($reportingSites[0]->UseOfLocalBricks==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
		</td>
	</tr>
	<tr>
		<td width="30%"><strong>Local Bricks Bought From</strong></td>
		<td class="text-center">
			{{$reportingSites[0]->LocalBricksBoughtFrom}}
		</td>
	</tr>
	</tbody>
</table>
<br>
<table class="data-large table-smallfont" style="width: 100%;">
	<tbody>
	<tr style="background: #ccc;"><td colspan="2"><center><strong>OHS FACILITIES AT SITE</strong></center></td></tr>
	<tr>
		<td width="30%"><strong>OHS Facilities Present?</strong></td>
		<td class="text-center">
			{{($reportingSites[0]->OHSFacilitiesPresent==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
		</td>
	</tr>
	@if($reportingSites[0]->OHSFacilitiesPresent==1)
		<tr>
			<td width="30%"><strong>Types of OHS provided</strong></td>
			<td class="text-center">
				{{$reportingSites[0]->OHSDetail}}
			</td>
		</tr>
	@endif
	</tbody>
</table>
<br>
<table class="data-large table-smallfont" style="width: 100%;">
	<tbody>
	<tr style="background: #ccc;"><td colspan="2"><center><strong>TYPES OF LABOURERS AT SITE</strong></center></td></tr>
	<tr>
		<td width="50%"><strong>Total Bhutanese</strong></td>
		<td class="text-center">
			{{$reportingSites[0]->TotalBhutanese}}
		</td>
	</tr>
	<tr>
		<td width="50%"><strong>Total Non-Bhutanese</strong></td>
		<td class="text-center">
			{{$reportingSites[0]->TotalNonBhutanese}}
		</td>
	</tr>
	<tr>
		<td width="50%"><strong>No. of Interns</strong></td>
		<td class="text-center">
			{{$reportingSites[0]->NoOfInterns}}
		</td>
	</tr>
	<tr>
		<td width="50%"><strong>Percentage of VTI/ local skill labours committed during evaluation</strong></td>
		<td class="text-center">
			{{$reportingSites[0]->EmploymentOfVTI}}
		</td>
	</tr>
	<tr>
		<td width="50%"><strong>Number of interns committed during evaluation</strong></td>
		<td class="text-center">
			{{$reportingSites[0]->CommitmentOfInternship}}
		</td>
	</tr>
	</tbody>
</table>
<br>
@if($type == 3 && count($committedHR)>0)
	<table class="data-large table-smallfont" style="width: 100%;">
		<tbody>
		<tr style="background: #ccc;"><td colspan="3"><center><strong>COMMITTED HR</strong></center></td></tr>
		<?php $count = 1; ?>
		@foreach($committedHR as $hrDetail)
			<tr>
				<td width="3%">{{$count++}}</td>
				<td width="90%">{{"<strong>".$hrDetail->Designation."</strong><br/>CID/Work Permit: ".$hrDetail->CIDNo}}{{((bool)$hrDetail->Name)?" <br/>Name: ".$hrDetail->Name:''}}
						<br>Qualification: {{$hrDetail->Qualification}}</td>
				<td class="text-center">
					{{($hrDetail->Checked==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
@endif

@if($type == 3 && count($committedEq)>0)
	<br>
	<table class="data-large table-smallfont" style="width: 100%;">
		<tbody>
		<tr style="background: #ccc;"><td colspan="3"><center><strong>COMMITTED EQUIPMENT</strong></center></td></tr>
		<?php $count = 1; ?>
		@foreach($committedEq as $eqDetail)
			<tr>
				<td width="3%">{{$count++}}</td>
				<td width="90%">{{"<strong>".$eqDetail->Equipment."</strong>"}}{{((bool)$eqDetail->RegistrationNo)?" <br/> Registration No.: ".$eqDetail->RegistrationNo."":''}}
					<br>Quantity: {{($eqDetail->Quantity==0)?1:$eqDetail->Quantity}}</td>
				<td class="text-center">
					{{($eqDetail->Checked==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
@endif
<p style="font-size: 10pt;">Remarks: {{(bool)$reportingSites[0]->Remarks?$reportingSites[0]->Remarks:"---"}}</p>
<p style="font-size: 10pt;">Status: {{($reportingSites[0]->MonitoringStatus == 1)?"Fulfilled":"Not fulfilled"}}</p>
@stop
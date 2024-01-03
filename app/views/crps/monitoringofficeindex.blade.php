@extends('master')
@section('pagescripts')
	{{ HTML::script('assets/global/scripts/contractor.js') }}
@stop
@section('content')

	<div id="suspend-modal" class="modal fade"  data-keyboard="false" data-backdrop="static" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h3 class="modal-title font-red-intense">Suspend contractor</h3>
				</div>
				{{Form::open(array('url'=>'monitoringreport/suspendcontractor'))}}
				<div class="modal-body">
					<div class="col-md-12">
						<p><strong>CDB No.:</strong> <span id="cdb-no"></span></p>
						<p><strong>Firm: </strong> <span id="firm-name"></span></p>
						<p><strong>Class: </strong> <span id="class"></span></p>
					</div>
					<input type="hidden" id="contractor-id" name="CrpContractorFinalId"/>
					<input type="hidden" id="monitoring-id" name="CrpId"/>
					<div class="col-md-5">
						<label class="control-label">Suspended On</label>
						<div class="input-icon right">
							<i class="fa fa-calendar"></i>
							<input type="text" name="FromDate" class="form-control datepicker required" value="{{Input::get('FromDate')}}" readonly="readonly" placeholder="">
						</div>
					</div>
				 
					<div class="col-md-12">
						<label class="control-label">Remarks</label>
						<textarea class="form-control required input-sm" rows="4" name="Remarks"></textarea>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn red-intense">Update</button>
					<button type="button" class="btn green" data-dismiss="modal" aria-hidden="true">Cancel</button>
				</div>
				{{Form::close()}}
			</div>
		</div>
	</div>
	
	<div id="warning-modal" class="modal fade"  data-keyboard="false" data-backdrop="static" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h3 class="modal-title font-red-intense">Warning</h3>
				</div>
				{{Form::open(array('url'=>'monitoringreport/warngincontractor'))}}
				<div class="modal-body">
					<div class="col-md-12">
						<p><strong>CDB No.:</strong> <span id="warningCdb-no"></span></p>
						<p><strong>Firm: </strong> <span id="warningFirm-name"></span></p>
						<p><strong>Class: </strong> <span id="warningClass"></span></p>
					</div>
					<input type="hidden" id="warningContractor-id" name="CrpContractorFinalId"/>
					<input type="hidden" id="warningMonitoring-id" name="CrpId"/>
					<div class="col-md-12">
						<label class="control-label">Remarks</label>
						<textarea class="form-control required input-sm" rows="4" name="Remarks"></textarea>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn red-intense">Update</button>
					<button type="button" class="btn green" data-dismiss="modal" aria-hidden="true">Cancel</button>
				</div>
				{{Form::close()}}
			</div>
		</div>
	</div>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">{{$pageTitle}}</span>
		</div>
	</div>
	<div class="portlet-body flip-scroll">
		<div class="form-body">
			{{ Form::open(array('url' =>Request::URL(),'role'=>'form','method'=>'get')) }}
			<div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Contractor/Firm/CDB No.</label>
                        <div class="ui-widget">
                            <input type="hidden" class="contractor-id" name="ContractorId" value="{{Input::get('ContractorId')}}"/>
                            <input type="text" name="CDBNo" value="{{Input::get('Contractor')}}" class="form-control input-sm contractor-autocomplete"/>
							<!-- <input type="text" name="Contractor" value="{{Input::get('Contractor')}}" class="form-control input-sm contractor-autocomplete"/> -->
                        </div>
                    </div>
                </div>
				<!-- <div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Dzongkhag</label>
						<select name="DzongkhagId" class="form-control select2me">
							<option value="">All</option>
							@foreach($dzongkhags as $dzongkhag)
								<option value="{{$dzongkhag->Id}}" @if(Input::get("DzongkhagId") == $dzongkhag->Id)selected="selected"@endif>{{$dzongkhag->Dzongkhag}}</option>
							@endforeach
						</select>
					</div>
				</div> -->
				
				<div class="col-md-2">
					<label class="control-label">&nbsp;</label>
					<div class="btn-set">
						<button type="submit" class="btn blue-hoki btn-sm">Search</button>
						<a href="{{Request::Url()}}" class="btn grey-cascade btn-sm">Clear</a>
					</div>
				</div>
			</div>
			{{Form::close()}}
		</div>
			@if(isset($isList))
				<div class="panel-group accordion" id="tender-accordion">
					<?php $count = 1; ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
							<thead class="flip-content">
							<tr>
								<th>
									CDB No.
								</th>
								@if(isset($isList))
									<th>Monitored On</th>
								@endif
								<th>
									Ownership Type
								</th>
								<th>
									Name of Firm
								</th>
								<th>
									Class
								</th>
								<th>
									Country
								</th>
								<th>
									Dzongkhag
								</th>
								<th>
									Mobile#
								</th>
								<th>
									Tel#
								</th>
								<th>
									Email
								</th>
								<th>
								Status
								</th>
								@if(isset($isList))
									<th>Status</th>
									<th>Remarks</th>
								@endif
								<th class="col-md-2">
									Action
								</th>
							</tr>
							</thead>
							<tbody>
							@forelse($contractorLists as $contractorList)
							<!-- @if($contractorList->CmnApplicationRegistrationStatusId != CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SUSPENDED) -->
								<tr>
									<td>
										{{$contractorList->CDBNo}}
									</td>
									@if(isset($isList))
										<td>
											{{convertDateToClientFormat($contractorList->MonitoringDate)}}
										</td>
									@endif
									<td>
										{{$contractorList->OwnershipType}}
									</td>
									<td>
										{{$contractorList->NameOfFirm}}
									</td>
									<td>
										{{$contractorList->ClassificationCode}}
									</td>
									<td>
										{{$contractorList->Country}}
									</td>
									<td>
										{{$contractorList->Dzongkhag}}
									</td>
									<td>
										{{str_replace(',','<br/>',$contractorList->MobileNo)}}
									</td>
									<td>
										{{str_replace(',','<br/>',$contractorList->TelephoneNo)}}
									</td>
									<td>
										{{$contractorList->Email}}
									</td>
									<td>
										{{$contractorList->Email}}
									</td>
									@if(isset($isList))
										<td>
											{{($contractorList->MonitoringStatus==1)?"Fulfilled":"Not fulfilled"}}
										</td>
										<td>
											{{$contractorList->Remarks}}
										</td>
										<td class="text-center">
										@if($isList == 1)
											@if($contractorList->CmnApplicationRegistrationStatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SUSPENDED)
											<ol style="margin-left: -25px;">
												<li><a href="{{URL::to('contractor/reregistration')}}?ContractorId={{$contractorList->Id}}&Contractor={{$contractorList->NameOfFirm.' ('.$contractorList->CDBNo.')'}}">Reregister</a></li>
											</ol>
											(SUSPENDED)
											@else
												<a href="{{URL::to("contractor/editworkclassification/$contractorList->Id")}}?monitoringofficeid={{$contractorList->Id}}&redirectUrl=monitoringreport/officeaction&usercdb=edit&final=true">Upgrade</a>
										@endif
											
												
												<!-- <a href="{{URL::to('monitoringreport/officeedit/'.$contractorList->Id)}}" class="btn btn-xs red"><i class="fa fa-edit"></i> Edit</a>
												<a href="{{URL::to('monitoringreport/officedelete/'.$contractorList->Id)}}" class="btn btn-xs blue"><i class="fa fa-times"></i> Delete</a>
												<a href="{{URL::to('monitoringreport/officeview/'.$contractorList->Id)}}" target="_blank" class="btn btn-xs purple"><i class="fa fa-print"></i> Print</a> -->
											</td>
										@else
											<td>
											
												@if((bool)$contractorList->ActionTaken)
													@if($contractorList->ActionTaken == 2)
														@if($contractorList->CmnApplicationRegistrationStatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SUSPENDED)
															<ol style="margin-left: -25px;">
																<li><a href="{{URL::to('contractor/reregistration')}}?ContractorId={{$contractorList->Id}}&Contractor={{$contractorList->NameOfFirm.' ('.$contractorList->CDBNo.')'}}">Reregister</a></li>
															</ol>
															(SUSPENDED)
														@endif
													@else
														<!-- (DOWNGRADED) -->
														<ol style="margin-left: -25px;">
															<li><a href="{{URL::to("contractor/editworkclassification/$contractorList->Id")}}?monitoringofficeid={{$contractorList->Id}}&redirectUrl=monitoringreport/officeaction&usercdb=edit&final=true">Downgrade</a></li>
															<li><a href="#" data-monitoringid="{{$contractorList->Id}}" data-class="{{$contractorList->ClassificationCode}}" data-cdbno="{{$contractorList->CDBNo}}" data-firmname="{{$contractorList->NameOfFirm}}" data-id="{{$contractorList->Id}}" class="suspend-contractor-monitoring">Suspend</a></li>
															<li><a href="#" data-monitoringid="{{$contractorList->Id}}" data-class="{{$contractorList->ClassificationCode}}" data-cdbno="{{$contractorList->CDBNo}}" data-firmname="{{$contractorList->NameOfFirm}}" data-id="{{$contractorList->Id}}"  class="warning-contractor-monitoring">Warning</a></li>
														</ol>
													@endif
												@else
													<ol style="margin-left: -25px;">
														<li><a href="{{URL::to("contractor/editworkclassification/$contractorList->Id")}}?monitoringofficeid={{$contractorList->Id}}&redirectUrl=monitoringreport/officeaction&usercdb=edit&final=true">Downgrade</a></li>
														<li><a href="#" data-monitoringid="{{$contractorList->Id}}" data-class="{{$contractorList->ClassificationCode}}" data-cdbno="{{$contractorList->CDBNo}}" data-firmname="{{$contractorList->NameOfFirm}}" data-id="{{$contractorList->Id}}" class="suspend-contractor-monitoring">Suspend</a></li>
														<li><a href="#" data-monitoringid="{{$contractorList->Id}}" data-class="{{$contractorList->ClassificationCode}}" data-cdbno="{{$contractorList->CDBNo}}" data-firmname="{{$contractorList->NameOfFirm}}" data-id="{{$contractorList->Id}}" class="warning-contractor-monitoring">Warning</a></li>

													</ol>
													(ACTION NOT TAKEN)
												@endif
											</td>
										@endif
									@else
										<td class="text-center">
											@if((int)$contractorList->StatusReference == 12003)
												<!-- <a href="{{URL::to('monitoringreport/officerecord/'.$contractorList->Id)}}" class="btn btn-xs red"><i class="fa fa-edit"></i> Record Monitoring</a> -->
											@endif
										</td>
									@endif
								</tr>
							<!-- @endif -->
							@empty
								<tr>
									<td class="font-red text-center" colspan="14">No data to display</td>
								</tr>
							@endforelse
							</tbody>
						</table>
			</div>
			</div>
			@else
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-condensed flip-content">
						<thead class="flip-content">
						<tr>
							<th>
								CDB No.
							</th>
							@if(isset($isList))
								<th>Year</th>
								<th>Monitoring Date</th>
							@endif
							<th>
								Ownership Type
							</th>
							<th>
								Name of Firm
							</th>
							<th>
								Class
							</th>
							<th>
								Country
							</th>
							<th>
								Dzongkhag
							</th>
							<th>
								Mobile#
							</th>
							<th>
								Tel#
							</th>
							<th>
								Email
							</th>
							<th>
								Status
							</th>
							@if(isset($isList))
								<th>Status</th>
							@endif
							<th class="col-md-2">
								Action
							</th>
						</tr>
						</thead>
						<tbody>
						@forelse($contractorLists as $contractorList)
							<tr>
								<td>
									{{$contractorList->CDBNo}}
								</td>
								@if(isset($isList))
									<td>
										{{$contractorList->Year}}
									</td>
									<td>
										{{convertDateToClientFormat($contractorList->MonitoringDate)}}
									</td>
								@endif
								<td>
									{{$contractorList->OwnershipType}}
								</td>
								<td>
									{{$contractorList->NameOfFirm}}
								</td>
								<td>
									{{$contractorList->ClassificationCode}}
								</td>
								<td>
									{{$contractorList->Country}}
								</td>
								<td>
									{{$contractorList->Dzongkhag}}
								</td>
								<td>
									{{$contractorList->MobileNo}}
								</td>
								<td>
									{{$contractorList->TelephoneNo}}
								</td>
								<td>
									{{$contractorList->Email}}
								</td>
								<td>
									@if((int)$contractorList->StatusReference == 12003)
										@if($contractorList->RegistrationExpiryDate<=date('Y-m-d G:i:s'))
											Expired
										@else
											Valid/Approved
										@endif
									@else
										{{$contractorList->Status}}
									@endif
								</td>
								@if(isset($isList))
									<td>
										{{($contractorList->MonitoringStatus==1)?"Fulfilled":"Not fulfilled"}}
									</td>
									@if($isList == 1)
										<td class="text-center">
											<a href="{{URL::to('monitoringreport/officeedit/'.$contractorList->Id)}}" class="btn btn-xs red"><i class="fa fa-edit"></i> Edit</a>
											<a href="{{URL::to('monitoringreport/officeview/'.$contractorList->Id)}}" target="_blank" class="btn btn-xs purple"><i class="fa fa-print"></i> Print</a>
										</td>
									@else
										<td>
											<ol style="margin-left: -25px;">
												<li><a href="{{URL::to("contractor/editworkclassification/$contractorList->Id")}}?monitoringofficeid={{$contractorList->Id}}&redirectUrl=monitoringreport/officeaction&usercdb=edit&final=true">Downgrade</a></li>
												<li><a href="#" data-monitoringid="{{$contractorList->Id}}" data-class="{{$contractorList->ClassificationCode}}" data-cdbno="{{$contractorList->CDBNo}}" data-firmname="{{$contractorList->NameOfFirm}}" data-id="{{$contractorList->Id}}" class="suspend-contractor-monitoring">Suspend</a></li>
											</ol>
										</td>
									@endif

								@else
									<td class="text-center">
										@if($formType=='DOWNGRADE')
											<ol style="margin-left: -25px;">
												
													<li><a href="{{URL::to("contractor/editworkclassification/$contractorList->Id")}}?monitoringofficeid={{$contractorList->Id}}&redirectUrl=monitoringreport/officeaction&usercdb=edit&final=true">Downgrade</a></li>
													<li><a href="#" data-monitoringid="{{$contractorList->Id}}" data-class="{{$contractorList->ClassificationCode}}" data-cdbno="{{$contractorList->CDBNo}}" data-firmname="{{$contractorList->NameOfFirm}}" data-id="{{$contractorList->Id}}" class="suspend-contractor-monitoring">Suspend</a></li>
													<li><a href="#" data-monitoringid="{{$contractorList->Id}}" data-class="{{$contractorList->ClassificationCode}}" data-cdbno="{{$contractorList->CDBNo}}" data-firmname="{{$contractorList->NameOfFirm}}" data-id="{{$contractorList->Id}}"  class="warning-contractor-monitoring">Warning</a></li>
												
											</ol>
										@elseif($formType=='REINSTATE')
											@if($contractorList->Status=="Suspended")
												<a href="#reregister" onclick="openReinstate('{{$contractorList->Id}}','{{$contractorList->CDBNo}}','{{$contractorList->NameOfFirm}}')" data-toggle="modal" class="reregistrationcontractor">Reinstate</a>
											@endif
										@endif
										@if((int)$contractorList->StatusReference == 12003)
											<!-- <a href="{{URL::to('monitoringreport/officerecord/'.$contractorList->Id)}}" class="btn btn-xs red"><i class="fa fa-edit"></i> Record Monitoring</a> -->
										@endif
									</td>
								@endif
								
							</tr>
						@empty
							<tr>
								<td class="font-red text-center" colspan="14">No data to display</td>
							</tr>
						@endforelse
						</tbody>
					</table>
				</div>
			@endif
	<?php //pagination($noOfPages,Input::all(),Input::get('page'),$route); ?>
</div>

<script>
	function openReinstate(contractorId,cdbNo,contractorName)
	{
		$("#cdb-no-reistate").text(cdbNo);
		$("#firm-name-reistate").text(contractorName);
		$("#contractor-id-reinstate").val(contractorId);
	}
</script>

<div id="reregister" class="modal fade"  data-keyboard="false" data-backdrop="static" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h3 class="modal-title font-red-intense">Reinstate Form</h3>
				</div>
				{{Form::open(array('url'=>'contractor/reinstate'))}}
				<div class="modal-body">
					<div class="col-md-12">
						<p><strong>CDB No.:</strong> <span id="cdb-no-reistate"></span></p>
						<p><strong>Firm Name: </strong> <span id="firm-name-reistate"></span></p>
					</div>
					<input type="hidden" id="contractor-id-reinstate" name="CrpContractorFinalId"/>
					<input type="hidden" id="monitoring-id-reinstate" name="CrpId"/>
					<div class="col-md-5">
						<label class="control-label">Suspended On</label>
						<div class="input-icon right">
							<i class="fa fa-calendar"></i>
							<input type="text" name="FromDate" class="form-control datepicker required" value="{{Input::get('FromDate')}}" readonly="readonly" placeholder="">
						</div>
					</div>
				 
					<div class="col-md-12">
						<label class="control-label">Remarks</label>
						<textarea class="form-control required input-sm" rows="4" name="Remarks"></textarea>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn red-intense">Update</button>
					<button type="button" class="btn green" data-dismiss="modal" aria-hidden="true">Cancel</button>
				</div>
				{{Form::close()}}
			</div>
		</div>
	</div>

<!-- <div id="reregister1" class="modal fade" role="dialog" aria-labelledby="reregister" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title font-green-seagreen">Are you sure you want to reinstate this contractor?</h4>
			</div>
			{{ Form::open(array('url' => 'contractor/reinstate','role'=>'form'))}}
			<input type="hidden" name="ContractorReference" value="" class="reregisterId"/>
			<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED}}">
			<div class="modal-body">
				<h4 class="displaycontractorname"></h4>
				<h4 class="displaycontractorcdbno"></h4>
				<div class="form-group">
					<label class="control-label">Date</label>
					<div class="input-icon right input-medium">
						<i class="fa fa-calendar"></i>
						<input type="text" name="ReRegistrationDate" class="form-control datepicker input-sm required input-medium" readonly="readonly" value="{{date('d-m-Y')}}">
					</div>
				</div>
				<div class="form-group">
					<label>Remarks</label>
					<textarea name="ReRegistrationRemarks" class="form-control required" rows="3"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn green">Re-Register</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div> -->


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".warning-contractor-monitoring").on('click',function(e){
				e.preventDefault();
				var id = $(this).data('id');
				var cdbNo = $(this).data('cdbno');
				var firmName = $(this).data('firmname');
				var contractorClass = $(this).data("class");
				var monitoringId = $(this).data('monitoringid');
				$("#warningContractor-id").val(id);
				$("#warningMonitoring-id").val(monitoringId);
				$("#warningCdb-no").text(cdbNo);
				$("#warningFirm-name").text(firmName);
				$("#warningClass").text(contractorClass);
				$("#warning-modal").modal("show");
		});
	});

</script>
@stop
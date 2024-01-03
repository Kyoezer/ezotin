@extends('horizontalmenumaster')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i> Consultant Service - <span class="step-title">Confirmation </span>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-body">
					<div class="note note-info">
		              	<p>If you have to edit any information that you have submitted you can click on the corresponding buttons. After everthing has been finalised you have to agree to <span class="bold">Terms of Services</span> and submit to CDB.</p>
		            </div>
					<div class="tabbable-custom nav-justified">
						<ul class="nav nav-tabs nav-justified">
							<li class="active">
								<a href="#generalinformation" data-toggle="tab">
								General Information</a>
							</li>
							<li>
								<a href="#workclassification" data-toggle="tab">
								Work Classification</a>
							</li>
							<li>
								<a href="#humanresource" data-toggle="tab">
								Human Resource </a>
							</li>
							<li>
								<a href="#equipment" data-toggle="tab">
								Equipment</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="generalinformation">
								<div class="row">
									<div class="col-md-6">
										<table class="table table-bordered table-striped table-condensed flip-content">
											<tbody>
												<tr>
													<td><strong>Proposed Name</strong></td>
													<td>{{$generalInformation[0]->NameOfFirm}}</td>
												</tr>
												<tr>
													<td><strong>Country</strong></td>
													<td>{{$generalInformation[0]->Country}}</td>
												</tr>
												<tr>
													<td><strong>Dzongkhag</strong></td>
													<td>{{$generalInformation[0]->Dzongkhag}}</td>
												</tr>
												<tr>
													<td><strong>Address</strong></td>
													<td>{{$generalInformation[0]->Address}}</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-md-6">
										<table class="table table-bordered table-striped table-condensed flip-content">
											<tbody>
												<tr>
													<td><strong>Email</strong></td>
													<td>{{$generalInformation[0]->Email}}</td>
												</tr>
												<tr>
													<td><strong>Telephone No.</strong></td>
													<td>{{$generalInformation[0]->TelephoneNo}}</td>
												</tr>
												<tr>
													<td><strong>Mobile No.</strong></td>
													<td>{{$generalInformation[0]->MobileNo}}</td>
												</tr>
												<tr>
													<td><strong>Fax No.</strong></td>
													<td>{{$generalInformation[0]->FaxNo}}</td>
												</tr>
											</tbody>	
										</table>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<h5 class="font-blue-madison bold">Name of Owner, Partners and/or others with Controlling Intrest</h5>
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-condensed">
												<thead>
													<tr>
														<th>
															Name
														</th>
														<th class="">
															CID No.
														</th>
														<th>
															Sex
														</th>
														<th>
															Country
														</th>
														<th>
															Designation
														</th>
														<th width="15%">
															Show in Certificate
														</th>
													</tr>
												</thead>
												<tbody>
													@foreach($ownerPartnerDetails as $ownerPartnerDetail)
													<tr>
														<td>
															{{$ownerPartnerDetail->Name}}
														</td>
														<td>
															{{$ownerPartnerDetail->CIDNo}}
														</td>
														<td>
															{{$ownerPartnerDetail->Sex}}
														</td>
														<td>
															{{$ownerPartnerDetail->Country}}
														</td>
														<td>
															{{$ownerPartnerDetail->Designation}}
														</td>
														<td>
															@if((int)$ownerPartnerDetail->ShowInCertificate==1)
															<i class="fa fa-check"></i>
															@endif
														</td>
													</tr>
													@endforeach
												</tbody>
											</table>
										</div>
										<div class="row">
					                        <div class="col-md-12">
				                                <a href="{{URL::to('consultant/applyservicegeneralinfo/'.$consultantId.'?redirectUrl=consultant/applyserviceconfirmation&confedit=1&edit=true')}}" class="btn green-seagreen editaction">
				                                Edit General Information <i class="fa fa-edit"></i>
				                                </a>
					                        </div>
					                    </div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="workclassification">
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-condensed">
										<thead class="flip-content">
											<tr>
												<th>
													Category
												</th>
												<th>
													Service
												</th>
											</tr>
										</thead>
										<tbody>
											@foreach($categories as $category)
											<tr>
												<td>{{$category->Category}}</td>
												<td>
													@foreach($services as $service)
														@if($service->CmnServiceCategoryId==$category->Id)
														<input type="checkbox" checked="checked" disabled="disabled"/>
														<span class="font-green-seagreen" data-toggle="tooltip" title="{{$service->ServiceName}}"><i class="fa fa-question-circle"></i></span>
														{{$service->ServiceCode}}
													@endif
													@endforeach
												</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
								<div class="row">
			                        <div class="col-md-12">
		                                <a href="{{URL::to('consultant/applyserviceworkclassification/'.$consultantId.'?redirectUrl=consultant/applyserviceconfirmation&confedit=1&edit=true')}}" class="btn green-seagreen">
		                                Edit Work Classification <i class="fa fa-edit"></i>
		                                </a>
			                        </div>
			                    </div>
							</div>
							<div class="tab-pane" id="humanresource">
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-condensed">
										<thead>
											<th>
												 Name
											</th>
											<th width="6%">
												 CID/Work Permit No.
											</th>
											<th width="5%">
												 Sex
											</th>
											<th class="">
												 Country
											</th>
											<th width="6%">
												 Qualification
											</th>
											<th>
												 Designation
											</th>
											<th>
												Trade/Fields
											</th>
											<th>
												Attachments(CV/UT/AT)
											</th>
										</thead>
										<tbody>
											@foreach($consultantHumanResources as $consultantHumanResource)
											<tr>
												<td>{{$consultantHumanResource->Salutation.' '.$consultantHumanResource->Name}}</td>
												<td>{{$consultantHumanResource->CIDNo}}</td>
												<td>{{$consultantHumanResource->Sex}}</td>
												<td>{{$consultantHumanResource->Country}}</td>
												<td>{{$consultantHumanResource->Qualification}}</td>
												<td>{{$consultantHumanResource->Designation}}</td>
												<td>{{$consultantHumanResource->Trade}}</td>
												<td>
													@foreach($humanResourcesAttachments as $humanResourcesAttachment)
														@if($consultantHumanResource->Id==$humanResourcesAttachment->CrpConsultantHumanResourceId)
															<i class="fa fa-check"></i> <a href="{{URL::to($humanResourcesAttachment->DocumentPath)}}" target="_blank">{{$humanResourcesAttachment->DocumentName}}</a><br />
														@endif
													@endforeach
												</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
								<div class="row">
			                        <div class="col-md-12">
		                                <a href="{{URL::to('consultant/applyservicehumanresource/'.$consultantId.'?redirectUrl=consultant/applyserviceconfirmation&confedit=1&edit=true')}}" class="btn green-seagreen">
		                                Edit Human Resource <i class="fa fa-edit"></i>
		                                </a>
			                        </div>
			                    </div>
							</div>
							<div class="tab-pane" id="equipment">
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-condensed">
										<thead>
											<tr>
												<th>
													Equipment Name
												</th>
												<th>
													 Registration No
												</th>
												<th>
													Equipment Model
												</th>
												<th>
													Quantity
												</th>
												<th>
													Attachment
												</th>
											</tr>
										</thead>
										<tbody>
											@foreach($consultantEquipments as $consultantEquipment)
											<tr>
												<td>{{$consultantEquipment->Name}}</td>
												<td>{{$consultantEquipment->RegistrationNo}}</td>
												<td>{{$consultantEquipment->ModelNo}}</td>
												<td>{{$consultantEquipment->Quantity}}</td>
												<td>
													@foreach($equipmentsAttachments as $equipmentsAttachment)
														@if($consultantEquipment->Id==$equipmentsAttachment->CrpConsultantEquipmentId)
														<i class="fa fa-check"></i> <a href="{{URL::to($equipmentsAttachment->DocumentPath)}}" target="_blank">{{$equipmentsAttachment->DocumentName}}</a><br />
														@endif
													@endforeach
												</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
								<div class="row">
			                        <div class="col-md-12">
		                                <a href="{{URL::to('consultant/applyserviceequipment/'.$consultantId.'?redirectUrl=consultant/applyserviceconfirmation&confedit=1&edit=true')}}" class="btn green-seagreen">
		                                Edit Equipments<i class="fa fa-edit"></i>
		                                </a>
			                        </div>
			                    </div>
							</div>
						</div>
					</div>
					<!-- Start the declaration Here -->
					<span class="bold">I/We declare and confirm that:-</span>
					<ul>
						<li>All information and attachments with this application are true and correct;</li>
						<li>I am/we are aware that any false information provided herein will result in rejection of my application and suspension of any registered granted;</li>
						<li>I/We shall not make refund claims of expenditure incurred in processing this application;</li>
						<li>I/We have read and understood the 'Code of Ethics' and shall perform in line with Code of Ethics and any other legislation in force. Failure to comply, will be subject to the penalities provided for in the applicable legislation of the country.</li>
						<li>I/We hereby declare that issue of CDB certificate does not in anyway constitute an obligation on the part of CDB or any other Goverment agency to provide contract works.</li>
					</ul>
					<div class="form-group">
						<label>
						<input type="checkbox" id="agreement-checkbox" name="tnc" class=""/> I agree to the <span class="bold">Terms of Service</span>
						</label>
					</div>
					<div class="form-actions">
						{{ Form::open(array('url' => 'consultant/mserviceconfirmation','role'=>'form'))}}
						<input type="hidden" name="ConsultantId" value="{{$consultantId}}" />
						<div class="btn-set">
							<button type="submit" id="submit-application" class="btn blue">Submit<i class="fa fa-arrow-circle-o-right"></i></button>
						</div>
						{{Form::close()}}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
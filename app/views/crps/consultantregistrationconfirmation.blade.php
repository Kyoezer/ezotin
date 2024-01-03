@extends('homepagemaster')
@section('content')
<?php
	include public_path()."/captcha/simple-php-captcha.php";
	?>
	<?php
	$_SESSION['captcha'] = simple_php_captcha(
			array(
					'min_font_size' => 24,
					'max_font_size' => 24,
			)
	);
	$imgSrc = $_SESSION['captcha']['image_src'];
	$indexOfQuestionMark = strpos($imgSrc,'?');
	$captchaUrl = substr($imgSrc,$indexOfQuestionMark+1,strlen($imgSrc));
?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i> Consultant Registration - <span class="step-title">Confirmation </span>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-body">
					<div class="note note-info">
		              	<p>If you need to edit any information, click on the corresponding button. Accept the <span class="bold">Terms & Conditions</span> to submit to CDB.</p>
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
													<td>{{$generalInformation[0]->OwnershipType}}</td>
												</tr>
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
								@if((int)$generalInformation[0]->OwnershipTypeReferenceNo!=14001)
								<div class="row">
									<div class="col-md-6">
										<h5 class="font-blue-madison bold">Certificate of Incorporation</h5>
										<table class="table table-bordered table-striped table-condensed">
											<thead>
												<tr>
													<th>
														Name of Document
													</th>
												</tr>
											</thead>
											<tbody>
												@foreach($incorporationOwnershipTypes as $incorporationOwnershipType)
												<tr>
													<td>
														<i class="fa fa-check"></i> <a href="{{URL::to($incorporationOwnershipType->DocumentPath)}}" target="_blank">{{$incorporationOwnershipType->DocumentName}}</a>
													</td>
												</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
								@endif
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
															{{$ownerPartnerDetail->Salutation.' '.$ownerPartnerDetail->Name}}
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
				                                <a href="{{URL::to('consultant/generalinforegistration/'.$consultantId.'?editbyapplicant=true')}}" class="btn green-seagreen editaction">
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
														<span class="font-green-seagreen tooltips" data-placement="top" data-original-title="{{$service->ServiceName}}"><i class="fa fa-question-circle"></i></span>
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
		                                <a href="{{URL::to('consultant/workclassificationregistration/'.$consultantId)}}" class="btn green-seagreen editaction">
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
														@if($humanResourcesAttachment->CrpConsultantHumanResourceId==$consultantHumanResource->Id)
														<i class="fa fa-check"></i> <a href="{{URL::to($humanResourcesAttachment->DocumentPath)}}" target="_blank">{{$humanResourcesAttachment->DocumentName}}</a><br/>
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
		                                <a href="{{URL::to('consultant/humanresourceregistration/'.$consultantId)}}" class="btn green-seagreen editaction">
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
														@if($equipmentsAttachment->CrpConsultantEquipmentId==$consultantEquipment->Id)
														<i class="fa fa-check fa-2"></i> <a href="{{URL::to($equipmentsAttachment->DocumentPath)}}" target="_blank">{{$equipmentsAttachment->DocumentName}}</a><br/>
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
		                                <a href="{{URL::to('consultant/equipmentregistration/'.$consultantId)}}" class="btn green-seagreen editaction">
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
							<input type="checkbox" name="tnc" id="agreement-checkbox" class=""/> I agree to the above <span class="bold">Terms & Conditions</span>
						</label>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label class="control-label">Scanned copy of Signature</label>
								<input type="file" class="form-control input" />
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<br>
					{{ Form::open(array('url' => 'consultant/mconfirmregistration','role'=>'form'))}}
					<div class="col-md-4">
						<img src="{{URL::to('/')."/captcha/simple-php-captcha.php?".$captchaUrl}}"/>
					</div><div class="clearfix"></div><br/>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Enter the Charaters show above:</label>
							<input type="text" name="CaptchaAnswer" class="form-control input" />
						</div>
					</div>
					<div class="clearfix"></div>
					<br>
					<div class="form-actions">

						<input type="hidden" name="ConsultantId" value="{{$consultantId}}" />
						<div class="btn-set">
							<button type="submit" id="submit-application" class="btn blue">Submit<i class="fa fa-arrow-circle-o-right"></i></button>
						</div>

					</div>
					{{Form::close()}}
				</div>
			</div>
		</div>
	</div>
</div>
@stop
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
		<div class="portlet light bordered" id="form_wizard_1">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i> Contractor Registration - <span class="step-title">Confirmation </span>
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
													<td><strong>Ownership Type</strong></td>
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
										<h5 class="font-blue-madison bold">Attachments</h5>
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
				                                <a href="{{URL::to('contractor/generalinforegistration/'.$contractorId.'?editbyapplicant=true')}}" class="editaction btn green-seagreen">
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
										<thead>
											<tr>
												<th>
													Category
												</th>
												<th>
													Class
												</th>
											</tr>
										</thead>
										<tbody>
											@foreach($appliedWorkClassifications as $appliedWorkClassification)
											<tr>
												<td>
													{{$appliedWorkClassification->Code.'('.$appliedWorkClassification->Category.')'}}
												</td>
												<td>
													{{$appliedWorkClassification->Classification}}
												</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
								<div class="row">
			                        <div class="col-md-12">
		                                <a href="{{URL::to('contractor/workclassificationregistration/'.$contractorId)}}" class="editaction btn green-seagreen">
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
											@foreach($contractorHumanResources as $contractorHumanResource)
											<tr>
												<td>{{$contractorHumanResource->Salutation.' '.$contractorHumanResource->Name}}</td>
												<td>{{$contractorHumanResource->CIDNo}}</td>
												<td>{{$contractorHumanResource->Sex}}</td>
												<td>{{$contractorHumanResource->Country}}</td>
												<td>{{$contractorHumanResource->Qualification}}</td>
												<td>{{$contractorHumanResource->Designation}}</td>
												<td>{{$contractorHumanResource->Trade}}</td>
												<td>
												@foreach($humanResourcesAttachments as $humanResourcesAttachment)
													@if($humanResourcesAttachment->CrpContractorHumanResourceId==$contractorHumanResource->Id)
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
		                                <a href="{{URL::to('contractor/humanresourceregistration/'.$contractorId)}}" class="editaction btn green-seagreen">
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
												<th scope="col" class="">
													 Registration No
												</th>
												<th scope="col" class="">
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
											@foreach($contractorEquipments as $contractorEquipment)
											<tr>
												<td>{{$contractorEquipment->Name}}</td>
												<td>{{$contractorEquipment->RegistrationNo}}</td>
												<td>{{$contractorEquipment->ModelNo}}</td>
												<td>{{$contractorEquipment->Quantity}}</td>
												<td>
													@foreach($equipmentsAttachments as $equipmentsAttachment)
														@if($equipmentsAttachment->CrpContractorEquipmentId==$contractorEquipment->Id)
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
		                                <a href="{{URL::to('contractor/equipmentregistration/'.$contractorId)}}" class="editaction btn green-seagreen">
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

					<span class="bold">Terms & Conditions of CDB Certification</span> <br>
					1.	 As provided in clause 2.1.1.2 and 2.3.1 of Procurement Rules and Regulations 2009, the holder of this Certificate is qualified to participate in public procurement procedure.
					<br><br>

					2.	The issuance of CDB Registration Certificate will be based largely on the fulfillment of the minimum criteria set against classification of Contractor/Consultant and Categorization of Works and upon certification by competent authority for construction professionals.
					<br><br>

					3.	All the registered contractors should comply with 'Code of ethics for Contractors'. <br><br>

					4.	CDB will not be accountable for any false/fabricated submission that could have led to the fulfillment of the criteria and subsequent issue of CDB Registration Certificate.
					<br><br>

					5.	CDB Registration Certificate once issued would not relieve the certificate holder of any relaxation on the minimum requirements for registration.
					<br><br>

					6.	Notwithstanding the provisions of Companies Act of Bhutan, the certificate issued is non-transferable even if the promoters separate and establish similar companies.
					<br><br>

					7.	CDB Certificate cannot be leased or subleased to any individual or another firm. <br><br>

					8.	Certificate is valid during the period for which it was issued provided it has not been cancelled, suspended or revoked by CDB or any other competent authority.
					<br><br>

					9.	Failing to renew within the expiry date will lead to penalty of Nu.100 per day. <br><br>

					10.	Failing to pay the fees for approved online application within 30 days will lead to cancellation of the application.
					<br><br>

					11.	All registered construction firm must attend the mandatory refresher course in order to apply for renewal.
					<br><br>

					12.	No Contractors can submit bid, participate in bidding  or be on the contention for award if the registration has expired.
					<br><br>

					13.	No Contractors can undertake/implement works which is not within the scope of the registration.
					<br><br>

					14.	CDB may verify the resources committed for the projects as and when desires. <br><br>

					15.	The registration is subject to verification whenever the CDB so desires.  CDB will inspect the minimum mandatory requirement of manpower and equipment of Large and Medium contractors and the during the time of monitoring, every firm must extend necessary support and cooperation to CDB Officials.
					<br><br>

					16.	Large and Medium Contractors must have Office established with Signboard (requirements of office and signboard as determined by CDB)
					<br><br>

					17.	Registered firms are required to inform the CDB of any changes in their address, contact details or any pertinent particulars within one month.
					<br><br>

					18.	The CDB Registration Certificate can be revoked, downgraded, suspended or cancelled at any given time if the:
					<br>
					a.	Holder undertakes unlawful participation in the procurement process; <br>
					b.	Entity does not possess the minimum requirements during the physical verification process (at the discretion of CDB);
					<br>
					c.	Entity has obtained the same due to false submissions; <br>
					d.	Entity becomes bankrupt or winds up; or <br>
					e.	Entity has been charged by the court for penal offence. <br>

					<br><br>
					This Terms of Condition is hereby endorsed and enforced with immediate effect.

					<br><br>



					(Phub Rinzin) <br>
					Director <br><br>



					{{ Form::open(array('url' => 'contractor/mconfirmregistration','role'=>'form'))}}
					<div class="form-group">
						<label>
						<input type="checkbox" id="agreement-checkbox" name="tnc" class="required"/> I agree to the above <span class="bold">Terms & Conditions</span>
						</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Scanned copy of Signature</label>
							<input type="file" class="form-control input" />
						</div>
					</div>
					<div class="clearfix"></div>
					<br>
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
						<input type="hidden" name="ContractorId" value="{{$contractorId}}" />
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
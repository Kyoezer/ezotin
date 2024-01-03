@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/specializedtrade.js') }}
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>
			<span class="caption-subject">Registration Information</span>
		</div>
		@if((int)$userSpecializedTrade==1)
		<div class="actions">
			<a href="{{URL::to('specializedtrade/viewprintdetails/'.$specializedTradeId)}}" class="btn btn-small blue-madison" target="_blank"><i class="fa fa-print"></i> Print Information</a>
			<a href="{{URL::to('specializedtrade/certificate/'.$specializedTradeId)}}" class="btn btn-small green-seagreen" target="_blank"><i class="fa fa-print"></i> Print Certificate</a>
		</div>
		@endif
	</div>
	<div class="row form-froup">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <img src='https://www.citizenservices.gov.bt/BtImgWS/ImageServlet?type=PH&cidNo=${App_Details.cidNo}'  width='200px'  height='200px' class='pull-right'/>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                    <div class="table-responsive">
					@foreach($specializedTradeInformations as $specializedTradeInformation)
					    <div class="col-md-6 table-responsive">
							<table class="table">
								<tbody>
									<tr>
										<td colspan="2" class="font-blue-madison bold warning">Personal Information</td>
									</tr>
									<tr>
										<td>
											<table class="table table-condensed">
												<tr>
													<td><strong>Full Name: </strong></td>
													<td>{{$specializedTradeInformation->Salutation.' '.$specializedTradeInformation->Name}}</td>
												</tr>
												<tr>
													<td><strong>CID Number: </strong></td>
													<td>{{$specializedTradeInformation->CIDNo}}</td>
												</tr>
												<tr>
													<td><strong>Contact Number: </strong></td>
													<td>{{$specializedTradeInformation->MobileNo}}</td>
												</tr>
												<tr>
													<td><strong>Email Address: </strong></td>
													<td>{{$specializedTradeInformation->Email}}</td>
												</tr>
												<tr>
													<td><strong>Village: </strong></td>
													<td>{{$specializedTradeInformation->Village}}</td>
												</tr>
												<tr>
													<td><strong>Gewog: </strong></td>
													<td>{{$specializedTradeInformation->Gewog}}</td>
												</tr>
												<tr>
													<td><strong>Dzongkhag: </strong></td>
													<td>{{$specializedTradeInformation->Dzongkhag}}</td>
												</tr>
												<tr>
													<td><strong>Country: </strong></td>
													<td>{{$specializedTradeInformation->Country}}</td>
												</tr>
												<tr>
													<td><strong>Employer Name</strong></td>
													<td>{{'M/s.'.$specializedTradeInformation->EmployerName}}</td>
												</tr>
												<tr>
													<td><strong>Employer Address</strong></td>
													<td>{{$specializedTradeInformation->EmployerAddress}}</td>
												</tr>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
                        <div class="col-md-6 table-responsive">
                            <table class="table">
                                <tbody>
                                <td colspan="1" class="font-blue-madison bold warning">Category Information</td>
                                <tr>
                                    <td>
                                        <table class="table table-condensed">
										    <thead>
												<tr>
													<th>Category</th>
													<th width="5%" class="table-checkbox">Applied</th>
													<th width="5%" class="table-checkbox">Verified</th>
													<th width="5%" class="table-checkbox">Approved</th>
												</tr>
											</thead>
											<tbody>
												@foreach($workClasssifications as $workClasssification)
													<tr>
														<td>
															{{$workClasssification->Code.' ('.$workClasssification->Name.')'}}
														</td>
														<td class="text-center">
															@if((bool)$workClasssification->CmnAppliedCategoryId!=NULL)
															<i class="fa fa-check font-green-seagreen"></i>
															@else
															<i class="fa fa-times font-red"></i>
															@endif
														</td>
														<td class="text-center">
															@if((bool)$workClasssification->CmnVerifiedCategoryId!=NULL)
															<i class="fa fa-check font-green-seagreen"></i>
															@else
															<i class="fa fa-times font-red"></i>
															@endif
														</td>
														<td class="text-center">
															@if((bool)$workClasssification->CmnApprovedCategoryId!=NULL)
															<i class="fa fa-check font-green-seagreen"></i>
															@else
															<i class="fa fa-times font-red"></i>
															@endif
														</td>
													</tr>
												@endforeach
											</tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 table-responsive">
                            <table class="table">
                                <tbody>
                                <td colspan="1" class="font-blue-madison bold warning">CDB Certificate Information</td>
                                <tr>
                                    <td colspan="1">
                                        <table class="table table-condensed">
                                            <tr>
                                                <td><strong>SPNo: </strong></td>
                                                <td>{{$specializedTradeInformation->SPNo}}</td>
                                            </tr>
                                                                                        <tr>
                                                <td><strong>Registration Expiry Date</strong></td>
												<td>
                                                @if($specializedTradeInformation->RegistrationExpiryDate<=date('Y-m-d G:i:s'))
												<p class="font-red bold warning">	{{convertDateToClientFormat($specializedTradeInformation->RegistrationExpiryDate)}} </p>
												@else
												{{convertDateToClientFormat($specializedTradeInformation->RegistrationExpiryDate)}}
												@endif
                                                </td>
                                            </tr>
											@if($specializedTradeInformation->RegistrationExpiryDate<=date('Y-m-d G:i:s'))

										    <tr>
												<td><strong>Status:</strong></td>
												<td class="font-red bold warning">Expired</td>
											</tr>
											
										@else
											<tr>
												<td><strong>Status</strong></td>
												<td>{{$specializedTradeInformation->Status}}</td>
											</tr>		
										@endif
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>        
				        <div class="col-md-6 table-responsive">
							<table class="table">
                                <tbody>
									<td colspan="1" class="font-blue-madison bold warning">Attachments</td>
									<tr>
										<td>
											<table class="table table-condensed">
											@foreach($specializedTradeAttachments as $specializedTradeAttachment)
												<tr>
												<td><strong>Document Name: </strong></td>
												<td>
													<a href="{{URL::to($specializedTradeAttachment->DocumentPath)}}" target="_blank">{{$specializedTradeAttachment->DocumentName}}</a>
												</td>
												</tr>
												@endforeach
											</table>
										</td>
									</tr>
                                </tbody>
                            </table>
				        </div>
				@endforeach
			</div>
			</div>
			<div class="col-md-12">
					<h5 class="font-blue-madison bold"> Curriculum Vitae/Employment History</h5>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th colspan='2' style="text-align:center">Date</th>
								<th rowspan='2'>Employer</th>
								<th rowspan='2'>Position in the firm</th>
								<th rowspan='2'>Achievements/work executed</th>
							</tr>
							<tr>
								<th style="border: 1px solid #0000000f;">From</th>
								<th style="border: 1px solid #0000000f;">To</th>
							</tr>
						</thead>
						<tbody>
							@foreach($CV as $cvItem)
							<tr>
								<td>
									{{$cvItem->ActualStartDate}}
								</td>
								<td>
									{{$cvItem->ActualEndDate}}
								</td>
								<td>
								{{$cvItem->NameOfFirm}}
								</td>
								<td>{{$cvItem->designation}}</td>
								<td>
								{{$cvItem->workStatus}}
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>

			
			<div class="row">
                <div class="col-md-12">
                   <a href="{{URL::to('specializedtrade/editregistrationinfo/'.$specializedTradeId.'?redirectUrl=specializedtrade/editdetails&usercdb=edit')}}" class="btn blue-madison editaction"><i class="fa fa-edit"></i> Edit Information</a>
                </div>
            </div>
            
		</div>
	</div>
</div>
@stop
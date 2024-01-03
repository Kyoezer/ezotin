@extends('master')
@section('pagescripts')

@stop
@section('content')

<div class="portlet light bordered" id="form_wizard_1">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>
			<span class="caption-subject">Registration Information</span>
		</div>
		@if((int)$userSurvey==1)
		<div class="actions">
			<a href="{{URL::to('surveyor/viewprintdetails/'.$surveyId)}}" class="btn btn-small blue-madison" target="_blank"><i class="fa fa-print"></i> Print Information</a>
			<a href="{{URL::to('surveyor/certificate/'.$surveyId)}}" class="btn btn-small green-seagreen" target="_blank"><i class="fa fa-print"></i> Print Certificate</a>
		</div>
		@endif
	</div>
	<div class="row form-froup">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <img src='https://www.citizenservices.gov.bt/BtImgWS/ImageServlet?type=PH&cidNo=${App_Details.cidNo}'  width='200px'  height='200px' class='pull-right'/>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                    <div class="table-responsive">
					@foreach($surveyInformations as $surveyInformation)
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
                                                <td>{{$surveyInformation->Salutation.' '.$surveyInformation->Name}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>CID Number: </strong></td>
                                                <td>{{$surveyInformation->CIDNo}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Contact Number: </strong></td>
                                                <td>{{$surveyInformation->MobileNo}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Email Address: </strong></td>
                                                <td>{{$surveyInformation->Email}}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <table class="table table-condensed">
                                            <tr>
                                                <td><strong>Village: </strong></td>
                                                <td>{{$surveyInformation->Village}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Gewog: </strong></td>
                                                <td>{{$surveyInformation->Gewog}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Dzongkhag: </strong></td>
                                                <td>{{$surveyInformation->Dzongkhag}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Country: </strong></td>
                                                <td>{{$surveyInformation->Country}}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <hr />
                        <div class="col-md-6 table-responsive">
                            <table class="table">
                                <tbody>
                                <td colspan="1" class="font-blue-madison bold warning">Qualification Information</td>
                                <tr>
                                    <td>
                                        <table class="table table-condensed">
                                            <tr>
                                                <td><strong>Qualification: </strong></td>
                                                <td>{{$surveyInformation->Qualification}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Year of Graduation: </strong></td>
                                                <td>{{$surveyInformation->GraduationYear}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Country of Study: </strong></td>
                                                <td>{{$surveyInformation->UniversityCountry}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>University Name: </strong></td>
                                                <td>{{$surveyInformation->NameOfUniversity}}</td>
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
                                <td colspan="1" class="font-blue-madison bold warning">CDB Certificate Information</td>
                                <tr>
                                    <td colspan="1">
                                        <table class="table table-condensed">
                                            <tr>
                                                <td><strong>AR Number: </strong></td>
                                                <td>{{$surveyInformation->ARNo}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Type: </strong></td>
                                                <td>{{$surveyInformation->SurveyType}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Trade: </strong></td>
                                                <td>{{$surveyInformation->Trade}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Registration Expiry Date</strong></td>
												<td>
                                                @if($surveyInformation->RegistrationExpiryDate<=date('Y-m-d G:i:s'))
												<p class="font-red bold warning">	{{convertDateToClientFormat($surveyInformation->RegistrationExpiryDate)}} </p>
												@else
												{{convertDateToClientFormat($surveyInformation->RegistrationExpiryDate)}}
												@endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                </div>
				<hr />
                        <div class="col-md-6 table-responsive">
                            <table class="table">
                                <tbody>
                                <td colspan="1" class="font-blue-madison bold warning">Status Information</td>
                                <tr>
                                    <td>
                                        <table class="table table-condensed">
										@if($surveyInformation->CmnApplicationRegistrationStatusId != CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
											<tr>
												<td><strong>Status</strong></td>
												<td class="font-yellow bold warning">{{$surveyInformation->Status}}</td>
											</tr>

												@if($surveyInformation->CmnApplicationRegistrationStatusId == 'b1fa6607-b1dd-11e4-89f3-080027dcfac6')
													<tr>
														<td><strong>Deregistered Date</strong></td>
														<td>{{convertDateToClientFormat($surveyInformation->DeRegisteredDate)}}</td>
													</tr>
													<tr>
														<td><strong>Reason</strong></td>
														<td>{{$surveyInformation->DeregisteredRemarks}}</td>
													</tr>
												@endif
										@elseif($surveyInformation->RegistrationExpiryDate<=date('Y-m-d G:i:s'))
											<tr>
											<td><strong>Status</strong></td>
												<td class="font-red bold warning">Expired</td>
												
											</tr>
										@else
										<tr>
										<td><strong>Status</strong></td>
												<td>{{$surveyInformation->Status}}</td>
											</tr>
										
										@endif
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
				@endforeach
				
			</div>
			
			<div class="row">
                <div class="col-md-12">
                   <a href="{{URL::to('surveyor/editregistrationinfo/'.$surveyId.'?redirectUrl=surveyor/editdetails&usercdb=edit')}}" class="btn blue-madison editaction"><i class="fa fa-edit"></i> Edit</a>
                </div>
            </div>
            
		</div>
	</div>
</div>
@stop
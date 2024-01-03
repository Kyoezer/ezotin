@extends('horizontalmenumaster')
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
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered" id="form_wizard_1">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>
					<span class="caption-subject">Confirmation</span>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-body">
					<div class="note note-info">
		              	<p>If you need to edit any information, click on the corresponding button. Accept the <span class="bold">Terms & Conditions</span> to submit to CDB.</p>
		            </div>
					<div class="row">
						@foreach($specializedTradeInformations as $specializedTradeInformation)
						<div class="col-md-6">
							<h5 class="font-blue-madison bold">General Information</h5>
							<table class="table table-bordered table-striped table-condensed">
								<tbody>
									<tr>
										<td><strong>Application No. (Dt.)</strong></td>
										<td>{{$specializedTradeInformation->ReferenceNo.' ('.convertDateToClientFormat($specializedTradeInformation->ApplicationDate).')'}}</td>
									</tr>
									<tr>
										<td><strong>Name</strong></td>
										<td>{{$specializedTradeInformation->Salutation.' '.$specializedTradeInformation->Name}}</td>
									</tr>
									<tr>
										<td><strong>CID No.</strong></td>
										<td>{{$specializedTradeInformation->CIDNo}}</td>
									</tr>
									<tr>
										<td><strong>Dzongkhag</strong></td>
										<td>{{$specializedTradeInformation->Dzongkhag}}</td>
									</tr>
									<tr>
										<td><strong>Gewog</strong></td>
										<td>{{$specializedTradeInformation->Gewog}}</td>
									</tr>
									<tr>
										<td><strong>Village</strong></td>
										<td>{{$specializedTradeInformation->Village}}</td>
									</tr>
									<tr>
										<td><strong>Email</strong></td>
										<td>{{$specializedTradeInformation->Email}}</td>
									</tr>
									<tr>
										<td><strong>Mobile No.</strong></td>
										<td>{{$specializedTradeInformation->MobileNo}}</td>
									</tr>
									<tr>
										<td><strong>Telephone No.</strong></td>
										<td>{{$specializedTradeInformation->MobileNo}}</td>
									</tr>
									<tr>
										<td><strong>Employer Name</strong></td>
										<td>{{'M/s.'.$specializedTradeInformation->EmployerName}}</td>
									</tr>
									<tr>
										<td><strong>Employer Address</strong></td>
										<td>{{$specializedTradeInformation->EmployerAddress}}</td>
									</tr>
								</tbody>
							</table>
						</div>
						@endforeach
						<div class="col-md-6">
							<h5 class="font-blue-madison bold">Category Information</h5>
							<table class="table table-bordered table-striped table-condensed">
								<thead>
									<tr>
										<th>Category</th>
										<th width="5%" class="table-checkbox">Applied</th>
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
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						<div class="col-md-6">
							<h5 class="font-blue-madison bold">Attachments</h5>
							<table class="table table-bordered table-striped table-condensed">
								<thead>
									<tr>
										<th>Document Name</th>
									</tr>
								</thead>
								<tbody>
									@forelse($specializedTradeAttachments as $specializedTradeAttachment)
									<tr>
										<td>
											<a href="{{URL::to($specializedTradeAttachment->DocumentPath)}}" target="_blank">{{$specializedTradeAttachment->DocumentName}}</a>
										</td>
									</tr>
									@empty
									<tr>
										<td class="text-center font-red">No data to display</td>
									</tr>
									@endforelse
								</tbody>
							</table>
						</div>
						<div class="col-md-12">
		                   <a href="{{URL::to('specializedtrade/applyrenewalregistrationdetails/'.$specializedTradeId.'?redirectUrl=specializedtrade/applyrenewalconfirmation&editconf='.$specializedTradeId)}}" class="btn green-seagreen editaction">
	                    		Edit <i class="fa fa-edit"></i>
	                    	</a>
		                </div>
					</div><br />
                    <div class="row">
                    	<div class="col-md-12">
							<!-- Start the declaration Here -->
							<span class="bold">I/We declare and confirm that:-</span>
							<ul>
								<li>All information and attachments with this application are true and correct;</li>
								<li>I am/we are aware that any false information provided herein will result in rejection of my application and suspension of any registered granted;</li>
								<li>I/We have read and understood the 'Code of Ethics' and shall perform in line with Code of Ethics and any other legislation in force. Failure to comply, will be subject to the penalities provided for in the applicable legislation of the country.</li>
							</ul>
							<div class="form-group">
								<label>
								<input type="checkbox" id="agreement-checkbox" name="tnc" class=""/> I agree to the above <span class="bold">Terms & Conditions</span>
								</label>
							</div>
						</div>
					</div>
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
						{{ Form::open(array('url' => 'specializedtrade/mconfirmregistration','role'=>'form'))}}
						<input type="hidden" name="CrpSpecializedTradeId" value="{{$specializedTradeId}}" />
						<input type="hidden" name="IsServiceBySpecializedTrade" value="{{$isServiceBySpecializedTrade}}" />
						<div class="btn-set">
							<button type="submit" id="submit-application" class="btn blue">Submit <i class="fa fa-arrow-circle-o-right"></i></button>
						</div>
						{{Form::close()}}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
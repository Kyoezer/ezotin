@extends('websitemaster')
@section('main-content')
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

	<div class="col-md-8 col-xs-12 col-sm-12">
		@foreach ($tenderDetails as $tenderDetail)
			<table class="table table-responsive table-condensed table-bordered">
				<tbody>
					<tr>
						<td><strong>Procuring Agency</strong></td>
						<td>{{ $tenderDetail -> ProcuringAgencyName }}</td>
					</tr>
					<tr>
						<td><strong>Name of Work</strong></td>
						<td>{{ $tenderDetail -> NameOfWork }}</td>
					</tr>
					<tr>
						<td><strong>Description of Work</strong></td>
						<td>{{ HTML::decode($tenderDetail -> DescriptionOfWork) }}</td>
					</tr>
					<tr>
						<td><strong>Contract Period (in Months)</strong></td>
						<td>{{ $tenderDetail -> ContractPeriod }}</td>
					</tr>
					<tr>
						<td><strong>Dzongkhag (Site)</strong></td>
						<td>{{ $tenderDetail -> Dzongkhag }}</td>
					</tr>
					<tr>
						<td><strong>Category of Work</strong></td>
						<td>{{ $tenderDetail -> WorkCategoryCode.' - '.$tenderDetail -> WorkCategory }}</td>
					</tr>
					<tr>
						<td><strong>Classification of Work</strong></td>
						<td>{{ $tenderDetail -> WorkClassificationCode.' - '.$tenderDetail -> WorkClassification }}</td>
					</tr>
					<tr>
						<td><strong>Cost of Tender (Nu.)</strong></td>
						<td>{{ $tenderDetail -> CostOfTender }}</td>
					</tr>
					<tr>
						<td><strong>Date of Sale of Tender</strong></td>
						<td>{{ convertDateToClientFormat($tenderDetail -> DateOfSaleOfTender) }}</td>
					</tr>
					<tr>
						<td><strong>Date of Closing for Sale of Tender</strong></td>
						<td>{{ convertDateToClientFormat($tenderDetail -> DateOfClosingSaleOfTender) }}</td>
					</tr>
					<tr>
						<td><strong>Last Date And Time Of Submission</strong></td>
						<td>{{ convertDateTimeToClientFormat($tenderDetail -> LastDateAndTimeOfSubmission) }}</td>
					</tr>
					<tr>
						<td><strong>Tender Opening Date And Time</strong></td>
						<td>{{ convertDateTimeToClientFormat($tenderDetail -> TenderOpeningDateAndTime) }}</td>
					</tr>
					<tr>
						<td><strong>EMD (Nu. or %)</strong></td>
						<td>{{ $tenderDetail -> EMD }}</td>
					</tr>
					<tr>
						<td><strong>Project Estimate Cost (Nu.)</strong></td>
						<td>
							@if((int)$tenderDetail -> ShowCostInWebsite==1)
							{{ $tenderDetail -> ProjectEstimateCost }}
							@else
							<span class="text-danger">Not Available</span>
							@endif
						</td>
					</tr>
					<tr>
						<td><strong>Contact Person</strong></td>
						<td>{{ $tenderDetail -> ContactPerson }}</td>
					</tr>
					<tr>
						<td><strong>Contact No.</strong></td>
						<td>{{ $tenderDetail -> ContactNo }}</td>
					</tr>
					<tr>
						<td><strong>Contact Email</strong></td>
						<td>{{ $tenderDetail -> ContactEmail }}</td>
					</tr>
				</tbody>
			</table>
		@endforeach
	</div>
	@if((int)$attachmentCount>0)
	<div class="col-md-4">
		<a href="#downloadTender" role="button" class="btn btn-large btn-primary" data-toggle="modal">Download Tender Document <span class="fa fa-download"></span></a>
	</div>
	@endif
</div>

<div id="downloadTender" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			{{ Form::open(array('action'=>'WebsiteTender@downloadTender', 'method'=>'POST')) }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Download Tender</h4>
				</div>
				<div class="modal-body">
					<div class="col-md-12">
					<div class="alert alert-danger hide" id="tendererrormessage">
						<p>Please enter either your Email or Mobile No.</p>
					</div>
					<p>Please enter either your email or phone number to continue your download.</p>
					<label>Email Address:</label>
					<input type="email" name="Email" class="form-control email" id="tenderdownloademail" placeholder="Email Address">

					<label>Mobile Number:</label>
					<input type="text" name="PhoneNo" class="form-control number" id="tenderdownloadphone" placeholder="Phone Number">

					<input type="hidden" name="TenderId" value="{{isset($tenderDetails[0]->Id)?$tenderDetails[0]->Id:''}}">
					</div>

					<div class="col-md-4">
						<br>
						<img src="{{URL::to('/')."/captcha/simple-php-captcha.php?".$captchaUrl}}"/>
					</div>
					<div class="col-md-4">
						<br><br>
						<input type="text" placeholder="Answer" name="CaptchaAnswer" class="form-control input-small">
					</div><div class="clearfix"></div>
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-success" id="downloadtenderdocuments">Submit</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
@stop
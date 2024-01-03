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
			@foreach($architectInformations as $architectInformation)
			<div class="row">
				<div class="col-md-6">
					<table class="table table-bordered table-striped table-condensed">
						<tbody>
							<tr>
								<td><strong>Type of Architect </strong></td>
								<td>{{$architectInformation->ArchitectType}}</td>
							</tr>
							<tr>
								<td><strong>Name</strong></td>
								<td>{{$architectInformation->Salutation.' '.$architectInformation->Name}}</td>
							</tr>
							<tr>
								<td><strong>CID No./Work Permit No.</strong></td>
								<td>{{$architectInformation->CIDNo}}</td>
							</tr>
							<tr>
								<td><strong>Country</strong></td>
								<td>{{$architectInformation->Country}}</td>
							</tr>
							<tr>
								<td><strong>Dzongkhag</strong></td>
								<td>{{$architectInformation->Dzongkhag}}</td>
							</tr>
							<tr>
								<td><strong>Gewog</strong></td>
								<td>{{$architectInformation->Gewog}}</td>
							</tr>
							<tr>
								<td><strong>Village</strong></td>
								<td>{{$architectInformation->Village}}</td>
							</tr>
							<tr>
								<td><strong>Email</strong></td>
								<td>{{$architectInformation->Email}}</td>
							</tr>
							<tr>
								<td><strong>Mobile No.</strong></td>
								<td>{{$architectInformation->MobileNo}}</td>
							</tr>
							<tr>
								<td><strong>Employer Name</strong></td>
								<td>{{'M/s.'.$architectInformation->EmployerName}}</td>
							</tr>
							<tr>
								<td><strong>Employer Address</strong></td>
								<td>{{$architectInformation->EmployerAddress}}</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-md-6">
					<h5 class="font-blue-madison bold">Professional Qualification</h5>
					<table class="table table-bordered table-striped table-condensed flip-content">
						<tbody>
							<tr>
								<td><strong>Qualification</strong></td>
								<td>{{$architectInformation->Qualification}}</td>
							</tr>
							<tr>
								<td><strong>Year of Graduation</strong></td>
								<td>{{$architectInformation->GraduationYear}}</td>
							</tr>
							<tr>
								<td><strong>Name of University</strong></td>
								<td>{{$architectInformation->NameOfUniversity}}</td>
							</tr>
							<tr>
								<td><strong>University Country</strong></td>
								<td>{{$architectInformation->UniversityCountry}}</td>
							</tr>
						</tbody>	
					</table>
				</div>
				@endforeach
				<div class="col-md-6">
					<h5 class="font-blue-madison bold">Existing Documents</h5>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Document Name</th>
							</tr>
						</thead>
						<tbody>
							@foreach($oldUploads as $oldUpload)
							<tr>
								<td>
									<a href="{{URL::to($oldUpload->DocumentPath)}}" target="_blank">{{$oldUpload->DocumentName}}</a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<div class="col-md-6">
					<h5 class="font-blue-madison bold">New Documents</h5>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Document Name</th>
							</tr>
						</thead>
						<tbody>
							@forelse($architectAttachments as $architectAttachment)
							<tr>
								<td>
									<a href="{{URL::to($architectAttachment->DocumentPath)}}" target="_blank">{{$architectAttachment->DocumentName}}</a>
								</td>
							</tr>
							@empty
							<tr>
								<td class="font-red text-center">No New Uploads</td>
							</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>		
			<div class="row">
                <div class="col-md-12">
                    <a href="{{URL::to('architect/applyrenewalregistrationdetails/'.$architectId.'?redirectUrl=architect/applyrenewalconfirmation&editconf='.$architectId)}}" class="btn green-seagreen editaction">
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
			<div class="form-actions">
				{{ Form::open(array('url' => 'architect/mconfirmregistration','role'=>'form'))}}
				<input type="hidden" name="IsServiceByArchitect" value="{{$isServiceByArchitect}}" />
				<input type="hidden" name="CrpArchitectId" value="{{$architectId}}" />
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
				<div class="btn-set">
					<button type="submit" id="submit-application" class="btn blue">Submit <i class="fa fa-arrow-circle-o-right"></i></button>
				</div>
				{{Form::close()}}
			</div>
		</div>
	</div>
</div>
@stop
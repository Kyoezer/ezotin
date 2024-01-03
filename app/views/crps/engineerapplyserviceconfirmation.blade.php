@extends('horizontalmenumaster')
@section('content')
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
				@foreach($engineerInformations as $engineerInformation)
				<div class="col-md-6">
					<table class="table table-bordered table-striped table-condensed">
						<tbody>
							<tr>
								<td><strong>Type of Engineer </strong></td>
								<td>{{$engineerInformation->EngineerType}}</td>
							</tr>
							<tr>
								<td><strong>Trade</strong></td>
								<td>{{$engineerInformation->Trade}}</td>
							</tr>
							<tr>
								<td><strong>Name</strong></td>
								<td>{{$engineerInformation->Salutation.' '.$engineerInformation->Name}}</td>
							</tr>
							<tr>
								<td><strong>CID No./Work Permit No.</strong></td>
								<td>{{$engineerInformation->CIDNo}}</td>
							</tr>
							<tr>
								<td><strong>Country</strong></td>
								<td>{{$engineerInformation->Country}}</td>
							</tr>
							<tr>
								<td><strong>Dzongkhag</strong></td>
								<td>{{$engineerInformation->Dzongkhag}}</td>
							</tr>
							<tr>
								<td><strong>Gewog</strong></td>
								<td>{{$engineerInformation->Gewog}}</td>
							</tr>
							<tr>
								<td><strong>Village</strong></td>
								<td>{{$engineerInformation->Village}}</td>
							</tr>
							<tr>
								<td><strong>Email</strong></td>
								<td>{{$engineerInformation->Email}}</td>
							</tr>
							<tr>
								<td><strong>Mobile No.</strong></td>
								<td>{{$engineerInformation->MobileNo}}</td>
							</tr>
							<tr>
								<td><strong>Employer Name</strong></td>
								<td>{{'M/s.'.$engineerInformation->EmployerName}}</td>
							</tr>
							<tr>
								<td><strong>Employer Address</strong></td>
								<td>{{$engineerInformation->EmployerAddress}}</td>
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
								<td>{{$engineerInformation->Qualification}}</td>
							</tr>
							<tr>
								<td><strong>Year of Graduation</strong></td>
								<td>{{$engineerInformation->GraduationYear}}</td>
							</tr>
							<tr>
								<td><strong>Name of University</strong></td>
								<td>{{$engineerInformation->NameOfUniversity}}</td>
							</tr>
							<tr>
								<td><strong>University Country</strong></td>
								<td>{{$engineerInformation->UniversityCountry}}</td>
							</tr>
						</tbody>	
					</table>
				</div>
				@endforeach
				<div class="col-md-6">
					<h5 class="font-blue-madison bold">Attachments</h5>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Document Name</th>
							</tr>
						</thead>
						<tbody>
							@forelse($engineerAttachments as $engineerAttachment)
							<tr>
								<td>
									<a href="{URL::to($engineerAttachment->DocumentPath)}}" target="_blank">{{$engineerAttachment->DocumentName}}</a>
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
			</div>
			<div class="row">
                <div class="col-md-12">
                	<a href="{{URL::to('engineer/applyrenewalregistrationdetails/'.$engineerId.'?redirectUrl=engineer/applyrenewalconfirmation&editconf='.$engineerId)}}" class="btn editaction green-seagreen">
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
				{{ Form::open(array('url' => 'engineer/mconfirmregistration','role'=>'form'))}}
				<input type="hidden" name="CrpEngineerId" value="{{$engineerId}}" />
				<input type="hidden" name="IsServiceByEngineer" value="{{$isServiceByEngineer}}" />
				<div class="btn-set">
					<button type="submit" id="submit-application" class="btn blue">Submit <i class="fa fa-arrow-circle-o-right"></i></button>
				</div>
				{{Form::close()}}
			</div>
		</div>
	</div>
</div>
@stop
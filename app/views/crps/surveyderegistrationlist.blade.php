@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/survey.js') }}
@stop
@section('content')
@if((int)$reRegistration==0)
<div id="deregister" class="modal fade" role="dialog" aria-labelledby="deregister" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title font-green-seagreen">Are you sure you want to deregister this surveyor?</h4>
			</div>
			{{ Form::open(array('url' => 'surveyor/blacklistandderegister','role'=>'form'))}}
			<input type="hidden" name="CrpSurveyId" value="" class="deregisterid"/>
			<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED}}">
			<div class="modal-body">
				<h4 class="displaysurveyname"></h4>
				<h4 class="displaysurveycdbno"></h4>
				<div class="form-group">
					<label class="control-label">Date</label>
					<div class="input-icon right input-medium">
						<i class="fa fa-calendar"></i>
						<input type="text" name="DeRegisteredDate" value="{{date('d-m-Y')}}" class="form-control datepicker input-sm required input-medium" readonly="readonly">
					</div>
				</div>
				<div class="form-group">
					<label>Remarks</label>
					<textarea name="DeregisteredRemarks" class="form-control required" rows="3"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn green">Deregister</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
<div id="blacklist" class="modal fade" role="dialog" aria-labelledby="blacklist" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title font-green-seagreen">Are you sure you want to Revoke/Suspend/Debarred this surveyor?</h4>
			</div>
			{{ Form::open(array('url' => 'surveyor/blacklistandderegister','role'=>'form'))}}
			<input type="hidden" name="CrpSurveyId" value="" class="blacklistId"/>
			<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_BLACKLISTED}}">
			<div class="modal-body">
				<h4 class="displaysurveyname"></h4>
				<h4 class="displaysurveycdbno"></h4>
				<div class="form-group">
					<label class="control-label">Date</label>
					<div class="input-icon right input-medium">
						<i class="fa fa-calendar"></i>
						<input type="text" name="BlacklistedDate" class="form-control datepicker input-sm required input-medium" value="{{date('d-m-Y')}}" readonly="readonly">
					</div>
				</div>
				<div class="form-group">
					<label>Remarks</label>
					<textarea name="BlacklistedRemarks" class="form-control required" rows="3"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn green">Blacklist</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
@else 
<div id="reregister" class="modal fade" role="dialog" aria-labelledby="reregister" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title font-green-seagreen">Are you sure you want to re-register this surveyor?</h4>
			</div>
			{{ Form::open(array('url' => 'surveyor/blacklistandderegister','role'=>'form'))}}
			<input type="hidden" name="CrpSurveyId" value="" class="reregisterId"/>
			<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED}}">
			<div class="modal-body">
				<h4 class="displaysurveyname"></h4>
				<h4 class="displaysurveycdbno"></h4>
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
</div>
@endif
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">{{$captionSubject}}</span>
			<span class="caption-helper">The surveyors listed below are all <span class="font-red-thunderbird bold">{{$captionHelper}}</span> surveyors.</span>
		</div>
	</div>
	<div class="portlet-body">
		<div class="form-body">
			{{ Form::open(array('url' =>Request::URL(),'role'=>'form','method'=>'get')) }}
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">AR No.</label>
						<input type="text" name="ARNo" class="form-control" value="{{$ARNo}}">
					</div>
				</div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Surveyor</label>
                        <div class="ui-widget">
                            <input type="hidden" class="survey-id" name="CrpSurveyId" value="{{Input::get('CrpSurveyId')}}"/>
                            <input type="text" name="Survey" value="{{Input::get('Survey')}}" class="form-control survey-autocomplete"/>
                        </div>
                    </div>
                </div>
				
				<div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">No. of Rows</label>
                        {{Form::select('Limit',array(10=>10,20=>20,30=>30,'All'=>'All'),Input::has('Limit')?Input::get('Limit'):20,array('class'=>'form-control'))}}
                    </div>
                </div>
				<div class="col-md-3">
					<label for="">|</label>
					<div class="btn-set">
						<button type="submit" class="btn blue-hoki btn-sm">Search</button>
						<a href="{{Request::URL()}}" class="btn grey-cascade btn-sm">Clear</a>
					</div>
				</div>
			</div>
		</div>
		{{Form::close()}}
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-condensed">
				<thead>
				
				<tr>
					<th>
						AR No.
					</th>
					<th>
						 Name
					</th>
					<th>
						CID/Work Permit No.
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
						Email
					</th>
					<th>
						Action
					</th>
				</tr>
			</thead>
				<tbody>
					@forelse($surveyLists as $surveyList)
					<tr>
					<td>
							<input type="hidden" value="{{$surveyList->Id}}" class="surveyid" />
							<input type="hidden" value="{{$surveyList->SurveyName}}" class="surrveyname" />
							<input type="hidden" value="{{$surveyList->ARNo}}" class="surveyarno" />
							 {{{$surveyList->ARNo}}}
						</td>
					<td>
							{{{$surveyList->SurveyName}}}
						</td>
						<td>
							{{{$surveyList->CIDNo}}}
						</td>
						<td>
							 {{{$surveyList->Country}}}
						</td>
						<td>
							 {{{$surveyList->Dzongkhag}}}
						</td>
						<td>
							 {{{$surveyList->MobileNo}}}
						</td>
						<td>
							{{{$surveyList->Email}}}
						</td>
						<td>
							@if((int)$reRegistration==0)
								@if((int)$type==1)
								<a href="#deregister" data-toggle="modal" class="deregistersurvey">Deregister</a>
								@else
								<a href="#blacklist" data-toggle="modal" class="blacklistsurvey">Blacklist</a>
								@endif
							@else
							<a href="#reregister" data-toggle="modal" class="reregistrationsurvey">Re-Registration</a>
							@endif
						</td>
					</tr>
					@empty
					<tr>
						<td class="font-red text-center" colspan="9">No data to display</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@stop
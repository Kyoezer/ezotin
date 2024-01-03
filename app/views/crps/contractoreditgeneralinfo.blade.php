@extends('master')
@section('pagescripts')
	{{ HTML::script('assets/global/scripts/contractor.js') }}
	@if((int)$serviceByContractor==1)
		<script>
			$('#generalinfoservice').modal('show');
		</script>
	@endif
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Edit General Information
		</div>
		@if((int)$serviceByContractor==1)
			<button id="reselectchangeownerlocation" type="button" class="btn green-seagreen btn-sm pull-right">Re-select Services</button>
		@endif
	</div>
	<div class="portlet-body">
		{{ Form::open(array('url' =>$postRouteReference,'role'=>'form','id'=>'changeoflocationaddressserviceform','files'=>true))}}
		<input type="hidden" value="1" name="EditByCdb">
		<input type="hidden" value="{{$newGeneralInfoSave}}" name="NewGeneralInfoSave">
		<input type="hidden" name="PostBackUrl" value="{{$redirectUrl}}">
		{{Form::hidden('OldApplicationId',Input::has('oldapplicationid')?Input::get('oldapplicationid'):'')}}
		@if((int)$serviceByContractor==1)
			<input type="hidden" id="is-service-application" value="1"/>
			<input type="hidden" name="CrpContractorId" value="{{$isServiceByContractor}}">
			@if((int)$isRenewalService==1)
			<input type="hidden" name="RenewalService" value="{{CONST_SERVICETYPE_RENEWAL}}" />
			@endif
			<input type="hidden" name="ServiceByContractor" value="{{$serviceByContractor}}" />
			<div id="generalinfoservice" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="generalinfoservice" aria-hidden="true">
			    <div class="modal-dialog modal-lg">
			        <div class="modal-content">
			              <div class="modal-header">
			                <h3 class="modal-title font-green-seagreen">Service</h3>
			              </div>
			              <div class="modal-body">
			              	<div class="note note-danger hide locationownererror">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
				                <p class="font-red">You should atleast select one of the services.</p>
				            </div>
							  <div class="note note-danger hide refresherattachmenterror">
								  <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
								  <p class="font-red">Please upload your refresher course certificate</p>
							  </div>
			                <p>Would you like to avail the services listed below along with this application? Please tick on the check box if you wish to.</p>
			                <div class="form-group">
								<div class="checkbox-list" id="services-list">
									<label>
										<input type="checkbox" <?php if(isset($hasExpired)): ?>@if((int)$isRenewalService == 0 && $hasExpired)disabled="disabled"@endif<?php endif; ?> data-servicetype="1" name="ChangeOfLocationOwner[]" value="{{CONST_SERVICETYPE_CHANGELOCATION}}" data-type="1"> Change of Location</label>
									<label>
										<input type="checkbox" <?php if(isset($hasExpired)): ?>@if((int)$isRenewalService == 0 && $hasExpired)disabled="disabled"@endif<?php endif; ?> data-servicetype="1" name="ChangeOfLocationOwner[]" value="{{CONST_SERVICETYPE_CHANGEOWNER}}" data-type="2"> Change of Owner</label>
									<label>
										<input type="checkbox" <?php if(isset($hasExpired)): ?>@if((int)$isRenewalService == 0 && $hasExpired)disabled="disabled"@endif<?php endif; ?> data-servicetype="1" name="ChangeOfLocationOwner[]" value="{{CONST_SERVICETYPE_CHANGEOFFIRMNAME}}" data-type="3"> Change of Firm Name</label>
									<label>
										<input type="checkbox" <?php if(isset($hasExpired)): ?>@if((int)$isRenewalService == 0 && $hasExpired)disabled="disabled"@endif<?php endif; ?> data-servicetype="1" name="ChangeOfLocationOwner[]" value="{{CONST_SERVICETYPE_INCORPORATION}}" data-type="4"> Incorporation</label>
									<label>
										<input type="checkbox" <?php if(isset($hasExpired)): ?>@if((int)$isRenewalService == 0 && $hasExpired)disabled="disabled"@endif<?php endif; ?> data-servicetype="2" class="other-service" name="OtherServices[]" value="{{CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION}}"> Upgrade/Downgrade/Add Category/Classification</label>
									<label>
									<input type="checkbox" data-servicetype="2" class="other-service" name="OtherServices[]" value="{{CONST_SERVICETYPE_UPDATEHUMANRESOURCE}}"> Update Human Resource</label>
									<label>
									<input type="checkbox" data-servicetype="2" class="other-service" name="OtherServices[]" value="{{CONST_SERVICETYPE_UPDATEEQUIPMENT}}"> Update Equipment</label>
								</div>
							</div>
							  @if(Input::has('srenew') && $isRejectedApp == 1)
								  <input type="hidden" id="IsRejected" value="1"/>
								  @if(isset($refreshersCourseCertificate))
									  <a href="{{asset($refreshersCourseCertificate)}}">Refresher's Course Certificate</a>
								  @endif
							  @endif
						  	@if(Input::has('srenew'))
							  @if($isRejectedApp == 1)
								  <h5 style="color:#f00;">Upload refresher course certificate again only if Rejected!</h5>
							  @endif
								<div class="form-group">
									<input type="hidden" name="DocumentName[]" value="Refresher Course Certificate" />
									<label>Refresher Course Certificate</label>
									<input type="file" name="attachments[]" class="input-sm" />
								</div>
							@endif
			              </div>
			              <div class="modal-footer">
			                {{--<button type="button" id="changeoflocationowner" class="btn green-seagreen" >Yes</button>--}}
			                {{--<button type="submit" id="submit-services" class="btn red">No</button>--}}
			                <button type="submit" id="changeoflocationowner" class="btn green-seagreen services-btn">Continue</button>
			              </div>
			        </div>
			    </div>
			</div>
		@endif
		@include('crps.contractorgeneralinfocontrols');
		{{Form::close()}}
	</div>
</div>
@stop
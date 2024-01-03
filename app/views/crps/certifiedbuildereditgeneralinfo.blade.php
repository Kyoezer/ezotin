@extends('master')
@section('pagescripts')
	{{ HTML::script('assets/global/scripts/certifiedbuilder.js') }}
	@if((int)$serviceByCertifiedbuilder==1)
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
	</div>
	<div class="portlet-body">
		{{ Form::open(array('url' => 'certifiedbuilder/mcertifiedbuildergeneralinfo','role'=>'form','files'=>true))}}
		<input type="hidden" value="1" name="EditByCDB">
		<input type="hidden" value="{{$newGeneralInfoSave}}" name="NewGeneralInfoSave">
		<input type="hidden" name="PostBackUrl" value="{{$redirectUrl}}">
		@if((int)$serviceByCertifiedbuilder==1)
			<input type="hidden" name="CrpCertifiedbuilderId" value="{{$isServiceByCertifiedbuilder}}">
			@if((int)$isRenewalService==1)
			<input type="hidden" name="RenewalService" value="{{CONST_SERVICETYPE_RENEWAL}}" />
			@endif
			<input type="hidden" name="serviceByCertifiedbuilder" value="{{$serviceByCertifiedbuilder}}" />
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
			                <p>Would you like to avail the services listed below along with this application? Please tick on the check box if you wish to.</p>
			                <div class="form-group">
								<div class="checkbox-list"  id="services-list">
									<label>
									<input type="checkbox" <?php if(isset($hasExpired)): ?>@if((int)$isRenewalService == 0 && $hasExpired)disabled="disabled"@endif<?php endif; ?> data-servicetype="1" name="ChangeOfLocationOwner[]" value="{{CONST_SERVICETYPE_CHANGELOCATION}}" data-type="1"> Change of Location</label>
									<label>
									<input type="checkbox" <?php if(isset($hasExpired)): ?>@if((int)$isRenewalService == 0 && $hasExpired)disabled="disabled"@endif<?php endif; ?> data-servicetype="1" name="ChangeOfLocationOwner[]" value="{{CONST_SERVICETYPE_CHANGEOWNER}}" data-type="2"> Change of Owner</label>
									<label>
									<input type="checkbox" <?php if(isset($hasExpired)): ?>@if((int)$isRenewalService == 0 && $hasExpired)disabled="disabled"@endif<?php endif; ?> data-servicetype="1" name="ChangeOfLocationOwner[]" value="{{CONST_SERVICETYPE_CHANGEOFFIRMNAME}}" data-type="3"> Change of Firm Name</label>
									<label>
									<input type="checkbox" <?php if(isset($hasExpired)): ?>@if((int)$isRenewalService == 0 && $hasExpired)disabled="disabled"@endif<?php endif; ?> data-servicetype="2" class="other-service" name="OtherServices[]" value="{{CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION}}"> Upgrade/Downgrade/Add Category/Classification</label>
									<label>
									<input type="checkbox" data-servicetype="2" class="other-service" name="OtherServices[]" value="{{CONST_SERVICETYPE_UPDATEHUMANRESOURCE}}"> Update Human Resource</label>
									<label>
									<input type="checkbox" data-servicetype="2" class="other-service" name="OtherServices[]" value="{{CONST_SERVICETYPE_UPDATEEQUIPMENT}}"> Update Equipment</label>
								</div>
							</div>
			              </div>
			              <div class="modal-footer">
						  	<button type="submit" id="changeoflocationowner" class="btn green-seagreen services-btn">Continue</button>
			              </div>
			        </div>
			    </div>
			</div>
		@endif
		@include('crps.certifiedbuildergeneralinfocontrols');
		{{Form::close()}}
	</div>
</div>
@stop
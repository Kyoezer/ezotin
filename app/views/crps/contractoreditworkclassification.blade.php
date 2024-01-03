@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/contractor.js') }}
	{{--@if((int)$serviceByContractor==1)--}}
		{{--<script>--}}
			{{--$('#workclassificationservice').modal('show');--}}
			{{--$('input.tablerowcheckbox').attr('disabled','disabled');--}}
		{{--</script>--}}
	{{--@endif--}}
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Upgrade/Downgrade
		</div>
		{{--@if((int)$serviceByContractor==1)--}}
			{{--<button id="reselectchangeofcategoryclassification" type="button" class="btn green-seagreen btn-sm pull-right">Re-select Service</button>--}}
		{{--@endif--}}
	</div>
	<div class="portlet-body">
		<?php $postUrl = "contractor/mcontractorworkclassification"; ?>
		@if(isset($final))
			@if((bool)$final)
				<?php $postUrl = "contractor/mcontractorworkclassificationfinal"; ?>
			@endif
		@endif
		{{ Form::open(array('url' => $postUrl,'role'=>'form','files'=>true))}}
		<input type="hidden" value="1" name="EditByCdb">
		<input type="hidden" name="PostBackUrl" value="{{$redirectUrl}}">
		<input type="hidden" name="ServiceByContractor" value="{{$serviceByContractor}}">
		<input type="hidden" name="CrpApplicationContractorId" value="{{$contractorId}}" />
			@if(Input::has('downgrade'))
				<input type="hidden" value="1" name="downgrade"/>
			@endif
		@if((int)$serviceByContractor==1)
			<div id="workclassificationservice" data-backdrop="static" data-keyboard="false" class="modal fade" role="dialog" aria-labelledby="workclassificationservice" aria-hidden="true">
			    <div class="modal-dialog modal-lg">
			        <div class="modal-content">
			              <div class="modal-header">
			                <h3 class="modal-title font-green-seagreen">Service</h3>
			              </div>
			              <div class="modal-body">
			              	<div class="note note-danger hide changeofcategoryclasserror">
				                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
				                <p class="font-red">Please tick on the checkbox if you wish to avail this service along with this application.</p>
				            </div>
			                <p>Would you like to avail the services listed below along with this application? Please tick on the chech box if you wish to.</p>
			                <div class="form-group">
								<div class="checkbox-list">
									<label>
									<input type="checkbox" name="ChangeInCategoryClassificationService" data-type="1" value="{{CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION}}">Upgrade/Downgrade/Add Category/Classification</label>
								</div>
							</div>
			              </div>
			              <div class="modal-footer">
			                <button type="button" id="changeofcategoryclassification" class="btn green-seagreen" >Yes</button>
			                <button type="submit" class="btn red">No</button>
			              </div>
			        </div>
			    </div>
			</div>
			
		@endif
		@if(!Input::has('monitoringofficeid'))
		<div class="note note-info"><strong>Note:</strong> If you are upgrading/downgrading, it is necessary to attach Letter of Undertaking
		</div>
		@endif
		@include('crps.contractorworkclassificationcontrols')
		{{Form::close()}}
	</div>
</div>
@stop
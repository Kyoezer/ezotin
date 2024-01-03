<p class="bold font-green-seagreen">Please tick the checkbox to select the services.</p>
<div class="note note-info">
	<p>Hover over the <i class="font-green-seagreen fa fa-question-circle"></i> icon to view what the abbreviation means.</p>
</div>
<div class="table-responsive">
	<input type="hidden" name="CrpConsultantId" value="{{$consultantId}}" />
	<table class="table table-bordered table-striped table-condensed">
		<thead class="">
			<tr>
				<th width="40%">
					Category
				</th>
				<th>
					Apply for Service
				</th>
			</tr>
		</thead>
		<tbody>
			@foreach($serviceCategories as $serviceCategory)
			<tr>
				<td>
					{{{$serviceCategory->Name}}}
				</td>
				<td>
					@foreach($services as $service)
						<?php $randomKey=randomString(); ?>
						@if($service->CmnConsultantServiceCategoryId==$serviceCategory->Id)
						<input type="hidden" name="ConsultantWorkClassificationModel[{{$randomKey}}][CrpConsultantId]" value="{{$consultantId}}" class="setselectservicecontrol" @if($service->CmnAppliedServiceId!=$service->Id)disabled="disabled"@endif/>
						<input type="hidden" name="ConsultantWorkClassificationModel[{{$randomKey}}][CmnServiceCategoryId]" value="{{$serviceCategory->Id}}" class="setselectservicecontrol" @if($service->CmnAppliedServiceId!=$service->Id)disabled="disabled"@endif/>
						<input type="checkbox" name="ConsultantWorkClassificationModel[{{$randomKey}}][CmnAppliedServiceId]" value="{{$service->Id}}" class="selectconsultantservice" @if($service->CmnAppliedServiceId==$service->Id)checked="checked"@endif/>
						<span class="font-green-seagreen tooltips" data-placement="top" data-original-title="{{$service->Name}}"><i class="fa fa-question-circle"></i></span>
						{{$service->Code}}
						@endif
					@endforeach
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
<div class="form-actions">
	<div class="btn-set">
		@if((bool)$isEdit==null)
			<button type="submit" class="btn blue">Continue <i class="fa fa-arrow-circle-o-right"></i></button>
		@else
			<button type="submit" class="btn blue">Update <i class="fa fa-arrow-circle-o-right"></i></button>
			@if(!empty($redirectUrl))
				<a href="{{URL::to($redirectUrl.'/'.$isEdit)}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
			@else
				<a href="{{URL::to('consultant/confirmregistration')}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
			@endif
		@endif
	</div>
</div>
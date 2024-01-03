<p class="bold font-green-seagreen">Please tick the checkbox to select a class.</p>
@if((int)$serviceByContractor==1)
<div class="upgradedowngradeattachments">
	<div class="col-md-6 table-responsive">
		<h5 class="font-blue-madison bold">Attachments</h5>
		<table id="upgradedowngradeattachments" class="table table-bordered table-striped table-condensed">
			<thead>
				<tr>
					<th></th>
					<th>Document Name</th>
					<th>Upload File</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<button type="button" class="deletetablerow"><i class="fa fa-times fa-lg"></i></button>
					</td>
					<td>
						<input type="text" name="DocumentName[]" readonly value="Letter of Undertaking" class="dontdisable form-control input-sm">
					</td>
					<td>
						<input type="file" name="attachments[]" class="dontdisable input-sm" multiple="multiple" />
					</td>
				</tr>
				<tr class="notremovefornew">
					<td>
						<button type="button" class="addtablerow"><i class="fa fa-plus fa-lg"></i></button>
					</td>
					<td colspan="2"></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
@endif
<div class="table-responsive">
	<input type="hidden" name="CrpContractorId" value="{{$contractorId}}" />
	<table class="table table-bordered table-striped table-condensed flip-content">
		<thead class="">
			<tr>
				<th width="5%" class="table-checkbox"></th>
				<th width="40%">
					Category
				</th>
				<th>
					 Apply for Class
				</th>
			</tr>
		</thead>
		<tbody>
			@foreach($projectCategories as $projectCategory)
			<?php $randomKey=randomString(); ?>
			<tr>
				<td>
					<input type="checkbox" class="tablerowcheckbox" value="1" @if((bool)$projectCategory->CmnAppliedClassificationId!=null){{'checked=checked'}}@endif)/>
				</td>
				<td>
					<input type="hidden" name="ContractorWorkClassificationModel[{{$randomKey}}][CrpContractorId]" value="{{$contractorId}}" class="tablerowcontrol" @if((bool)$projectCategory->CmnAppliedClassificationId==null){{"disabled=disabled"}} @endif/>
					<input type="hidden" name="ContractorWorkClassificationModel[{{$randomKey}}][CmnProjectCategoryId]" value="{{$projectCategory->Id}}" class="tablerowcontrol" @if((bool)$projectCategory->CmnAppliedClassificationId==null){{"disabled=disabled"}} @endif/>
					{{{$projectCategory->Name}}}
				</td>
				<td>
					<select name="ContractorWorkClassificationModel[{{$randomKey}}][CmnAppliedClassificationId]" class="form-control input-sm input-medium tablerowcontrol" @if((bool)$projectCategory->CmnAppliedClassificationId==null)disabled="disabled"@endif>
						<option value="">---SELECT ONE---</option>
						@if((int)$projectCategory->ReferenceNo!=6002)
							@foreach($classes as $class)
								@if((int)$class->ReferenceNo!=4)
								<option value="{{$class->Id}}" @if($projectCategory->CmnAppliedClassificationId==$class->Id)selected="selected"@endif>{{$class->Name}}</option>
								@endif
							@endforeach
						@else
							@foreach($classes as $class)
								@if((int)$class->ReferenceNo==4)
								<option value="{{$class->Id}}" @if($projectCategory->CmnAppliedClassificationId==$class->Id)selected="selected"@endif>{{$class->Name}}</option>
								@endif
							@endforeach
						@endif
					</select>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>

</div>
@if(Input::has('monitoringofficeid'))
	{{Form::hidden('MonitoringOfficeId',Input::get('monitoringofficeid'))}}
	<div class="row">
		<!-- <div class="col-md-2">
			<label class="control-label">Downgraded w.e.f</label>
			<div class="input-icon right">
				<i class="fa fa-calendar"></i>
				<input type="text" name="FromDate" class="form-control datepicker required" readonly="readonly" placeholder="">
			</div>
		</div>
		<div class="col-md-2">
			<label class="control-label">To</label>
			<div class="input-icon right">
				<i class="fa fa-calendar"></i>
				<input type="text" name="ToDate" class="form-control datepicker required" readonly="readonly" placeholder="">
			</div>
		</div> -->

		<div class="col-md-5">
			<div class="form-group">
				<label class="control-label">Remarks</label>
				<textarea name="Remarks" rows="4" class="input-sm required form-control"></textarea>
			</div>
			<br>
			<label class="control-label">Downgrade On</label>
			<div class="input-icon right">
				<i class="fa fa-calendar"></i>
				<input type="text" name="MonitoringDate" class="form-control datepicker required" value="{{Input::get('MonitoringDate')}}" readonly="readonly" placeholder="">
			</div>
		</div>
	</div>
	<br><br>
@endif
<div class="form-actions">
	<div class="btn-set">
		@if((bool)$isEdit==null)
			<button type="submit" class="btn blue">Continue <i class="fa fa-arrow-circle-o-right"></i></button>
		@else
			<button type="submit" class="btn blue">Update <i class="fa fa-arrow-circle-o-right"></i></button>
			@if(!empty($redirectUrl))
			<a href="{{URL::to($redirectUrl.'/'.$isEdit)}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
			@else 
			<a href="{{URL::to('contractor/confirmregistration')}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
			@endif
		@endif
	</div>
</div>
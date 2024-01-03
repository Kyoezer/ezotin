@if(isset($isRejectedApp) && (int)$isRejectedApp==1)
<input type="hidden" value="1" name="ApplicationRejectedReapply">
@endif
 <!-- BEGIN FORM-->
<div class="form-body">
	@foreach($specializedtradeRegistrations as $SpecializedTradeRegistration)
	<div class="row">
		<div class="col-md-9">
			<div class="form-group">
				<input type="hidden" value="{{$SpecializedTradeRegistration->Id}}" name="Id" />
				<input type="hidden" value="{{$newGeneralInfoSave}}" name="NewGeneralInfoSave">
				@if((int)$isServiceBySpecializedTrade==1)
				<input type="hidden" value="{{$specializedfirmFinalTableId}}" name="CrpSpecializedTradeId" />
				@endif
				<label class="bold">Application No.</label>
				<p class="form-control-static">
					@if(!empty($SpecializedTradeRegistration->ReferenceNo) || !empty($applicationReferenceNo))
						@if(!empty($SpecializedTradeRegistration->ReferenceNo))
							{{$SpecializedTradeRegistration->ReferenceNo}}
						@else
							{{$applicationReferenceNo}}
						@endif
					@else
						{{Input::old('ReferenceNo')}}
					@endif
				</p>
				<input type="hidden" name="ReferenceNo" value="@if(!empty($SpecializedTradeRegistration->ReferenceNo) || !empty($applicationReferenceNo))@if(!empty($SpecializedTradeRegistration->ReferenceNo)){{$SpecializedTradeRegistration->ReferenceNo}}@else{{$applicationReferenceNo}}@endif@else{{Input::old('ReferenceNo')}}@endif" />
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="bold">Application Date</label>
				<p class="form-control-static">@if(!empty($SpecializedTradeRegistration->ApplicationDate)){{convertDateToClientFormat($SpecializedTradeRegistration->ApplicationDate)}}@else{{convertDateToClientFormat(date('Y-m-d G:i:s'))}}@endif</p>
				<input type="hidden" name="ApplicationDate" value="@if(!empty($SpecializedTradeRegistration->ApplicationDate)){{$SpecializedTradeRegistration->ApplicationDate}}@else{{date('Y-m-d G:i:s')}}@endif" />
			</div>
		</div>
	</div>
	
		@endforeach
		@if(empty($editWorkClassificationsByCDB))
		<div class="col-md-4">
			<h5 class="font-blue-madison bold">Category Information</h5>
			<label class="">Please tick the checkbox to select a category</label>				
			<table class="table table-bordered table-striped table-condensed">
				<thead>
					<tr>
						<th width="5%" class="table-checkbox"></th>
						<th>Category</th>
					</tr>
				</thead>
				<tbody>
					@foreach($categories as $category)
						<tr>
							<td>
								<input type="checkbox" class="tablerowcheckbox" value="1" @if($category->CategoryId==$category->CmnAppliedCategoryId)checked="checked"@endif/>
								<input type="hidden" name="CmnAppliedCategoryId[]" value="{{$category->CategoryId}}" class="tablerowcontrol" @if($category->CategoryId!=$category->CmnAppliedCategoryId)disabled="disabled"@endif/>
							</td>
							<td>
								{{$category->Code.'-'.$category->Name}}
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@else 
		<div class="col-md-4 table-responsive">
			<h5 class="font-blue-madison bold">Edit Category Information</h5>
			<input type="hidden" name="CmnEditCategoryByCDB" value="1">
			<table class="table table-bordered table-striped table-condensed">
				<thead>
					<tr>
						<th width="5%" class="table-checkbox"></th>
						<th>Category</th>
					</tr>
				</thead>
				<tbody>
					@foreach($editWorkClassificationsByCDB as $editWorkClassificationByCDB)
						<tr>
							<td>
								<input type="checkbox" class="tablerowcheckbox" value="1" @if($editWorkClassificationByCDB->CategoryId==$editWorkClassificationByCDB->CmnApprovedCategoryId)checked="checked"@endif/>
								<input type="hidden" name="CmnAppliedCategoryId[]" value="{{$editWorkClassificationByCDB->CategoryId}}" class="tablerowcontrol" @if($editWorkClassificationByCDB->CategoryId!=$editWorkClassificationByCDB->CmnApprovedCategoryId)disabled="disabled"@endif/>
								<input type="hidden" name="CmnVerifiedCategoryId[]" value="{{$editWorkClassificationByCDB->CategoryId}}" class="tablerowcontrol" @if($editWorkClassificationByCDB->CategoryId!=$editWorkClassificationByCDB->CmnApprovedCategoryId)disabled="disabled"@endif/>
								<input type="hidden" name="CmnApprovedCategoryId[]" value="{{$editWorkClassificationByCDB->CategoryId}}" class="tablerowcontrol" @if($editWorkClassificationByCDB->CategoryId!=$editWorkClassificationByCDB->CmnApprovedCategoryId)disabled="disabled"@endif/>
							</td>
							<td>
								{{$editWorkClassificationByCDB->Code.'-'.$editWorkClassificationByCDB->Name}}
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@endif
	</div>
	<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>

<div class="form-actions">
	<div class="btn-set">
		@if((bool)$specializedTradeId==null)
		<button type="submit" class="btn green">Register <i class="fa fa-arrow-circle-o-right"></i></button>
		<a href="{{URL::to('ezhotin/home/'.Session::get('UserViewerType'))}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
		@else
		<button type="submit" class="btn green">Update <i class="fa fa-arrow-circle-o-right"></i></button>
			@if(!empty($redirectUrl))
			<a href="{{URL::to($redirectUrl.'/'.$specializedTradeId)}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
			@else
			<a href="{{URL::to('specializedtrade/confirmregistration')}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
			@endif
		@endif
	</div>
</div>
<!-- END FORM-->

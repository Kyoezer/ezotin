@extends('master')
@section('content')
<h3 class="font-blue-madison">{{$specializedTrade[0]->Name.' (Sp No.'.$specializedTrade[0]->SPNo.')'}}</h3>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Comments/Adverse Records</span>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			{{ Form::open(array('url' => 'specializedtrade/mcommentadverserecord','role'=>'form'))}}
				<div class="form-body">
					<div class="form-group">
						<label>Type</label>
						<select name="Type" class="form-control input-medium required">
							<option value="">---SELECT ONE---</option>
							<option value="1" @if((int)Input::old('Type')==1)selected="selected"@endif>Comment</option>
							<option value="2" @if((int)Input::old('Type')==2)selected="selected"@endif>Adverse Record</option>
						</select>
					</div>
					<div class="form-group">
						<input type="hidden" name="CrpSpecializedTradeFinalId" value="{{$specializedTrade[0]->Id}}" />
						<label for="Date">Date</label>
						<div class="input-icon right input-medium">
							<i class="fa fa-calendar"></i>
							<input type="text" name="Date" value="{{Input::old('Date')}}" class="form-control datepicker required" placeholder="" readonly>
						</div>
					</div>
					<div class="form-group">
						<label>Remarks</label>
						<textarea name="Remarks" class="form-control required" rows="3">{{Input::old('Remarks')}}</textarea>
					</div>
					<div class="form-actions">
						<div class="btn-set">
							<button type="submit" class="btn green">Save</button>
						</div>
					</div>
				</div>
			{{Form::close()}}
		</div>
	</div>
</div>
@stop
@extends('master')
@section('content')
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li>
			<a href="#">Contractor</a>
			<i class="fa fa-angle-right"></i>
		</li>
		<li>
			<a href="#">New Comments/Adverse Records</a>
		</li>
	</ul>
</div>
<h3 class="font-blue-madison">{{$contractor[0]->NameOfFirm.' (CDB No.'.$contractor[0]->CDBNo.')'}}</h3>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Comments/Adverse Records</span>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			{{ Form::open(array('url' => 'contractor/mcommentadverserecord','role'=>'form'))}}
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
						<input type="hidden" name="CrpContractorFinalId" value="{{$contractor[0]->Id}}" />
						<label for="Date">Date</label>
						<div class="input-icon right input-medium">
							<i class="fa fa-calendar"></i>
							<input type="text" name="Date" class="form-control datepicker required" value="{{Input::old('Date')}}" placeholder="" readonly>
						</div>
					</div>
					<div class="form-group">
						<label>Remarks</label>
						<textarea name="Remarks" class="form-control required" rows="3">{{Input::old('Remarks')}}</textarea>
					</div>
					<div class="form-actions">
						<div class="btn-set">
							<button type="submit" class="btn green">Save</button>
							<a href="{{URL::to('contractor/newcommentsadverserecordslist')}}" class="btn red">Cancel</a>
						</div>
					</div>
				</div>
			{{Form::close()}}
		</div>
	</div>
</div>
@stop
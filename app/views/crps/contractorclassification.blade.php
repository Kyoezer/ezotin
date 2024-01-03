@extends('master')
@section('content')
<div class="col-md-4">
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption font-green-seagreen">
				<i class="fa fa-gift"></i> 
				<span class="caption-subject">Contractor Classification</span>
			</div>
		</div>
		<div class="portlet-body form">
			{{ Form::open(array('url' => 'contractor/mclassification','role'=>'form'))}}
			@foreach($editClassifications as $editClassification)
				<div class="form-body">
					<input type="hidden" name="Id" value="{{$editClassification->Id}}">
					<div class="form-group">
						<label class="control-label">Code</label>
						<input type="text" name="Code" class="form-control required" placeholder="Code" value="{{$editClassification->Code}}" />
					</div>
					<div class="form-group">
						<label class="ontrol-label">Name</label>
						<input type="text" name="Name" class="form-control required" placeholder="Name" value="{{$editClassification->Name}}"/>
					</div>
					<div class="form-group">
						<label class="ontrol-label">Registration Fees</label>
						<input type="text" name="RegistrationFee" class="form-control text-right required number" value="{{$editClassification->RegistrationFee}}"/>
					</div>
					<div class="form-group">
						<label class="ontrol-label">Renewal Fee</label>
						<input type="text" name="RenewalFee" class="form-control text-right required number" value="{{$editClassification->RenewalFee}}"/>
					</div>
				</div>
				<div class="form-actions">
					<div class="row">
						<div class="col-md-12">
							<button type="submit" class="btn green">Save</button>
							<a href="{{URL::to(Request::url())}}" class="btn red">Cancel</a>
						</div>
					</div>
				</div>
			@endforeach
			{{Form::close()}}
		</div>
	</div>
</div>
<div class="col-md-8">
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption font-green-seagreen">
				<i class="fa fa-gift"></i> 
				<span class="caption-subject">Edit/Delete Contractor Classification</span>
			</div>
		</div>
		<div class="portlet-body form">
			<table id="tablefilters_1" class="table table-bordered table-striped table-condensed">
	            <thead>
	                <tr>
	                    <th>
	                        Code
	                    </th>
	                    <th class="order">
	                        Name
	                    </th>
	                    <th>
	                    	Registration Fee
	                    </th>
	                    <th>
	                    	Renewal Fee
	                    </th>
	                    <th>
	                        Actions
	                    </th>
	                </tr>
	            </thead>
	            <tbody>
	                @foreach($classifications as $classification)
	                <tr>
	                    <td>
	                        {{$classification->Code}}
	                    </td>
	                    <td>
	                        {{$classification->Name}}
	                    </td>
	                    <td class="text-right">
	                    	{{$classification->RegistrationFee}}
	                    </td>
	                    <td class="text-right">
	                    	{{$classification->RenewalFee}}
	                    </td>
	                    <td>
	                        <a href="{{Request::url().'?sref='.$classification->Id}}" class="">Edit</a>|
	                        <a href="#" class="">Delete</a>
	                    </td>
	                </tr>
	                @endforeach
	            </tbody>
	        </table>
		</div>
	</div>
</div>
@stop
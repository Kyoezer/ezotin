@extends('master')
@section('content')

<h4 class="head-title">Edit CDB Secretariat</h4>

{{ Form::open(array('action'=>'CDBSecretariatController@updateCDBSecretariat', 'files'=>true)) }}
	@foreach($cdbSecretariat as $secretariatDetail)
		<div class="row">
			<div class="col-md-6 col-sm-12 col-xs-12">
				{{ Form::label('Full Name:') }}
				{{ Form::text('FullName', $secretariatDetail->FullName, array('class'=>'form-control','autofocus','placeholder'=>'Full Name')) }}
			</div>

			<div class="col-md-6 col-sm-12 col-xs-12">
				{{ Form::label('Email:') }}
				{{ Form::text('Email', $secretariatDetail->Email, array('class'=>'form-control','placeholder'=>'Email')) }}
			</div>
		</div>

		<div class="clear2"></div>

		<div class="row">
			<div class="col-md-6 col-sm-12 col-xs-12">
				{{ Form::label('Designation:') }}
				<select name="DesignationId" class="form-control" required>
					<option>---SELECT ONE---</option>
					@foreach($cdbdesignations as $cdbdesignation)
						<option value="{{ $cdbdesignation->Id }}" @if(($secretariatDetail->DesignationId) == ($cdbdesignation->Id)) {{ "selected" }} @endif>{{ $cdbdesignation->DesignationName }}</option>
					@endforeach
				</select>

				<div class="clear2"></div>

				<a href="#addNewDesignation" role="button" class="btn btn-large btn-primary" data-toggle="modal">Add New Designation</a>
			</div>

			<div class="col-md-6 col-sm-12 col-xs-12">
				{{ Form::label('Department:') }}
				<select name="DepartmentId" class="form-control" required>
					<option>---SELECT ONE---</option>
					@foreach($cdbdepartments as $cdbdepartment)
						<option value="{{ $cdbdepartment->Id }}" @if(($secretariatDetail->DepartmentId) == ($cdbdepartment->Id)) {{ "selected" }} @endif>{{ $cdbdepartment->DepartmentName }}</option>
					@endforeach
				</select>

				<div class="clear2"></div>

				<a href="#addNewDepartment" role="button" class="btn btn-large btn-primary" data-toggle="modal">Add New Department</a>
			</div>
		</div>

		<div class="clear2"></div>

		<div class="row">
			<div class="col-md-6 col-sm-12 col-xs-12">
				{{ Form::label('Phone Number:') }}
				{{ Form::text('PhoneNo', $secretariatDetail->PhoneNo, array('class'=>'form-control','placeholder'=>'Phone Number')) }}
			</div>

			<div class="col-md-6 col-sm-12 col-xs-12">
				{{ Form::label('Extension Number:') }}
				{{ Form::text('ExtensionNo', $secretariatDetail->ExtensionNo, array('class'=>'form-control','placeholder'=>'Extension Number')) }}
			</div>
		</div>

		<div class="row">
			<div class="col-md-6 col-sm-12 col-xs-12">
				{{ Form::label('Image Upload:') }}
				{{ Form::file('ImageUpload') }}
			</div>
		</div>

		<div class="clear2"></div>

		{{ Form::hidden('cdbSecretariatId', $secretariatDetail->CDBSecretariatId) }}
		{{ Form::hidden('FullName1', $secretariatDetail->FullName) }}
		{{ Form::hidden('Email1', $secretariatDetail->Email) }}
		{{ Form::hidden('DesignationId1', $secretariatDetail->DesignationId) }}
		{{ Form::hidden('DepartmentId1', $secretariatDetail->DepartmentId) }}
		{{ Form::hidden('PhoneNo1', $secretariatDetail->PhoneNo) }}
		{{ Form::hidden('ExtensionNo1', $secretariatDetail->ExtensionNo) }}
		{{ Form::hidden('ImageUpload1', $secretariatDetail->Image) }}

		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<input type="submit" value="Update Secretariat" class="btn btn-success">
				<input type="reset" value="Reset" class="btn btn-danger">
			</div>
		</div>
	@endforeach
{{ Form::close() }}


<!-- Modals Start -->
<div id="addNewDesignation" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			{{ Form::open(array('action'=>'CDBSecretariatController@addCDBDesignation', 'method'=>'POST')) }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Add New Designation</h4>
				</div>

				<div class="modal-body">
					<p>Please enter the new designation name.</p>

					<label>Designation Name:</label>
					<input type="text" name="DesignationName" class="form-control" placeholder="Designation Name" autofocus>
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Submit</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>

<div id="addNewDepartment" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			{{ Form::open(array('action'=>'CDBSecretariatController@addCDBDDepartment', 'method'=>'POST')) }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Add New Department Category</h4>
				</div>

				<div class="modal-body">
					<p>Please enter the new department name.</p>

					<label>Department Name:</label>
					<input type="text" name="DepartmentName" class="form-control" placeholder="Department Name" autofocus>
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Submit</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>

@stop
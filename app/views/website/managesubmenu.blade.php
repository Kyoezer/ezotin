@extends('master')
@section('content')

<h4 class="head-title">Add Sub Menu Item</h4>

{{ Form::open(array('action'=>'WebMenuManagementController@manageSubMenuDetails', 'method'=>'POST', 'class'=>'form-group')) }}
	<div class="row">
		<div class="col-md-6 col-xs-12 col-sm-12">
			{{ Form::label('Title:') }}
			{{ Form::text('Title', '', array('class'=>'form-control','placeholder'=>'Sub Menu Title')) }}
		</div>

		<div class="col-md-6 col-xs-12 col-sm-12">
			{{ Form::label('Select Parent Menu:') }}
			<select name="ParentId" class="form-control select2me">
				<option>---SELECT ONE---</option>
				@forelse($parentList as $parentDetail)
					<option value="{{ $parentDetail->Id }}">{{ $parentDetail->Title }}</option>
				@empty
					<option disabled>Please Add a Parent Menu</option>
				@endforelse
			</select>
			<div class="clear2"></div>
			<a href="#addMainMenu" role="button" class="btn btn-large btn-primary" data-toggle="modal">Add Main Menu</a>
		</div>
	</div>

	<div class="clear2"></div>

	<div class="row">
		<div class="col-md-12 col-xs-12 col-sm-12">
			{{ Form::label('Page Content:') }}
			{{ Form::textarea('Content', '', array('class'=>'form-control')) }}
		</div>
	</div>

	<div class="clear2"></div>

	<div class="row">
		<div class="col-md-6 col-xs-12 col-sm-12">
			{{ Form::label('Upload Image:') }}
			{{ Form::file('Image_Upload') }}
		</div>

		<div class="col-md-6 col-xs-12 col-sm-12">
			{{ Form::label('Attachment:') }}
			{{ Form::file('Attachment') }}
		</div>
	</div>

	<div class="clear2"></div>

	<div class="row">
		<div class="col-md-12 col-xs-12 col-sm-12">
			<input type="submit" value="Add Menu Item" class="btn btn-success">
			<input type="reset" value="Clear" class="btn btn-danger">
		</div>
	</div>
{{ Form::close() }}




<div id="addMainMenu" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			{{ Form::open(array('action'=>'WebMenuManagementController@manageMainMenuDetails', 'method'=>'POST', 'class'=>'form-group')) }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Add Main Menu Item</h4>
				</div>

				<div class="modal-body">
					{{ Form::label('Title:') }}
					{{ Form::text('Title', '', array('class'=>'form-control','placeholder'=>'Main Menu Title')) }}
				</div>

				<div class="modal-footer">
					<input type="submit" value="Add Menu Item" class="btn btn-success">
					<input type="reset" value="Clear" class="btn btn-danger">
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>

@stop
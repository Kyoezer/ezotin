@extends('master')
@section('content')

<div class="row">
	<div class="col-md-12 col-xs-12 col-sm-12">
		<h4 class="head-title">Edit Page</h4>
		<div class="form-group">
			@foreach ($page_details as $pdetail)
				{{ Form::open(array('action'=>'WebMenuManagementController@updateSubMenuDetails', 'method'=>'POST', 'files'=>true)) }}
					{{ Form::label('Page Title:') }}
					{{ Form::text('Title', $pdetail->Title, array('class'=>'form-control')) }}

					<div class="clear2"></div>

					{{ Form::label('Select Parent Menu:') }}
					<select name="ParentId" class="form-control select2me">
						<option>---SELECT ONE---</option>
						@forelse($parentList as $parentDetail)
							<option value="{{ $parentDetail->Id }}" @if($parentDetail->Id == $parentId){{ "selected" }} @endif>{{ $parentDetail->MenuTitle }}</option>
						@empty
							<option>Please Add a Parent Menu</option>
						@endforelse
					</select>

					<div class="clear2"></div>

					{{ Form::label('Page Content:') }}
					{{ Form::textarea('Content', $pdetail->Content, array('class'=>'form-control')) }}

					<div class="clear2"></div>

					{{ Form::label('Upload Image:') }}
					{{ Form::file('Image_Upload') }}

					<div class="clear2"></div>

					{{ Form::label('Attachment:') }}
					{{ Form::file('Attachment') }}

					<div class="clear"></div>

					{{ Form::hidden('Id', $pdetail->Id) }}
					{{ Form::hidden('imageUpload', $pdetail->ImageUpload) }}
					{{ Form::hidden('attachments', $pdetail->Attachment) }}
					{{ Form::hidden('parentId', $pdetail->ParentId) }}

					{{ Form::submit('Update', array('class'=>'btn btn-success')) }}
					{{ Form::reset('Clear', array('class'=>'btn btn-danger')) }}
				{{ Form::close() }}
			@endforeach
		</div>
	</div>
</div>

@stop
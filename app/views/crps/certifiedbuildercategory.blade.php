@extends('master')
@section('content')
<div class="col-md-4">
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption font-green-seagreen">
				<i class="fa fa-gift"></i>
				<span class="caption-subject">Specialized Firm Category</span>
			</div>
		</div>
		<div class="portlet-body form">
			{{ Form::open(array('url' => 'master/mspecializedfirmcategory','role'=>'form'))}}
			@foreach($editCategories as $editCategory)
				<input type="hidden" name="Id" value="{{$editCategory->Id}}">
				<div class="form-body">
					<div class="form-group">
						<label class="control-label">Code</label>
						<input type="text" name="Code" class="form-control" placeholder="Code" value="{{{$editCategory->Code or Input::old('Code')}}}">
					</div>
					<div class="form-group">
						<label class="ontrol-label">Name</label>
						<input type="text" name="Name" class="form-control" placeholder="Name" value="{{{$editCategory->Name or Input::old('Name')}}}">
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
				<span class="caption-subject">Edit/Delete Specialized Firm Category</span>
			</div>
		</div>
		<div class="portlet-body form">
			<table id="tablefilters_1" class="table table-bordered table-striped table-condensed">
	            <thead>
	                <tr>
	                    <th class="order">
	                        Code
	                    </th>
	                    <th>
	                        Name
	                    </th>
	                    <th>
	                        Actions
	                    </th>
	                </tr>
	            </thead>
	            <tbody>
	                @foreach($categories as $category)
	                <tr>
	                    <td>
	                        {{$category->Code}}
	                    </td>
	                    <td>
	                    	 {{$category->Name}}
	                    </td>
	                    <td>
	                        <a href="{{Request::url().'?sref='.$category->Id}}" class="editaction">Edit</a>|
	                        <a href="#" class="deleteaction">Delete</a>
	                    </td>
	                </tr>
	                @endforeach
	            </tbody>
	        </table>
		</div>
	</div>
</div>
@stop
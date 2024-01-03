@extends('master')
@section('content')
<div class="col-md-4">
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption font-green-seagreen">
				<i class="fa fa-gift"></i>
				<span class="caption-subject">Contractor Work Category</span>
			</div>
		</div>
		<div class="portlet-body form">
			{{ Form::open(array('url' => 'contractor/mworkcategory','role'=>'form'))}}
			@foreach($workCategoriesEdit as $workCategoryEdit)
				<input type="hidden" name="Id" value="{{$workCategoryEdit->Id}}">
				<div class="form-body">
					<div class="form-group">
						<label class="control-label">Code</label>
						<input type="text" name="Code" class="form-control" placeholder="Code" value="@if(!empty($workCategoryEdit->Code)){{$workCategoryEdit->Code}}@else{{Input::old('Code')}}@endif">
					</div>
					<div class="form-group">
						<label class="ontrol-label">Name</label>
						<input type="text" name="Name" class="form-control" placeholder="Name" value="@if(!empty($workCategoryEdit->Name)){{$workCategoryEdit->Name}}@else{{Input::old('Name')}}@endif">
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
				<i class="fa fa-gift"></i>Edit/Delete Contarctor Work Category
			</div>
		</div>
		<div class="portlet-body">
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
	                @foreach($categories as $categories)
	                <tr>
	                    <td>
	                        {{$categories->Code}}
	                    </td>
	                    <td>
	                    	 {{$categories->Name}}
	                    </td>
	                    <td>
	                        <a href="{{Request::url().'?sref='.$categories->Id}}" class="editaction">Edit</a>|
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
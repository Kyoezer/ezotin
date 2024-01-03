@extends('master')
@section('content')
<div class="col-md-4">
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption font-green-seagreen">
				<i class="fa fa-gift"></i>
				<span class="caption-subject">Consultant Service Category</span>
			</div>
		</div>
		<div class="portlet-body form">
			{{ Form::open(array('url' => 'consultant/mservicecategory','role'=>'form'))}}
			@foreach($editServiceCategories as $editServiceCategory)
				<input type="hidden" name="Id" value="{{$editServiceCategory->Id}}">
				<div class="form-body">
					<div class="form-group">
						<label class="control-label">Code</label>
						<input type="text" name="Code" class="form-control" placeholder="Code" value="@if(!empty($editServiceCategory->Code)){{$editServiceCategory->Code}}@else{{Input::old('Code')}}@endif">
					</div>
					<div class="form-group">
						<label class="ontrol-label">Name</label>
						<input type="text" name="Name" class="form-control" placeholder="Name" value="@if(!empty($editServiceCategory->Name)){{$editServiceCategory->Name}}@else{{Input::old('Name')}}@endif">
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
				<i class="fa fa-gift"></i>Edit/Delete Consultant Services
			</div>
		</div>
		<div class="portlet-body">
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
	                        Actions
	                    </th>
	                </tr>
	            </thead>
	            <tbody>
	                @foreach($serviceCategories as $serviceCategory)
	                <tr>
	                    <td>
	                        {{$serviceCategory->Code}}
	                    </td>
	                    <td>
	                    	 {{$serviceCategory->Name}}
	                    </td>
	                    <td>
	                        <a href="{{Request::url().'?sref='.$serviceCategory->Id}}" class="editaction">Edit</a>|
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
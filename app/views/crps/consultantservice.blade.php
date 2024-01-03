@extends('master')
@section('content')
<div class="col-md-4">
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption font-green-seagreen">
				<i class="fa fa-gift"></i>
				<span class="caption-subject">Consultant Service</span>
			</div>
		</div>
		<div class="portlet-body form">
			{{ Form::open(array('url' => 'consultant/mservice','role'=>'form'))}}
			@foreach($editServices as $editService)
				<div class="form-body">
				<input type="hidden" name="Id" value="{{$editService->Id}}">
					<div class="form-group">
						<label class="control-label">Category</label>
						<select name="CmnConsultantServiceCategoryId" class="form-control required">
							<option value="">---SELECT ONE---</option>
							@foreach($categories as $category)
							<option value="{{$category->Id}}" @if(Input::old('CmnConsultantServiceCategoryId')==$category->Id || $editService->CmnConsultantServiceCategoryId==$category->Id)selected="selected"@endif>{{$category->Name}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">Code</label>
						<input type="text" name="Code" class="form-control required" value="{{{ $editService->Code or Input::old('Code') }}}" placeholder="Code">
					</div>
					<div class="form-group">
						<label class="ontrol-label">Name</label>
						<input type="text" name="Name" class="form-control required" value="{{{ $editService->Name or Input::old('Name') }}}" placeholder="Name">
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
				<span class="caption-subject">Edit/Delete Consultant Service</span>
			</div>
		</div>
		<div class="portlet-body">
			<table id="tablefilters_1" class="table table-bordered table-striped table-condensed">
	            <thead>
	                <tr>
	                	<th class="order">
	                		Category
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
	                @foreach($services as $service)
	                <tr>
	                    <td>
	                        {{$service->CategoryName}}
	                    </td>
	                    <td>
	                    	 {{$service->ServiceName.' ('.$service->ServiceCode.')'}}
	                    </td>
	                    <td>
	                        <a href="{{Request::url().'?sref='.$service->Id}}" class="editaction">Edit</a>|
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
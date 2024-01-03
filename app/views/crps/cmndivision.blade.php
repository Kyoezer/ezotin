@extends('master')
@section('content')
 <div class="col-md-4">
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption font-green-seagreen">
				<i class="fa fa-gift"></i> 
				<span class="caption-subject">Division</span>
			</div>
		</div>
		<div class="portlet-body form">
			{{ Form::open(array('url' => 'master/savedivision','role'=>'form'))}}
				@foreach($divisions as $division)
				<div class="form-body">
					<input type="hidden" name="Id" value="{{$division->Id}}" />
					<div class="form-group">
						<label class="control-label">Code</label>
						<input type="text" name="Code" class="form-control" placeholder="Code" value="{{$division->Code}}" />
					</div>
					<div class="form-group">
						<label class="control-label">Name</label>
						<input type="text" name="Name" class="form-control required" placeholder="Name" value="{{$division->Name}}" />
					</div>
					<div class="form-group">
						<label class="control-label">Under</label>
						<select name="CmnProcuringAgencyId" class="form-control select2me required">
							<option value="">---SELECT---</option>
							@foreach($parentPAs as $parentPA)
								<option value="{{$parentPA->Id}}" @if($division->CmnProcuringAgencyId == $parentPA->Id)selected="selected"@endif>{{$parentPA->Name}} ({{$parentPA->Code}})</option>
							@endforeach
						</select>
					</div>
				</div>
				@endforeach
				<div class="form-actions">
					<div class="row">
						<div class="col-md-12">
							<button type="submit" class="btn green">Save</button>
							<a href="{{URL::to('master/division')}}" class="btn red">Cancel</a>
						</div>
					</div>
				</div>
			{{Form::close()}}
		</div>
	</div>
</div>
<div class="col-md-8">
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption font-green-seagreen">
				<i class="fa fa-gift"></i> 
				<span class="caption-subject">{{'Edit/Delete Division'}}</span>
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
	                        Division
	                    </th>
						<th>
							Department
						</th>
	                    <th>
	                        Actions
	                    </th>
	                </tr>
	            </thead>
	            <tbody>
	                @foreach($saved as $value)
	                <tr>
	                    <td>
	                        {{$value->Code}}
	                    </td>
	                    <td>
	                        {{$value->Name}}
	                    </td>
						<td>
							{{$value->Division}}
						</td>
	                    <td>
	                        <a href="{{URL::to('master/division').'/'.$value->Id}}" class="editaction">Edit</a>|
	                        <a href="{{URL::to('master/deletedivision')."/$value->Id"}}" class="deleteaction">Delete</a>
	                    </td>
	                </tr>
	                @endforeach
	            </tbody>
	        </table>
		</div>
	</div>
</div><div class="clearfix"></div>
@stop
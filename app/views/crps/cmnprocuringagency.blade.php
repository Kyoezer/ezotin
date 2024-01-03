@extends('master')
@section('content')
<div class="col-md-6">
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-gift"></i> Procuring Agency
			</div>
		</div>
		<div class="portlet-body form">
			{{ Form::open(array('url' => 'master/mprocuringagency','role'=>'form'))}}
				@foreach($editProcuringAgencies as $editProcuringAgency)
				<input type="hidden" name="Id" value="{{$editProcuringAgency->Id}}">
				<div class="form-body">
					<div class="form-group">
						<label class="control-label">Code</label>
						<input type="text" name="Code" class="form-control required" placeholder="Code" value="{{$editProcuringAgency->Code}}"/>
					</div>
					<div class="form-group">
						<label class="control-label">Name</label>
						<input type="text" name="Name" class="form-control required" placeholder="Name" value="{{$editProcuringAgency->Name}}"/>
					</div>
					<div class="form-group">
						<label class="control-label">Ministry</label>
						<select name="CmnMinistryId" class="form-control select2me">
							<option>---SELECT ONE---</option>
							@foreach($ministries as $ministry)
								<option value="{{$ministry->Id}}" @if($editProcuringAgency->CmnMinistryId==$ministry->Id)selected="selected"@endif>{{$ministry->Name.'('.$ministry->Code.')'}}</option>
							@endforeach
						</select>
					</div>
				</div>
				@endforeach
				<div class="form-actions">
					<div class="row">
						<div class="col-md-12">
							<button type="submit" class="btn green">Save</button>
							<a href="{{URL::to('master/procuringagency')}}" class="btn red">Cancel</a>
						</div>
					</div>
				</div>
			{{Form::close()}}
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-gift"></i> Procuring Agency List
			</div>
		</div>
		<div class="portlet-body ">
			<table id="tablefilters_1" class="table table-bordered table-striped table-condensed">
	            <thead>
	                <tr>
	                    <th>
	                        Ministry Code
	                    </th>
	                    <th class="order">
	                        Ministry Name
	                    </th>
	                    <th>
	                    	Procuring Agency Code
	                    </th>
	                    <th>
	                    	Procuring Agency Name
	                    </th>
	                    <th>
	                        Actions
	                    </th>
	                </tr>
	            </thead>
	            <tbody>
	                @foreach($procuringAgencies as $procuringAgency)
	                <tr>
	                    <td>
	                        {{$procuringAgency->MinistryCode}}
	                    </td>
	                    <td>
	                        {{$procuringAgency->MinistryName}}
	                    </td>
	                    <td>
	                    	{{$procuringAgency->ProcuringAgencyCode}}
	                    </td>
	                    <td>
	                    	{{$procuringAgency->ProcuringAgencyName}}
	                    </td>
	                    <td>
	                        <a href="{{Request::url().'?sref='.$procuringAgency->Id}}" class="editaction">Edit</a>|
	                        <a href="#" class="deleteaction">Delete</a>
	                    </td>
	                </tr>
	                @endforeach
	            </tbody>
	        </table>
		</div>
	</div>
</div>
	<div class="clearfix"></div>
@stop
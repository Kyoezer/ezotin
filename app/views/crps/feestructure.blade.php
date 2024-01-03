@extends('master')
@section('content')
<div class="col-md-4">
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption font-green-seagreen">
				<i class="fa fa-gift"></i> 
				<span class="caption-subject">Registration Validity</span>
			</div>
		</div>
		<div class="portlet-body form">
			{{ Form::open(array('url' => 'master/savefeestructure','role'=>'form'))}}
			@foreach($feeDetails as $feeDetail)
				<div class="form-body">
					<input type="hidden" name="Id" value="{{$feeDetail->Id}}">
					<div class="form-group">
						<label class="ontrol-label">Name</label>
						<input type="text" class="form-control required" readonly placeholder="Name" value="{{$feeDetail->Name}}"/>
					</div>
					<div class="form-group">
						<label class="ontrol-label">Registration Validity</label>
						<input type="text" name="RegistrationValidity" class="form-control text-right required number" value="{{$feeDetail->RegistrationValidity}}"/>
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
				<span class="caption-subject">Edit Registration Validity</span>
			</div>
		</div>
		<div class="portlet-body form">
			<table id="tablefilters_1" class="table table-bordered table-striped table-condensed">
	            <thead>
	                <tr>
	                    <th class="order">
	                        Name
	                    </th>
						<th>
							Registration Validity
						</th>
	                    <th>
	                        Actions
	                    </th>
	                </tr>
	            </thead>
	            <tbody>
	                @foreach($applicantTypes as $applicantType)
	                <tr>
	                    <td>
	                        {{$applicantType->Name}}
	                    </td>
						<td>
							{{$applicantType->RegistrationValidity}}
						</td>
	                    <td>
	                        <a href="{{URL::to("master/fees")."/$applicantType->Id"}}" class="">Edit</a>
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
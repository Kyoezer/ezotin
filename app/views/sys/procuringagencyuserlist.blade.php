@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/architect.js') }}
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Assign Report Privileges to Etool/CiNET users</span>
			{{--<span class="caption-helper">The users listed below are all active</span>--}}
		</div>
	</div>
	<div class="portlet-body flip-scroll">
		<div class="form-body">
			{{ Form::open(array('url' =>Request::URL(),'role'=>'form','method'=>'get')) }}
			<div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Username / Full Name</label>
                        <div class="ui-widget">
                            <input type="hidden" class="pauser-id" name="SysUserId" value="{{Input::get('SysUserId')}}"/>
                            <input type="text" name="User" value="{{Input::get('User')}}" class="form-control pauser-autocomplete"/>
                        </div>
                    </div>
                </div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Procuring Agency</label>
						<select class="form-control select2me" name="Agency">
							<option value="">---SELECT ONE---</option>
							@foreach($procuringAgencies as $procuringAgency)
								<option value="{{$procuringAgency->ProcuringAgency}}" @if(Input::has('Agency')) @if($procuringAgency->ProcuringAgency == Input::get('Agency'))selected @endif @else<?php if(Session::get('report_mapAgency') == $procuringAgency->ProcuringAgency): ?>selected<?php endif; ?>@endif>{{$procuringAgency->ProcuringAgency}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-2">
					<label class="control-label">&nbsp;</label>
					<div class="btn-set">
						<button type="submit" class="btn blue-hoki btn-sm">Search</button>
						<a href="{{Request::Url()}}" class="btn grey-cascade btn-sm">Clear</a>
					</div>
				</div>
			</div>
			{{Form::close()}}
		</div>
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-condensed flip-content">
			<thead class="flip-content">
				<tr>
					<th>Sl#</th>
					<th>
						 Username
					</th>
					<th>
						Full Name
					</th>
					<th>
						Procuring Agency
					</th>
					<th>
						 Type
					</th>
					<th>
						 Email
					</th>
					<th>
						Action
					</th>
				</tr>
			</thead>
			<tbody>
				<?php $slNo = Input::has('page')?((Input::get('page')==1)?1:(Input::get('page')-1)*15 + 1):1; ?>
				@forelse($etoolCinetUsers as $user)
					<tr>
						<td>{{$slNo++}}</td>
						<td>
							<input type="hidden" value="{{$user->Id}}" class="userid" />
							 {{$user->username}}
						</td>
						<td>
							{{$user->FullName}}
						</td>
						<td>
							{{$user->Agency}}
						</td>
						<td>
							{{$user->Type}}
						</td>
						<td>
							 {{$user->Email}}
						</td>
						<td class="text-center">
							<a href="{{URL::to('sys/editpausers')."?type=$user->Type&id=$user->Id"}}" data-toggle="modal" class="reregistrationarchitect">Edit Report Privilege</a>
						</td>
					</tr>
				@empty
					<tr>
						<td class="font-red text-center" colspan="7">No data to display</td>
					</tr>
				@endforelse

				</tbody>
			</table>
		</div>
		{{$etoolCinetUsers->links()}}
	</div>
</div>
@stop
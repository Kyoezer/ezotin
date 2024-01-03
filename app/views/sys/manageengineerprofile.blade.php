@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/sys.js') }}
@stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption font-green-seagreen">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject">Manage Engineer Profile <a href="{{URL::to("etoolsysadm/importcorporateengineer")}}" class="btn btn-sm blue"><i class="fa fa-plus"></i> Add</a>&nbsp;<a href="{{URL::to("etoolsysadm/deletecorporateengineer")}}" class="btn btn-sm red"><i class="fa fa-times"></i> Delete</a></span>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-body">
					{{ Form::open(array('url' =>Request::URL(),'role'=>'form','method'=>'get')) }}
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label class="control-label">CID No.</label>
									<input type="text" name="CIDNo" value="{{Input::get('CIDNo')}}" class="form-control input-sm"/>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">Name</label>
									<input type="text" name="NameOfEngineer" value="{{Input::get('NameOfEngineer')}}" class="form-control input-sm"/>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="agency" class="control-label">Agency</label>
									<select name="Agency" class="form-control input-sm select2me">
										<option value="">---SELECT---</option>
										@foreach($agencies as $agency)
											<option value="{{$agency->Agency}}" @if($agency->Agency == Input::get('Agency'))selected="selected"@endif>{{$agency->Agency}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-2">
			                    <div class="form-group">
			                        <label class="control-label">No. of Rows</label>
			                        {{Form::select('Limit',array(10=>10,20=>20,30=>30,'All'=>'All'),Input::has('Limit')?Input::get('Limit'):20,array('class'=>'form-control input-sm'))}}
			                    </div>
			                </div>
							<div class="col-md-2">
								<label>&nbsp;</label>
								<div class="btn-set">
									<button type="submit" class="btn blue-hoki btn-sm">Search</button>
									<a href="{{Request::URL()}}" class="btn grey-cascade btn-sm">Clear</a>
								</div>
							</div>
						</div>
					</div>
					{{Form::close()}}
					<div class="table-responsive">
						<table class="dont-flip table table-bordered table-condensed table-striped">
							<thead>
								<tr class="success">
									<th>Sl#</th>
									<th>
										CID No.
									</th>
									<th>
										Name
									</th>
									<th>
										Agency
									</th>
									<th>
										Action
									</th>
								</tr>
							</thead>
							<tbody>
								<?php $slNo = 1; ?>
								@foreach($govermentEngineers as $govermentEngineer)
								<tr>
									<td>{{$slNo++}}</td>
									<td>
										<input type="hidden" value="{{$govermentEngineer->Id}}" class="engineerid" />
										<input type="hidden" value="{{$govermentEngineer->CIDNo}}" class="cidno" />
										<input type="hidden" value="{{$govermentEngineer->EngineerName}}" class="nameofengineer" />
										{{$govermentEngineer->CIDNo}}
									</td>
									<td>
										{{$govermentEngineer->EngineerName}}
									</td>
									<td>
										{{$govermentEngineer->EmployerName}}
									</td>
									<td>
										<button type="button" class="btn red relieveengineer btn-xs">Relieve</button>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>
<div id="manageengineerprofiledialog" class="modal fade" role="dialog" aria-labelledby="manageengineerprofiledialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			{{ Form::open(array('url' => 'etoolsysadm/relieveengineer','role'=>'form','id'=>'relievegovermentengineer'))}}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-red">Manage Engineer Profile</h3>
			</div>
			<div class="modal-body">
				<input type="hidden" name="RedirectRoute" value="verifyregistration">
				<p class="bold">Are you sure you want to relieve the Engineer?</p>
				<input type="hidden" name="EngineerReference" class="relieveengineerid" />
				<div class="form-group">
					<label><strong>CID No: </strong><span class="cidnoofengineerdisplay"></span></label><br />
					<label><strong>Name: </strong><span class="nameofengineerdisplay"></span></label>
				</div>
				<div class="form-group">
					<label>Relieving Date</label>
					<div class="input-icon right">
						<i class="fa fa-calendar"></i>
						<input type="text" name="RelievingDate" class="form-control datepicker required" value="{{date('d-m-Y')}}"/>
					</div>
				</div>
				<div class="form-group">
					<label>Relieving Letter No.</label>
					<input type="text" name="RelievingLetterNo" class="form-control required" />
				</div>
			</div>
			<div class="modal-footer">
				<button id="relievegovermentengineersubmit" type="button" class="btn green">Relieve</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>	
@stop
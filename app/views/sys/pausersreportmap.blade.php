@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/sys.js') }}
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Report Map ({{$captionText}})</span>
		</div>
	</div>
	{{Form::open(array('url' => 'sys/muserreportmap','role'=>'form','id'=>'roleregistration'))}}
	<div class="portlet-body form">
		<div class="form-body">
			<div class="row">
				<div class="col-md-6">
					<strong>User Name:</strong> {{$userDetails[0]->username}} <br>
					<strong>Full Name:</strong> {{$userDetails[0]->FullName}} <br>
					<strong>Procuring Agency:</strong> {{$userDetails[0]->ProcuringAgency}}
					<div class="table-responsive">
					<h5 class="font-blue-hoki bold">Reports</h5>
						<table id="sysrolemenumap" class="table table-condensed table-striped table-bordered table-hover table-responsive">
							<thead>
								<tr>
									<th>Page</th>
									<th width="5%">View</th>
								</tr>
							</thead>
							<tbody>
								@foreach($reports as $report)
								<?php $randomKey = randomString(); ?>
									<tr>
						        		<td>
						                    {{$report->MenuTitle}}
						                    <input type="hidden" class="menuid" name="systemuserreportmap[{{$randomKey}}][Id]" value="{{$report->Id}}" />
						                </td>
						        		<td class="text-center">
											<input type="hidden" class="page-view" name="systemuserreportmap[{{$randomKey}}][PageView]" value="{{$report->PageView}}"/>
											<input type="checkbox" class="toggle-hidden" @if($report->PageView==1)checked="checked"@endif/>
										</td>
					        		</tr>
				        		@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="form-actions">
			<div class="btn-set">
				<button type="submit" class="btn green">Save</button>
				<a href="{{URL::to('sys/procuringagencyreportmap')}}" class="btn red">Cancel</a>
			</div>
		</div>
	</div>
	{{Form::close()}}
</div>
@stop
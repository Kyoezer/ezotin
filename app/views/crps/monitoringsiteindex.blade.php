@extends('master')
@section('pagescripts')
	{{ HTML::script('assets/global/scripts/contractor.js') }}
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">{{$pageTitle}}</span>
		</div>
	</div>
	<div class="portlet-body flip-scroll">
		<div class="form-body">
			{{ Form::open(array('url' =>Request::URL(),'role'=>'form','method'=>'get')) }}
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">CDB No.</label>
						<input type="text" name="CDBNo" class="form-control input-sm" value="{{$CDBNo}}">
					</div>
				</div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Contractor/Firm</label>
                        <div class="ui-widget">
                            <input type="hidden" class="contractor-id" name="ContractorId" value="{{Input::get('ContractorId')}}"/>
                            <input type="text" name="Contractor" value="{{Input::get('Contractor')}}" class="form-control input-sm contractor-autocomplete"/>
                        </div>
                    </div>
                </div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Dzongkhag</label>
						<select name="DzongkhagId" class="form-control select2me">
							<option value="">All</option>
							@foreach($dzongkhags as $dzongkhag)
								<option value="{{$dzongkhag->Id}}" @if(Input::get("DzongkhagId") == $dzongkhag->Id)selected="selected"@endif>{{$dzongkhag->Dzongkhag}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Classification</label>
						<select name="Priority" class="form-control input-sm">
							<option value="">All</option>
							@foreach($priorities as $priority)
								<option value="{{$priority->Priority}}" @if(Input::get("Priority") == $priority->Priority)selected="selected"@endif>{{$priority->Class}}</option>
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
					<th>
						CDB No.
					</th>
					@if(isset($isList))
						<th>Year</th>
						<th>Monitoring Date</th>
						<th>Name of Work</th>
						<th>Agency</th>
					@endif
					<th>
						Ownership Type
					</th>
					<th>
						Name of Firm
					</th>
					<th>
						Class
					</th>
					<th>
						 Country
					</th>
					<th>
						 Dzongkhag
					</th>
					<th>
						Mobile#
					</th>
					<th>
						Tel#
					</th>
					<th>
						Email
					</th>
					@if(isset($isList))
						<th>Status</th>
					@endif
					<th class="col-md-2">
						Action
					</th>
				</tr>
			</thead>
			<tbody>
				@forelse($contractorLists as $contractorList)
				<tr>
					<td>
						 {{$contractorList->CDBNo}}
					</td>
					@if(isset($isList))
						<td>
							{{$contractorList->Year}}
						</td>
						<td>
							{{convertDateToClientFormat($contractorList->MonitoringDate)}}
						</td>
						<td>
							{{$contractorList->NameOfWork}}
						</td>
						<td>
							{{$contractorList->ProcuringAgency}}
						</td>
					@endif
					<td>
						 {{$contractorList->OwnershipType}}
					</td>
					<td>
						{{$contractorList->NameOfFirm}} 
					</td>
					<td>
						{{$contractorList->ClassificationCode}} 
					</td>
					<td>
						 {{$contractorList->Country}}
					</td>
					<td>
						 {{$contractorList->Dzongkhag}}
					</td>
					<td>
						 {{$contractorList->MobileNo}}
					</td>
					<td>
						{{$contractorList->TelephoneNo}}
					</td>
					<td>
						{{$contractorList->Email}}
					</td>
					@if(isset($isList))
						<td>
							{{($contractorList->MonitoringStatus==1)?"Fulfilled":"Not fulfilled"}}
						</td>
						@if($isList == 1)
							<td class="text-center">
								<a href="{{URL::to('monitoringreport/siteedit/'.$contractorList->MonitoringSiteId)}}" class="btn btn-xs red"><i class="fa fa-edit"></i> Edit</a>
								<a href="{{URL::to('monitoringreport/siteview/'.$contractorList->MonitoringSiteId)}}" target="_blank" class="btn btn-xs purple"><i class="fa fa-print"></i> Print</a>
							</td>
						@else
							<td>
								<ol style="margin-left: -25px;">
									<li><a href="{{URL::to("contractor/editworkclassification/$contractorList->Id")}}?monitoringofficeid={{$contractorList->MonitoringSiteId}}&redirectUrl=monitoringreport/officeaction&usercdb=edit&final=true">Downgrade</a></li>
									<li><a href="#" data-monitoringid="{{$contractorList->MonitoringSiteId}}" data-class="{{$contractorList->ClassificationCode}}" data-cdbno="{{$contractorList->CDBNo}}" data-firmname="{{$contractorList->NameOfFirm}}" data-id="{{$contractorList->Id}}" class="suspend-contractor-monitoring">Suspend</a></li>
								</ol>
							</td>
						@endif

					@else
						<td class="text-center">
							@if((int)$contractorList->StatusReference == 12003)
								<a href="{{URL::to('monitoringreport/sitecontractorinfo/'.$contractorList->Id)}}" class="btn btn-xs red"><i class="fa fa-edit"></i> Pick for Monitoring</a>
							@endif
						</td>
					@endif

				</tr>
				@empty
				<tr>
					<td class="font-red text-center" colspan="15">No data to display</td>
				</tr>
				@endforelse
			</tbody>
		</table>

		</div>
	</div>
	<?php pagination($noOfPages,Input::all(),Input::get('page'),$route); ?>
</div>
@stop
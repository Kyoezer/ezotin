@extends('master')
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
						<input type="text" name="CDBNo" class="form-control" value="{{$CDBNo}}">
					</div>
				</div>
          				<div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Name of Firm</label>
                        <div class="ui-widget">
                            <input type="hidden" class="contractor-id" name="CrpContractorId" value="{{Input::get('CrpContractorId')}}"/>
                            <input type="text" name="contractor" value="{{Input::get('Contractor')}}" class="form-control contractor-autocomplete"/>
                        </div>
                    </div>
                </div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Proprietor's CID</label>
						<input type="text" name="CIDNo" class="form-control" value="{{Input::get('CIDNo')}}">
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Trade License No.</label>
						<input type="text" name="TradeLicenseNo" class="form-control" value="{{Input::get('TradeLicenseNo')}}">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						@if($isMonitoring)
							<label class="control-label">Monitored Between</label>
						@else
							<label class="control-label">Approved Between</label>
						@endif
						<div class="input-group input-large date-picker input-daterange">
							<input type="text" name="FromDate" class="form-control date-picker" value="{{Input::get('FromDate')}}" />
						<span class="input-group-addon">
						to </span>
							<input type="text" name="ToDate" class="form-control date-picker" value="{{Input::get('ToDate')}}" />
						</div>
					</div>
				</div>
				<div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">No. of Rows</label>
                        {{Form::select('Limit',array(10=>10,20=>20,30=>30,'All'=>'All'),Input::has('Limit')?Input::get('Limit'):20,array('class'=>'form-control'))}}
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
				     <th>Status</th>
					@if($isMonitoring)
						<th class="col-md-2">
							Action
						</th>
					@else
						<!-- <th>
							Certificate
						</th> -->
						<th>
							Info.
						</th>
						<th>
							Expiry Date
						</th>
					@endif
				</tr>
			</thead>
			<tbody>
				@forelse($contractorLists as $contractorList)
				<tr>
					<td>
						 {{$contractorList->CDBNo}}
					</td>
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
				<td>
						@if((int)$contractorList->StatusReference == 12003)
							@if($contractorList->RegistrationExpiryDate<=date('Y-m-d G:i:s'))
									<p class="font-red bold warning">Expired </p>
							@else
								Valid/Approved
                                              
							@endif
						@else
							<p class="font-red bold warning">{{$contractorList->Status}}</p>
						@endif
					</td>
					@if($isMonitoring)
						<td>
							@if((int)$contractorList->StatusReference == 12003)
								1. <a href="{{URL::to('contractor/monitoringreportoffice/'.$contractorList->Id)}}">Office Establistment</a>
								<br>
								2. <a href="{{URL::to('contractor/monitoringreportsites/'.$contractorList->Id)}}">Sites</a>
							@endif
						</td>
					@else
						<!-- <td>
							@if((int)$contractorList->StatusReference == 12003)
							<a href="{{URL::to('contractor/certificate/'.$contractorList->Id)}}" class="btn default btn-xs blue @if($linkText == 'View/Print') {{'editaction'}}@endif" target="_blank"><i class="fa fa-edit"></i>View/Print</a>
							@endif
						</td> -->
						<td>
						<a href="{{URL::to($link.$contractorList->Id)}}" class="btn default btn-xs green-seagreen @if($linkText=="Edit"){{"editaction"}}@endif" @if($linkText=="View/Print"){{"target='_blank'"}}@endif><i class="fa fa-edit"></i> {{$linkText}}</a>
						</td>
						<td>
							@if((int)$contractorList->StatusReference == 12003)
								<a href="{{URL::to('contractor/changeexpirydate/'.$contractorList->Id)}}" class="btn default btn-xs green"><i class="fa fa-edit"></i> Change</a>
							@endif
						</td>
					@endif
				</tr>
				@empty
				<tr>
					<td class="font-red text-center" colspan="14">No data to display</td>
				</tr>
				@endforelse
			</tbody>
		</table>
		</div>
	</div>
</div>
@stop
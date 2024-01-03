@extends('master')
@section('lastcdbno')

@stop
@section('content')
<div id="changeexpirydatemodal" class="modal fade"  data-keyboard="false" data-backdrop="static" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title font-red-intense">Change expiry Date</h4>
			</div>
			{{Form::open(array('url'=>'all/changeexpirydate','role'=>'form'))}}
			<div class="modal-body">
				<div class="col-md-8">
					<h5><strong>Current Registration Expiry Date: </strong><span id="current-expdate"></span></h5>
				</div>
				<br><br><div class="clearfix"></div>
				<div class="col-md-4">
					<div class="form-group">
						<input type="hidden" name="Model" value="CertifiedbuilderFinalModel"/>
						<input type="hidden" name="RedirectUrl" value="certifiedbuilder/editlist"/>
						<input type="hidden" name="Id" id="Id"/>
						<label for="expiry-date" class="control-label">New Expiry Date</label>
						<input type="text" id="expiry-date" class="datepicker form-control required" name="RegistrationExpiryDate"/>
					</div>
				</div>
			</div><div class="clearfix"></div>
			<div class="modal-footer">
				<button class="btn blue" type="submit">Save</button>
				<button class="btn green-seagreen" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
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
                        <label class="control-label">Certified Builder</label>
                        <div class="ui-widget">
                            <input type="hidden" class="specializedtrade-id" name="CrpSpecializedtradeId" value="{{Input::get('CrpCertifiedbuilderId')}}"/>
                            <input type="text" name="Specializedtrade" value="{{Input::get('Certifiedbuilder')}}" class="form-control specializedtrade-autocomplete"/>
                        </div>
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
						<label class="control-label">Approved Between</label>
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
						Mobile#
					</th>
					<th>
						Email
					</th>
                    <th>
						Status
					</th>
					<!-- <th>
						Certificate
					</th> -->
					<th>
						Info
					</th>
					<th>
						Expiry Date
					</th>
				</tr>
			</thead>
			<tbody>
				@forelse($certifiedbuilderlists as $certifiedbuilderList)
				<tr>
					
					<td>
						 {{$certifiedbuilderList->CDBNo}}
					</td>
					<td>
						 {{$certifiedbuilderList->OwnershipType}}
					</td>
					<td>
						{{$certifiedbuilderList->NameOfFirm}} 
					</td>
				
					<td>
						 {{$certifiedbuilderList->MobileNo}}
					</td>
				
					<td>
						{{$certifiedbuilderList->Email}}
					</td>
			<td>
						@if((int)$certifiedbuilderList->StatusReference == 12003)
							@if($certifiedbuilderList->RegistrationExpiryDate<=date('Y-m-d G:i:s'))
									<p class="font-red bold warning">Expired </p>
							@else
								Valid/Approved
							@endif
						@else
							<p class="font-red bold warning">{{$certifiedbuilderList->Status}}</p>
						@endif
					</td>
					<!-- <td>
						<a href="{{URL::to('certifiedbuilder/certificate/'.$certifiedbuilderList->Id)}}" class="btn default btn-xs blue" target='_blank'><i class="fa fa-edit"></i> View/Print</a>
					</td> -->
					<td>
						<a href="{{URL::to($link.$certifiedbuilderList->Id)}}" class="btn default btn-xs green-seagreen @if($linkText=="Edit"){{"editaction"}}@endif" @if($linkText=="View/Print"){{"target='_blank'"}}@endif><i class="fa fa-edit"></i> {{$linkText}}</a>
					</td>
					<td>
						<a href="#changeexpirydatemodal" data-expirydate="{{convertDateToClientFormat($certifiedbuilderList->RegistrationExpiryDate)}}" data-id="{{$certifiedbuilderList->Id}}" data-toggle="modal" class="change-expirydate btn default btn-xs green"><i class="fa fa-edit"></i> Change</a>
					</td>
				</tr>
				@empty
				<tr>
					<td class="font-red text-center" colspan="9">No data to display</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
@stop
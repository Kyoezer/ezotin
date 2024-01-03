@extends('master')
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
						<input type="hidden" name="Model" value="ArchitectFinalModel"/>
						<input type="hidden" name="RedirectUrl" value="architect/editlist"/>
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
			<span class="caption-helper font-red">- Use the filters provided below to customize your search</span>
		</div>
	</div>
	<div class="portlet-body flip-scroll">
		<div class="form-body">
			{{ Form::open(array('url' =>Request::URL(),'role'=>'form','method'=>'get')) }}
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">AR No.</label>
						<input type="text" name="ARNo" class="form-control" value="{{$ARNo}}">
					</div>
				</div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Architect</label>
                        <div class="ui-widget">
                            <input type="hidden" class="architect-id" name="CrpArchitectId" value="{{Input::get('CrpArchitectId')}}"/>
                            <input type="text" name="Architect" value="{{Input::get('Architect')}}" class="form-control architect-autocomplete"/>
                        </div>
                    </div>
                </div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Architect Type</label>
						<select name="ArchitectType" class="form-control select2me">
							<option value="">---SELECT ONE---</option>
							@foreach($architectServiceSectorTypes as $architectServiceSectorType)
							<option value="{{$architectServiceSectorType->Id}}" @if($architectServiceSectorType->Id==$architectType)selected="selected"@endif>{{$architectServiceSectorType->Name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">No. of Rows</label>
                        {{Form::select('Limit',array(10=>10,20=>20,30=>30,'All'=>'All'),Input::has('Limit')?Input::get('Limit'):20,array('class'=>'form-control'))}}
                    </div>
                </div>
				<div class="col-md-2">
					<label class="control-label">|</label>
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
						AR No.
					</th>
					<th>
						Architect Type
					</th>
					<th>
						 Name
					</th>
					<th>
						CID/Work Permit No.
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
						Email
					</th>
<th>Status</th>
					<!-- <th>
						Certificate
					</th> -->
					<th>
						Info.
					</th>
					<th>Expiry Date</th>
				</tr>
			</thead>
			<tbody>
				@forelse($architectLists as $architectList)
				<tr>
					<td>
						{{$architectList->ARNo}}
					</td>
					<td>
						{{$architectList->ArchitectType}}
					</td>
					<td>
						{{$architectList->ArchitectName}}
					</td>
					<td>
						{{$architectList->CIDNo}}
					</td>
					<td>
						 {{$architectList->Country}}
					</td>
					<td>
						 {{$architectList->Dzongkhag}}
					</td>
					<td>
						 {{$architectList->MobileNo}}
					</td>
					<td>
						{{$architectList->Email}}
					</td>
		<td>
						@if((int)$architectList->StatusReference == 12003)
							@if($architectList->RegistrationExpiryDate<=date('Y-m-d G:i:s'))
									<p class="font-red bold warning">Expired </p>
							@else
								Valid/Approved
							@endif
						@else
							<p class="font-red bold warning">{{$architectList->Status}}</p>
						@endif
					</td>

					<!-- <td>
						<a href="{{URL::to('architect/certificate/'.$architectList->Id)}}" class="btn default btn-xs blue @if($linkText == 'Edit') {{'editaction'}}@endif" target="_blank"><i class="fa fa-edit"></i>View/Print</a>
					</td> -->
					<td>
						<a href="{{URL::to($link.$architectList->Id)}}" class="btn default btn-xs green-seagreen" @if($linkText=="View/Print"){{"target='_blank'"}}@endif><i class="fa fa-edit"></i> {{$linkText}}</a>
					</td>
					<td>
						<a href="#changeexpirydatemodal" data-expirydate="{{convertDateToClientFormat($architectList->RegistrationExpiryDate)}}" data-id="{{$architectList->Id}}" data-toggle="modal" class="change-expirydate btn default btn-xs green"><i class="fa fa-edit"></i> Change</a>
					</td>
				</tr>
				@empty
				<tr>
					<td class="font-red text-center" colspan="11">No data to display</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
@stop
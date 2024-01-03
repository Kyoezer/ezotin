@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel';  if($type == 1): $route = 'specializedfirmrpt.expiredspecializedfirm'; else: if($type==3): $route="specializedfirmrpt.listofspecializedfimbynearingexpiry"; else: $route = 'consultantrpt.listofnonbhutaneseconsultants'; endif; endif;?>
                <i class="fa fa-cogs"></i>List of @if($type == 2){{"Non Bhutanese "}}<?php endif; ?>Expired Specialized Firm @if($type ==3){{"whose registration is expiring"}}@endif &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route($route,$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel </a>   <?php $parameters['export'] = 'print'; ?><a href="{{route($route,$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != 'print')
            @if($type == 1)
                <?php $url = 'specializedfirmrpt/expiredspecializedfirm'; ?>
            @else
                @if($type == 3)
                    <?php $url = 'specializedfirmrpt/listofspecializedfimbynearingexpiry'; ?>
                @else
                    <?php $url = 'specializedfirmrpt/listofnonbhutaneseconsultants'; ?>
                @endif
            @endif
        {{Form::open(array('url'=>$url,'method'=>'get'))}}
		<div class="form-body">
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">SP No</label>
						<input type="text" name="SPNo" value="{{Input::get('SPNo')}}" class="form-control" />
					</div>
				</div>
                <div class="col-md-2">
                    <label class="control-label">From</label>
                    <div class="input-icon right">
                        <i class="fa fa-calendar"></i>
                        <input type="text" name="FromDate" class="form-control datepicker" value="{{Input::get('FromDate')}}" readonly="readonly" placeholder="">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="control-label">To</label>
                    <div class="input-icon right">
                        <i class="fa fa-calendar"></i>
                        <input type="text" name="ToDate" class="form-control datepicker" value="{{Input::get('ToDate')}}" readonly="readonly" placeholder="">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Country</label>
                        <select class="form-control select2me" name="CountryId">
                            <option value="">---SELECT ONE---</option>
                            @foreach($countries as $country)
                                <option value="{{$country->Id}}" @if(Input::has('CountryId'))@if($country->Id == Input::get('CountryId'))selected @endif @else @if($country->Id == CONST_COUNTRY_BHUTAN)selected="selected"@endif @endif>{{$country->Name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Dzongkhag</label>
                        <select class="form-control select2me" name="DzongkhagId">
                            <option value="">---SELECT ONE---</option>
                            @foreach($dzongkhags as $dzongkhag)
                                <option value="{{$dzongkhag->Id}}" @if($dzongkhag->Id == Input::get('DzongkhagId'))selected @endif>{{$dzongkhag->NameEn}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Category</label>
						<select class="form-control" name="Classification">
							<option value="">---SELECT ONE---</option>
                            @foreach($specializedfirmClassifications as $specializedfirmClassification)
                                <option value="{{$specializedfirmClassification->Code}}" @if($specializedfirmClassification->Code == Input::get('Classification'))selected="selected"@endif>{{$specializedfirmClassification->Name.' ('.$specializedfirmClassification->Code.')'}}</option>
                            @endforeach
						</select>
					</div>
				</div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Application Status</label>
                        <select class="form-control" name="Status">
                            <option value="">---SELECT ONE---</option>
                            @foreach($statuses as $status)
                                <option value="{{$status->Id}}" @if(Input::has('StatusId')) @if($status->Id == Input::get('StatusId'))selected="selected"@endif @else @if($status->Id == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)selected="selected"@endif @endif>{{$status->Name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">No. of Rows</label>
                        {{Form::select('Limit',array(10=>10,20=>20,30=>30,'All'=>'All'),Input::has('Limit')?Input::get('Limit'):'All',array('class'=>'form-control'))}}
                    </div>
                </div>
                @if(!Input::has('export'))
                    <div class="col-md-2">
                        <label class="control-label">&nbsp;</label>
                        <div class="btn-set">
                            <button type="submit" class="btn blue-hoki btn-sm">Search</button>
                            <a href="{{Request::url()}}" class="btn grey-cascade btn-sm">Clear</a>
                        </div>
                    </div>
                @endif
			</div>
		</div>
        {{Form::close()}}
        @else
            @foreach(Input::all() as $key=>$value)
                @if($key != 'export')
                    <b>{{$key}}: {{$value}}</b><br>
                @endif
            @endforeach
            <br/>
        @endif
		<table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
			<thead class="flip-content">
				<tr>
                    <th>
                        Sl.No.
                    </th>
					<th class="order">
						 SP No.
					</th>
					<th>
						Firm/Name
					</th>
<th>Owner Name</th>
<th>Gender</th>
	<th>
						Email
					</th>
					<th class="">
						 Address
					</th>
					<th class="">
						 Country
					</th>
					<th>
						Dzongkhag
					</th>
					<th>
						Tel.No.
					</th>
					<th class="">
						 Mobile No.
					</th>
					<th class="">
						Category
					</th>
               
                    <th>
                        Expiry Date
                    </th>
                    <th>Status</th>
				</tr>
			</thead>
			<tbody>
            @forelse($specializedfirmList as $specializedfirm)
				<tr>
                    <td>{{$start++}}</td>
                    <td>{{$specializedfirm->SPNo}}</td>
                    <td>{{$specializedfirm->NameOfFirm}}</td>
                     <td>{{$specializedfirm->Name}}</td>
                     <td>{{$specializedfirm->Sex}}</td>
 <td>{{$specializedfirm->Email}}</td>
                    <td>{{$specializedfirm->Address}}</td>
                    <td>{{$specializedfirm->Country}}</td>
                    <td>{{$specializedfirm->Dzongkhag}}</td>
                    <td>{{$specializedfirm->TelephoneNo}}</td>
                    <td>{{$specializedfirm->MobileNo}}</td>
                    <td>{{$specializedfirm->Category}}</td>                 
                    <td>{{$specializedfirm->ExpiryDate}}</td>
                       <td >
					
							@if($specializedfirm->ExpiryDate<=date('Y-m-d G:i:s'))
							<p class="font-red bold warning">Expired</p>
							@else
							{{convertDateToClientFormat($specializedfirm->ExpiryDate)}}
							@endif
					
					</td>
				</tr>
            @empty
                <tr>
                    <td colspan="13" class="font-red text-center">No data to display!</td>
                </tr>
            @endforelse
			</tbody>
		</table>
        <?php pagination($noOfPages,Input::all(),Input::get('page'),$route); ?>
	</div>
</div>
@stop
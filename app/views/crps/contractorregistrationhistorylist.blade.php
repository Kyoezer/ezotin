@extends('master')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; $route = 'contractorrpt.contractorregistrationhistory';  ?>
            @if(!Input::has('export'))
            <i class="fa fa-cogs"></i>List of Contractors </i>
		    @endif
        </div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != "print")
        {{Form::open(array('url'=>'contractor/contractorregistrationhistorylist','method'=>'get'))}}
		<div class="form-body">
			<div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">CDB No.</label>
                        <input type="text" name="CDBNo" class="form-control" value="{{$CDBNo}}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Contractor/Firm</label>
                        <div class="ui-widget">
                            <input type="hidden" class="contractor-id" name="ContractorId" value="{{Input::get('ContractorId')}}"/>
                            <input type="text" name="Contractor" value="{{Input::get('Contractor')}}" class="form-control contractor-autocomplete"/>
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
            @foreach($parametersForPrint as $key=>$value)
                <b>{{$key}}: {{$value}}</b><br>
            @endforeach
            <br/>
        @endif
		<table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
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
                <th>
                    Registration History
                </th>
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
                        <a href="{{URL::to('contractor/contractorregistrationhistory/'.$contractorList->Id)}}" class="btn default btn-xs green-seagreen" ><i class="fa fa-edit"></i> {{"View"}}</a>
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
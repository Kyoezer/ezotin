@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $route="cbrpt.listofcertifiedbuilderrevoked"; $parameters = Input::all(); $parameters['export']='excel'; ?>
            @if(!Input::has('export'))
            <i class="fa fa-cogs"></i>List of Revoked/Suspended/Debarred Certifiedbuilder &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route("cbrpt.listofcertifiedbuilderrevoked",$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel </a>   <?php $parameters['export'] = 'print'; ?><a href="{{route("cbrpt.listofcertifiedbuilderrevoked",$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		    @endif
        </div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != "print")
        {{Form::open(array('url'=>'cbrpt/listofcertifiedbuilderrevoked','method'=>'get'))}}
		<div class="form-body">
			<div class="row">
                <div class="col-md-1">
                    <div class="form-group">
                        <label class="control-label">CDB No.</label>
                        <input type="text" class="form-control" name="CdbRegistrationNo" placeholder="CDB No."  value="{{Input::get('CdbRegistrationNo')}}" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label"> Certified Builder:</label>
                        <input type="text" class="form-control" name="SpecializedId" placeholder="Name of Firm"  value="{{Input::get('SpecializedId')}}" />
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="control-label">Dzongkhag:</label>
                    <select class="form-control select2me" name="CmnDzongkhagId">
                        <option value="">---SELECT ONE---</option>
                        @foreach($dzongkhagId as $dzongkhag)
                            <option value="{{$dzongkhag->NameEn}}" @if($dzongkhag->NameEn == Input::get('CmnDzongkhagId'))selected @endif>{{$dzongkhag->NameEn}}</option>
                        @endforeach
                    </select>
                </div>
           
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Type:</label>
                        {{Form::select('Type',array('1'=>'All','2'=>'Bhutanese','3'=>'Non-Bhutanese'),Input::get('Type'),array('class'=>'form-control'))}}
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-2">
                    <label class="control-label">From</label>
                    <div class="input-icon right">
                        <input type="text" name="FromDate" class="form-control datepicker" value="{{Input::get('FromDate')}}" readonly="readonly" placeholder="SELECT DATE">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="control-label">To</label>
                    <div class="input-icon right">
                        <input type="text" name="ToDate" class="form-control datepicker" value="{{Input::get('ToDate')}}" readonly="readonly" placeholder="SELECT DATE">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">No. of Rows</label>
                        {{Form::select('Limit',array(10=>10,20=>20,30=>30,50=>50,'All'=>'All'),Input::has('Limit')?Input::get('Limit'):20,array('class'=>'form-control'))}}
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
		<table class="table table-bordered table-striped table-condensed flip-content" id="consultanthumanresource">
            <thead class="flip-content">
            <tr>
                <th>
                    Sl.No.
                </th>
                <th class="order">
                    CDB No.
                </th>
                <th>
                    Firm Name
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
                <th>
                    Expiry Date
                </th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @forelse($certifiedbuilderLists as $specializedfirm)
                <tr>
                    <td>{{$start++}}</td>
                    <td>{{$specializedfirm->CDBNo}}</td>
                    <td>{{$specializedfirm->NameOfFirm}}</td>
                    <td>{{$specializedfirm->Address}}</td>
                    <td>{{$specializedfirm->Country}}</td>
                    <td>{{$specializedfirm->Dzongkhag}}</td>
                    <td>{{$specializedfirm->TelephoneNo}}</td>
                    <td>{{$specializedfirm->MobileNo}}</td>
                    <td>{{$specializedfirm->ExpiryDate}}</td>
                    <td>{{$specializedfirm->Status}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
		</table>
        <?php pagination($noOfPages,Input::all(),Input::get('page'),$route); ?>
	</div>
</div>
@stop
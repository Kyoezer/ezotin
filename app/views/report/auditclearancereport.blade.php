@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
            @if(!Input::has('export'))
            <i class="fa fa-cogs"></i>Audit Memo Report &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route("rpt.auditclearancereport",$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel </a>   <?php $parameters['export'] = 'print'; ?><a href="{{route("rpt.auditclearancereport",$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		    @endif
        </div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != "print")
        {{Form::open(array('url'=>'rpt/auditclearancereport','method'=>'get'))}}
		<div class="form-body">
			<div class="row">
                <div class="col-md-2">
                    <label class="control-label">Firm/CDB No.:</label>
                    <select class="form-control select2me" name="FirmId">
                        <option value="">---SELECT ONE---</option>
                        @foreach($contractorConsultants as $firm)
                            <option value="{{$firm->Id}}" @if($firm->Id == Input::get('FirmId'))selected @endif>{{$firm->NameOfFirm}} ({{$firm->CDBNo}}, {{$firm->FirmType}})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Dropped:</label>
                        {{Form::select('Dropped',array(''=>'All','1'=>'Yes','2'=>'No'),Input::get('Dropped'),array('class'=>'form-control'))}}
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
                    Sl.No.
                </th>
                <th class="order">
                    Firm
                </th>
                <th>
                    Agency
                </th>
                <th class="">
                    Audited Period
                </th>
                <th class="">
                    AIN
                </th>
                <th>
                    Para No.
                </th>
                <th>
                    Audit Observation
                </th>
                <th class="">
                    Dropped
                </th>
            </tr>
            </thead>
            <tbody>
            <?php $count = 1; ?>
            @forelse($reportData as $data)
                <tr>
                    <td>{{$start++}}</td>
                    <td>{{$data->NameOfFirm}} ({{$data->CDBNo}}, {{$data->Type == 1 ? "Contractor":"Consultant"}})</td>
                    <td>{{$data->Agency}}</td>
                    <td>{{$data->AuditedPeriod}}</td>
                    <td>{{$data->AIN}}</td>
                    <td>{{$data->ParoNo}}</td>
                    <td>{{nl2br($data->AuditObservation)}}</td>
                    <td>{{$data->Dropped == 1 ? "Yes":"No"}}</td>
                </tr>
                <?php $count++; ?>
            @empty
                <tr>
                    <td colspan="8" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
		</table>
            <?php pagination($noOfPages,Input::all(),Input::get('page'),"rpt.auditclearancereport"); ?>
	</div>
</div>
@stop
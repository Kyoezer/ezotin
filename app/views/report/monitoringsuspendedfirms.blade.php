@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel';  ?>
            @if(!Input::has('export'))
            <i class="fa fa-cogs"></i>List of Firms Suspended/Downgraded After Office Monitoring &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route("contractorrpt.monitoringlistofsuspendedfirms",$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel </a>   <?php $parameters['export'] = 'print'; ?><a href="{{route("contractorrpt.monitoringlistofsuspendedfirms",$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		    @endif
        </div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != "print")
        {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
		<div class="form-body">
			<div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Contractor/Firm/CDBNo</label>
                        <div class="ui-widget">
                            <input type="hidden" class="contractor-id" name="ContractorId" value="{{Input::get('ContractorId')}}"/>
                            <input type="text" name="Contractor" value="{{Input::get('Contractor')}}" class="form-control input-sm contractor-autocomplete"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="control-label">Registered From</label>
                    <div class="input-icon right">
                        <i class="fa fa-calendar"></i>
                        <input type="text" name="FromDate" class="form-control input-sm datepicker" value="{{Input::get('FromDate')}}" readonly="readonly" placeholder="">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="control-label">To</label>
                    <div class="input-icon right">
                        <i class="fa fa-calendar"></i>
                        <input type="text" name="ToDate" class="form-control input-sm datepicker" value="{{Input::get('ToDate')}}" readonly="readonly" placeholder="">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Dzongkhag</label>
                        <select class="form-control select2me" name="DzongkhagId">
                            <option value="">All</option>
                            @foreach($dzongkhags as $dzongkhag)
                                <option value="{{$dzongkhag->Id}}" @if($dzongkhag->Id == Input::get('DzongkhagId'))selected @endif>{{$dzongkhag->NameEn}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
				<div class="col-md-1">
					<div class="form-group">
						<label class="control-label">Class</label>
						<select class="form-control input-sm" name="ClassId">
							<option value="">All</option>
                            @foreach($classes as $class)
                                <option value="{{$class->Priority}}" @if($class->Priority == Input::get('ClassId'))selected="selected"@endif>{{$class->Code}}</option>
                            @endforeach
						</select>
					</div>
				</div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Action Taken</label>
                        {{Form::select('Action',array(''=>'All','1'=>'Downgrade','2'=>'Suspension'),Input::get('Action'),array('class'=>'form-control input-sm'))}}
                    </div>
                </div>
                @if(!Input::has('export'))
                    <div class="col-md-2">
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
            @foreach(Input::except("export") as $key=>$value)
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
						 CDB No.
					</th>
					<th>
						Firm/Name
					</th>
					<th class="">
						 Dzongkhag
					</th>
					<th class="">
						 Class
					</th>
                    <th>
                        Registered On
                    </th>
					<th>
                        Monitored On
                    </th>
                    <th>Action Taken</th>
                    <th>
                        Action Date
                    </th>
                    <th>
                        Suspended From/Downgraded From
                    </th>
                    <th>
                        Suspended To/Downgraded To
                    </th>
                    <th>Remarks</th>
				</tr>
			</thead>
			<tbody>
            @forelse($reportData as $data)
				<tr>
                    <td>{{$start++}}</td>
                    <td>{{$data->CDBNo}}</td>
                    <td>{{$data->NameOfFirm}}</td>
                    <td>{{$data->Dzongkhag}}</td>
                    <td>{{$data->Class}}</td>
                    <td>{{convertDateToClientFormat($data->InitialDate)}}</td>
                    <td>{{convertDateToClientFormat($data->MonitoringDate)}}</td>
                    <td>{{((int)$data->ActionTaken==1)?"Downgrade":"Suspension"}}</td>
                    <td>{{convertDateToClientFormat($data->ActionDate)}}</td>
                    <td>{{convertDateToClientFormat($data->FromDate)}}</td>
                    <td>{{convertDateToClientFormat($data->ToDate)}}</td>
                    <td>{{$data->Remarks}}</td>
				</tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
			</tbody>
		</table>
        <?php pagination($noOfPages,Input::all(),Input::get('page'),"contractorrpt.monitoringlistofsuspendedfirms"); ?>
	</div>
</div>
@stop
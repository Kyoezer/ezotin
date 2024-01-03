@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel';  ?>
            @if(!Input::has('export'))
            <i class="fa fa-cogs"></i>List of Monitored Firms Status Wise&nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route("contractorrpt.monitoringlistofsuspendedfirms",$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel </a>   <?php $parameters['export'] = 'print'; ?><a href="{{route("contractorrpt.monitoringlistoffirms",$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
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
                        <label class="control-label">Status</label>
                        {{Form::select('Status',array('2'=>'Not fulfilled','1'=>'Fulfilled'),Input::get('Status'),array('class'=>'form-control input-sm'))}}
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
                        Monitored On
                    </th>
                    <th>Monitoring Status</th>
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
                    <td>{{convertDateToClientFormat($data->MonitoringDate)}}</td>
                    <td>{{($data->MonitoringStatus == 1) ? "Ok":"Not Ok"}}</td>
				</tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
			</tbody>
		</table>
        <?php pagination($noOfPages,Input::all(),Input::get('page'),"contractorrpt.monitoringlistoffirmsstatuswise"); ?>
	</div>
</div>
@stop
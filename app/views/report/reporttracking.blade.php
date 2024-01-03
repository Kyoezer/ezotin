@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; $route="rpt.trackingreport";?>
            @if(!Input::has('export'))
            <i class="fa fa-cogs"></i>Report Tracking &nbsp;&nbsp;@if(!Input::has('export'))   <?php $parameters['export'] = 'print'; ?><a href="{{route($route,$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		    @endif
        </div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != "print")
        {{Form::open(array('url'=>str_replace('.','/',$route),'method'=>'get'))}}
		<div class="form-body">
			<div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Report No.</label>
                        <select name="operation" class="form-control input-sm">
                            <option value="">All</option>
                            @foreach($operations as $operation)
                                <option value="{{$operation->operation}}" @if($operation->operation == Session::get("reporttracking_operation"))selected="selected"@endif>{{$operation->operation}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Detail</label>
                        <input type="text" name="workid" value="{{Session::get("reporttracking_workid")}}" class="form-control input-sm"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">User</label>
                        <select name="username" class="form-control select2me input-sm">
                            <option value="">All</option>
                            @foreach($users as $user)
                                <option value="{{$user->username}}" @if($user->username == Session::get("reporttracking_username") && Session::has("reporttracking_username"))selected="selected"@endif>{{$user->username}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Date Between</label>
                        <div class="input-group input-large date-picker input-daterange">
                            <input type="text" name="FromDate" class="input-sm form-control datepicker" value="{{Session::has("reporttracking_fromdate")?convertDateToClientFormat(Session::get("reporttracking_fromdate")):''}}" />
						<span class="input-group-addon input-sm">
						to </span>
                            <input type="text" name="ToDate" class="input-sm form-control datepicker" value="{{Session::has("reporttracking_todate")?convertDateToClientFormat(Session::get("reporttracking_todate")):''}}" />
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Records per page</label>
                        {{Form::select("RecordsPerPage",array('200'=>'200','300'=>'300','500'=>'500','1000'=>'1000','2000'=>'2000','5000'=>'5000'),Input::get('RecordsPerPage'),array('class'=>'form-control input-sm'))}}
                    </div>
                </div>
                @if(!Input::has('export'))
                    <div class="col-md-2">
                        <label class="control-label"></label>
                        <div class="btn-set">
                            <button type="submit" class="btn blue-hoki btn-sm">Search</button>
                            <a href="{{Request::url()}}?clear=true" class="btn grey-cascade btn-sm">Clear</a>
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
		<table class="table table-bordered dont-flip table-striped table-condensed flip-content" id="contractorhumanresource">
			<thead class="flip-content">
				<tr>
                    <th>
                        Sl.No.
                    </th>
					<th>
						Details
					</th>
                    <th>User</th>
                    <th>Operation</th>
					<th class="">
						 Operation TIme
					</th>
				</tr>
			</thead>
			<tbody>
            <?php $count = 1; ?>
            @if(Input::has('page'))
                <?php $count = (Input::get('page') - 1) * (Input::has('RecordsPerPage')?Input::get('RecordsPerPage'):200) + 1; ?>
            @endif
            @forelse($reportData as $data)
				<tr>
                    <td>{{$count}}</td>
                    <td>{{$data->workid}}</td>
                    <td>{{$data->username}}</td>
                    <td>{{$data->operation}}</td>
                    <td>{{convertDateTimeToClientFormat($data->op_time)}}</td>
				</tr>
                <?php $count++; ?>
            @empty
                <tr>
                    <td colspan="5" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse

			</tbody>
		</table>
            @if(count($reportData)>0)
                <?php echo $reportData->appends(Input::all())->links(); ?>
            @endif
	</div>
</div>
@stop
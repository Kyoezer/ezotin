@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; $route = 'etoolrpt.hindrancereport';?>
            @if(!Input::has('export'))
            <i class="fa fa-cogs"></i>Hindrance Report&nbsp;&nbsp;@if(!Input::has('export'))  <a href="{{route($route,$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel </a> <?php $parameters['export'] = 'print'; ?><a href="{{route($route,$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		    @endif
        </div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != "print")
        {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
		<div class="form-body">
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">CDB No</label>
						<input type="text" name="CDBNo" value="{{Input::get('CDBNo')}}" class="form-control" />
					</div>
				</div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Work Id</label>
                        <input type="text" name="WorkId" value="{{Input::get('WorkId')}}" class="form-control" />
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
					<th>
						Firm
					</th>
                    <th>Name of Work</th>
                    <th>WorkId</th>
                    <th>Amount</th>
					<th class="">
						 Start Date
					</th>
                    <th class="">
                        End Date
                    </th>
                    <th class="">
                        No. of Days
                    </th>
                    <th class="">
                        Remarks
                    </th>
				</tr>
			</thead>
			<tbody>
            <?php $count = 1; ?>
            @forelse($hindranceWorks as $data)
				<tr>
                    <td>{{$count++}}</td>
                    <td>{{$data->Contractors}}</td>
                    <td>{{$data->NameOfWork}}</td>
                    <td>{{$data->WorkId}}</td>
                    @if($data->CmnWorkExecutionStatusId == CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                        <td>
                            {{$data->AwardedAmount}}
                        </td>
                        <td>
                            {{convertDateToClientFormat($data->ActualStartDate)}}
                        </td>
                        <td>
                            {{convertDateToClientFormat($data->ActualEndDate)}}
                        </td>
                    @else
                        <td>
                            {{$data->ContractPriceFinal}}
                        </td>
                        <td>
                            {{convertDateToClientFormat($data->CommencementDateFinal)}}
                        </td>
                        <td>
                            {{convertDateToClientFormat($data->CompletionDateFinal)}}
                        </td>
                    @endif
                    <td>{{$data->HindranceNoOfDays}}</td>
                    <td>{{$data->Remarks}}</td>
				</tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
			</tbody>
		</table>
	</div>
    {{$hindranceWorks->links()}}
</div>
@stop
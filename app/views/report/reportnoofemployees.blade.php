@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; $route = Request::segment(1).'.'.Request::segment(2); ?>
            @if(!Input::has('export'))
            <i class="fa fa-cogs"></i>Report of Employees @if(!Input::has('export')) <a href="{{route($route,$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel </a>   <?php $parameters['export'] = 'print'; ?><a href="{{route($route,$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		    @endif
        </div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != "print")
        {{Form::open(array('url'=>Request::segment(1).'/'.Request::segment(2),'method'=>'get'))}}
		<div class="form-body">
			<div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Dzongkhag</label>
                        <select class="form-control select2me" name="Dzongkhag">
                            <option value="">---SELECT ONE---</option>
                            @foreach($dzongkhags as $dzongkhag)
                                <option value="{{$dzongkhag->NameEn}}" @if($dzongkhag->NameEn == Input::get('Dzongkhag'))selected @endif>{{$dzongkhag->NameEn}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Country</label>
                        <select class="form-control select2me" name="Country">
                            <option value="">---SELECT ONE---</option>
                            @foreach($countries as $country)
                                <option value="{{$country->Name}}" @if($country->Name== Input::get('Country'))selected @endif>{{$country->Name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Gender</label>
                        {{Form::select('Gender',array(''=>'All','F'=>'Female','M'=>'Male'),Input::get('Gender'),array('class'=>'form-control'))}}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Contractor/Consultant</label>
                        {{Form::select('Type',array(''=>'All','2'=>'Contractor','3'=>'Consultant'),Input::get('Type'),array('class'=>'form-control'))}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Joining Date Between</label>
                        <div class="input-group input-large date-picker input-daterange">
                            <input type="text" name="FromDate" class="form-control datepickerfrom" value="{{Input::get('FromDate')}}" />
						<span class="input-group-addon">
						to </span>
                            <input type="text" name="ToDate" class="form-control datepickerto" value="{{Input::get('ToDate')}}" />
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
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
            @if(Input::has('Dzongkhag'))
                <b>Dzongkhag: {{Input::get('Dzongkhag')}}</b> <br/>
            @else
                <b>Dzongkhag: All</b> <br/>
            @endif
            @if(Input::has('Country'))
                <b>Country: {{Input::get('Country')}}</b> <br/>
            @else
                <b>Country: All</b> <br/>
            @endif
            @if(Input::has('Gender'))
                <b>Gender: {{Input::get('Gender')}}</b> <br/>
            @else
                <b>Gender: All</b><br/>
            @endif
            @if(Input::has('Type'))
                <b>Type: @if(Input::get('Type') == 2){{"Contractor"}}@else{{"Consultant"}}@endif</b> <br/>
            @else
                <b>Type: All</b><br/>
            @endif
            @if(Input::has('FromDate'))
                <b>Joining Date from: {{Input::get('FromDate')}}</b> <br/>
            @endif
            @if(Input::has('ToDate'))
                <b>Joining Date to: {{Input::get('ToDate')}}</b> <br/>
            @endif
            @if(Input::has('Limit'))
                <b>{{Input::get('Limit')}} records</b>
            @else
                <b>All records</b>
            @endif
        @endif
		<table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
			<thead class="flip-content">
				<tr>
                    <th>
                        Sl.No.
                    </th>
					<th class="order">
						 Firm Name
					</th>
					<th>
						Name
					</th>
					<th class="">
						 Dzongkhag
					</th>
					<th class="">
						 CID No.
					</th>
					<th>
						Sex
					</th>
                    <th>
                        Joining Date
                    </th>
					<th>
						Country
					</th>
					<th class="">
						 Qualification
					</th>
					<th class="">
						Designation
					</th>
                    <th class="">
                        Trade
                    </th>
				</tr>
			</thead>
			<tbody>
            <?php $count = 1; ?>
            @forelse($reportData as $value)
				<tr>
                    <td>{{$count}}</td>
                    <td>{{$value->FirmName}}</td>
                    <td>{{$value->Name}}</td>
                    <td>{{$value->Dzongkhag}}</td>
                    <td>{{$value->CIDNo}}</td>
                    <td>{{$value->Sex}}</td>
                    <td>{{convertDateToClientFormat($value->JoiningDate)}}</td>
                    <td>{{$value->Country}}</td>
                    <td>{{$value->Qualification}}</td>
                    <td>{{$value->Designation}}</td>
                    <td>{{$value->Trade}}</td>
				</tr>
                <?php $count++; ?>
            @empty
                <tr>
                    <td colspan="10" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
			</tbody>
		</table>
	</div>
</div>
@stop
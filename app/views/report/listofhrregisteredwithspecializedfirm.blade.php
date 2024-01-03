@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
            @if(!Input::has('export'))
            <i class="fa fa-cogs"></i>List of Hr Registered with Specialized Firm &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route("specializedfirmrpt.listofregisteredhrwithspecializedfirm",$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel </a>   <?php $parameters['export'] = 'print'; ?><a href="{{route("specializedfirmrpt.listofregisteredhrwithspecializedfirm",$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		    @endif
        </div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != "print")
        {{Form::open(array('url'=>'specializedfirmrpt/listofregisteredhrwithspecializedfirm','method'=>'get'))}}
		<div class="form-body">
        <div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">SP No</label>
						<input type="text" name="SPNo" value="{{Input::get('SPNo')}}" class="form-control" />
					</div>
				</div>
              
                <div class="col-md-2">
                    <label class="control-label">Designation:</label>
                    <select class="form-control select2me" name="CmnDesignationId">
                        <option value="">---SELECT ONE---</option>
                        @foreach($designationId as $designation)
                            <option value="{{$designation->Name}}" @if($designation->Name == Input::get('CmnDesignationId'))selected @endif>{{$designation->Name}}</option>
                        @endforeach
                    </select>
                </div>
               
                <div class="col-md-2">
                    <label class="control-label">Qualification:</label>
                    <select class="form-control select2me" name="CmnQualificationId">
                        <option value="">---SELECT ONE---</option>
                        @foreach($qualificationId as $qualification)
                            <option value="{{$qualification->Name}}" @if($qualification->Name == Input::get('CmnQualificationId'))selected @endif>{{$qualification->Name}}</option>
                        @endforeach
                    </select>
                </div>
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
                Name
                </th>
                <th>
                    CIDNo
                </th>
                <th>
                Individual Reg No
                </th>
                 <th>
                    Gender
                </th>
<th>Country</th>
                <th>
                    Qualification
                </th>
                <th>
                    Designation
                </th>
   <th>
                    Joining Date
                </th>
                <th class="">
                    SP No.
                </th>
                <th class="">
                    Name of Firm
                </th>
                      
            </tr>
            </thead>
            <tbody>
            @forelse($specializedfirmLists as $specializedfirm)
                <tr>
                    <td>{{$start++}}</td>
                    <td>{{$specializedfirm->Name}}</td>
<td>{{$specializedfirm->CIDNo}}</td>
<td>{{$specializedfirm->IndividualRegNo}}</td>
<td>{{$specializedfirm->Gender}}</td>
<td>{{$specializedfirm->Country}}</td>
                    <td>{{$specializedfirm->Qualification}}</td>
                    <td>{{$specializedfirm->Designation}}</td>
<td>{{$specializedfirm->JoiningDate}}</td>
                    <td>{{$specializedfirm->SPNo}}</td>
                    <td>{{$specializedfirm->Firm}}</td>
       
                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
		</table>
            <?php pagination($noOfPages,Input::all(),Input::get('page'),"specializedfirmrpt.listofregisteredhrwithspecializedfirm"); ?>
	</div>
</div>
@stop
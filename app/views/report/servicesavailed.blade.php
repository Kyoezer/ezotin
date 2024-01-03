@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel';  $route = 'ezotinrpt.servicesavailed'; ?>
                <i class="fa fa-cogs"></i>Report of Services Availed &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route($route,$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel </a>   <?php $parameters['export'] = 'print'; ?><a href="{{route($route,$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != 'print')
        {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
		<div class="form-body">
			<div class="row">
                <div class="col-md-2">
                    <label class="control-label">From</label>
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
            @foreach(Input::except('export') as $key=>$value)
                <b>{{$key}}: {{$value}}</b><br>
            @endforeach
            <br/>
        @endif
            <br>
        <div class="row">
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
                    <thead class="flip-content">
                        <tr>
                            <th>Applicant Type</th>
                            <th>
                                Service Type
                            </th>
                            <th class="text-right">
                                 No. of times Availed
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $contractorCount = $consultantCount = $architectCount = $engineerCount = $spCount = 0; ?>
                        <tr>
                            <td class="bold">Contractor</td>
                            <td>New Registration</td>
                            <td class="text-right">{{$contractorNewRegistrations}}<?php $contractorCount+= $contractorNewRegistrations;?></td>
                        </tr>
                        @foreach($contractorServices as $contractorServiceName => $contractorService)
                            <tr>
                                <td class="bold"></td>
                                <td>{{$contractorServiceV[$contractorService]['Name']}}</td>
                                <td class="text-right">{{$contractorServiceV[$contractorService]['Count']}}<?php $contractorCount+= $contractorServiceV[$contractorService]['Count'];?></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" class="text-right bold">
                                Total for Contractors: </td>
                            <td class="text-right">{{$contractorCount}}</td>
                        </tr>
                        <tr>
                            <td class="bold">Consultant</td>
                            <td>New Registration</td>
                            <td class="text-right">{{$consultantNewRegistrations}}<?php $consultantCount+= $consultantNewRegistrations;?></td>
                        </tr>
                        @foreach($consultantServices as $consultantServiceName => $consultantService)
                            <tr>
                                <td class="bold"></td>
                                <td>{{$consultantServiceV[$consultantService]['Name']}}</td>
                                <td class="text-right">{{$consultantServiceV[$consultantService]['Count']}}<?php $consultantCount+= $consultantServiceV[$consultantService]['Count'];?></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" class="text-right bold">
                                Total for Consultants: </td>
                            <td class="text-right">{{$consultantCount}}</td>
                        </tr>

                        <tr>
                            <td class="bold">Architect</td>
                            <td>New Registration</td>
                            <td class="text-right">{{$architectNewRegistrations}}<?php $architectCount+= $architectNewRegistrations;?></td>
                        </tr>
                        @foreach($architectServices as $architectServiceName => $architectService)
                            <tr>
                                <td class="bold"></td>
                                <td>{{$architectServiceV[$architectService]['Name']}}</td>
                                <td class="text-right">{{$architectServiceV[$architectService]['Count']}}<?php $architectCount+= $architectServiceV[$architectService]['Count'];?></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" class="text-right bold">
                                Total for Architects: </td>
                            <td class="text-right">{{$architectCount}}</td>
                        </tr>

                        <tr>
                            <td class="bold">Specialized Trade</td>
                            <td>New Registration</td>
                            <td class="text-right">{{$spNewRegistrations}}<?php $spCount+= $spNewRegistrations;?></td>
                        </tr>
                        @foreach($spServices as $spServiceName => $spService)
                            <tr>
                                <td class="bold"></td>
                                <td>{{$spServiceV[$spService]['Name']}}</td>
                                <td class="text-right">{{$spServiceV[$spService]['Count']}}<?php $spCount+= $spServiceV[$spService]['Count'];?></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" class="text-right bold">
                                Total for Specialized Traders: </td>
                            <td class="text-right">{{$spCount}}</td>
                        </tr>

                        <tr>
                            <td colspan="2" class="text-right bold">
                                Grand Total: </td>
                            <td class="text-right">{{$spCount+$architectCount+$contractorCount+$consultantCount}}</td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div><div class="clearfix"></div>
    </div>
</div>
@stop
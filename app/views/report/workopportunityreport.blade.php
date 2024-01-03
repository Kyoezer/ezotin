@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; $route = 'ezotinrpt.etoolworkopportunityreport'; ?>
			<i class="fa fa-cogs"></i>Employment Opportunity Report &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route($route,$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route($route,$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export')!='print')
        {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
		<div class="form-body">
            <div class="row">
                <div class="col-md-12">
                    <p class="font-red"><i>*Date field is required. You can view track records for a contractor with combinations of different parameters.</i></p>
                </div>
            </div>
			<div class="row">
                    <div class="clearfix"></div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Date Between</label>
                            <div class="input-group input-large date-picker input-daterange">
                                <input type="text" name="FromDate" class="input-sm form-control datepicker" value="{{Input::has("FromDate")?convertDateToClientFormat(Input::get('FromDate')):''}}" />
						<span class="input-group-addon input-sm">
						to </span>
                                <input type="text" name="ToDate" class="input-sm form-control datepicker" value="{{Input::has("ToDate")?convertDateToClientFormat(Input::get('ToDate')):''}}" />
                                <input type="hidden" name="x" value="1" />
                            </div>
                        </div>
                    </div>
                @if(!Input::has('export'))
                    <div class="clearfix"></div>
                    <div class="col-md-2">
                        {{--<label class="control-label">&nbsp;</label>--}}
                        <div class="btn-set">

                            <button type="submit" class="btn blue-hoki btn-sm">Search</button>
                            <a href="{{Request::url()}}" class="btn grey-cascade btn-sm">Clear</a>
                        </div>
                    </div>
                    <br><br>
                @endif
			</div>
		</div>
        {{Form::close()}}
        @endif
        @if($show)
        <div class="row">
        <div class="col-md-6">
            <div class="table-responsive">
            <table class="table table-bordered table-condensed" id="">
                <thead class="flip-content">
                <tr>
                    <th>No. of works</th>
                    <th>Designations/Vacancy</th>
                </tr>
                </thead>
                <tbody>
                    <?php $count = 0; ?>
                    <tr>
                        <td>
                            @foreach($worksByClass as $workByClass)
                                {{$workByClass->Code}} - {{$workByClass->WorkCount}}<br>
                            @endforeach
                        </td>
                        <td>
                            @if(count($designations)>0)
                                <table class="table table-bordered table-condensed">
                                    <tbody>
                                        @foreach($designations as $designation)
                                            <tr>
                                                <td>{{$designation->Designation}}</td>
                                                <td>{{$designation->HRCount}} <?php $count+=(int)$designation->HRCount ?></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                        </td>
                    </tr>
                    <tr>
                        <td class="bold text-right">Total:</td>
                        <td>{{$count}}</td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
        </div>
        <div class="clearfix"></div>
        @endif
    </div>
</div>
@stop
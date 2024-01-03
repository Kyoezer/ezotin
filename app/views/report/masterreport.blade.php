@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; if(isset($overrunReport)): if($overrunReport == 1):$route = "rpt.costoverrunreport";else:$route="rpt.costoverrunreportsummary"; endif; else: $route="contractorrpt.trackrecord"; endif; ?>
			<i class="fa fa-cogs"></i>Master Report&nbsp;@if(!Input::has('export')) <a href="{{route($route,$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route($route,$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export')!='print')
        {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
		<div class="form-body">
            <div class="row">
                <div class="col-md-12">
                    <p class="font-red"><i>*At least one parameter is required. You can view reports with combinations of different parameters.</i></p>
                </div>
            </div>
			<div class="row">
                <div class="col-md-1">
                    <div class="form-group">
                        <label class="control-label">CDB No.</label>
                        <input type="text" name="CDBNo" class="form-control input-sm" value="{{Input::get('CDBNo')}}">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label class="control-label">Category</label>
                        <select class="form-control input-sm" name="ProjectCategory">
                            <option value="">--</option>
                            @foreach($categories as $category)
                                <option value="{{$category->ProjectCategory}}" @if($category->ProjectCategory == Input::get('ProjectCategory'))selected="selected"@endif>{{$category->ProjectCategory}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label class="control-label">Class</label>
                        <select class="form-control input-sm" name="Class">
                            <option value="">--</option>
                            @foreach($classes as $class)
                                <option value="{{$class->Class}}" @if($class->Class== Input::get('Class'))selected="selected"@endif>{{$class->Class}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Procuring Agency</label>
                        <select class="form-control input-sm select2me" name="ProcuringAgency">
                            <option value="">---SELECT ONE---</option>
                            @foreach($procuringAgencies as $procuringAgency)
                                <option value="{{$procuringAgency->ProcuringAgency}}" @if($procuringAgency->ProcuringAgency == Input::get('ProcuringAgency'))selected="selected"@endif>{{$procuringAgency->ProcuringAgency}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Dzongkhag</label>
                        <select class="form-control selectfilter input-sm" name="Dzongkhag">
                            <option value="">---SELECT ONE---</option>
                            @foreach($dzongkhags as $dzongkhag)
                                <option value="{{$dzongkhag->Dzongkhag}}" @if($dzongkhag->Dzongkhag == Input::get('Dzongkhag'))selected @endif>{{$dzongkhag->Dzongkhag}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                    <div class="clearfix"></div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Date Between</label>
                            <div class="input-group input-large date-picker input-daterange">
                                <input type="text" name="FromDate" class="input-sm form-control datepicker" value="{{Input::has("FromDate")?convertDateToClientFormat(Input::get('FromDate')):''}}" />
						<span class="input-group-addon input-sm">
						to </span>
                                <input type="text" name="ToDate" class="input-sm form-control datepicker" value="{{Input::has("ToDate")?convertDateToClientFormat(Input::get('ToDate')):''}}" />
                            </div>
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
                    <br><br>
                @endif
			</div>
		</div>
        @else
            @foreach($parametersForPrint as $key=>$value)
                <b>{{$key}}: {{$value}}</b><br>
            @endforeach
            <br/>
        @endif
        {{Form::close()}}
            <table class="table table-bordered table-striped table-condensed" id="">
                <thead class="flip-content">
                <tr>
                    <th>Sl.No.</th>
                    <th>Year</th>
                    <th>
                        Firm
                    </th>
                    <th>Agency</th>
                    <th>Work Id</th>
                    <th>Work Name</th>
                    <th>Category</th>
                    <th>Class</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Awarded Amount (Nu.)</th>
                    <th>Final Amount (Nu.)</th>
                    <th>Dzongkhag</th>
                    <th>Status</th>
                    <th>APS scoring</th>
                    <th>APS (100)</th>
                    <th>LD</th>
                    <th>Hindrance</th>
                    <th>Remarks</th>
                </tr>
                </thead>
                <tbody>
                <?php $count = 1; $awardedAmount = 0; $totalCostOverrun = 0;?>
                @forelse($workDetails as $workDetail)
                    <tr>
                        <td>{{$count}}</td>
                        <td>{{$workDetail->Year}}</td>
                        <td>{{$workDetail->Contractor}} ({{$workDetail->CDBNo}})</td>
                        <td>{{$workDetail->Agency}}</td>
                        <td>{{$workDetail->WorkId}}</td>
                        <td>{{strip_tags($workDetail->NameOfWork)}}</td>
                        <td>{{$workDetail->Category}}</td>
                        <td>{{$workDetail->Classification}}</td>
                        <td>{{convertDateToClientFormat($workDetail->WorkStartDate)}}</td>
                        <td>{{convertDateToClientFormat($workDetail->CompletionDateFinal)}}</td>
                        <td class="text-right">{{number_format($workDetail->AwardedAmount,2)}}</td>
                        <td class="text-right">{{number_format($workDetail->FinalAmount,2)}}</td>
                        <td>{{$workDetail->Dzongkhag}}</td>
                        <td>{{$workDetail->Status}}</td>
                        <td>
                            <?php if((int)$workDetail->APS == 100) {
                                $points = 10;
                            }
                            if(((int)$workDetail->APS < 100) && ((int)$workDetail->APS >= 50)) {
                                $points = 10 - (ceil((100 - (int)$workDetail->APS) / 5));
                            }
                            if((int)$workDetail->APS < 50){
                                $points = 0;
                            }
                            ?>
                            {{$points}}
                        </td>
                        <td>{{$workDetail->APS}}</td>
                        <td>
                            @if((int)$workDetail->LDImposed == 1)
                                {{$workDetail->LDNoOfDays}}, {{$workDetail->LDAmount}}
                            @else
                                ---
                            @endif
                        </td>
                        <td>
                            @if((int)$workDetail->Hindrance == 1)
                                {{$workDetail->HindranceNoOfDays}}
                            @else
                                ---
                            @endif
                        </td>
                        <td>{{$workDetail->Remarks}}</td>
                    </tr>
                    <?php $count++ ?>
                @empty
                    <tr><td colspan="19" class="font-red text-center">No data to display</td></tr>
                @endforelse
                </tbody>
            </table>
	</div>
</div>
@stop
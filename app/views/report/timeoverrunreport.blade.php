@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; $route = "rpt.timeoverrunreport"; ?>
			<i class="fa fa-cogs"></i>Time overrun Report @if($summary){{"Summary"}}@endif &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route($route,$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route($route,$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
		<div class="form-body">
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
                            <option value="">All</option>
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
                            <option value="">All</option>
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
                        <select class="form-control input-sm" name="Dzongkhag">
                            <option value="">---SELECT ONE---</option>
                            @foreach($dzongkhags as $dzongkhag)
                                <option value="{{$dzongkhag->Dzongkhag}}" @if($dzongkhag->Dzongkhag == Input::get('Dzongkhag'))selected @endif>{{$dzongkhag->Dzongkhag}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Completion Date Between</label>
                            <div class="input-group input-large date-picker input-daterange">
                                <input type="text" name="FromDate" class="input-sm form-control datepicker" value="{{Input::has("FromDate")?convertDateToClientFormat(Input::get('FromDate')):''}}" />
						<span class="input-group-addon input-sm">
						to </span>
                                <input type="text" name="ToDate" class="input-sm form-control datepicker" value="{{Input::has("ToDate")?convertDateToClientFormat(Input::get('ToDate')):''}}" />
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
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-condensed" id="">
                <thead class="flip-content">
                <tr>
                    <th>Sl.No.</th>
                    <th>Year</th>
                    @if(!$summary)
                        <th>Firm</th>
                    @endif
                    <th>Agency</th>
                    <th>Work Id</th>
                    <th>Work Name</th>
                    <th>Category</th>
                    <th>Class</th>
                    <th>Awarded Amount (Nu.)</th>
                    <th>Final Amount (Nu.)</th>
                    @if(!$summary)
                    <th width="85">Awarded Date</th>
                    <th width="85">Final Date</th>
                    @endif
                    <th>Time overrun (days)</th>
                    @if(!$summary)
                    <th>LD</th>
                    <th>Hindrance</th>
                    <th>Remarks</th>
                    <th>Dzongkhag</th>
                    <th>Status</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                <?php $count = 1; $awardedAmount = 0; $totalTimeOverrun = 0; $totalAwardedAmount = 0; $totalFinalAmount = 0;?>
                @forelse($workDetails as $workDetail)
                    <tr>
                        <td>{{$count}}</td>
                        <td>{{$workDetail->Year}}</td>
                        @if(!$summary)
                        <td>{{$workDetail->Contractor}} ({{$workDetail->CDBNo}})</td>
                        @endif
                        <td>{{$workDetail->Agency}}</td>
                        <td>{{$workDetail->WorkId}}</td>
                        <td>{{strip_tags($workDetail->NameOfWork)}}</td>
                        <td>{{$workDetail->Category}}</td>
                        <td>{{$workDetail->Classification}}</td>
                        <td class="text-right">{{number_format($workDetail->AwardedAmount,2)}}<?php $totalAwardedAmount+=doubleval($workDetail->AwardedAmount); ?></td>
                        <td class="text-right">{{number_format($workDetail->FinalAmount,2)}}<?php $totalFinalAmount+=doubleval($workDetail->FinalAmount); ?></td>
                        @if(!$summary)
                            <td class="text-right">{{convertDateToClientFormat($workDetail->ActualEndDate)}}</td>
                            <td class="text-right">{{convertDateToClientFormat($workDetail->CompletionDateFinal)}}</td>
                        @endif
                        <?php $totalTimeOverrun+=(int)$workDetail->DateDiff; ?>
                        <td class="text-right">{{number_format($workDetail->DateDiff)}}</td>
                        @if(!$summary)
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
                        <td>{{$workDetail->Dzongkhag}}</td>
                        <td>{{$workDetail->Status}}<?php $awardedAmount+=($workDetail->ReferenceNo == 3001)?1:0;?></td>
                        @endif
                    </tr>
                    <?php $count++ ?>
                @empty
                    <tr><td colspan="@if($summary){{"10"}}@else{{"18"}}@endif" class="font-red text-center">No data to display</td></tr>
                @endforelse
                @if(count($workDetails)>0)
                    <tr>
                        <td colspan="@if($summary){{"7"}}@else{{"8"}}@endif class="text-right bold">Total:</td>
                        <td class="text-right">{{number_format($totalAwardedAmount,2)}}</td>
                        <td class="text-right">{{number_format($totalFinalAmount,2)}}</td>
                        @if(!$summary)
                            <td></td>
                            <td></td>
                        @endif
                        <td class="text-right">{{number_format($totalTimeOverrun)}}</td>
                        @if(!$summary)
                            <td colspan="5"></td>
                        @endif
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
	</div>
</div>
@stop
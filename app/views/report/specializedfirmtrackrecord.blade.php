@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; if(isset($overrunReport)): if($overrunReport == 1):$route = "rpt.costoverrunreport";else:$route="rpt.costoverrunreportsummary"; endif; else: $route="specializedfirmrpt.trackrecords"; endif; ?>
			<i class="fa fa-cogs"></i>@if(isset($overrunReport)){{"Cost overrun report"}}@else{{"Specializedfirm's Track Record"}}@endif &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route($route,$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route($route,$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export')!='print')
        {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
		<div class="form-body">
            @if(!isset($overrunReport))
            <div class="row">
                <div class="col-md-12">
                    <p class="font-red"><i>*SP No. Field is required. You can view track records for a contractor with combinations of different parameters.</i></p>
                </div>
            </div>
            @endif
			<div class="row">
                <div class="col-md-1">
                    <div class="form-group">
                        <label class="control-label">SP No.@if(!isset($overrunReport))<span class="font-red"> *</span>@endif</label>
                        <input type="text" name="SPNo" class="form-control input-sm" value="{{Input::get('SPNo')}}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Category</label>
                        <select class="form-control input-sm" name="ProjectCategory">
                            <option value="">---SELECT ONE---</option>
                            @foreach($categories as $category)
                                <option value="{{$category->ProjectCategory}}" @if($category->ProjectCategory == Input::get('ProjectCategory'))selected="selected"@endif>{{$category->ProjectCategory}}</option>
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
                @if(isset($overrunReport))
                    <div class="clearfix"></div>
                    <div class="col-md-4">
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
                @endif
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
            <table class="table table-bordered table-striped table-condensed" id="">
                <thead class="flip-content">
                <tr>
                    <th>Sl.No.</th>
                    @if(!isset($overrunReport))
                        <th>Year</th>
                    @else
                        @if($overrunReport != 2)
                            <th>Year</th>
                        @endif
                    @endif
                    @if(isset($overrunReport) && $overrunReport == 1)
                        <th>
                            Firm
                        </th>
                    @endif
                    <th>Agency</th>
                    @if(!isset($overrunReport))
                        <th>Work Id</th>
                        <th>Work Name</th>
                        <th>Category</th>
                        
                    @else
                        @if($overrunReport != 2)
                            <th>Work Id</th>
                            <th>Work Name</th>
                            <th>Category</th>
                           
                        @endif
                    @endif

                    <th>Awarded Amount (Nu.)</th>
                    <th>Final Amount (Nu.)</th>
                    @if(isset($overrunReport))
                        <th>Cost Overrun (Nu.)</th>
                    @endif
                    @if(!isset($overrunReport))
                        <th>Dzongkhag</th>
                        <th>Status</th>
                        <th>APS scoring</th>
                        <th>APS (100)</th>
                        <th>Remarks</th>
                    @else
                        @if($overrunReport != 2)
                            <th>Dzongkhag</th>
                            <th>Status</th>
                            {{--<th>APS scoring</th>--}}
                            {{--<th>APS (100)</th>--}}
                            {{--<th>Remarks</th>--}}
                        @endif
                    @endif

                </tr>
                </thead>
                <tbody>
                <?php $count = 1; $awardedAmount = 0; $totalCostOverrun = 0;?>
                @forelse($workDetails as $workDetail)
                    <tr>
                        <td>{{$count}}</td>
                        @if(!isset($overrunReport))
                            <td>{{$workDetail->Year}}</td>
                        @else
                            @if($overrunReport != 2)
                                <td>{{$workDetail->Year}}</td>
                            @endif
                        @endif
                        @if(isset($overrunReport) && $overrunReport == 1)
                        <td>{{$workDetail->Contractor}} ({{$workDetail->CDBNo}})</td>
                        @endif
                        <td>{{$workDetail->Agency}}</td>
                        @if(!isset($overrunReport))
                            <td>{{$workDetail->WorkId}}</td>
                            <td>{{strip_tags($workDetail->NameOfWork)}}</td>
                            <td>{{$workDetail->Category}}</td>
                            <td>{{$workDetail->Classification}}</td>
                        @else
                            @if($overrunReport != 2)
                                <td>{{$workDetail->WorkId}}</td>
                            <td>{{strip_tags($workDetail->NameOfWork)}}</td>
                            <td>{{$workDetail->Category}}</td>
                            <td>{{$workDetail->Classification}}</td>
                            @endif
                        @endif

                        <td class="text-right">{{number_format($workDetail->AwardedAmount,2)}}</td>
                        <td class="text-right">{{number_format($workDetail->FinalAmount,2)}}</td>
                        @if(isset($overrunReport))
                            <?php $costOverRun = (doubleval($workDetail->FinalAmount)-doubleval($workDetail->AwardedAmount)); $totalCostOverrun+=$costOverRun; ?>
                            <td class="text-right">{{number_format($costOverRun,2)}}</td>
                        @endif
                        @if(!isset($overrunReport))
                            <td>{{$workDetail->Dzongkhag}}</td>
                        <td>{{$workDetail->Status}}<?php $awardedAmount+=($workDetail->ReferenceNo == 3001)?1:0;?></td>
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
                        <td>{{$workDetail->Remarks}}</td>
                        @else
                            @if($overrunReport != 2)
                                <td>{{$workDetail->Dzongkhag}}</td>
                        <td>{{$workDetail->Status}}<?php $awardedAmount+=($workDetail->ReferenceNo == 3001)?1:0;?></td>
                        {{--<td>--}}
                            <?php /*if((int)$workDetail->APS == 100) {
                                $points = 10;
                            }
                            if(((int)$workDetail->APS < 100) && ((int)$workDetail->APS >= 50)) {
                                $points = 10 - (ceil((100 - (int)$workDetail->APS) / 5));
                            }
                            if((int)$workDetail->APS < 50){
                                $points = 0;
                            }*/
                            ?>
                            {{--{{$points}}--}}
                        {{--</td>--}}
                        {{--<td>{{$workDetail->APS}}</td>--}}
                        {{--<td>{{$workDetail->Remarks}}</td>--}}
                            @endif
                        @endif

                    </tr>
                    <?php $count++ ?>
                @empty
                    <tr><td colspan="@if(isset($overrunReport))@if($overrunReport == 1){{"12"}}@else{{"5"}}@endif @else{{"14"}}}@endif" class="font-red text-center">No data to display</td></tr>
                @endforelse
                @if(isset($overrunReport))
                    @if(count($workDetails) > 0)
                        <tr>
                            <td colspan="@if(isset($overrunReport))@if($overrunReport == 1){{"10"}}@else{{"4"}}@endif @endif" class="bold text-right">Total:</td>
                            <td class="text-right">{{number_format($totalCostOverrun)}}</td>
                            @if($overrunReport == 1)
                            <td colspan="2"></td>
                            @endif
                        </tr>
                    @endif
                @endif
                </tbody>
            </table>
	</div>
</div>
@stop
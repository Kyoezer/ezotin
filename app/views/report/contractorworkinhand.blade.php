@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
    {{HTML::script('assets/global/scripts/etool.js')}}
@stop
@section('content')
    <input type="hidden" name="URL" value="{{CONST_APACHESITELINK}}"/>
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>Contractor's work in hand &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('etoolrpt.contractorworkinhand',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('etoolrpt.contractorworkinhand',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            @if(Input::get('export')!="print")
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="cdbno">CDB No.:</label>
                            <input class="form-control" id="cdbno" type="text" class="cdbno" name="CDBNo" value="{{Input::get('CDBNo')}}"/>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="ContractorName">Contractor:</label>
                            <input type="hidden" name="CrpContractorFinalId" class="contractor-id"/>
                            <input type="text" id="ContractorName" class="contractorName form-control contractor-name"/>
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
                @foreach(Input::all() as $key=>$value)
                    @if($key != 'export')
                        <b>{{$key}}: {{$value}}</b><br>
                    @endif
                @endforeach
                <br/>
            @endif
            @forelse($singleContractor as $value)
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-condensed table-bordered">
                        <tbody>
                            <tr>
                                <td colspan="2" class="text-right"><strong>Name of Firm:</strong></td>
                                <td colspan="6"> {{$value->NameOfFirm}}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-right"><strong>CDB No.:</strong></td>
                                <td colspan="6"> {{$value->CDBNo}}</td>
                            </tr>
                            <tr>
                                <td class="text-right"><strong>W1:</strong></td>
                                <td> {{$value->Classification1}}</td>
                                <td class="text-right"><strong>W2:</strong></td>
                                <td> {{$value->Classification2}}</td>
                                <td class="text-right"><strong>W3:</strong></td>
                                <td> {{$value->Classification3}}</td>
                                <td class="text-right"><strong>W4:</strong></td>
                                <td> {{$value->Classification4}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @empty
            @endforelse

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed flip-content" id="">
                    <thead class="flip-content">
                        <tr>
                            <th>Sl.No.</th>
                            <th>Year</th>
                            <th>Agency</th>
                            <th>Work Id</th>
                            <th>Work Name</th>
                            <th>Category</th>
                            <th>Awarded Amount</th>
                            <th>Final Amount</th>
                            <th>Dzongkhag</th>
                            <th>Status</th>
                            <th>APS scoring</th>
                            <th>APS (100)</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $count = 1; $awardedAmount = 0;?>
                    @forelse($workDetails as $workDetail)
                        <tr>
                            <td>{{$count}}</td>
                            <td>{{$workDetail->Year}}</td>
                            <td>{{$workDetail->Agency}}</td>
                            <td>{{$workDetail->WorkId}}</td>
                            <td>{{strip_tags($workDetail->NameOfWork)}}</td>
                            <td>{{$workDetail->Category}}</td>
                            <td>{{$workDetail->AwardedAmount}}</td>
                            <td>{{$workDetail->FinalAmount}}</td>
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
                        </tr>
                        <?php $count++ ?>
                    @empty
                        <tr><td colspan="12" class="font-red text-center">No data to display</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if(!empty($workDetails))
                <h5><strong>No. of Works in Hand: </strong>{{$awardedAmount}}</h5>
            @endif
                <b>Note: </b>The information on track records/status of works are by no means up to date and hence the attached information are only those that the Secretariat could collect and compile. We urge you to verify with the procuring agencies concerned for complete list of works in hand/or its status.
        </div>
    </div>
@stop
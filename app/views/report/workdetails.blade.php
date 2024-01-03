@extends('reportsmaster')
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>Work Details &nbsp;&nbsp;@if(!Input::has('export')) <?php $parameters['export'] = 'print'; ?><a href="{{route('etoolrpt.workdetails',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Work Id</label>
                            <input type="text" class="form-control input-sm" name="WorkId" value="{{Input::get('WorkId')}}"/>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">&nbsp;</label>
                        <div class="btn-set">
                            <button type="submit" class="btn blue-hoki btn-sm">Search</button>
                            <a href="{{Request::url()}}" class="btn grey-cascade btn-sm">Clear</a>
                        </div>
                    </div>
                </div>
            </div>
            {{Form::close()}}
            @if(isset($tenderDetails[0]->TenderId) && $tenderDetails[0]->TenderId)
            <table class="table table-bordered table-condensed">
                <tbody>
                @foreach($tenderDetails as $tenderDetail)
                    <tr>
                        <td class="bold">Work ID:</td>
                        <td>{{$tenderDetail->WorkId}}</td>
                        <td class="bold">Class & Category</td>
                        <td>{{$tenderDetail->Classification.", ".$tenderDetail->Category}}</td>
                        <td class="bold">Estimated Project Cost</td>
                        <td>{{$tenderDetail->ProjectEstimateCost}}</td>
                    </tr>
                    <tr>
                        <td class="bold">Name of work</td>
                        <td>{{$tenderDetail->NameOfWork}}</td>
                        <td class="bold">Contract Period</td>
                        <td>{{$tenderDetail->ContractPeriod." month(s)"}}</td>
                        <td class="bold">Description of Work</td>
                        <td>{{html_entity_decode($tenderDetail->DescriptionOfWork)}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
                <h4>Human Resource Criteria for Work</h4>
                <table class="table table-bordered table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>Tier</th>
                            <th>Designation</th>
                            <th>Qualification</th>
                            <th>Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($criteriaHR as $hr)
                            <tr>
                                <td>{{$hr->Tier}}</td>
                                <td>{{$hr->Designation}}</td>
                                <td>{{$hr->Qualification}}</td>
                                <td>{{$hr->Points}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="font-red text-center">No data to display</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <h4>Equipment Criteria for Work</h4>
                <table class="table table-bordered table-condensed table-striped">
                    <thead>
                    <tr>
                        <th>Tier</th>
                        <th>Equipment</th>
                        <th>Quantity</th>
                        <th>Points</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($criteriaEquipments as $eq)
                        <tr>
                            <td>{{$eq->Tier}}</td>
                            <td>{{$eq->Equipment}}</td>
                            <td>{{$eq->Quantity}}</td>
                            <td>{{$eq->Points}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="font-red text-center">No data to display</td></tr>
                    @endforelse
                    </tbody>
                </table>
            @else
                <div class="font-red">No results to display</div>
            @endif
        </div>
    </div>
@stop
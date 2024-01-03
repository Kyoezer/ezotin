@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>No. of Personnel in Construction Industry Qualification and Gender Wise &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('contractorrpt.constructionpersonnel',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('contractorrpt.constructionpersonnel',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Gender</label>
                            {{Form::select('Sex',array(''=>'All','F'=>'Female','M'=>'Male'),Input::get('Sex'),array('class'=>'form-control input-sm'))}}
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
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed flip-content" id="">
                    <thead class="flip-content">
                    <tr>
                        <th>Qualification</th>
                        <th class="text-right">Employed in Contractors</th>
                        <th class="text-right">Employed in Consultants</th>
                        <th class="text-right">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $total = $contractorTotal = $consultantTotal = 0;?>
                    @foreach($reportData as $data)
                        <tr>
                            <td>{{$data->Qualification}}</td>
                            <td class="text-right">{{number_format($data->ContractorNumber)}}<?php $contractorTotal+= doubleval($data->ContractorNumber); ?></td>
                            <td class="text-right">{{number_format($data->ConsultantNumber)}}<?php $consultantTotal+= doubleval($data->ConsultantNumber); ?></td>
                            <?php $rowTotal = (int)$data->ContractorNumber + (int)$data->ConsultantNumber; ?>
                            <td class="text-right">{{number_format($rowTotal)}}</td>
                            <?php $total+=$rowTotal; ?>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="bold text-right">Total</td>
                        <td class="text-right">{{number_format($contractorTotal)}}</td>
                        <td class="text-right">{{number_format($consultantTotal)}}</td>
                        <td class="text-right">{{number_format($total)}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
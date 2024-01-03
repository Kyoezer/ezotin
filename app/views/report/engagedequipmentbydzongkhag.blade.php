@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>Engaged Equipments by dzongkhag &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('etoolrpt.engagedequipmentbydzongkhag',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('etoolrpt.engagedequipmentbydzongkhag',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            @if(Input::get('export') != 'print')
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">As on Date</label><div class="clearfix"></div>
                            <div class="input-icon right">
                                <i class="fa fa-calendar"></i>
                                <input type="text" name="Date" class="form-control datepicker" value="{{Input::has('Date')?Input::get('Date'):date('d-m-Y')}}" readonly="readonly" placeholder="">
                            </div>
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
            @else
                <b>From Date: {{Input::get('FromDate')}}</b> <br/>
                <b>To Date: {{Input::get('ToDate')}}</b>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed flip-content" style="font-size: 8.5pt;">
                    <thead class="flip-content">
                    <tr>
                        <th style="font-size: 8.5pt;">Sl.No.</th>
                        <th style="font-size: 8.5pt;">Equipment Name</th>
                        @foreach($dzongkhags as $dzongkhag)
                            <th style="font-size: 8.5pt;">{{$dzongkhag->NameEn}}</th>
                        @endforeach
                        <th style="font-size: 8.5pt;">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $count = 1; $grandTotal = 0; ?>
                    @foreach($dzongkhags as $dzongkhag)
                        <?php $total[$dzongkhag->Id] = 0; ?>
                    @endforeach
                    @forelse($equipmentList as $equipment)
                        <?php $rowTotal = 0; ?>
                        <tr>
                            <td  style="font-size: 8.5pt;">{{$count}}</td>
                            <td  style="font-size: 8.5pt;">{{$equipment->Name}}</td>
                            @foreach($dzongkhags as $dzongkhag)
                                <td  style="font-size: 8.5pt;" class="text-right">{{number_format($equipmentDetail[$equipment->Id][$dzongkhag->Id])}}</td>
                                <?php $rowTotal += (int)$equipmentDetail[$equipment->Id][$dzongkhag->Id]; ?>
                                <?php $total[$dzongkhag->Id] += (int)$equipmentDetail[$equipment->Id][$dzongkhag->Id]; ?>
                            @endforeach
                            <td  style="font-size: 8.5pt;" class="text-right">{{number_format($rowTotal)}}</td>
                        </tr>
                        <?php $count++ ?>
                    @empty
                        <tr><td  style="font-size: 8.5pt;" colspan="23" class="font-red text-center">No data to display</td></tr>
                    @endforelse
                    @if(count($equipmentList)>0)
                        <tr>
                            <td colspan="2" class="bold text-right">Total:</td>
                            @foreach($dzongkhags as $dzongkhag)
                                <td class="text-right">{{number_format($total[$dzongkhag->Id])}}</td>
                                <?php $grandTotal+=(int)$total[$dzongkhag->Id]; ?>
                            @endforeach
                            <td class="text-right">{{number_format($grandTotal)}}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
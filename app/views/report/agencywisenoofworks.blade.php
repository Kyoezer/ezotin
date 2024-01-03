@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>Agency with Highest and Lowest No. of works &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('etoolrpt.agencywisenoofworks',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('etoolrpt.agencywisenoofworks',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            @if(Input::get('export') != 'print')
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Order</label>
                            {{Form::select('Order',array('DESC'=>'Highest First', 'ASC'=>'Lowest First'),Input::has('Order')?Input::get('Order'):'ASC',array('class'=>'form-control'))}}
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
            @endif
                <table class="table table-bordered table-striped table-condensed flip-content" id="">
                    <thead class="flip-content">
                    <tr>
                        <th>Sl.No.</th>
                        <th>Agency</th>
                        <th>Number of Works</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($reportData as $data)
                        <tr>
                            <td>{{$start++}}</td>
                            <td>{{$data->Agency}}</td>
                            <td>{{$data->NoOfWorks}}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="font-red text-center">No data to display</td></tr>
                    @endforelse
                    </tbody>
                </table>
                <?php pagination($noOfPages,Input::all(),Input::get('page'),"etoolrpt.agencywisenoofworks"); ?>
        </div>
    </div>
@stop
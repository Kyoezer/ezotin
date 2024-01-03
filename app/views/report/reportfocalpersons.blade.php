@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>Ezotin Focal Persons &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('rpt.focalpersonsreport',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('rpt.focalpersonsreport',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            @if(Input::get('export') != 'print')
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Type</label><div class="clearfix"></div>
                            <div class="input-icon right">
                                {{Form::select('Type',array('All'=>'All',7=>'CiNet',8=>'Etool'),Input::get('Type'),array('class'=>'form-control input-sm'))}}
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
                    @endif
                </div>
            </div>
            {{Form::close()}}
            @else
                @if(Input::has('Type'))
                    <b>Type: {{(Input::get('Type') == 'All')?"All":(Input::get('Type')==7)?"CiNet":"Etool"}}</b>
                @endif
                    <br/><br/>
            @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-condensed flip-content" id="">
                        <thead class="flip-content">
                        <tr>
                            <th>Name</th>
                            <th>Phone No.</th>
                            <th>Email Address</th>
                            <th>Agency</th>
                            <th>Type</th>
                            <th>Status</th>                           
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($reportData as $data)
                            <tr>
                                <td>{{$data->FullName}}</td>
                                <td>{{$data->ContactNo}}</td>
                                <td>{{$data->Email}}</td>
                                <td>{{$data->Agency}}</td>
                                <td>@if($data->ReferenceNo == 7){{"CiNet"}}@endif @if($data->ReferenceNo==8){{"Etool"}}@endif</td>
                                <td>@if($data->Status == 1){{"Active"}}@endif @if($data->Status == 0){{"Inactive"}}@endif</td>
                            </tr>
                        @empty

                            <tr><td colspan="5" class="font-red text-center">No data to display</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

        </div>
    </div>
@stop
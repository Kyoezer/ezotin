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
                <i class="fa fa-cogs"></i>Certifiedbuilder's Key Personnel &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('cb.certifiedbuilderhumanresource',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('cb.certifiedbuilderhumanresource',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            @if(Input::get('export') != "print")
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="cdbno">CDB No.:</label>
                            <input class="form-control" id="cdbno" type="text" class="cdbno" name="CDBNo" value="{{Input::get('CDBNo')}}"/>
                        </div>
                    </div>
                    <!-- <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="ContractorName">Contractor:</label>
                            <input type="hidden" name="ContractorId" class="contractor-id"/>
                            <input type="text" id="ContractorName" class="contractorName form-control contractor-name"/>
                        </div>
                    </div> -->
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
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed flip-content" id="">
                    <thead class="flip-content">
                        <tr>
                            <th>Sl.No.</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>CID No.</th>
                            <th>Qualification</th>
                            <th>Trade</th>
                            <th>Nationality</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $count = 1; ?>
                    @forelse($reportData as $data)
                        <tr>
                            <td>{{$count}}</td>
                            <td>{{$data->Name}}</td>
                            <td>{{$data->Designation}}</td>
                            <td>{{$data->CIDNo}}</td>
                            <td>{{$data->Qualification}}</td>
                            <td>{{$data->Trade}}</td>
                            <td>{{$data->Country}}</td>
                        </tr>
                        <?php $count++ ?>
                    @empty
                        <tr><td colspan="7" class="font-red text-center">No data to display</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
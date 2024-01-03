@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
            </div>
        </div>
            <div class="portlet-body flip-scroll">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed flip-content" id="">
                    <thead class="flip-content">
                       <tr>
                           <th>CDB No</th>
                           <th>Partner Count in New DB</th>
                           <th>Partner Count in Old DB</th>
                           <th>Employee Count in Old DB</th>
                           <th>Employee Count in New DB</th>
                       </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData as $data)
                            <tr>
                                <td>
                                    {{$data->CDBNo}}
                                </td>
                                <td>{{$data->PartnerCountInNewDB}}</td>
                                <td>{{$data->PartnerCountInOldDB}}</td>
                                <td>{{$data->EmployeeCountInNewDB}}</td>
                                <td>{{$data->EmployeeCountInOldDB}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
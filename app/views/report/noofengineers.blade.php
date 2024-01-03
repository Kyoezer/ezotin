@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/cdb/morris/raphael-min.js')}}
    {{HTML::script('assets/cdb/morris/modernizr-2.5.3-respond-1.1.0.min.js')}}
    {{HTML::script('assets/cdb/morris/morris.min.js')}}
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('pagestyles')
    {{HTML::style('assets/cdb/morris/morris.css')}}
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); ?>
			<i class="fa fa-cogs"></i>No. of Engineers &nbsp;&nbsp;@if(!Input::has('export')) <?php $parameters['export'] = 'print'; ?><a href="{{route('contractorrpt.noofengineers',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        <div class="table-responsive">
            <div class="col-md-10">
                <table class="table table-bordered table-condensed flip-content" id="contractorsdzongkhag-table">
                    <thead class="flip-content">
                    <tr style="border-bottom: 1px solid #ddd;">
                        <th colspan="6" class="text-center">Civil Engineers</th>
                        <th colspan="6" class="text-center">Electrical Engineers</th>
                    </tr>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <th colspan="2"><center>Master</center></th>
                        <th colspan="2"><center>Degree</center></th>
                        <th colspan="2"><center>Diploma</center></th>
                        <th colspan="2"><center>Master</center></th>
                        <th colspan="2"><center>Degree</center></th>
                        <th colspan="2"><center>Diploma</center></th>
                    </tr>
                    <tr>
                        <th width="12.5%">Bhutanese</th>
                        <th width="12.5%">Non-Bhutanese</th>
                        <th width="12.5%">Bhutanese</th>
                        <th width="12.5%">Non-Bhutanese</th>
                        <th width="12.5%">Bhutanese</th>
                        <th width="12.5%">Non-Bhutanese</th>
                        <th width="12.5%">Bhutanese</th>
                        <th width="12.5%">Non-Bhutanese</th>
                        <th width="12.5%">Bhutanese</th>
                        <th width="12.5%">Non-Bhutanese</th>
                        <th width="12.5%">Bhutanese</th>
                        <th width="12.5%">Non-Bhutanese</th>
                    </tr>
                    </thead>
                    <tbody>


                        <tr>
                             <td>{{$civilMasterBhutanese}}</td>
                            <td>{{$civilMasterNonBhutanese}}</td>
                            <td>{{$civilDegreeBhutanese}}</td>
                            <td>{{$civilDegreeNonBhutanese}}</td>
                            <td>{{$civilDiplomaBhutanese}}</td>
                            <td>{{$civilDiplomaNonBhutanese}}</td>
                            <td>{{$electricalMasterBhutanese}}</td>
                            <td>{{$electricalMasterNonBhutanese}}</td>
                            <td>{{$electricalDegreeBhutanese}}</td>
                            <td>{{$electricalDegreeNonBhutanese}}</td>
                            <td>{{$electricalDiplomaBhutanese}}</td>
                            <td>{{$electricalDiplomaNonBhutanese}}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>Total: </strong>{{$civilMaster}}</td>
                            <td colspan="2"><strong>Total: </strong>{{$civilDegree}}</td>
                            <td colspan="2"><strong>Total: </strong>{{$civilDiploma}}</td>
                            <td colspan="2"><strong>Total: </strong>{{$electricalMaster}}</td>
                            <td colspan="2"><strong>Total: </strong>{{$electricalDegree}}</td>
                            <td colspan="2"><strong>Total: </strong>{{$electricalDiploma}}</td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <strong>Total Civil Engineers: </strong>
                                {{$civilMasterBhutanese+$civilMasterNonBhutanese+$civilDegreeBhutanese+$civilDegreeNonBhutanese+$civilDiplomaBhutanese+$civilDiplomaNonBhutanese}}
                            </td>
                            <td colspan="6">
                                <strong>Total Electrical Engineers: </strong>
                                {{$electricalMasterBhutanese+$electricalMasterNonBhutanese+$electricalDegreeBhutanese+$electricalDegreeNonBhutanese+$electricalDiplomaBhutanese+$electricalDiplomaNonBhutanese}}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="12">
                                <strong>Grand Total: </strong>
                                {{$electricalMasterBhutanese+$electricalMasterNonBhutanese+$civilMasterBhutanese+$civilMasterNonBhutanese+$civilDegreeBhutanese+$civilDegreeNonBhutanese+$civilDiplomaBhutanese+$civilDiplomaNonBhutanese+$electricalDegreeBhutanese+$electricalDegreeNonBhutanese+$electricalDiplomaBhutanese+$electricalDiplomaNonBhutanese}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div><div class="clearfix"></div>

        </div>
	</div>
</div>
@stop
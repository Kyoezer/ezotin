@extends('horizontalmenumaster')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/etool.js') }}
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Seek Clarification Details
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        <div class="portlet-body">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table class="table table-condensed table-bordered">        
                        <tbody>
                            <tr>
                                <td colspan="2" class="font-blue-madison bold warning">Enquiry Details
                                    <div class="pull-right"> 
                                        <a  href="{{URL::to('etl/seekclarification/'.$seekClaraificationDtls[0]->etlTenderId.'/'.$seekClaraificationDtls[0]->CDB_No.'/'.$contractorId)}}"  class="btn btn-primary btn-sm rounded-4">Go Back</a></div>
                                </td>  
                            </tr>
                            <tr>
                                <td class="col-lg-2"><strong>CDB No.</strong></td>
                                <td>{{$seekClaraificationDtls[0]->CDB_No}}</td>
                            </tr>
                            <tr>
                                <td><strong>Tender Id</strong></td>
                                <td>{{$seekClaraificationDtls[0]->Tender_Id}}</td>
                            </tr>
                            <tr>
                                <td><strong>Enquiry</strong></td>
                                <td>{{$seekClaraificationDtls[0]->Enquiry}}</td>
                            </tr>
                            <tr>
                                <td><strong>Enquiry Date</strong></td>
                                <td>{{$seekClaraificationDtls[0]->Created_On}}</td>
                            </tr>
                            <tr>
                                <td><strong>Response</strong></td>
                                <td>{{$seekClaraificationDtls[0]->Respond}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
	</div>
</div>
@stop
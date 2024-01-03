@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
			<i class="fa fa-cogs"></i>List of Applications with Pending Payment   &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('ezotinrpt.listofapplicantsdueforpayment',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('ezotinrpt.listofapplicantsdueforpayment',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body dont-flip">
        {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
        <div class="form-body">
            <div class="row">
                <div class="col-md-2">
                    <label class="control-label">As on Date</label>
                    <div class="input-icon right">
                        <i class="fa fa-calendar"></i>
                        <input type="text" name="Date" value="{{convertDateToClientFormat($date)}}" class="form-control datepicker" value="{{Input::get('Date')}}" readonly="readonly" placeholder="">
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
        <div class="table-responsive dont-flip">
            <table class="table table-bordered table-striped table-condensed" id="">
                <thead>
                <tr style="border-bottom: 1px solid #ddd;">
                    <th colspan="6">CONTRACTORS</th>
                </tr>
                    <tr>
                        <th>Sl #</th>
                     
                        <th>Name of Firm</th>
                        <th>CDBNo</th>
                        <th>ApplicationNo</th>
                        <th>Type</th>
                        <th>Mobile #</th>
                        <th>Amount</th>
          
                    </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
             
                    @foreach($contractorService as $contractorServ)
                        <tr>
                            <td>{{$count++}}</td>
                            <td>{{$contractorServ->NameOfFirm}}</td>
                            <td>{{$contractorServ->CDBNo}}</td>
                            <td>{{$contractorServ->ReferenceNo}} dt. {{convertDateToClientFormat($contractorServ->ApplicationDate)}}</td>
                            <td>{{$contractorServ->ServiceType}}</td>
                            <td>{{$contractorServ->MobileNo}}</td>
                            <td>{{$contractorServ->ApprovedAmount}}{{$contractorServ->TotalAmount}}</td>
              
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <div class="table-responsive dont-flip">
            <table class="table table-bordered table-striped table-condensed" id="">
                <thead>
                <tr style="border-bottom: 1px solid #ddd;">
                    <th colspan="6">CONSULTANTS</th>
                </tr>
                <tr>
                        <th>Sl #</th>
                     
                        <th>Name of Firm</th>
                        <th>CDBNo</th>
                        <th>ApplicationNo</th>
                        <th>Type</th>
                        <th>Mobile #</th>
<th>Amount</th>
          
                    </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
           
                @foreach($consultantService as $consultantServ)
                <tr>
                            <td>{{$count++}}</td>
                            <td>{{$consultantServ->NameOfFirm}}</td>
                            <td>{{$consultantServ->CDBNo}}</td>
                            <td>{{$consultantServ->ReferenceNo}} dt. {{convertDateToClientFormat($consultantServ->ApplicationDate)}}</td>
                            <td>{{$consultantServ->ServiceType}}</td>
                            <td>{{$consultantServ->MobileNo}}</td>
<td>{{$consultantServ->ApprovedAmount}}{{$consultantServ->TotalAmount}}</td>
              
                        </tr>
                @endforeach

                </tbody>
            </table>
        </div>


        <div class="table-responsive dont-flip">
            <table class="table table-bordered table-striped table-condensed" id="">
                <thead>
                <tr style="border-bottom: 1px solid #ddd;">
                    <th colspan="6">SPECIALIZED FIRM</th>
                </tr>
                <tr>
                        <th>Sl #</th>
                     
                        <th>Name of Firm</th>
                        <th>SPNo</th>
                        <th>ApplicationNo</th>
                        <th>Type</th>
                        <th>Mobile #</th>
<th>Amount</th>
          
                    </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
           
                @foreach($specializedfirmService as $specializedfirmServ)
                <tr>
                            <td>{{$count++}}</td>
                            <td>{{$specializedfirmServ->NameOfFirm}}</td>
                            <td>{{$specializedfirmServ->SPNo}}</td>
                            <td>{{$specializedfirmServ->ReferenceNo}} dt. {{convertDateToClientFormat($specializedfirmServ->ApplicationDate)}}</td>
                            <td>{{$specializedfirmServ->ServiceType}}</td>
                            <td>{{$specializedfirmServ->MobileNo}}</td>
<td>{{$specializedfirmServ->ApprovedAmount}}{{$specializedfirmServ->TotalAmount}}</td>
              
                        </tr>
                @endforeach

                </tbody>
            </table>
        </div>


        <div class="table-responsive dont-flip">
            <table class="table table-bordered table-striped table-condensed" id="">
                <thead>
                <tr style="border-bottom: 1px solid #ddd;">
                    <th colspan="6">SPECIALIZED TRADE</th>
                </tr>
                <tr>
                        <th>Sl #</th>
                     
                        <th>Name</th>
                        <th>SPNo</th>
                        <th>ApplicationNo</th>
                        <th>Type</th>
                        <th>Mobile #</th>
<th>Amount</th>
          
                    </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
           
                @foreach($specializedtradeService as $specializedtradeServ)
                <tr>
                            <td>{{$count++}}</td>
                            <td>{{$specializedtradeServ->Name}}</td>
                            <td>{{$specializedtradeServ->SPNo}}</td>
                            <td>{{$specializedtradeServ->ReferenceNo}} dt. {{convertDateToClientFormat($specializedtradeServ->ApplicationDate)}}</td>
                            <td>{{$specializedtradeServ->ServiceType}}</td>
                            <td>{{$specializedtradeServ->MobileNo}}</td>
<td>{{$specializedtradeServ->ApprovedAmount}}{{$specializedtradeServ->TotalAmount}}</td>
              
                        </tr>
                @endforeach

                </tbody>
            </table>
        </div>





        <div class="table-responsive dont-flip">
            <table class="table table-bordered table-striped table-condensed" id="">
                <thead>
                <tr style="border-bottom: 1px solid #ddd;">
                    <th colspan="6">ARCHITECTS</th>
                </tr>
                <tr>
                        <th>Sl #</th>
                     
                        <th>Name </th>
                        <th>ARNo</th>
                        <th>ApplicationNo</th>
                        <th>Type</th>
                        <th>Mobile #</th>
<th>Amount</th>
          
                    </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
           
                @foreach($architectService as $architectServ)
                <tr>
                            <td>{{$count++}}</td>
                            <td>{{$architectServ->Name}}</td>
                            <td>{{$architectServ->ARNo}}</td>
                            <td>{{$architectServ->ReferenceNo}} dt. {{convertDateToClientFormat($architectServ->ApplicationDate)}}</td>
                            <td>{{$architectServ->ServiceType}}</td>
                            <td>{{$architectServ->MobileNo}}</td>
<td>{{$architectServ->Amount}}{{$architectServ->TotalAmount}}</td>
              
                        </tr>
                @endforeach

                </tbody>
            </table>
        </div>

        <div class="table-responsive dont-flip">
            <table class="table table-bordered table-striped table-condensed" id="">
                <thead>
                <tr style="border-bottom: 1px solid #ddd;">
                    <th colspan="6">ENGINEERS</th>
                </tr>
                <tr>
                        <th>Sl #</th>
                     
                        <th>Name </th>
                        <th>CDBNo</th>
                        <th>ApplicationNo</th>
                        <th>Type</th>
                        <th>Mobile #</th>
<th>Amount</th>
          
                    </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
           
                @foreach($engineerService as $engineerServ)
                <tr>
                            <td>{{$count++}}</td>
                            <td>{{$engineerServ->Name}}</td>
                            <td>{{$engineerServ->CDBNo}}</td>
                            <td>{{$engineerServ->ReferenceNo}} dt. {{convertDateToClientFormat($engineerServ->ApplicationDate)}}</td>
                            <td>{{$engineerServ->ServiceType}}</td>
                            <td>{{$engineerServ->MobileNo}}</td>
<td>{{$engineerServ->Amount}}{{$engineerServ->TotalAmount}}</td>
              
                        </tr>
                @endforeach

                </tbody>
            </table>
        </div>


        <div class="table-responsive dont-flip">
            <table class="table table-bordered table-striped table-condensed" id="">
                <thead>
                <tr style="border-bottom: 1px solid #ddd;">
                    <th colspan="6">SURVEYORS</th>
                </tr>
                <tr>
                        <th>Sl #</th>
                     
                        <th>Name </th>
                        <th>ARNo</th>
                        <th>ApplicationNo</th>
                        <th>Type</th>
                        <th>Mobile #</th>
<th>Amount</th>
          
                    </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
           
                @foreach($surveyService as $surveyServ)
                <tr>
                            <td>{{$count++}}</td>
                            <td>{{$surveyServ->Name}}</td>
                            <td>{{$surveyServ->ARNo}}</td>
                            <td>{{$surveyServ->ReferenceNo}} dt. {{convertDateToClientFormat($surveyServ->ApplicationDate)}}</td>
                            <td>{{$surveyServ->ServiceType}}</td>
                            <td>{{$surveyServ->MobileNo}}</td>
<td>{{$surveyServ->Amount}}{{$surveyServ->TotalAmount}}</td>
              
                        </tr>
                @endforeach

                </tbody>
            </table>
        </div>

	<div class="table-responsive dont-flip">
            <table class="table table-bordered table-striped table-condensed" id="">
                <thead>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <th colspan="6">CERTIFIED BUILDER</th>
                    </tr>
                    <tr>
                        <th>Sl #</th>

                        <th>Name of Firm</th>
                        <th>CDBNo</th>
                        <th>ApplicationNo</th>
                        <th>Type</th>
                        <th>Mobile #</th>
                        <th>Amount</th>

                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; ?>

                    @foreach($certifiedbuilderService as $certifiedbuilderServ)
                    <tr>
                        <td>{{$count++}}</td>
                        <td>{{$certifiedbuilderServ->NameOfFirm}}</td>
                        <td>{{$certifiedbuilderServ->CDBNo}}</td>
                        <td>{{$certifiedbuilderServ->ReferenceNo}} dt. {{convertDateToClientFormat($certifiedbuilderServ->ApplicationDate)}}</td>
                        <td>{{$certifiedbuilderServ->ServiceType}}</td>
                        <td>{{$certifiedbuilderServ->MobileNo}}</td>
                        <td>{{$certifiedbuilderServ->ApprovedAmount}}{{$certifiedbuilderServ->TotalAmount}}</td>

                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>


	</div>
</div>
@stop
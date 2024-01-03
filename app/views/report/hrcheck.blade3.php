<html>
<head><title>Ezotin - Human Resource Details</title></head>
<body>
<center><u><b>CAUTION</b></u></center>
<center><b>An engineer is allowed to execute only two work at a time for that particular firm.</b></center>
<br/>
<center><b>For any other Human Resource they are allowed to involve only in a single project of work</b></center>
<center><h2>Details of CID No: {{Input::get('CIDNo')}}</h2></center>

@if(!empty($responseArray))
@if((bool)$responseArray['name'])
    <h4 style="text-align:center"><strong>From DCRC database</strong></h4>
    <div style="text-align:center">
    Sex: <span style="color:blue">{{$responseArray['gender']}}</span>&nbsp;|&nbsp;Name:  <span style="color:blue">{{$responseArray['name']}}</span>&nbsp;|&nbsp;Dzongkhag:  <span style="color:blue">{{$responseArray['dzongkhag']}}</span>&nbsp;|&nbsp;
    Gewog: <span style="color:blue">{{$responseArray['gewog']}}</span>&nbsp;|&nbsp;Village: <span style="color:blue">{{$responseArray['village']}}</span>&nbsp;|&nbsp;DOB: <span style="color:blue">{{$responseArray['dob']}}</span>
    <div>
    <hr>

@endif
        
@endif

@if($hrIsEngineer)
    @if(count($hrWorks) > 0)
        <center>The Individual holding CID/Permit no. {{Input::get('CIDNo')}} @if(isset($individualName) && !empty($individualName)){{"($individualName)"}}@endif is engaged in following project(s):</center>
        @forelse($hrWorks as $value)
            <center><strong>Work:</strong> {{$value->WorkId}}  &nbsp;&nbsp;&nbsp;&nbsp;<strong>CDB No.:</strong> {{$value->CDBNo}}  &nbsp;&nbsp;&nbsp;&nbsp;<strong>Procuring Agency:</strong> {{$value->ProcuringAgency}}</center>
            <br/>
        @endforeach
    @endif
    @if(count($hrWorks) == 0)
        <center><b>Engineer is not engaged in any work or project</b></center>
    @endif
@else
    @if(count($hrWorks) > 0)
        <center>The Individual holding CID/Permit no. {{Input::get('CIDNo')}} @if(isset($individualName) && !empty($individualName)){{"($individualName)"}}@endif is engaged in following project(s):</center>
        @forelse($hrWorks as $value)
            <center><strong>Work:</strong> {{$value->WorkId}}  &nbsp;&nbsp;&nbsp;&nbsp;<strong>CDB No.:</strong> {{$value->CDBNo}} &nbsp;&nbsp;&nbsp;&nbsp; <strong>Procuring Agency:</strong> {{$value->ProcuringAgency}}</center>
            <br/>
        @endforeach
    @endif
    @if(count($hrWorks) == 0)
        <center><b>Human Resource is not engaged in any work or project</b></center>
    @endif
@endif
@if(count($checkPartnerDeregistered)>0)
    @foreach($checkPartnerDeregistered as $deregistered)
        <center>Owner/Partner of CDB No.: Deregistered Firm {{$deregistered->CDBNo}} ({{$deregistered->NameOfFirm}})</center>
        <br/>
    @endforeach
@endif
@if(count($hrDetails) > 0)
    @foreach($hrDetails as $hrDetail)
        <center>@if($hrDetail->IsPartnerOrOwner == 1){{"Owner/Partner of CDB No.:"}}@else{{"Employee of CDB No:"}}@endif  {{$hrDetail->CDBNo}} ({{$hrDetail->NameOfFirm}})</center>
        <br/>
    @endforeach
@else
    <center>Employee of CDB No.: NIL</center>
@endif
            <br/>
{{--Move below--}}
@if($hrIsGovtEmp)
    <center><h3><b>Is a Govt/Corporate Employee of {{$workingAgency}}</b></h3></center>
@else
    @if($hrIsEngineer)
        @if((bool)$govtOrCorporateOrg)
            <center>Is a Govt/Corporate Engineer of {{$govtOrCorporateOrg}} @if($workingAgency!='null'){{$workingAgency}}@endif</center>
        @endif
    @else
        <center>Is not a Govt/Corporate Employee</center>
    @endif
@endif
{{--End--}}

<br/>

<hr/>
<center>With regard to Corporate Employee please verify with the conceerned agencies. There may be certain inconsistency.
</center>
<hr/>
<center><h5 style="font-weight:bold">Attachment</h5></center>
<center>
@forelse($hrAttachment as $item)
<i class="fa fa-check"></i><a href="{{$item->DocumentPath}}" target="_blank">{{$item->DocumentName}}</a>,
@endforeach
</center>
<br>
<center><b>Print this page as an evidence to prove that particular HR is engaged or not in a work or
        project</b></center>
<center>Printed on: {{date('d m Y')}} By: {{(isset(Auth::user()->Id))?Auth::user()->FullName:'Applicant'}}</center>
@if(Request::segment(1) == 'etoolrpt')
    <?php $url = "etl/reports"; ?>
@endif
@if(Request::segment(1) == 'cinet')
    <?php $url = "cinet/reports"; ?>
@endif
@if(!Input::has('from'))

    @if(!Input::has('print'))
        <br>
        {{--<center><a href="#" onclick="window.history.back();" style="color: blue;">Click here to return to previous page</a></center><br/>--}}
        <center><input type="button" onclick="window.print();" value="Print"/> </center>
    @endif
@else
    <center><a href="{{URL::to((isset(Auth::user()->Id))?'etoolrpt/hrcheck':'hrcheck')}}?CIDNo={{Input::get('CIDNo')}}&print=true<?php if(!isset(Auth::user()->Id)): ?>{{"&from=true"}}<?php endif; ?>" target="_blank" class="btn blue">Print</a>&nbsp;<button type="button" class="btn green" data-dismiss="modal">OK</button></center><br/>
@endif
@if(Input::get('print') == true)
    <script>window.print();</script>
@endif
</body>
</html>
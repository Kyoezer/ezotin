<html>
<head><title>Ezotin - Equipment Details</title></head>
<body>
    <center><u><b>CAUTION</b></u></center>
    <center><b>An equipment is allowed to execute only one work at a time for that particular firm.</b></center>
    <center><h2>Details of Registration No: {{Input::get('RegistrationNo')?Input::get('RegistrationNo'):'----'}}</h2></center>
    @if(!empty($responseArray))

    @if(count($responseArray)>0)
        <center>
            <h4><strong>From RSTA database</strong></h4>
        @foreach($responseArray as $index=>$array)
            Owner: <span style="color:#0b3e7d;">{{$array['Owner']}}</span>&nbsp; | &nbsp;Owner CID: <span style="color:#0b3e7d;">{{$array['CIDNo']}}</span>&nbsp; | &nbsp;Vehicle Type: <span style="color:#0b3e7d;">{{$array['VehicleType']}}</span>
            | &nbsp;Vehicle Model: <span style="color:#0b3e7d;">{{$array['VehicleModel']}}</span>| &nbsp;Chassis Number:<span style="color:#0b3e7d;">{{$array['chassisNumber']}}</span>| &nbsp;Engine Number: <span style="color:#0b3e7d;">{{$array['engineNumber']}}</span>| &nbsp;Expiry Date:<span style="color:#0b3e7d;"> {{$array['ExpiryDate']}}  </span>
            <br>
        @endforeach
        <br/>
        <strong>In case of RSTA registered equipment kindly verify with RSTA if there are any mismatch in ownership as the RSTA data is currently under migration and stabilization.</strong>
        </center>
            <hr>
    @else
        @if((bool)$vehicleTypeFromDB)
            <center>
                <b>Vehicle Type: {{$vehicleTypeFromDB}}</b>
            </center>
        @endif
    @endif
    @forelse($equipmentOwner as $owner)
        <center><b>Equipment is owned by {{$owner->NameOfFirm." (CDB No. ".$owner->CDBNo.")"}}</b></center>
    @empty
    @endforelse
    <br>
    @if(count($equipmentDetails)>0)
        <center><b>Equipment is engaged in {{count($equipmentDetails)}} Work(s)</b></center>
    @endif
    @forelse($equipmentDetails as $value)
        <center><b>Work: </b>{{$value->WorkId}} &nbsp;&nbsp;&nbsp;&nbsp;   <b>ProcuringAgency: </b>{{$value->ProcuringAgency}}</center>
        <br/>
    @empty
        <center>This equipment is not engaged in any work at this time.</center>
    @endforelse
    <br/>
    <hr/>
    @else
        <p><center><strong>No details about this equipment</strong></center></p>
    @endif
    <center><b>Print this page as an evidence to prove that particular equipment is engaged or not in a work or
            project</b></center>
    <center>Printed on: {{date('d m Y')}} By: {{(isset(Auth::user()->Id))?Auth::user()->FullName:'Applicant'}}</center>
    @if(Request::segment(1) == 'etoolrpt')
        <?php $url = "etl/reports"; ?>
    @endif
    @if(Request::segment(1) == 'cinet')
        <?php $url = "cinet/reports"; ?>
    @endif
    @if(!Input::has('from'))
        {{--<center><a href="#" onclick="window.history.back();" style="color: blue;">Click here to return to previous page</a></center><br/>--}}
        @if(!Input::has('print'))
            <br>
            <center><input type="button" onclick="window.print();" value="Print"/> </center>
        @endif
    @else
        <center><a href="{{URL::to((isset(Auth::user()->Id))?'etoolrpt/equipmentcheck':'equipmentcheck')}}?RegistrationNo={{Input::get('RegistrationNo')}}&print=true<?php if(!isset(Auth::user()->Id)): ?>{{"&from=true"}}<?php endif; ?>" target="_blank" class="btn blue">Print</a>&nbsp;<button type="button" class="btn green" data-dismiss="modal">OK</button></center><br/>
    @endif
    @if(Input::get('print') == true)
        <script>window.print();</script>
    @endif
</body>
</html>
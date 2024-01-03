<html>
<head><title>Ezotin - Human Resource Details</title></head>
<body>
<center><u><b>CAUTION</b></u></center>
<center><b>An engineer is allowed to execute only two work at a time for that particular firm.</b></center>
<center><b>For any other Human Resource they are allowed to involve only in a single project of work</b></center>
<center><h2>Details of CID No No: {{Input::get('CIDNo')}}</h2></center>
@if(count($otherFirms) > 0)
    @foreach($otherFirms as $otherFirm)
    <center><b>Employee of CDB No:  </b>{{$otherFirm->CDBNo}}</center>
    <br/>
    @endforeach
@endif

<br/>
<hr/>
<center><b>Print this page as an evidence to prove that particular HR is employee of other firm or not</b></center>
<center>Printed on: {{date('d m Y')}} By: {{Auth::user()->FullName}}</center>
@if(Request::segment(1) == 'etoolrpt')
    <?php $url = "etl/reports"; ?>
@endif
@if(Request::segment(1) == 'cinet')
    <?php $url = "cinet/reports"; ?>
@endif
@if(!Input::has('from'))
    <center><a href="{{URL::to($url)}}" style="color: blue;">Click here to return to previous page</a></center><br/>
    @if(!Input::has('print'))
        <center><input type="button" onclick="window.print();" value="Print"/> </center>
    @endif
@else
    <center><a href="{{URL::to('etoolrpt/hrotherfirms')}}?CIDNo={{Input::get('CIDNo')}}&etlTenderBidderContractorId={{Input::get('etlTenderBidderContractorId')}}&print=true" target="_blank" class="btn blue">Print</a>&nbsp;<button type="button" class="btn green" data-dismiss="modal">OK</button></center><br/>
@endif
@if(Input::get('print') == true)
    <script>window.print();</script>
@endif
</body>
</html>
<html>
<head><title>Ezotin - Human Resource Details</title></head>
<body>
<center><u><b>CAUTION</b></u></center>
<center><h3>The contractor ({{$contractorDetails[0]->NameOfFirm." (CDB No. ".$contractorDetails[0]->CDBNo.")"}}) is {{$contractorDetails[0]->Status}}</h3></center>
<br/>
    <center><b>Print this page as an evidence to prove that above contractor is {{$contractorDetails[0]->Status}}</b></center>
    <center>Printed on: {{date('d m Y')}} By: {{Auth::user()->FullName}}</center>

    @if(!Input::has('from'))
        @if(!Input::has('print'))
            <center><input type="button" onclick="window.print();" value="Print"/> </center>
        @endif
    @else
        <center><a href="{{URL::to('etl/blacklistedcontractor')}}?cdbNo={{$contractorDetails[0]->CDBNo}}&print=true" target="_blank" class="btn blue">Print</a></center><br/>
    @endif
    @if(Input::get('print') == true)
        <script>window.print();</script>
    @endif
</body>
</html>
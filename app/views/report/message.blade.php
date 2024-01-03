<html>
<head><title>Ezotin - Human Resource Details</title></head>
<body>
<center><h4 class="bold">{{$message}}</h4></center>
@if(!Input::has('print'))
    <br/><br/>
    <center><a href="{{Request::url()}}?cdbNo={{$cdbNo}}&print=true" target="_blank" class="btn blue">Print</a></center><br/>
@else
    <center>Printed on: {{date('d m Y')}} By: {{Auth::user()->FullName}}</center>
@endif
@if(Input::get('print') == true)
    <script>window.print();</script>
@endif
</body>
</html>
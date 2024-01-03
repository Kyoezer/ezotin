@extends('emailmaster')
@section('emailcontent')
    <p><strong>{{$applicantName}} (CDB No.: {{$cdbNo}})</strong></p>
    <p>{{$mailMessage}}</p>
@stop
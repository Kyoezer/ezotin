@extends('master')

@section('content')

    <h1>Helllo</h1>
    <iframe src="{{ asset($downloadDetail->PDF) }}" width="100%" ></iframe>
    
@stop
@extends('websitemaster')
@section('main-content')

<div class="container">
    <h1>Thank you! Your file is successfully uploaded</h1>
    @if (Session::get('successful'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>{{ Session::get('successful') }}</strong>
        </div>
    @endif
</div>
@stop
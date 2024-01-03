@extends('master')
@section('content')
<div class="row">
        <div class="col-md-12"> 
	@if (Session::get('approved'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{ Session::get('approved') }}</strong>
                        </div>
                    @endif
            <div class="portlet light bordered">
            {{ Form::open(['url' => 'web/journalapplicationno','method'=>'post']) }}
                    <div class="form-group">
                        <label class="required"><strong>Application Number</strong> </label>
                        <input type="text" name="Application_No" value="{{Input::get('Application_No')}}" class="form-control" placeholder="Please Provide the Journal Application Number">
                    </div>
                    
                        <button class="btn btn-primary" type="submit">View Details</button>
                    {{ Form::close() }}
            </div>
        </div> 
    </div> 
@stop
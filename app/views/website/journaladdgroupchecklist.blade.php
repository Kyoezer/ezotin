@extends('master')

@section('content')
    <div class="row">
        <div class="col-md-12"> 
            <div class="portlet light bordered">
                <div class="portlet-body form">
                        <h4 class="head"><strong>Add Group Checklist</strong></h4><br>
                    {{ Form::open(['url' => 'web/journaladdgroupchecklistsave']) }}

                    <div class="form-group">
                        <label class="required">Title </label>
                        <input type="text" name="Title" class="form-control" placeholder="Add your title here..">
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                    
                    {{ Form::close() }}

                </div>       
            </div>
        </div> 
    </div>    

@stop
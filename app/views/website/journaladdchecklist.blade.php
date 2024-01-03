@extends('master')

@section('content')
    <div class="row">
        <div class="col-md-12"> 
            <div class="portlet light bordered">
                <div class="portlet-body form">
                        <h4 class="head"><strong>Add Checklist</strong></h4><br>
                    {{ Form::open(['url' => 'web/journaladdchecklistsave']) }}

                    <div class="form-group">
                        <label class="required">Title </label>
                        <input type="text" name="Name" class="form-control" placeholder="Add your title here..">
                    </div>
                    <div class="form-group">
                        <label class="required">Option Type </label>
                        <select name="" id="" class="form-control">
                            <option value="">Select</option>
                            <option value="textarea">Textarea</option>
                            <option value="Option">Option</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                    
                    {{ Form::close() }}

                </div>       
            </div>
        </div> 
    </div>    

@stop
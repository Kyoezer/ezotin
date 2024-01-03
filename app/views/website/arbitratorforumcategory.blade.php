@extends('arbitratorforummaster')
@section('main-content')
<h4 class="text-primary"><strong>Add a Category</strong></h4>
{{Form::open(array('url'=>URL::to('web/savearbforumcategory'),'role'=>'form'))}}
<div class="form-body">
    <div class="form-group">
        <label for="Name" class="control-label col-md-2 text-right">Category</label>
        <div class="col-md-4">
            <input type="text" class="form-control input-sm required" name="CategoryName" id="Name"/>
        </div>
    </div>
    <div class="clearfix"></div>
    <br>
    <div class="form-group">
        <label for="Description" class="control-label col-md-2 text-right">Description</label>
        <div class="col-md-7">
            <textarea name="CategoryDescription" id="Description" rows="4" class="form-control required"></textarea>
        </div>
    </div>
    <div class="clearfix"></div>
    <br>
    <div class="col-md-3 col-md-offset-2">
        <div class="form-buttons">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="reset" class="btn btn-danger">Cancel</button>
        </div>
    </div>
{{Form::close()}}
</div>
@stop
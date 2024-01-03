@extends('arbitratorforummaster')
@section('main-content')
<h4 class="text-primary"><strong>Add a Topic</strong></h4>
{{Form::open(array('url'=>URL::to('web/savearbforumtopic'),'role'=>'form'))}}
<div class="form-body">
    <div class="form-group">
        <label for="CategoryId" class="control-label col-md-2 text-right">Category</label>
        <div class="col-md-4">
            <select name="CategoryId" id="CategoryId" class="form-control input-sm required">
                <option value="">PICK ONE</option>
                @foreach($categories as $category)
                    <option value="{{$category->Id}}">{{$category->CategoryName}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="clearfix"></div>
    <br>
    <div class="form-group">
        <label for="Subject" class="control-label col-md-2 text-right">Topic</label>
        <div class="col-md-4">
            <input type="text" class="form-control input-sm required" name="Subject" id="Subject"/>
        </div>
    </div>
    <div class="clearfix"></div>
    <br>
    <div class="form-group">
        <label for="PostContent" class="control-label col-md-2 text-right">Message</label>
        <div class="col-md-7">
            <textarea class="form-control input-sm required" rows="5" name="PostContent" id="PostContent"></textarea>
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
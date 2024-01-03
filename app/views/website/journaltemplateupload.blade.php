@extends('master')

@section('content')
@if (Session::get('successfully'))
<div class="alert alert-success alert-block text-center">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>{{ Session::get('successfully') }}</strong>
</div>
@endif
<div class="row">
    <div class="col-md-10" id="author">
        <div class="col-md-offset-2 col-md-8">
            <h3 class="text"><b> Upload Journal Template.</b></h3>
            <hr>
            {{ Form::open(['url' => 'web/journaltemplateupload/', 'files'=>true]) }}
            <label><b>Article Template Upload</b></label><br><br>
            <div class="col-sm-6">
                <div class="form-group">
                    <input type="file" class="form-control-file" name="articletemplate">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                <button type="submit" class="btn btn-primary" id="btn1">Upload</button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
        <div class="col-md-offset-2 col-md-8">
            <hr>
            {{ Form::open(['url' => 'web/journalReviewerChecklistUpload/', 'files'=>true]) }}
            <label><b>Reviewer Checklist Upload</b></label><br><br>
            <div class="col-sm-6">
                <div class="form-group">
                <input class="form-control-file" type="file" name="reviewerchecklist"> <br>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                <button type="submit" class="btn btn-primary" id="btn1">Upload</button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<style>
    input {
        background: #F3C911;
        padding: 10px;
        /* margin-top: 5px; */
        font-family: Arial;
        margin-left: 150px;
    }

    #author {
        border: 1px solid lightgray;
        border-radius: 5mm;
        box-shadow: 0px 0px 5px 0px #00000024;
        margin-left: 2cm;
        padding-bottom: 1cm;
        text-align: center;
    }
</style>
@stop
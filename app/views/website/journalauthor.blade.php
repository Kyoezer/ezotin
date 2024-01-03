@extends('websitemaster')
@section('main-content')
    @if (Session::get('message'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>{{ Session::get('message') }}</strong>
        </div>
    @endif
    
    <div class="container-fluid" id="main">
        <div class="row">
            <div class="col-md-6" id="author">
                <div>
                <h3><b>Welcome Author</b></h3>
                <h5>Please upload your document below.</h5>
                </div>
            {{-- {{ Form::open(array('url' => 'web/journalauthor' , 'files'=> true)) }} --}}
            <form Method="post" action="uploadfile" enctype="multipart/form-data" file="true"> 
                <div class="form-group">
                    <label class="required">Upload File</label>
                    <input type="file" name="file"> <br>
                    {{-- @if ($errors->has('file')) <p class="error">{{$errors->first('file')}}</p>@endif --}}
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            {{ Form::close() }}
            </div>
        </div>
    </div>
    <style>
        .required:after {
          content:" *";
          color: red;
        }
        #author {
            border: 1px solid lightgray;
            border-radius: 5mm;
            box-shadow: 0px 0px 5px 0px #000;
            margin-left: 7cm;
        }
        #main {
            margin-bottom: 5%;
        }
      </style>
@stop

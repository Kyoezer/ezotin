@extends('master')

@section('content')
<div class="row">
        <div class="col-md-12"> 
                        <h4 class="head"><strong>Edit Checklist</strong></h4><br>
                        
                        <!-- Striped table -->
                        {{ Form::open(['url' => 'web/uploadeditchecklist']) }}

                        <div class="col-lg-8">
                            <label for="">Name</label>
                            <textarea class="form-control" name="title">{{$checkList[0]->Name}}</textarea>
                            <input type="hidden" name="id" value="<?=$checkList[0]->Id?>">
                        </div>
                        <div class="col-lg-12 margin-top-20">
                            <button class="btn btn-primary">Save</button>
                        </div>
                        {{ Form::close() }}
                   
        </div> 
    </div>    
     

@stop
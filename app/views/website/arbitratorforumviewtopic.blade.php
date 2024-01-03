@extends('arbitratorforummaster')
@section('main-content')
<h4 class="text-primary"><strong>Posts in "{{$topicName}}" Topic</strong></h4>
<div class="table-responsive">
    <table class="table table-bordered table-condensed table-striped dont-flip">
        <thead style="background: #09963E;color:#fff;">
            <tr>
                <th colspan="2" class="text-center">{{$topicName}}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
                <tr>
                    <td style="width:24%;">{{$post->User}}<br>{{date_format(date_create($post->PostDate),'d-M-Y')}}<br/>{{date_format(date_create($post->PostDate),'G:i')}}</td>
                    <td>{{$post->Content}}</td>
                </tr>
            @empty
                <tr>
                    <td class="text-center font-red">No posts in this Topic!</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(count($posts)>0)
{{Form::open(array('url'=>URL::to('web/savearbforumpost'),'role'=>'form'))}}
    {{Form::hidden('TopicId',$topicId)}}
    <div class="form-body">
    <div class="form-group">
        <label for="Description" class="control-label col-md-2">Reply</label><div class="clearfix"></div>
        <div class="col-md-7">
            <textarea name="Content" id="Description" rows="4" class="form-control required"></textarea>
        </div>
    </div>
    <div class="clearfix"></div>
    <br>
    <div class="col-md-3 col-md-offset-2">
        <div class="form-buttons">
            <button type="submit" class="btn btn-primary">Submit Reply</button>
            <button type="reset" class="btn btn-danger">Cancel</button>
        </div>
    </div>
    </div>
{{Form::close()}}
@endif
@stop
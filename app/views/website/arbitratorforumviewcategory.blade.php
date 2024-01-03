@extends('arbitratorforummaster')
@section('main-content')
<h4 class="text-primary"><strong>Topics in "{{$categoryName}}" Category</strong></h4>
<div class="table-responsive">
    <table class="table table-bordered table-condensed table-striped">
        <thead style="background: #09963E;">
            <tr>
                <th>Topic</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topics as $topic)
                <tr>
                    <td><strong><a href="{{URL::to("web/arbforumtopicview/$topic->Id")}}">{{$topic->Subject}}</a></strong></td>
                    <td>{{convertDateToClientFormat($topic->TopicDate)}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center font-red">No topics in this Category!</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@stop
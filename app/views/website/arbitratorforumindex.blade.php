@extends('arbitratorforummaster')
@section('main-content')
<h4 class="text-primary"><strong>Closed Forum for arbitrators</strong></h4>
<div class="table-responsive">
    <table class="table table-bordered table-condensed table-striped">
        <thead style="background: #09963E; color:#fff;">
            <tr>
                <th>Category</th>
                <th>Last Topic</th>
            </tr>
        </thead>
        <tbody>
        <?php $sl = 1; ?>
            @foreach($categories as $category)
                <tr>
                    <td><strong style="text-decoration:underline; font-size: 12pt; color: #000;"><a href="{{URL::to("web/arbforumcategoryview/$category->Id")}}">{{$category->CategoryName}}</a></strong> ({{$topicCount[$category->Id]}}) <br>{{$category->CategoryDescription}}</td>
                    <td>
                        @if(count($lastTopic[$category->Id])>0)
                            <a href="{{URL::to("web/arbforumtopicview/".$lastTopic[$category->Id][0]->Id)}}">{{$lastTopic[$category->Id][0]->Subject}}</a>
                        @else
                            No topics!
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop
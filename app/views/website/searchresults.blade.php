@extends('websitemaster')
@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <?php $count = 1; ?>
            <h3 class="bold">SEARCH RESULTS:</h3>
            @forelse($results as $result)
                <div class="well" style="padding:8px 19px; margin-bottom:4px;">
                <span class="h4">{{$count++}}. {{$result->Name}}</span><br>
                <p>
                    <strong>{{$result->Detail1Name}}: </strong>{{HTML::decode($result->Detail1)}}
                    @if((bool)$result->Detail2Name)
                        <br>
                        <strong>{{$result->Detail2Name}}: </strong>{{HTML::decode($result->Detail2)}}
                    @endif
                    @if((bool)$result->Link1)
                        <br><br>
                        <a href="{{URL::to($result->Link1)}}"><strong>Click on this link for more</strong></a>
                    @endif
                </p>
                </div>
                <br>
            @empty
                <p>
                    <h4>No results!</h4>
                </p>
            @endforelse
        </div>

@stop
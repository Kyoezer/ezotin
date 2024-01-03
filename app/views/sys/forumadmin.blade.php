@extends('master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-green-seagreen">
                        <i class="fa fa-cogs"></i>
                        <span class="caption-subject">Forum Manager</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="note note-info">Here you can manage the Arbitration Forum. However, to add Categories and Topics, the admin for the Forum has to login to the forum.</div>
                        <ol>
                            <li><a href="{{URL::to("sys/arbitrationforumadmin")}}">Create/Change Arbitration Forum Admin</a></li>
                            <li><a href="{{URL::to("sys/userforarbitrationforum")}}">Manage Forum Users</a></li>
                            <li><a href="{{URL::to("sys/arbitrationforumcategories")}}">Manage Categories</a></li>
                            <li><a href="{{URL::to("sys/arbitrationforumtopics")}}">Manage Topics</a></li>
                            <li><a href="{{URL::to("sys/arbitrationforumposts")}}">Manage Posts</a></li>

                        </ol>
                </div>
            </div>
        </div>
    </div>
@stop
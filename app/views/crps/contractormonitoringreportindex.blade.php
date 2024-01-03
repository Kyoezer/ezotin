@extends('master')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/sys.js') }}
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-green-seagreen">
                        <i class="fa fa-cogs"></i>
                        <span class="caption-subject">Monitoring Report</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    @if(!Input::has('type'))
                        <ol>
                            <li><a href="{{URL::to('contractor/monitoringreportoffice')}}">Office Establistment</a></li>
                            <li><a href="{{URL::to('contractor/monitoringreportsites')}}">Sites</a></li>
                        </ol>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
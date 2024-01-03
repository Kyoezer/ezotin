@extends('master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-gift"></i>
                        <span class="caption-subject font-green-seagreen">Add New Videos</span><br>
                        <span class="caption-helper font-red-thunderbird">Please use a <b>video converter application</b> like Freemake Video converter to convert your video to HTML5 format and upload three videos (in mp4, webm and ogv format) in order to ensure compatibility in all browsers. <br><a href="http://www.freemake.com/free_video_converter" target="_blank">Link to Video Converter</a></span>
                    </div>
                </div>
                <div class="portlet-body form">
                    {{ Form::open(array('url'=>'web/savevideo', 'method'=>'POST','class'=>'form-horizontal','files'=>true)) }}
                    @foreach($video as $data)
                        {{Form::hidden('Id',$data->Id)}}
                        @if((bool)$data->Id)
                            <video id=0 controls width=260 height=200 >
                                <source src="{{asset(asset($data->OggVideoPath))}}" type='video/ogg; codecs="theora, vorbis"'/>
                                <source src="{{asset(asset($data->WebmVideoPath))}}" type='video/webm' >
                                <source src="{{asset(asset($data->Mp4VideoPath))}}" type='video/mp4'>
                                <p>Video is not visible, most likely your browser does not support HTML5 video</p>
                            </video>
                        @endif
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-md-3">Ogg Video </label>
                                <div class="col-md-9">
                                    {{ Form::file('OggVideo') }}

                                </div>
                            </div>
                        </div>
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-md-3">Webm Video </label>
                                <div class="col-md-9">
                                    {{ Form::file('WebmVideo') }}

                                </div>
                            </div>
                        </div>
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-md-3">Mp4 Video </label>
                                <div class="col-md-9">
                                    {{ Form::file('Mp4Video') }}

                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="btn-set">
                                <button type="submit" class="btn green">Save</button>
                                <a href="{{URL::to('web/newvideo')}}" class="btn red">Cancel</a>
                            </div>
                        </div>
                    @endforeach
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
@stop
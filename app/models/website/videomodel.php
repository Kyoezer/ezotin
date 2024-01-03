<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 7/20/2016
 * Time: 10:53 AM
 */
class videomodel extends BaseModel
{
    protected $table = 'webvideo';
    protected $fillable = array('Id','DisplayStatus','OggVideoPath','WebmVideoPath','Mp4VideoPath','UploadedDate','CreatedBy','EditedBy');
    protected $rules = array(
        'OggVideo' => 'required',
        'Mp4Video' => 'required|mimes:mp4',
        'WebmVideo' => 'required|mimes:webm'
    );
    protected $messages = array(
        'OggVideo.required' => 'The Ogg video field is required',
//        'OggVideo.mimes' => 'The ogg video must be in ogg format',
        'Mp4Video.required' => 'The Mp4 video field is required',
        'Mp4Video.mimes' => 'The Mp4 video must be in mp4 format',
        'WebmVideo.required' => 'The Webm video field is required',
        'WebmVideo.mimes' => 'The Webm video must be in webm format'
    );
}
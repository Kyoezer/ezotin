<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 8/4/2016
 * Time: 2:15 PM
 */
class WebsiteVideo extends WebsiteController
{
    public function getList(){
        $videos = DB::table("webvideo")->where(DB::raw("coalesce(DisplayStatus,0)"),'=','1')->orderBy("UploadedDate","Desc")->get(array("OggVideoPath","WebmVideoPath","Mp4VideoPath","UploadedDate"));
        return View::make('website.allvideos')
                    ->with('videos',$videos);
    }
}
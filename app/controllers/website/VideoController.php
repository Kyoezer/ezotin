<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 7/20/2016
 * Time: 10:50 AM
 */
class VideoController extends WebsiteController
{
    public function getNew($id = null){
        $video = array(new videomodel());
        if((bool)$id){
            $video = videomodel::where('Id',$id)->get(array('Id','OggVideoPath','Mp4VideoPath','WebmVideoPath'));
        }
        return View::make('website.addnewvideo')
                    ->with('id')
                    ->with('video',$video);
    }
    public function postSave(){
        $postedValues = Input::all();
        $object = new videomodel();
        $update = false;
        if(!$object->validate($postedValues)){
            if(isset($postedValues['Id']) && !empty($postedValues['Id'])){
                return Redirect::to('web/newvideo/'.$postedValues['Id'])->withErrors($object->errors());
            }else{
                return Redirect::to('web/newvideo')->withErrors($object->errors());
            }
        }
        if(isset($postedValues['Id']) && !empty($postedValues['Id'])){
            $update = true;
            $oldVideos = videomodel::where('Id',$postedValues['Id'])->get(array('OggVideoPath','Mp4VideoPath','WebmVideoPath'));
            $oggVideoPath = $oldVideos[0]->OggVideoPath;
            $mp4VideoPath = $oldVideos[0]->Mp4VideoPath;
            $webmVideoPath = $oldVideos[0]->WebmVideoPath;

            if((bool)$oggVideoPath){
                File::delete($oggVideoPath);
            }
            if((bool)$mp4VideoPath){
                File::delete($mp4VideoPath);
            }
            if((bool)$webmVideoPath){
                File::delete($webmVideoPath);
            }
        }
        $mp4File = Input::file('Mp4Video');
        $fileName = $mp4File->getClientOriginalName();
        $fileName = randomString().'_'.str_replace(' ','_',$fileName);
        $folder = "uploads/video";
        $path = $folder."/$fileName";
        $mp4File->move($folder,$fileName);
        $postedValues['Mp4VideoPath'] = $path;

        $oggFile = Input::file('OggVideo');
        if($oggFile->getClientMimeType() != 'video/ogg'){
            return Redirect::to('web/newvideo')->with('customerrormessage','Ogg video should be in ogg format');
        }
        $fileName = $oggFile->getClientOriginalName();
        $fileName = randomString().'_'.str_replace(' ','_',$fileName);
        $folder = "uploads/video";
        $path = $folder."/$fileName";
        $oggFile->move($folder,$fileName);
        $postedValues['OggVideoPath'] = $path;

        $webmFile = Input::file('WebmVideo');
        $fileName = $webmFile->getClientOriginalName();
        $fileName = randomString().'_'.str_replace(' ','_',$fileName);
        $folder = "uploads/video";
        $path = $folder."/$fileName";
        $webmFile->move($folder,$fileName);
        $postedValues['WebmVideoPath'] = $path;


        if($update){
            $object = videomodel::find($postedValues['Id']);
            $object->fill($postedValues);
            $object->update();
        }else{
            DB::table('webvideo')->update(array('DisplayStatus'=>0));
            $postedValues['Id'] = $this->UUID();
            $postedValues['UploadedDate'] = date('Y-m-d G:i:s');
            $postedValues['DisplayStatus'] = 1;
            videomodel::create($postedValues);
        }

        return Redirect::to('web/newvideo')->with("savedsuccessmessage",$update?"Video has been updated!":"Video has been saved!");
    }

    public function getList(){
        $videos = videomodel::orderBy('DisplayStatus','desc')->orderBy('UploadedDate','desc')->get(array('Id','UploadedDate','OggVideoPath','WebmVideoPath','Mp4VideoPath','DisplayStatus'));
        return View::make('website.videolist')
                    ->with('videos',$videos);
    }

    public function getDelete($id=null){
        if((bool)$id){
            $oldVideos = videomodel::where('Id',$id)->get(array('OggVideoPath','Mp4VideoPath','WebmVideoPath'));
            $oggVideoPath = $oldVideos[0]->OggVideoPath;
            $mp4VideoPath = $oldVideos[0]->Mp4VideoPath;
            $webmVideoPath = $oldVideos[0]->WebmVideoPath;

            if((bool)$oggVideoPath){
                File::delete($oggVideoPath);
            }
            if((bool)$mp4VideoPath){
                File::delete($mp4VideoPath);
            }
            if((bool)$webmVideoPath){
                File::delete($webmVideoPath);
            }
            $displayStatus = videomodel::where('Id',$id)->pluck('DisplayStatus');
            if((int)$displayStatus == 1){
                $maxId = DB::table("webvideo")
                            ->whereRaw("UploadedDate = (select max(UploadedDate) from webvideo where Id <> '$id')")
                            ->pluck('Id');
                videomodel::find($maxId)->update(array('DisplayStatus'=>1));
            }
            videomodel::where('Id',$id)->delete();
            return Redirect::to('web/videolist')->with("savedsuccessmessage","Video has been deleted!");
        }else{
            App::abort(404);
        }
    }
    public function getShow($id=null){
        if((bool)$id){
            $maxId = DB::table("webvideo")
                ->whereRaw("UploadedDate = (select max(UploadedDate) from webvideo where Id <> '$id')")
                ->pluck('Id');
            videomodel::find($id)->update(array('DisplayStatus'=>1));
            videomodel::where('Id','<>',$id)->update(array('DisplayStatus'=>0));
            return Redirect::to('web/videolist')->with('savedsuccessmessage',"Video has been updated");
        }else{
            App::abort(404);
        }
    }
}
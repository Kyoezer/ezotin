<?php
/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 9/21/2015
 * Time: 5:37 PM
 */

class WebsiteMarquee extends WebsiteController{
    public function getIndex(){
        $details = DB::table('webmarqueesetting')->get(array('Id','EnableMarquee','MarqueeHeading'));
        if(!$details){
            $details = array();
        }
        return View::make('website.marqueesetting')
                ->with('details',$details);
    }
    public function postSave(){
        $enable = Input::get('EnableMarquee');
        if((int)$enable == 1){
            $marqueeHeading = Input::get('MarqueeHeading');
        }else{
            $marqueeHeading = NULL;
        }
        if(Input::get('Id') != ''){
            $update = true;
            $id = Input::get('Id');
        }else{
            $update = false;
            $id = $this->UUID();
        }
        DB::beginTransaction();
        try{
            if($update){
                DB::table('webmarqueesetting')->where('Id',$id)->update(array('EnableMarquee'=>$enable,'MarqueeHeading'=>$marqueeHeading,'EditedBy'=>Auth::user()->Id,'EditedOn'=>date('Y-m-d G:i:s')));
            }else{
                DB::table('webmarqueesetting')->insert(array('Id'=>$id,'EnableMarquee'=>$enable,'MarqueeHeading'=>$marqueeHeading,'CreatedBy'=>Auth::user()->Id,'CreatedOn'=>date("Y-m-d G:i:s")));
            }

        }catch(Exception $e){
            DB::rollback();
            throw $e;
        }
        DB::commit();
        return Redirect::to('web/togglemarquee')->with('savedsuccessmessage','Setting has been updated');
    }
}
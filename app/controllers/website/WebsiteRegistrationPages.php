<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 11/27/2016
 * Time: 9:14 PM
 */
class WebsiteRegistrationPages extends BaseController
{
    public function getIndex($type){
        if((int)$type == 1){
            $pageTitle = "Contractor Registration";
        }elseif((int)$type == 2){
            $pageTitle = "Consultant Registration";
        }elseif((int)$type == 3){
            $pageTitle = "Architect Registration";
        }elseif((int)$type == 4){
            $pageTitle = "Specialized Trade Registration";
        }elseif((int)$type == 5){
            $pageTitle = "Manage about Journal";
        }elseif((int)$type == 6){
            $pageTitle = "Manage about Journal Aims and Scope"; 
        }elseif((int)$type == 7){
            $pageTitle = "Manage about Journal Peer Review Process";  
        }elseif((int)$type == 8){
            $pageTitle = "Manage about Journal Conflict of interest"; 
        }elseif((int)$type == 9){
            $pageTitle = "Manage about Journal Contact";
        }elseif((int)$type == 10){
            $pageTitle = "Manage about Journal Editorial Policies";
        }elseif((int)$type == 11){
            $pageTitle = "Manage about Journal Editorial Team";
        }elseif((int)$type == 12){
            $pageTitle = "Manage about Journal Submission preparation checklist";
        }elseif((int)$type == 13){
            $pageTitle = "Manage about Journal Manuscript preparation guideline to the author";
        }elseif((int)$type == 14){
            $pageTitle = "Manage about Journal Current";
        }elseif((int)$type == 15){
            $pageTitle = "Manage about Journal Archive";
        }elseif((int)$type == 16){
                $pageTitle = "Manage about Journal Submission";
        }elseif((int)$type == 17){
                $pageTitle = "Manage about Journal Open Access";
        }elseif((int)$type == 18){
                $pageTitle = "Manage about Journal Information";
        // }elseif((int)$type == 19){
        //         $pageTitle = "Manage about Journal Reviewer Checklist";
	}elseif((int)$type == 21){
            $pageTitle = "Certified Builder Registration";
        }else{
            App::abort(404);
        }
        $saved = DB::table('webregistrationpagecontent')->where('Type',$type)->get(array('Id','Content'));
        if(empty($saved)){
            $saved = array(new WebRegistrationPageModel);
        }
        return View::make('website.registrationpages')
                ->with('saved',$saved)
                ->with('pageTitle',$pageTitle)
                ->with('type',$type);
    }

    public function postSave(){
        $id = Input::get('Id');
        $inputs = Input::all();
        DB::beginTransaction();
        try{
            if(empty($id)){
                $append = "saved";
                $inputs['Id'] = $this->UUID();
                WebRegistrationPageModel::create($inputs);
            }else{
                $append = "updated";
                $object = WebRegistrationPageModel::find($id);
                $object->fill($inputs);
                $object->update();
            }
        }catch(Exception $e){
            DB::rollback();
            return Redirect::to('web/editwebreg/'.Input::get('Type'))->with('customerrormessage',$e->getMessage());
        }
        DB::commit();
        return Redirect::to('web/editwebreg/'.Input::get('Type'))->with('savedsuccessmessage',"Page has been $append");

    }
}
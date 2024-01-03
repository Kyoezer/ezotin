<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 8/8/2016
 * Time: 12:31 PM
 */
class WebsiteArbitrator extends WebsiteController
{
    public function getIndex(){
        $arbitrators = DB::table("webarbitrators")->orderBy("RegNo")->whereRaw("coalesce(IsDeleted,0)=0")->get(array("Id","RegNo","Name","Designation","Email","ContactNo","CasesInHand","FilePath","FileType"));
        return View::make("sys.managearbitrators")
                ->with("arbitrators",$arbitrators);
    }
    public function postSave(){
        $postedValues = Input::get("webarbitrator");
        $images = Input::file('webarbitrator');
        DB::beginTransaction();
        try{
            foreach($postedValues as $a=>$b):
                foreach($b as $key=>$value):
                    $inputArray[$key] = $value;
                endforeach;

                if($images[$a]['Image']){
                    $oldImage = DB::table('webarbitrators')->where('Id',$inputArray['Id'])->pluck('FilePath');
                    $oldImage = substr($oldImage,1,strlen($oldImage));
                    File::delete($oldImage);
                    $image = $images[$a]['Image'];
                    $imageName="arbitrator_".$inputArray['RegNo'].randomString().'_'.'.'.$image->getClientOriginalExtension();
                    $destination=public_path().'/uploads/arbitrators';
                    $destinationDB='/uploads/arbitrators/'.$imageName;
                    $inputArray["FilePath"]=$destinationDB;
                    $inputArray["FileType"]=".".$image->getClientOriginalExtension();
                    $uploadAttachments=$image->move($destination, $imageName);


                    if(isset($b['OldImage'])){
                        unset($inputArray['OldImage']);
                        unset($inputArray['OldImageType']);
                    }
                }else{
                    if(isset($b['OldImage'])){
                        $inputArray['FilePath'] = $b['OldImage'];
                        $inputArray['FileType'] = $b['OldImageType'];

                        unset($inputArray['OldImage']);
                        unset($inputArray['OldImageType']);
                    }
                }
                if((bool)$inputArray['Id']){

                    $object = ArbitratorModel::find($inputArray['Id']);
                    $object->fill($inputArray);
                    $object->update();
                }else{
                    ArbitratorModel::create($inputArray);
                }

                $inputArray = array();
            endforeach;
        }catch (Exception $e){
            DB::rollBack();
            return Redirect::to("web/arbitratorlist")->with('customerrormessage',$e->getMessage());
        }
        DB::commit();
        return Redirect::to("web/arbitratorlist")->with("savedsuccessmessage","Record has been updated");
    }
    public function postDelete(){
        $id = Input::get('id');
        DB::table('webarbitrators')->where('Id',$id)->update(array('IsDeleted'=>1));
        return Response::json(array('response'=>1));
    }
}
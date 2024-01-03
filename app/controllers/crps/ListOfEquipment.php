<?php
class ListOfEquipment extends CrpsController{
	public function index(){
		$equipments=CmnEquipmentModel::equipment()->whereNotNull('Code')->get(array('Id','Code','Name','IsRegistered','VehicleType'));
		$equipmentId=Input::get('sref');
        if((bool)$equipmentId==NULL || empty($equipmentId)){
            $editEquipments=array(new CmnEquipmentModel());
        }else{
            $editEquipments=CmnEquipmentModel::equipment()->where('Id',$equipmentId)->get(array('Id','Code','Name','IsRegistered','VehicleType'));
        }
		return View::make('crps.cmnlistofequipment')
					->with('equipments',$equipments)
					->with('editEquipments',$editEquipments);
	}
	public function save(){
		$postedValues=Input::all();
		$id=$postedValues["Id"];
		$validation = new CmnEquipmentModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::to('master/listofequipment')->withErrors($errors)->withInput();
		}else{
			if(empty($id)){
                CmnEquipmentModel::create($postedValues);
                $message='Equipment saved successfully';
            }else{
                $instance=CmnEquipmentModel::find($id);
                $instance->fill($postedValues);
                $instance->save();
                $message='Equipment updated successfully';
            }
			return Redirect::to('master/listofequipment')->with('savedsuccessmessage',$message);
		}
	}
	public function search($searchId){
		$redirectUrl=Input::get('redirectUrl');
        $columns=$this->selectSearchColumns($searchId);
        $searchResults=CmnEquipmentModel::select(DB::raw("Id,Code,Name,case when IsRegistered=1 then 'Registered' else 'Not-Registered' end as IsRegistered"))->get();
		return View::make('crps.cmnsearch')
                    ->with('title','Equipment')
                    ->with('redirectUrl',$redirectUrl)
                    ->with('columns',$columns)
                    ->with('searchResults',$searchResults);
	}
}
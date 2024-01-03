<?php
class ProcuringAgency extends CrpsController{
	public function index(){
        $procuringAgencies=ProcuringAgencyModel::procuringAgency()->whereNull('cmnprocuringagency.CmnProcuringAgencyId')->get(array('T1.Code as MinistryCode','T1.Name as MinistryName','T2.Name as Division','cmnprocuringagency.Id','cmnprocuringagency.Code as ProcuringAgencyCode','cmnprocuringagency.Name as ProcuringAgencyName'));
		$ministry=CmnListItemModel::ministry()->get(array('Id','Code','Name'));

        $procuringAgencyId=Input::get('sref');
        if((bool)$procuringAgencyId==NULL || empty($procuringAgencyId)){
            $editProcuringAgencies=array(new ProcuringAgencyModel());
        }else{
            $editProcuringAgencies=ProcuringAgencyModel::procuringAgencyHardList()->where('Id',$procuringAgencyId)->get(array('Id','Code','Name','CmnMinistryId'));
        }
		return View::make('crps.cmnprocuringagency')
                    ->with('procuringAgencies',$procuringAgencies)
					->with('ministries',$ministry)
                    ->with('editProcuringAgencies',$editProcuringAgencies);
	}
	public function save(){
        $postedValues=Input::all();
        $id=$postedValues["Id"];
        $validation = new ProcuringAgencyModel;
        if(!($validation->validate($postedValues))){
            $errors = $validation->errors();
            return Redirect::to('master/procuringagency')->withErrors($errors)->withInput();
        }else{
            if(empty($id)){
                ProcuringAgencyModel::create($postedValues);
                $message='Procuring Agency saved successfully';
            }else{
                $instance=ProcuringAgencyModel::find($id);
                $instance->fill($postedValues);
                $instance->save();
                $message='Procuring Agency updated successfully';
            }
            return Redirect::to('master/procuringagency')->with('savedsuccessmessage',$message);
        }
    }
    public function search($searchId){
        $redirectUrl=Input::get('redirectUrl');
        $columns=$this->selectSearchColumns($searchId);
        $searchResults=DB::table('cmnprocuringagency as T1')->join('cmnlistitem as T2','T1.CmnMinistryId','=','T2.Id')->select(DB::raw("T1.Id,T1.Code,T1.Name as ProcuringAgency,T2.Name as Ministry"))->get();
        return View::make('crps.cmnsearch')
                    ->with('title','Procuring Agency')
                    ->with('redirectUrl',$redirectUrl)
                    ->with('columns',$columns)
                    ->with('searchResults',$searchResults);
    }
}
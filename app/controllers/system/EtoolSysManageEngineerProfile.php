<?php
class EtoolSysManageEngineerProfile extends SystemController{
	public function index(){
		$parameters=array();
		$nameOfEngineer=Input::get('NameOfEngineer');
		$cidNo=Input::get('CIDNo');
		$agency = Input::get("Agency");
		$limit=Input::get('Limit');

		$agencies = DB::table('crpgovermentengineer')->distinct()->get(array(DB::raw("coalesce(Agency,'--') as Agency")));
		if((bool)$limit){
            if($limit != 'All'){
                $limit=" limit $limit";
            }else{
            	$limit="";
            }
        }else{
            $limit.=" limit 20";
        }
        $query="select Id,Name as EngineerName,CIDNo,coalesce(Agency,'---') as EmployerName from crpgovermentengineer where Releaved=0";
        if((bool)$nameOfEngineer!=NULL || (bool)$cidNo!=NULL || (bool)$agency){
			if((bool)$nameOfEngineer!=NULL){
				$nameOfEngineer="%$nameOfEngineer%";
				$query.=" and Name like ?";
				array_push($parameters,$nameOfEngineer);
			}
			if((bool)$cidNo!=NULL){
				$query.=" and CIDNo=?";
				array_push($parameters,$cidNo);
			}
			if((bool)$agency){
				$query.=" and Agency = ?";
				array_push($parameters,$agency);
			}
		}
		$govermentEngineers=DB::select($query." order by EngineerName".$limit,$parameters);
		return View::make('sys.manageengineerprofile')
					->with('agencies',$agencies)
					->with('govermentEngineers',$govermentEngineers);
	}
	public function relieveEngineer(){
		$engineerReference=Input::get('EngineerReference');
		$relievingDate=$this->convertDate(Input::get('RelievingDate'));
		$relievingLetterNo=Input::get('RelievingLetterNo');
		DB::statement("update crpgovermentengineer set Releaved=1,RelieveingDate=?,RelieveingLetterNo=? where Id=?",array($relievingDate,$relievingLetterNo,$engineerReference));
		return Redirect::to('etoolsysadm/manageengprofile')->with('savedsuccessmessage','Engineer has been successfully relieved');
	}
	public function importIndex(){
		return View::make('sys.importcorporateengineers');
	}
	public function postSave(){
		$arrayNames = array('Name','CIDNo','PositionTitle','Agency','Qualification','Trade');
		DB::beginTransaction();
		try{
			Excel::load(Input::file('Excel'), function($reader) use ($arrayNames) {
				$results = $reader->toArray();
				foreach($results as $key=>$value):
					$count = 0;
					$postedValue['Id'] = $this->UUID();
					foreach($value as $x=>$y):
						$postedValue[$arrayNames[$count]] = $y;
						$count++;
					endforeach;
					CorporateEngineerModel::create($postedValue);
				endforeach;
			});
		}catch(Exception $e){
			DB::rollBack();
			return Redirect::to("etoolsysadm/importcorporateengineer")->with("customerrormessage",$e->getMessage());
		}
		DB::commit();
		return Redirect::to("etoolsysadm/manageengprofile")->with("savedsuccessmessage","Corporate Engineers have been imported");
	}
	public function deleteIndex(){
		$agencies = DB::table("crpgovermentengineer")->orderBy("Agency")->distinct()->get(array("Agency"));
		return View::make("sys.deletecorporateengineers")
				->with('agencies',$agencies);
	}
	public function postDelete(){
		$agency = Input::get('Agency');
		DB::table("crpgovermentengineer")->where(DB::raw("TRIM(Agency)"),'=',trim($agency))->delete();
		return Redirect::to("etoolsysadm/manageengprofile")->with("savedsuccessmessage","Corporate Engineers of $agency have been deleted!");
	}
}
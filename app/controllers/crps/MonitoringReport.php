<?php

/**
 * Created by PhpStorm.
 * User: SWM
 * Date: 3/15/2017
 * Time: 3:12 PM
 */
class MonitoringReport extends CrpsController
{
    public function getOfficeIndex(){
        $routeSegment = Request::segment(2);
        $isList = 1;
        $parameters=array();
        $contractorId=Input::get('ContractorId');
        $CDBNo=Input::get('CDBNo');
        $status=Input::get('Status');
        $contractorLists = array();
        
        $dzongkhagId = Input::get('DzongkhagId');
        $priority = Input::get("Priority");
        $route = "monitoringreport.officelist";
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn as Dzongkhag'));
        $limit=Input::get('Limit');
		if((bool)$limit){
			if($limit != 'All'){
				$limit=" limit $limit";
			}else{
				$limit="";
			}
		}else{
			$limit.=" limit 20";
		}
		$query="select distinct T1.Id,T1.RegistrationExpiryDate,T1.CDBNo,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,Z.Name as Status,Z.ReferenceNo as StatusReference,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType,B.Code as ClassificationCode,B.Name as ClassificationName from crpcontractorfinal T1 left join cmnlistitem Z on Z.Id = T1.CmnApplicationRegistrationStatusId join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join (crpcontractorworkclassificationfinal A join cmncontractorclassification B on A.CmnApprovedClassificationId=B.Id) on T1.Id=A.CrpContractorFinalId and B.Priority=(select max(Priority) from crpcontractorworkclassificationfinal X join cmncontractorclassification Y on X.CmnApprovedClassificationId=Y.Id where X.CrpContractorFinalId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where 1";
//		array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		if(Route::current()->getUri()=="contractor/viewprintlist"){
			$linkText='View/Print';
			$link='contractor/viewprintdetails/';
		}elseif(Route::current()->getUri()=="contractor/newcommentsadverserecordslist"){
			$linkText='Add';
			$link='contractor/newcommentsadverserecords/';
		}elseif(Route::current()->getUri()=="contractor/editcommentsadverserecordslist"){
			$linkText='View';
			$link='contractor/editcommentsadverserecords/';
		}elseif(Route::current()->getUri()=="contractor/monitoringlist"){
			$isMonitoring = true;
		}
		if((bool)$contractorId!=NULL || (bool)$CDBNo!=NULL ){
			if((bool)$contractorId!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$contractorId);
			}
			if((bool)$CDBNo!=NULL){
				$query.=" and T1.CDBNo=?";
				array_push($parameters,$CDBNo);
			}
		}
		$contractorLists=DB::select($query." order by T1.CDBNo,Z.ReferenceNo,NameOfFirm".$limit,$parameters);
        return View::make('crps.monitoringofficeindex')
            ->with('pageTitle','List of Contractors')
            ->with('route',$route)
            ->with('CDBNo',$CDBNo)
            ->with('formType','REINSTATE')
            ->with('dzongkhags',$dzongkhags)
            ->with('contractorLists',$contractorLists);
    }
    public function getOfficeRecord($id){
        $contractorDetails = DB::table('crpcontractorfinal')->where('Id',$id)->get(array('Id','NameOfFirm','CDBNo'));
        if(count($contractorDetails) == 0){
            return Redirect::to('monitoringreport/officenew')->with('customerrormessage','No such Contractor!');
        }
        $hrDetails = DB::table('crpcontractorhumanresourcefinal as T1')
            ->leftJoin('cmnlistitem as T2','T2.Id','=','T1.CmnDesignationId')
            ->where('T1.CrpContractorFinalId',$id)->get(array('T1.CIDNo','T1.Name','Sex','T1.CmnDesignationId','T2.Name as Designation','T1.Name as Personnel'));
        $eqDetails = DB::table('crpcontractorequipmentfinal as T1')
            ->leftJoin('cmnequipment as T2','T2.Id','=','T1.CmnEquipmentId')
            ->where('T1.CrpContractorFinalId',$id)->get(array('T1.CmnEquipmentId','T1.RegistrationNo',DB::raw('TRIM(T2.Name) as Equipment'),DB::raw("coalesce(T1.RegistrationNo,'') as RegistrationNo")));
        $workClassificationDetails = DB::table('crpcontractorworkclassificationfinal as T1')
            ->join('cmncontractorclassification as T2','T2.Id','=','T1.CmnApprovedClassificationId')
            ->join('cmncontractorworkcategory as T3','T3.Id','=','T1.CmnProjectCategoryId')
            ->where('T1.CrpContractorFinalId',$id)
            ->orderBy('T3.ReferenceNo')
            ->get(array('T3.Code as Category','T2.Name as Class'));

        return View::make('crps.contractormonitoringreportoffice')
                    ->with('contractorDetails',$contractorDetails)
                    ->with('hrDetails',$hrDetails)
                    ->with('eqDetails',$eqDetails)
                    ->with('workClassificationDetails',$workClassificationDetails);
    }
    public function postSaveOffice(){
        $redirectUrl = "monitoringreport/officenew";
        $mainTableInputs = Input::except("MonitoringOfficeEquipment",'MonitoringOfficeHR','_token');
        $mainTableInputs['MonitoringDate'] = $this->convertDate($mainTableInputs['MonitoringDate']);
        $equipmentInputs = Input::get('MonitoringOfficeEquipment');
        $hrInputs = Input::get("MonitoringOfficeHR");
        $equipmentInputArray = array();
        $hrInputArray = array();
        DB::beginTransaction();
        try{
            if(isset($mainTableInputs['Id'])){
                $redirectUrl = 'monitoringreport/officelist';
                $mainId = $mainTableInputs['Id'];
                $mainTableInputs['EditedBy'] = Auth::user()->Id;
                $mainTableInputs['EditedOn'] = date('Y-m-d G:i:s');
                DB::table('crpmonitoringoffice')->where('Id',$mainId)->update($mainTableInputs);
                MonitoringOfficeEquipmentModel::where('CrpMonitoringOfficeId',$mainId)->delete();
                MonitoringOfficeHRModel::where('CrpMonitoringOfficeId',$mainId)->delete();
            }else{
                $mainTableInputs['Id'] = $mainId = $this->UUID();
                MonitoringOfficeModel::create($mainTableInputs);
            }

            if(isset($equipmentInputs)){
                foreach($equipmentInputs as $key=>$value):
                    foreach($value as $x=>$y):
                        $equipmentInputArray[$x] = $y;
                    endforeach;
                    $equipmentInputArray['Id'] = $this->UUID();
                    $equipmentInputArray['CrpMonitoringOfficeId'] = $mainId;
                    MonitoringOfficeEquipmentModel::create($equipmentInputArray);
                    $equipmentInputArray = array();
                endforeach;
            }


            if(isset($hrInputs)):
                foreach($hrInputs as $key=>$value):
                    foreach($value as $x=>$y):
                        $hrInputArray[$x] = $y;
                    endforeach;
                    $hrInputArray['Id'] = $this->UUID();
                    $hrInputArray['CrpMonitoringOfficeId'] = $mainId;
                    MonitoringOfficeHRModel::create($hrInputArray);
                    $hrInputArray = array();
                endforeach;
            endif;

        }catch(Exception $e){
            DB::rollBack();
            if(strstr($e->getMessage(),"1062")){
                return Redirect::to($redirectUrl)->with('customerrormessage',"You have already monitored this contractor for this year");
            }
            return Redirect::to($redirectUrl)->with('customerrormessage',$e->getMessage());
        }
        DB::commit();

        return Redirect::to($redirectUrl)->with('savedsuccessmessage',"<STRONG>SUCCESS!</STRONG> Monitoring has been recorded.");
    }

    public function getOfficeListOld(){
        $routeSegment = Request::segment(2);
        $isList = 1;
        $parameters=array();
        $contractorId=Input::get('ContractorId');
        $CDBNo=Input::get('CDBNo');
        $status=Input::get('Status');
        $contractorLists = array();
        
        $dzongkhagId = Input::get('DzongkhagId');
        $priority = Input::get("Priority");
        $route = "monitoringreport.officelist";
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn as Dzongkhag'));
        $priorities = DB::table('cmncontractorclassification')->orderBy('Priority')->get(array('Priority',"Code as Class"));

        $queryForYears = "select distinct V.Year from crpmonitoringoffice V join crpcontractorfinal T1 on V.CrpContractorFinalId = T1.Id left join cmnlistitem Z on Z.Id = T1.CmnApplicationRegistrationStatusId join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join (crpcontractorworkclassificationfinal A join cmncontractorclassification B on A.CmnApprovedClassificationId=B.Id) on T1.Id=A.CrpContractorFinalId and B.Priority=(select max(Priority) from crpcontractorworkclassificationfinal X join cmncontractorclassification Y on X.CmnApprovedClassificationId=Y.Id where X.CrpContractorFinalId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where 1";
        $query="select distinct V.Id as MonitoringOfficeId,V.ActionTaken,V.Remarks,T1.CmnApplicationRegistrationStatusId,T1.Id, V.Year,V.MonitoringStatus,V.MonitoringDate,T1.CDBNo,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,Z.Name as Status,Z.ReferenceNo as StatusReference,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType,B.Code as ClassificationCode,B.Name as ClassificationName from crpmonitoringoffice V join crpcontractorfinal T1 on V.CrpContractorFinalId = T1.Id left join cmnlistitem Z on Z.Id = T1.CmnApplicationRegistrationStatusId join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join (crpcontractorworkclassificationfinal A join cmncontractorclassification B on A.CmnApprovedClassificationId=B.Id) on T1.Id=A.CrpContractorFinalId and B.Priority=(select max(Priority) from crpcontractorworkclassificationfinal X join cmncontractorclassification Y on X.CmnApprovedClassificationId=Y.Id where X.CrpContractorFinalId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where 1";
        if($routeSegment == 'officeaction'){
            $route = "monitoringreport.officeaction";
            $isList = 2;
            $queryForYears.=" and coalesce(V.MonitoringStatus,0)=0";
            $query.=" and coalesce(V.MonitoringStatus,0)=0";
        }
//        array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);

        $countParam = 0;

        if((bool)$contractorId!=NULL){
            $countParam = 1;
            $queryForYears.=" and T1.Id=?";
            $query.=" and T1.Id=?";
            array_push($parameters,$contractorId);
        }
        if((bool)$CDBNo!=NULL){
            $countParam = 1;
            $queryForYears.=" and T1.CDBNo=?";
            $query.=" and T1.CDBNo=?";
            array_push($parameters,$CDBNo);
        }
        if((bool)$dzongkhagId){
            $countParam = 1;
            $queryForYears.=" and T1.CmnDzongkhagId = ?";
            $query.=" and T1.CmnDzongkhagId = ?";
            array_push($parameters,$dzongkhagId);
        }
        if((bool)$priority){
            $countParam = 1;
            $queryForYears.=" and B.Priority = ?";
            $query.=" and B.Priority = ?";
            array_push($parameters,$priority);
        }
        if((bool)$status){
            $countParam = 1;
            if((int)$status == 2){
                $queryForYears.=" and V.MonitoringStatus = ?";
                $query.=" and V.MonitoringStatus = ?";
                array_push($parameters,0);
            }else{
                $queryForYears.=" and V.MonitoringStatus = ?";
                $query.=" and V.MonitoringStatus = ?";
                array_push($parameters,1);
            }
        }
        $years = DB::select("$queryForYears order by V.Year desc",$parameters);
        if($countParam==1)
        {
           // foreach($years as $year){
                $contractorLists = DB::select($query." order by V.MonitoringDate desc,T1.CDBNo",$parameters);
            //}
        }
        return View::make('crps.monitoringofficeindex')
            ->with('pageTitle','List of Contractors')
            ->with('route',$route)
            ->with('CDBNo',$CDBNo)
            ->with('dzongkhags',$dzongkhags)
            ->with('priorities',$priorities)
            ->with('isList',$isList)
            ->with('years',$years)
            ->with('countParam',$countParam)
            ->with('contractorLists',$contractorLists);
    }

    public function getOfficeList(){
        $routeSegment = Request::segment(2);
        $isList = 1;
        $parameters=array();
        $contractorId=Input::get('ContractorId');
        $CDBNo=Input::get('CDBNo');
        $status=Input::get('Status');
        $contractorLists = array();
        
        $dzongkhagId = Input::get('DzongkhagId');
        $priority = Input::get("Priority");
        $route = "monitoringreport.officelist";
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn as Dzongkhag'));
        $limit=Input::get('Limit');
		if((bool)$limit){
			if($limit != 'All'){
				$limit=" limit $limit";
			}else{
				$limit="";
			}
		}else{
			$limit.=" limit 20";
		}
		$query="select distinct T1.Id,T1.RegistrationExpiryDate,T1.CDBNo,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,Z.Name as Status,Z.ReferenceNo as StatusReference,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType,B.Code as ClassificationCode,B.Name as ClassificationName from crpcontractorfinal T1 left join cmnlistitem Z on Z.Id = T1.CmnApplicationRegistrationStatusId join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join (crpcontractorworkclassificationfinal A join cmncontractorclassification B on A.CmnApprovedClassificationId=B.Id) on T1.Id=A.CrpContractorFinalId and B.Priority=(select max(Priority) from crpcontractorworkclassificationfinal X join cmncontractorclassification Y on X.CmnApprovedClassificationId=Y.Id where X.CrpContractorFinalId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where 1";
//		array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		if(Route::current()->getUri()=="contractor/viewprintlist"){
			$linkText='View/Print';
			$link='contractor/viewprintdetails/';
		}elseif(Route::current()->getUri()=="contractor/newcommentsadverserecordslist"){
			$linkText='Add';
			$link='contractor/newcommentsadverserecords/';
		}elseif(Route::current()->getUri()=="contractor/editcommentsadverserecordslist"){
			$linkText='View';
			$link='contractor/editcommentsadverserecords/';
		}elseif(Route::current()->getUri()=="contractor/monitoringlist"){
			$isMonitoring = true;
		}
		if((bool)$contractorId!=NULL || (bool)$CDBNo!=NULL ){
			if((bool)$contractorId!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$contractorId);
			}
			if((bool)$CDBNo!=NULL){
				$query.=" and T1.CDBNo=?";
				array_push($parameters,$CDBNo);
			}
		}
		$contractorLists=DB::select($query." order by T1.CDBNo,Z.ReferenceNo,NameOfFirm".$limit,$parameters);
        return View::make('crps.monitoringofficeindex')
            ->with('pageTitle','List of Contractors')
            ->with('route',$route)
            ->with('CDBNo',$CDBNo)
            ->with('dzongkhags',$dzongkhags)
            ->with('formType','DOWNGRADE')
            ->with('contractorLists',$contractorLists);
    }
    public function getOfficeEdit($id){
        $routeSegment = Request::segment(2);
        if($routeSegment == "officeview"){
            $view = "printpages.contractormonitoringreportofficedetails";
        }else{
            $view = "crps.contractormonitoringreportofficeedit";
        }
        $monitoringDetails = DB::table('crpmonitoringoffice as T1')
                                ->join('crpcontractorfinal as T2','T2.Id','=','T1.CrpContractorFinalId')
                                ->where('T1.Id',$id)
                                ->get(array('T1.Id','T1.CrpContractorFinalId','T1.Year','T2.NameOfFirm','T2.CDBNo','T1.MonitoringDate','T1.HasOfficeEstablishment','T1.HasSignBoard','T1.CannotBeContacted','T1.DeceivingOnLocationChange','T1.MonitoringStatus','T1.Remarks'));
        if(count($monitoringDetails) == 0){
            return Redirect::to('monitoringreport/officelist')->with('customerrormessage','<strong>ERROR!</strong> This contractor does not exist!');
        }
        $monitoringHRDetails = DB::table('crpmonitoringofficehumanresource as T1')
                                ->join('cmnlistitem as T2','T1.CmnDesignationId','=','T2.Id')
                                ->where('T1.CrpMonitoringOfficeId',$id)
                                ->get(array('T1.Id','T1.CmnDesignationId','T2.Name as Designation','T1.Name','T1.CIDNo','T1.Name as Personnel','T1.Sex','T1.Checked'));
        $monitoringEquipmentDetails = DB::table("crpmonitoringofficeequipment as T1")
                                        ->join('cmnequipment as T2','T2.Id','=','T1.CmnEquipmentId')
                                        ->where('T1.CrpMonitoringOfficeId',$id)
                                        ->get(array('T1.Id','T1.CmnEquipmentId','T2.Name as Equipment','T1.RegistrationNo','T1.Checked'));
        if($routeSegment == "officeview"){
            $data['printTitle'] ="Monitoring Report (Office Establishment)";
            $data['monitoringHRDetails']=$monitoringHRDetails;
            $data['monitoringEquipmentDetails'] = $monitoringEquipmentDetails;
            $data['monitoringDetails']=$monitoringDetails;
            $pdf = App::make('dompdf');
            $pdf->loadView($view,$data)->setPaper('a4')->setOrientation('potrait');
            return $pdf->stream();
        }
        return View::make($view)

                    ->with('monitoringDetails',$monitoringDetails)
                    ->with('monitoringHRDetails',$monitoringHRDetails)
                    ->with('monitoringEquipmentDetails',$monitoringEquipmentDetails);
    }
    public function fetchContractorWorkClassification(){
        $id = Input::get('id');
        $workClassification = DB::table('crpcontractorworkclassificationfinal as T1')
                                ->join('cmncontractorclassification as T2','T2.Id','=','T1.CmnApprovedClassificationId')
                                ->join('cmncontractorworkcategory as T3','T3.Id','=','T1.CmnProjectCategoryId')
                                ->where('T1.CrpContractorFinalId',$id)
                                ->orderBy('T3.ReferenceNo')
                                ->get(array('T3.Code as Category','T3.ReferenceNo','T3.Id as CategoryId','T2.Name as Class','T2.Id as ClassId'));
        $class=ContractorClassificationModel::classification()->select(DB::raw('Id,Name,coalesce(ReferenceNo,999999) as ReferenceNo'))->get();
        return View::make('crps.contractorloadworkclassification')
                    ->with('classes',$class)
                    ->with('contractorId',$id)
                    ->with('workClassification',$workClassification);
    }
    
    public function postWarningContractor(){
        $inputs['ActionDate'] = date('Y-m-d');
        $inputs['Remarks'] = Input::get('Remarks');
        $inputs['ActionTaken'] = 3;
        $inputs['MonitoringDate'] = date('Y-m-d');
        $inputs['CrpContractorFinalId'] = Input::get('CrpContractorFinalId');
        $monitoringId = Input::get('CrpMonitoringOfficeId');
        $inputs['CreatedBy'] = Auth::user()->Id;
        $inputs['CreatedOn'] = date('Y-m-d');
        $contractorId = Input::get('CrpContractorFinalId');
        $inputs['Id'] = $this->UUID();
        try{
            DB::table('crpmonitoringoffice')->insert($inputs);
        //    Artisan::call('cron:monitoring',array('id' => $monitoringId));
        }catch(Exception $e){
            dd($e->getMessage());
        }

        return Redirect::to('monitoringreport/officeaction')->with('savedsuccessmessage',"<strong>SUCCESS!</strong> Contractor action has been recorded!");
    }
    
    public function postMonitoringRecord(){
        $inputs['ActionDate'] = date('Y-m-d');
        $inputs['Remarks'] = Input::get('Remarks');
        $inputs['ActionTaken'] = 3;
        $inputs['CrpContractorFinalId'] = Input::get('CrpContractorFinalId');
        $monitoringId = Input::get('CrpMonitoringOfficeId');
        $contractorId = Input::get('CrpContractorFinalId');
        $inputs['Id'] = $this->UUID();
        try{
            DB::table('crpmonitoringoffice')->insert($inputs);
            Artisan::call('cron:monitoring',array('id' => $monitoringId));
        }catch(Exception $e){
            dd($e->getMessage());
        }

        return Redirect::to('monitoringreport/officeaction')->with('savedsuccessmessage',"<strong>SUCCESS!</strong> Contractor action has been recorded!");
    }
    
    public function postSuspendContractor(){
        $inputs['ActionDate'] = date('Y-m-d');
        $inputs['MonitoringDate'] = $this->convertDate(Input::get('FromDate'));
        //$inputs['ToDate'] = $this->convertDate(Input::get('ToDate'));
        $inputs['Remarks'] = Input::get('Remarks');
        $inputs['Id'] =  $this->UUID();
        $inputs['ActionTaken'] = 2;
        $inputs['CreatedBy'] = Auth::user()->Id;
        $inputs['CreatedOn'] = date('Y-m-d');
        $inputs['CrpContractorFinalId'] = Input::get('CrpContractorFinalId');
        $monitoringId = $this->UUID();
        //Input::get('CrpMonitoringOfficeId');
        //$contractorId = Input::get('CrpContractorFinalId');
        try{
            DB::table('crpmonitoringoffice')->insert($inputs);
            //DB::table('crpmonitoringoffice')->where('Id',$monitoringId)->update($inputs);
            Artisan::call('cron:monitoring',array('id' => $monitoringId));


            //$postedValues['RevokedDate']=$this->convertDate($postedValues['RevokedDate']);
            $contractorReference=Input::get('CrpContractorFinalId');
           
		    $contractorUserId=ContractorFinalModel::where('Id',$contractorReference)->pluck('SysUserId');
		    
            $postedValues['RevokedDate']=$this->convertDate(Input::get('FromDate'));
		
            $instance=ContractorFinalModel::find(Input::get('CrpContractorFinalId'));
            $instance->CmnApplicationRegistrationStatusId = 'f89a6f55-b1dd-xvid-89f3-080027dcfac6';
			$instance->fill($postedValues);
			$instance->update();
            $redirectRoute="revoke";
            $userInstance=User::find($contractorUserId);
            $userInstance->Status=0;
            $userInstance->save();

        /*---Insert Adverse Record i.e the remarks if the contractor is deregistered/blacklisted*/
        
            $contractorAdverserecordInstance = new ContractorCommentAdverseRecordModel;
            $contractorAdverserecordInstance->CrpContractorFinalId = $contractorReference;
            $contractorAdverserecordInstance->Date=date('Y-m-d');
            $contractorAdverserecordInstance->CmnApplicationRegistrationStatusId = 'f89a6f55-b1dd-xvid-89f3-080027dcfac6';
            $contractorAdverserecordInstance->Remarks=Input::get('Remarks');
            $contractorAdverserecordInstance->Type=1;
            $contractorAdverserecordInstance->save();
        

            


        }catch(Exception $e){
            dd($e->getMessage());
        }

        return Redirect::to('monitoringreport/officeaction')->with('savedsuccessmessage',"<strong>SUCCESS!</strong> Contractor action has been recorded!");
    }
    public function getSiteIndex(){
        $parameters=array();
        $contractorId=Input::get('ContractorId');
        $CDBNo=Input::get('CDBNo');

        $dzongkhagId = Input::get('DzongkhagId');
        $priority = Input::get("Priority");

        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn as Dzongkhag'));
        $priorities = DB::table('cmncontractorclassification')->orderBy('Priority')->get(array('Priority',"Code as Class"));

        $query="select distinct T1.Id,T1.CDBNo,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,Z.Name as Status,Z.ReferenceNo as StatusReference,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType,B.Code as ClassificationCode,B.Name as ClassificationName from crpcontractorfinal T1 left join cmnlistitem Z on Z.Id = T1.CmnApplicationRegistrationStatusId join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join (crpcontractorworkclassificationfinal A join cmncontractorclassification B on A.CmnApprovedClassificationId=B.Id) on T1.Id=A.CrpContractorFinalId and B.Priority=(select max(Priority) from crpcontractorworkclassificationfinal X join cmncontractorclassification Y on X.CmnApprovedClassificationId=Y.Id where X.CrpContractorFinalId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where (select count(*) from viewcontractorstrackrecords WHERE CrpContractorFinalId = T1.Id and ReferenceNo = 3001)>0 and T1.CmnApplicationRegistrationStatusId = ?";
        array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);

        if((bool)$contractorId!=NULL){
            $query.=" and T1.Id=?";
            array_push($parameters,$contractorId);
        }
        if((bool)$CDBNo!=NULL){
            $query.=" and T1.CDBNo=?";
            array_push($parameters,$CDBNo);
        }
        if((bool)$dzongkhagId){
            $query.=" and T1.CmnDzongkhagId = ?";
            array_push($parameters,$dzongkhagId);
        }
        if((bool)$priority){
            $query.=" and B.Priority = ?";
            array_push($parameters,$priority);
        }

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $contractorLists=DB::select($query." order by CDBNo,NameOfFirm".$limitOffsetAppend,$parameters);
        return View::make('crps.monitoringsiteindex')
            ->with('pageTitle','List of Contractors')
            ->with('route',"monitoringreport.sitenew")
            ->with('CDBNo',$CDBNo)
            ->with('dzongkhags',$dzongkhags)
            ->with('priorities',$priorities)
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('contractorLists',$contractorLists);
    }
    public function getSiteContractorInfo($contractorId){
        $generalInformation=ContractorFinalModel::contractor($contractorId)->get(array('crpcontractorfinal.Id','T3.Name as Status','crpcontractorfinal.DeregisteredDate','crpcontractorfinal.DeregisteredRemarks','crpcontractorfinal.RevokedDate','crpcontractorfinal.RevokedRemarks','crpcontractorfinal.SurrenderedDate','crpcontractorfinal.SurrenderedRemarks','crpcontractorfinal.CDBNo','crpcontractorfinal.TradeLicenseNo','crpcontractorfinal.TPN','crpcontractorfinal.ApplicationDate','crpcontractorfinal.RegistrationExpiryDate', 'crpcontractorfinal.NameOfFirm','crpcontractorfinal.RegisteredAddress','crpcontractorfinal.Gewog','crpcontractorfinal.Village','crpcontractorfinal.Address','crpcontractorfinal.Email','crpcontractorfinal.TelephoneNo','crpcontractorfinal.MobileNo','crpcontractorfinal.FaxNo','crpcontractorfinal.CmnApplicationRegistrationStatusId','T1.Name as Country','T2.NameEn as Dzongkhag','T4.Name as OwnershipType','T5.NameEn as RegisteredDzongkhag'));
        $ownerPartnerDetails=ContractorHumanResourceFinalModel::contractorPartner($contractorId)->get(array('crpcontractorhumanresourcefinal.Id','crpcontractorhumanresourcefinal.CIDNo','crpcontractorhumanresourcefinal.JoiningDate','crpcontractorhumanresourcefinal.Name','crpcontractorhumanresourcefinal.Sex','crpcontractorhumanresourcefinal.ShowInCertificate','T1.Nationality as Country','T2.Name as Salutation','T3.Name as Designation'));
        $contractorHumanResources=ContractorHumanResourceFinalModel::contractorHumanResource($contractorId)->get(array(DB::raw('distinct crpcontractorhumanresourcefinal.Id'),'crpcontractorhumanresourcefinal.Name','crpcontractorhumanresourcefinal.EditedOn','crpcontractorhumanresourcefinal.CIDNo','crpcontractorhumanresourcefinal.JoiningDate','crpcontractorhumanresourcefinal.Sex','crpcontractorhumanresourcefinal.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country'));
        $contractorEquipments=ContractorEquipmentFinalModel::contractorEquipment($contractorId)->get(array('crpcontractorequipmentfinal.Id','crpcontractorequipmentfinal.EditedOn','crpcontractorequipmentfinal.RegistrationNo','crpcontractorequipmentfinal.ModelNo','crpcontractorequipmentfinal.Quantity','T1.Name'));
        $contractorTrackrecords = DB::select("select RowId,Type,WorkId,CDBNo,WorkStartDate, WorkCompletionDate,ReferenceNo,ProcuringAgency as Agency,NameOfWork,ProjectCategory as Category,BidAmount as AwardedAmount,FinalAmount,Dzongkhag,ReferenceNo,WorkStatus as Status,(coalesce(OntimeCompletionScore,0) + coalesce(QualityOfExecutionScore,0)) as APS,Remarks from viewcontractorstrackrecords where CrpContractorFinalId = ? and coalesce(ReferenceNo,99999) = 3001 order by year(WorkStartDate),ProcuringAgency",array($contractorId));
        $contractorHumanResourceAttachments=ContractorHumanResourceAttachmentFinalModel::singleContractorHumanResourceAllAttachments($contractorId)->get(array('crpcontractorhumanresourceattachmentfinal.DocumentName','crpcontractorhumanresourceattachmentfinal.DocumentPath','crpcontractorhumanresourceattachmentfinal.CrpContractorHumanResourceFinalId as CrpContractorHumanResourceId'));
        $contractorEquipmentAttachments=ContractorEquipmentAttachmentFinalModel::singleContractorEquipmentAllAttachments($contractorId)->get(array('crpcontractorequipmentattachmentfinal.DocumentName','crpcontractorequipmentattachmentfinal.DocumentPath','crpcontractorequipmentattachmentfinal.CrpContractorEquipmentFinalId as CrpContractorEquipmentId'));
        return View::make('crps.monitoringsitecontractordetails')
            ->with('contractorId',$contractorId)
            ->with('generalInformation',$generalInformation)
            ->with('ownerPartnerDetails',$ownerPartnerDetails)
            ->with('contractorHumanResources',$contractorHumanResources)
            ->with('contractorEquipments',$contractorEquipments)
            ->with('contractorTrackrecords',$contractorTrackrecords)
            ->with('contractorHumanResourceAttachments',$contractorHumanResourceAttachments)
            ->with('contractorEquipmentAttachments',$contractorEquipmentAttachments);
    }
    public function getSiteRecord($id,$type){
        $contractorId = Input::get('contractor');
        $contractorDetails = DB::table('crpcontractorfinal')->where('Id',$contractorId)->get(array("CDBNo",'NameOfFirm','Id'));
        $committedHR = array();
        $committedEq = array();
        $commitmentDetails = array();
        $vtiEmployment = DB::table('etlbidevalutionparameters')->where('ReferenceNo','=',1002)->get(array('Name','Points'));
        $vtiInternship = DB::table('etlbidevalutionparameters')->where('ReferenceNo','=',1003)->get(array('Name','Points'));
        if(count($contractorDetails)==0){
            App::abort(404);
        }
        if(($type == 1) || ($type == 2)){
            $table = "crpbiddingform";
        }else{
            $table = "etltender";
            $committedHR = DB::select("select T4.CIDNo,T4.Name,T5.Name as Designation,T4.CmnDesignationId,T4.Qualification from (etltenderbiddercontractordetail as T1 join etltenderbiddercontractor as T2 on T2.Id = T1.EtlTenderBidderContractorId and T1.CrpContractorFinalId = ?) join etltender T3 on T3.Id = T2.EtlTenderId join (etlcontractorhumanresource T4 left join cmnlistitem T5 on T5.Id = T4.CmnDesignationId) on T4.EtlTenderBidderContractorId = T2.Id where T3.Id = ? and coalesce(T3.TenderSource,0) = 1 order by T4.Points Desc",array($contractorId,$id));
            $committedEq = DB::select("select T4.RegistrationNo,T4.CmnEquipmentId, T4.Quantity, T5.Name as Equipment from (etltenderbiddercontractordetail as T1 join etltenderbiddercontractor as T2 on T2.Id = T1.EtlTenderBidderContractorId and T1.CrpContractorFinalId = ?) join etltender T3 on T3.Id = T2.EtlTenderId join (etlcontractorequipment T4 join cmnequipment T5 on T5.Id = T4.CmnEquipmentId) on T4.EtlTenderBidderContractorId = T2.Id where T3.Id = ? and coalesce(T3.TenderSource,0) = 1 order by T4.Points Desc",array($contractorId,$id));
            $commitmentDetails = DB::table('etltenderbiddercontractor')->where('EtlTenderId',$id)->get(array('EmploymentOfVTI','CommitmentOfInternship'));
        }
        $workDetails = DB::table("$table as T1")
            ->leftJoin('cmnprocuringagency as T2','T2.Id','=','T1.CmnProcuringAgencyId')
            ->where('T1.Id',$id)->get(array('T2.Name as Agency','T1.NameOfWork','T1.Id'));
        if(count($workDetails)==0){
            App::abort(404);
        }
        $reportingSites = array(new MonitoringSiteModel());
        return View::make('crps.contractormonitoringreportsites')
                    ->with('type',$type)
                    ->with('edit',false)
                    ->with('commitmentDetails',$commitmentDetails)
                    ->with('vtiEmployment',$vtiEmployment)
                    ->with('vtiInternship',$vtiInternship)
                    ->with('committedHR',$committedHR)
                    ->with('committedEq',$committedEq)
                    ->with('reportingSites',$reportingSites)
                    ->with('contractorDetails',$contractorDetails)
                    ->with('workDetails',$workDetails);
    }
    public function postSaveSite(){
        $inputs = Input::except("_token","MonitoringSiteHR","MonitoringSiteEQ");
        $id = $inputs['Id'];
        $inputs['MonitoringDate'] = date('Y-m-d G:i:s');
        $equipmentInputs = Input::get('MonitoringSiteEQ');
        $hrInputs = Input::get("MonitoringSiteHR");

        DB::beginTransaction();
        try{
            foreach($inputs as $key=>$value):
                if($value == ""){
                    $inputs[$key] = NULL;
                }
            endforeach;
            if((bool)$id){
                $inputs['EditedBy'] = Auth::user()->Id;
                $mainId = $id;
                $inputs['EditedOn'] = date('Y-m-d G:i:s');
                DB::table('crpmonitoringsite')->where('Id',$id)->update($inputs);
                MonitoringSiteEquipmentModel::where('CrpMonitoringSiteId',$id)->delete();
                MonitoringSiteHRModel::where('CrpMonitoringSiteId',$id)->delete();
            }else{
                $inputs['Id'] = $this->UUID();
                $mainId = $inputs['Id'];
                $inputs['CreatedBy'] = Auth::user()->Id;
                $inputs['CreatedOn'] = date('Y-m-d G:i:s');
                DB::table('crpmonitoringsite')->insert($inputs);
            }
            if(count($equipmentInputs)>0){
                foreach($equipmentInputs as $key=>$value):
                    foreach($value as $x=>$y):
                        $equipmentInputArray[$x] = $y;
                    endforeach;
                    $equipmentInputArray['Id'] = $this->UUID();
                    $equipmentInputArray['CrpMonitoringSiteId'] = $mainId;
                    MonitoringSiteEquipmentModel::create($equipmentInputArray);
                    $equipmentInputArray = array();
                endforeach;
            }

            if(count($hrInputs)>0){
                foreach($hrInputs as $key=>$value):
                    foreach($value as $x=>$y):
                        $hrInputArray[$x] = $y;
                    endforeach;
                    $hrInputArray['Id'] = $this->UUID();
                    $hrInputArray['CrpMonitoringSiteId'] = $mainId;
                    MonitoringSiteHRModel::create($hrInputArray);
                    $hrInputArray = array();
                endforeach;
            }

        }catch(Exception $e){
            DB::rollBack();
            return Redirect::back()->with('customerrormessage',$e->getMessage());
        }
        DB::commit();
        return Redirect::to("monitoringreport/sitenew")->with('savedsuccessmessage',"<strong>SUCCESS! </strong>Record has been saved!");
    }
    public function getSiteList(){
        $parameters=array();
        $contractorId=Input::get('ContractorId');
        $CDBNo=Input::get('CDBNo');

        $dzongkhagId = Input::get('DzongkhagId');
        $priority = Input::get("Priority");

        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn as Dzongkhag'));
        $priorities = DB::table('cmncontractorclassification')->orderBy('Priority')->get(array('Priority',"Code as Class"));

        $query="select distinct V.Id as MonitoringSiteId,W.NameOfWork,W.ProcuringAgency,T1.Id, V.Year,V.MonitoringStatus,V.MonitoringDate,T1.CDBNo,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,Z.Name as Status,Z.ReferenceNo as StatusReference,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType,B.Code as ClassificationCode,B.Name as ClassificationName from crpmonitoringsite V join viewcontractorstrackrecords W on W.RowId = V.WorkId join crpcontractorfinal T1 on V.CrpContractorFinalId = T1.Id left join cmnlistitem Z on Z.Id = T1.CmnApplicationRegistrationStatusId join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join (crpcontractorworkclassificationfinal A join cmncontractorclassification B on A.CmnApprovedClassificationId=B.Id) on T1.Id=A.CrpContractorFinalId and B.Priority=(select max(Priority) from crpcontractorworkclassificationfinal X join cmncontractorclassification Y on X.CmnApprovedClassificationId=Y.Id where X.CrpContractorFinalId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where 1 and T1.CmnApplicationRegistrationStatusId = ?";
        array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);

        if((bool)$contractorId!=NULL){
            $query.=" and T1.Id=?";
            array_push($parameters,$contractorId);
        }
        if((bool)$CDBNo!=NULL){
            $query.=" and T1.CDBNo=?";
            array_push($parameters,$CDBNo);
        }
        if((bool)$dzongkhagId){
            $query.=" and T1.CmnDzongkhagId = ?";
            array_push($parameters,$dzongkhagId);
        }
        if((bool)$priority){
            $query.=" and B.Priority = ?";
            array_push($parameters,$priority);
        }

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $contractorLists=DB::select($query." order by T1.CDBNo,V.Year desc".$limitOffsetAppend,$parameters);
        return View::make('crps.monitoringsiteindex')
            ->with('pageTitle','List of Contractors')
            ->with('route',"monitoringreport.sitenew")
            ->with('CDBNo',$CDBNo)
            ->with('isList',1)
            ->with('dzongkhags',$dzongkhags)
            ->with('priorities',$priorities)
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('contractorLists',$contractorLists);
    }
    public function getSiteEdit($id){
        $committedHR = array();
        $committedEq = array();
        $routeSegment = Request::segment(2);
        $reportingSites = DB::table('crpmonitoringsite')->where("Id",$id)->get();
        $vtiEmployment = DB::table('etlbidevalutionparameters')->where('ReferenceNo','=',1002)->get(array('Name','Points'));
        $vtiInternship = DB::table('etlbidevalutionparameters')->where('ReferenceNo','=',1003)->get(array('Name','Points'));
        if(count($reportingSites)==0){
            App::abort(404);
        }
        $contractorId = $reportingSites[0]->CrpContractorFinalId;
        $type = $reportingSites[0]->Type;
        $contractorDetails = DB::table('crpcontractorfinal')->where('Id',$contractorId)->get(array("CDBNo",'NameOfFirm','Id'));
        if(count($contractorDetails)==0){
            App::abort(404);
        }
        if(($type == 1) || ($type == 2)){
            $table = "crpbiddingform";
        }else{
            $table = "etltender";
            $committedHR = DB::table("crpmonitoringsitehumanresource as T1")
                                ->leftJoin('cmnlistitem as T2','T2.Id','=','T1.CmnDesignationId')
                                ->where('T1.CrpMonitoringSiteId',$id)->get(array('T1.CIDNo','T1.Name','T1.Qualification','T1.CmnDesignationId','T1.Checked','T2.Name as Designation'));
            $committedEq = DB::table("crpmonitoringsiteequipment as T1")
                            ->leftJoin('cmnequipment as T2','T2.Id','=','T1.CmnEquipmentId')
                            ->where("T1.CrpMonitoringSiteId",$id)->get(array('T1.CrpMonitoringSiteId','T1.CmnEquipmentId','T2.Name as Equipment','T1.RegistrationNo','T1.Quantity','T1.Checked'));
        }

        $workDetails = DB::table("$table as T1")
            ->leftJoin('cmnprocuringagency as T2','T2.Id','=','T1.CmnProcuringAgencyId')
            ->where('T1.Id',$reportingSites[0]->WorkId)->get(array('T2.Name as Agency','T1.NameOfWork','T1.Id'));
        if(count($workDetails)==0){
            App::abort(404);
        }
        if($routeSegment == "siteview"){
            $data['printTitle'] ="Monitoring Report (Site)";
            $data['reportingSites']=$reportingSites;
            $data['contractorDetails']=$contractorDetails;
            $data['workDetails']=$workDetails;
            $data['type'] = $type;
            $data['committedHR'] = $committedHR;
            $data['committedEq'] = $committedEq;
            $pdf = App::make('dompdf');
            $pdf->loadView("printpages.contractormonitoringreportsitedetails",$data)->setPaper('a4')->setOrientation('potrait');
            return $pdf->stream();
        }
        return View::make('crps.contractormonitoringreportsites')
            ->with('type',$type)
            ->with('edit',true)
            ->with('committedHR',$committedHR)
            ->with('committedEq',$committedEq)
            ->with('commitmentDetails',array())
            ->with('vtiEmployment',$vtiEmployment)
            ->with('vtiInternship',$vtiInternship)
            ->with('reportingSites',$reportingSites)
            ->with('contractorDetails',$contractorDetails)
            ->with('workDetails',$workDetails);
    }
    public function getOfficeDelete($id){
        DB::table('crpmonitoringoffice')->where('Id',$id)->delete();
        return Redirect::back()->with('savedsuccessmessage','Record has been deleted');
    }
}

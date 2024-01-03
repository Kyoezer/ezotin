<?php

/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 2/12/2016
 * Time: 10:30 AM
 */
class ReportContractorRegistrationHistory extends ReportController
{
    public function getIndex(){
        $parameters = array();
        $parametersForPrint = array();
        $contractorId=Input::get('ContractorId');
        $contractor = Input::get('Contractor');
        $CDBNo=Input::get('CDBNo');
        $limit=Input::get('Limit');
        $tradeLicenseNo = Input::get('TradeLicenseNo');
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');
        if((bool)$limit){
            if($limit != 'All'){
                $limit=" limit $limit";
            }else{
                $limit="";
            }
        }else{
            $limit.=" limit 20";
        }
        $query="select distinct T1.Id,T1.CDBNo,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType,B.Code as ClassificationCode,B.Name as ClassificationName from crpcontractorfinal T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join (crpcontractorworkclassificationfinal A join cmncontractorclassification B on A.CmnApprovedClassificationId=B.Id) on T1.Id=A.CrpContractorFinalId and B.Priority=(select max(Priority) from crpcontractorworkclassificationfinal X join cmncontractorclassification Y on X.CmnApprovedClassificationId=Y.Id where X.CrpContractorFinalId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where 1";
        if((bool)$contractorId!=NULL || (bool)$CDBNo!=NULL){
            if((bool)$contractorId!=NULL){
                $parametersForPrint['Name of Firm'] = $contractor;
                $query.=" and T1.Id=?";
                array_push($parameters,$contractorId);
            }
            if((bool)$CDBNo!=NULL){
                $parametersForPrint['CDB No.'] = $CDBNo;
                $query.=" and T1.CDBNo=?";
                array_push($parameters,$CDBNo);
            }
        }
        if((bool)$tradeLicenseNo){
            $parametersForPrint['Trade License No.'] = $tradeLicenseNo;
            $query.=" and T1.TradeLicenseNo = ?";
            array_push($parameters,$tradeLicenseNo);
        }
        if((bool)$fromDate){
            $parametersForPrint['From Date'] = $fromDate;
            $query.=" and T1.RegistrationApprovedDate >= ?";
            array_push($parameters,$this->convertDate($fromDate));
        }
        if((bool)$toDate){
            $parametersForPrint['To Date'] = $toDate;
            $query.=" and T1.RegistrationApprovedDate <= ?";
            array_push($parameters,$this->convertDate($toDate));
        }

        $contractorLists=DB::select($query." order by NameOfFirm".$limit,$parameters);
        $status=CmnListItemModel::registrationStatus()->get(array('Id','Name'));

        return View::make('crps.contractorregistrationhistorylist')
            ->with('CDBNo',$CDBNo)
            ->with('parametersForPrint',$parametersForPrint)
            ->with('status',$status)
            ->with('contractorLists',$contractorLists);

    }

    public function getDetails($id = NULL){
        $contractor = array();
        $history = array();
        if((bool)$id){
            $registrationDetails = DB::table('crpcontractorcommentsadverserecord as T1')
                                    ->join('sysuser as T2','T2.Id','=','T1.CreatedBy')
                                    ->join('cmnlistitem as T3','T3.Id','=','T1.CmnApplicationRegistrationStatusId')
                                    ->where('CrpContractorFinalId',$id)
                                    ->orderBy('T1.CreatedOn','DESC')
                                    ->get(array('T1.Remarks','T1.CreatedOn','T2.FullName','T3.Name as Status'));
            $contractor=ContractorFinalModel::contractorHardList($id)->get(array('Id','CDBNo','NameOfFirm'));
        }else{
            App::abort('404');
        }


        return View::make('crps.contractorregistrationhistory')
            ->with('registrationDetails',$registrationDetails)
            ->with('contractor',$contractor)
            ->with('history',$history);
    }
}
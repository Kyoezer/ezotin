<?php

class ReportListOfRevokedCertifiedBuilder extends ReportController
{
    public function getIndex(){
        $parametersForPrint = array();
        $certifiedbuilderListAll=CertifiedbuilderFinalModel::certifiedBuilderHardListAll()->get(array('Id','NameOfFirm'));
 
        $dzongkhagId = DzongkhagModel::dzongkhag()->orderBy('NameEn')->get(array('Id','NameEn'));
        $parameters=array();
        $cdbNo=Input::get('CdbRegistrationNo');
        $certifiedbuilderId=Input::get('CertifiedBuilderId');
        $dzongkhags = Input::get('CmnDzongkhagId');
        
        $contractorLists = array();
        $limit=Input::get('Limit');
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');

        $query = "select CDBNo, NameOfFirm, Address,Status, ExpiryDate, Dzongkhag, Country, TelephoneNo, MobileNo from listofcertifiedbuilder Where StatusId = '463c2d4c-adbd-11e4-99d7-080027dcfac6'  or StatusId = 'f89a6f55-b1dd-aac4-89f3-080027dcfac6'";
      

        if((bool)$cdbNo){
            $parametersForPrint['CDB No.'] = $cdbNo;
            $query.=" and CDBNo=?";
            array_push($parameters,$cdbNo);
        }
        if((bool)$certifiedbuilderId){
            $parametersForPrint['Certifiedbuiilder'] = $certifiedbuiilderId;
            $certifiedbuiilderId="%$certifiedbuiilderId%";
            $query.=" and NameOfFirm like ?";
            array_push($parameters,$certifiedbuiilderId);
        }
        if((bool)$dzongkhags){
            $parametersForPrint['Dzongkhag'] = $dzongkhags;
            $query.=" and Dzongkhag=?";
            array_push($parameters,$dzongkhags);
        }
        
        if((bool)$fromDate){
            $parametersForPrint['From Date'] = $fromDate;
            $query.=" and ApprovedDate >= ?";
            array_push($parameters,date_format(date_create($fromDate),'Y-m-d'));
        }
        if((bool)$toDate){
            $parametersForPrint['To Date'] = $toDate;
            $query.=" and ApprovedDate <= ?";
            array_push($parameters,date_format(date_create($toDate),'Y-m-d'));
        }

        $query.=" order by CDBNo,NameOfFirm";
        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/
        $certifiedbuilderLists=DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('List of Revoked/Suspended/Debarred Certifiedbuilder', function($excel) use ($certifiedbuilderLists,$parametersForPrint) {
                    $excel->sheet('Sheet 1', function($sheet) use ($certifiedbuilderLists,$parametersForPrint) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.listofcertifiedbuilderrevoked')
                            ->with('parametersForPrint',$parametersForPrint)
                            ->with('certifiedbuilderLists',$certifiedbuilderLists);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofcertifiedbuilderrevoked')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('parametersForPrint',$parametersForPrint)
            ->with('certifiedbuilderListAll',$certifiedbuilderListAll)
            ->with('certifiedbuilderId',$certifiedbuilderId)
            ->with('certifiedbuilderLists',$certifiedbuilderLists)
            ->with('dzongkhagId',$dzongkhagId);
       
    }
}
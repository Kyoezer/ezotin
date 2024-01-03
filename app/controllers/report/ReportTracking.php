<?php

class ReportTracking extends ReportController{
    public function getIndex(){
        $workId = Input::get('WorkId');
        $equipmentDetails = array();
        $hrDetails = array();
        $engagedEquipmentDetails = array();
        $engagedHRDetails = array();
        $evaluationDetails = array();
        $trackBidDetails = array();
        $auditTrailDetails = array();
        if((bool)$workId){
            $tenderId = DB::table('etltender as T1')
                            ->join('cmnprocuringagency as T2','T2.Id','=','T1.CmnProcuringAgencyId')
                            ->whereRaw("case when T1.migratedworkid is not null then T1.migratedworkid = ? else concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) = ? end",array(trim($workId),trim($workId)))
                            ->pluck('T1.Id');
            $equipmentDetails = DB::table('etltrackequipment as T1')
                                    ->where('T1.WorkId',$workId)
                                    ->orderBy('T1.OperationTime','ASC')
                                    ->orderBy('T1.Operation')
                                    ->get(array('T1.CDBNo','T1.Tier','T1.Equipment','T1.RegistrationNo','T1.OwnedOrHired','T1.Points','T1.Operation','T1.User as PerformedBy','T1.OperationTime'));
            $hrDetails = DB::table('etltrackhumanresource as T1')
                            ->where('T1.WorkId',$workId)
                            ->orderBy('T1.OperationTime','ASC')
                            ->orderBy('T1.Operation')
                            ->get(array('T1.CDBNo','T1.CIDNo','T1.Tier','T1.Name','T1.HRName','T1.Qualification','T1.Points','T1.Operation','T1.User as PerformedBy','T1.OperationTime'));

            $engagedEquipmentDetails = DB::table('etltrackequipmentcheck as T1')
                                        ->join('sysuser as T2','T2.Id','=','T1.SysUserId')
                                        ->where('T1.WorkId',$workId)
                                        ->orderBy('OperationTime')
                                        ->get(array('T1.RegistrationNo','T1.CDBNo','T1.Operation','T2.FullName as PerformedBy','T1.OperationTime'));

            $engagedHRDetails = DB::table('etltrackhrcheck as T1')
                                    ->join('sysuser as T2','T2.Id','=','T1.SysUserId')
                                    ->where('T1.WorkId',$workId)
                                    ->orderBy('OperationTime')
                                    ->get(array('T1.CIDNo','T1.CDBNo','T1.Operation','T2.FullName as PerformedBy','T1.OperationTime'));
            $evaluationDetails = DB::table('etlevaluationtrack as T1')
                                        ->where('T1.WorkId',$workId)
                                        ->orderBy('OperationTime')
                                        ->get(array('T1.Operation','User as PerformedBy','T1.OperationTime'));
            $trackBidDetails = DB::table('etltrackbiddetails as T1')
                                ->where('T1.WorkId',$workId)
                                ->orderBy('OperationTime')
                                ->get(array('T1.CDBNo','T1.FinancialBidQuoted','T1.CreditLine','T1.PerformedBy','T1.OperationTime'));
            $auditTrailDetails = DB::table('sysaudittrail')
                                    ->where('RowId',$tenderId)
                                    ->where('Action',DB::raw("'U'"))
                                    ->get(array('ColumnName','OldValue','NewValue','ActionDate'));
        }
        return View::make('report.trackingreport')
                ->with('workId',$workId)
                ->with('trackBidDetails',$trackBidDetails)
                ->with('equipmentDetails',$equipmentDetails)
                ->with('hrDetails',$hrDetails)
                ->with('engagedEquipmentDetails',$engagedEquipmentDetails)
                ->with('engagedHRDetails',$engagedHRDetails)
                ->with('auditTrailDetails',$auditTrailDetails)
                ->with('evaluationDetails',$evaluationDetails);
    }
}
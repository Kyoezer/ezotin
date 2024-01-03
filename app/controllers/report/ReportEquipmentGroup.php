<?php

class ReportEquipmentGroup extends ReportController{
    public function getIndex(){
        $query = "select case IsRegistered when 1 then 'Registered' else 'Not Registered' end as Type, Name, sum(Quantity) as Quantity from viewequipmentgroup where 1";

        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('NameEn'));
        $dzongkhag = Input::get('Dzongkhag');
        $type = Input::get('Type');
        $parameters = array();

        if((bool)$dzongkhag){
            $query.=" and Dzongkhag = ?";
            array_push($parameters,(string)$dzongkhag);
        }else{
            $dzongkhag = "All";
        }
        if((bool)$type){
            $type = ($type == 1) ? 1:0;
            $isRegistered = ($type == 1)?'Registered':'Not Registered';
            $query.=" and IsRegistered = ?";
            array_push($parameters,$type);
        }else{
            $type = "All";
            $isRegistered = "All";
        }
        $equipmentGroups = DB::select("$query group by Name order by Type asc",$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('Equipment Summary', function($excel) use ($equipmentGroups,$dzongkhag,$isRegistered) {

                    $excel->sheet('Sheet 1', function($sheet) use ($equipmentGroups,$dzongkhag,$isRegistered) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.equipmentgroups')
                            ->with('dzongkhag',$dzongkhag)
                            ->with('isRegistered',$isRegistered)
                            ->with('equipmentGroups',$equipmentGroups);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.equipmentgroups')
                        ->with('dzongkhags',$dzongkhags)
                        ->with('equipmentGroups',$equipmentGroups);
    }
}
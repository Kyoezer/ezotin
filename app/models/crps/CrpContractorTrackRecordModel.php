<?php
class CrpContractorTrackRecordModel extends BaseModel{
	protected $table="viewcontractorstrackrecords";
	public function scopeTrackRecord($query,$contractorId){
		return $query->where('CrpContractorFinalId',$contractorId)
						->orderBy(DB::raw("case ReferenceNo when 3003 then year(WorkCompletionDate) else year(WorkStartDate) end"))
					 	->orderBy('ProcuringAgency');

	}
}
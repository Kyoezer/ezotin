<?php
class ConsultantFinalModel extends BaseModel{
	protected $table="crpconsultantfinal";
	protected $fillable=array('Id','CDBNo',"InitialDate",'CmnOwnershipTypeId','NameOfFirm','TradeLicenseNo','TPN', 'CmnRegisteredDzongkhagId','RegisteredAddress','Village','Gewog','Address','CmnCountryId','CmnDzongkhagId','Email','TelephoneNo','MobileNo','FaxNo','CmnApplicationRegistrationStatusId','BlacklistedRemarks','BlacklistedDate','DeRegisteredDate','DeregisteredRemarks','ReRegistrationDate','ReRegistrationRemarks','RegistrationApprovedDate','RegistrationExpiryDate','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnOwnershipTypeId'=>'required',
        'NameOfFirm'=>'required',
        'Address'=>'required',
        'CmnCountryId'=>'required',
        'CmnDzongkhagId'=>'required',
        'Email'=>'required|email',
        'TelephoneNo'=>'required',
        'MobileNo'=>'required',
    );
    protected $messages=array(
        'CmnOwnershipTypeId.required'=>'Ownership Type field is required',
        'NameOfFirm.required'=>'Proposed Name field is required',
        'Address.required'=>'Address field is required',
        'CmnCountryId.required'=>'Country field is required',
        'CmnDzongkhagId.required'=>'Dzongkhag field is required',
        'Email.required'=>'Email field is required',
        'Email.email'=>'Invalid Email format',
        'TelephoneNo.required'=>'Telephone No. field is required',
        'MobileNo.required'=>'Mobile No. field is required',
    );
	public function scopeConsultantIdAfterAuth($query,$userid){
	    return $query->where('SysUserId',$userid);
	}
    public function scopeConsultantHardList($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeConsultant($query,$reference){
        return $query->take(1)->join('cmncountry as T1','crpconsultantfinal.CmnCountryId','=','T1.Id')
                                ->join('cmnlistitem as T3','crpconsultantfinal.CmnApplicationRegistrationStatusId','=','T3.Id')
                                ->join('cmnlistitem as T4','crpconsultantfinal.CmnOwnershipTypeId','=','T4.Id')
                                ->leftJoin('cmndzongkhag as T2','crpconsultantfinal.CmnDzongkhagId','=','T2.Id')
                                ->leftJoin('cmndzongkhag as T5','crpconsultantfinal.CmnRegisteredDzongkhagId','=','T5.Id')
                                ->where('crpconsultantfinal.Id','=',$reference);
    }
    public function scopeConsultantHardListAll($query){
        return $query->orderBy('NameOfFirm');
    }
}
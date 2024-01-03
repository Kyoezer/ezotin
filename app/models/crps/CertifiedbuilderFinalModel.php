<?php
class CertifiedbuilderFinalModel extends BaseModel{
	protected $table="crpcertifiedbuilderfinal";
	protected $fillable=array('Id','ApplicationDate','InitialDate','TradeLicenseNo','CmnOwnershipTypeId','NameOfFirm','CDBNo','TPN', 'CmnRegisteredDzongkhagId','RegisteredAddress','Address','CmnCountryId','CmnDzongkhagId','Gewog','Village','Email','MobileNo','TelephoneNo','CmnApplicationRegistrationStatusId','RegistrationApprovedDate','RegistrationExpiryDate','BlacklistedRemarks','BlacklistedDate','DeRegisteredDate','DeregisteredRemarks','ReRegistrationDate','ReRegistrationRemarks','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnOwnershipTypeId'=>'required',
        'NameOfFirm'=>'required',
        'Name'=>'required',
        'Address'=>'required',
        'CmnCountryId'=>'required',
        'CmnDzongkhagId'=>'required',
        'Email'=>'required|email',
        'MobileNo'=>'required',
        'Gewog'=>'required',
        'Village'=>'required',
    );
    protected $messages=array(
        
        'CmnOwnershipTypeId.required'=>'Ownership Type field is required',
        'NameOfFirm.required'=>'Proposed Name field is required',
        'Email.required'=>'Email field is required',
        'Email.email'=>'Invalid email format for email field',
        'Address.required'=>'Correspondence Address field is required',
        'CmnCountryId.required'=>'Country field is required',
        'MobileNo.required'=>'Mobile No. field is required',
        'CmnDzongkhagId.required'=>'Dzongkhag field is required',
        'Gewog.required'=>'Gewog field is required',
        'Village.required'=>'Village field is required',
    );


    public function scopeBiddingFormCertifiedBuilderContractBidders($query,$reference){
        return $query->join('crpcertifiedbuilderfinal as T1','cbbiddingformdetail.CrpCertifiedBuilderFinalId','=','T1.Id')
        			->where('cbbiddingformdetail.CrpBiddingFormId',$reference)
        			->orderBy('T1.NameOfFirm');
    }

    public function scopeCertifiedBuilder($query,$reference){
                     $query->join('cmnlistitem as T4','crpcertifiedbuilderfinal.CmnOwnershipTypeId','=','T4.Id')
                     ->leftJoin('cmndzongkhag as T2','T2.Id','=','crpcertifiedbuilderfinal.CmnDzongkhagId')
                     ->leftJoin('cmncountry as T8','crpcertifiedbuilderfinal.CmnCountryId','=','T8.Id')
                     ->leftJoin('cmnlistitem as T3','T3.Id','=','crpcertifiedbuilderfinal.CmnApplicationRegistrationStatusId')
                     ->leftJoin('cmndzongkhag as T5','crpcertifiedbuilderfinal.CmnRegisteredDzongkhagId','=','T5.Id')
                    ->where('crpcertifiedbuilderfinal.Id',$reference);
    }
    public function scopeCertifiedBuilderHardList($query,$reference){
        return $query->where('Id',$reference);
    }

    
     public function scopeCertifiedBuilderIdAfterAuth($query,$userid){
        return $query->where('SysUserId',$userid);
    }
    public function scopecertifiedBuilderHardListAll($query){
        return $query->orderBy('NameOfFirm');
    }

}
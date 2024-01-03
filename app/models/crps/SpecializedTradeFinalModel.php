<?php
class SpecializedTradeFinalModel extends BaseModel{
	protected $table="crpspecializedtradefinal";
	protected $fillable=array('Id','ApplicationDate','InitialDate','TradeLicenseNo','CmnOwnershipTypeId','NameOfFirm','SPNo','CIDNo','TPN','CmnSalutationId','Name','CmnDzongkhagId','Gewog','Village','Email','MobileNo','TelephoneNo','EmployerName','EmployerAddress','RegistrationStatus','CmnApplicationRegistrationStatusId','RegistrationApprovedDate','RegistrationExpiryDate','BlacklistedRemarks','BlacklistedDate','DeRegisteredDate','DeregisteredRemarks','ReRegistrationDate','ReRegistrationRemarks','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CIDNo'=>'required',
        'CmnSalutationId'=>'required',
        'Name'=>'required',
        'CmnDzongkhagId'=>'required',
        'Email'=>'required|email',
        'MobileNo'=>'required',
        'Gewog'=>'required',
        'Village'=>'required',
    );
    protected $messages=array(
        'CIDNo.required'=>'CID No/Work Permit No. field is required',
        'CmnSalutationId.required'=>'Salutation  field is required',
        'Name.required'=>'Name field is required',
        'Email.required'=>'Email field is required',
        'Email.email'=>'Invalid email format for email field',
        'MobileNo.required'=>'Mobile No. field is required',
        'CmnDzongkhagId.required'=>'Dzongkhag field is required',
        'Gewog.required'=>'Gewog field is required',
        'Village.required'=>'Village field is required',
    );
    public function scopeSpecializedTrade($query,$reference){
                     $query->join('cmnlistitem as T1','T1.Id','=','crpspecializedtradefinal.CmnSalutationId')
                     ->leftJoin('cmndzongkhag as T2','T2.Id','=','crpspecializedtradefinal.CmnDzongkhagId')
                     ->leftJoin('cmnlistitem as T3','T3.Id','=','crpspecializedtradefinal.CmnApplicationRegistrationStatusId')
                    ->where('crpspecializedtradefinal.Id',$reference);
    }
    public function scopeSpecializedTradeHardList($query,$reference){
        return $query->where('Id',$reference);
    }
     public function scopeSpecializedTradeIdAfterAuth($query,$userid){
        return $query->where('SysUserId',$userid);
    }
    public function scopeSpecializedTradeHardListAll($query){
        return $query->orderBy('Name');
    }

}
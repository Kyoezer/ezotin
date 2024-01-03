<?php
class SysEmailAndSmsModel extends BaseModel{
    protected $table="sysemailandsms";
    protected $fillable=array('Id','MessageAs','MessageFor','SysUserId','CrpContractorClassificationId','Date','Message','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'MessageAs'=>'required|numeric',
        'MessageFor'=>'required|numeric',
        'Date'=>'required',
        'Message'=>'required',
    );
    protected $messages=array(
        'MessageAs.required'=>'Message As field is required',
        'MessageAs.numeric'=>'Message As field should be a number. You are not allowed to edit the page source. :) Caught you.',
        'MessageFor.required'=>'Message For field is required',
        'MessageFor.numeric'=>'Message For field should be a number. You are not allowed to edit the page source. :) Caught you.',
        'Date.required'=>'Date field is required',
        'Message.required'=>'Message field is required',
    );
}
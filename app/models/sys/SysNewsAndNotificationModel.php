<?php
class SysNewsAndNotificationModel extends BaseModel{
    protected $table="sysnewsandnotification";
    protected $fillable=array('Id','MessageFor','DisplayIn','CmnProcuringAgencyId','EtoolCinetWorkId','Date','Message','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'MessageFor'=>'required|numeric',
        'DisplayIn'=>'required|numeric',
        'Date'=>'required',
        'Message'=>'required',
    );
    protected $messages=array(
        'MessageFor.required'=>'Message For field is required',
        'MessageFor.numeric'=>'Message For field should be a number. You are not allowed to edit the page source. :) Caught you.',
        'DisplayIn.required'=>' Display In field is required',
        'DisplayIn.numeric'=>' Display In field should be a number. You are not allowed to edit the page source. :) Caught you.',
        'Date.required'=>'Date field is required',
        'Message.required'=>'Message field is required',
    );
}
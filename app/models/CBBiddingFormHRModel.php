<?php

/**
 * Created by PhpStorm.
 * User: SWM
 * Date: 3/29/2017
 * Time: 11:21 AM
 */
class CBBiddingFormHRModel extends BaseModel
{
    protected $table = "cbbidhumanresource";
    protected $fillable = array('Id','CrpBiddingFormId','CIDNo','Name','CmnDesignationId','CmnQualificationId','CreatedBy','CreatedOn','EditedBy','EditedOn');
}
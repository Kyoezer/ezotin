<?php

/**
 * Created by PhpStorm.
 * User: SWM
 * Date: 3/29/2017
 * Time: 11:21 AM
 */
class CBBiddingFormEquipmentModel extends BaseModel
{
    protected $table = "cbbidequipment";
    protected $fillable = array('Id','CrpBiddingFormId','CmnEquipmentId','RegistrationNo','OwnedOrHired','CreatedBy','CreatedOn','EditedBy','EditedOn');
}
<?php

/**
 * Created by PhpStorm.
 * User: SWM
 * Date: 3/29/2017
 * Time: 11:21 AM
 */
class BiddingFormEquipmentModel extends BaseModel
{
    protected $table = "cinetbidequipment";
    protected $fillable = array('Id','CrpBiddingFormId','CmnEquipmentId','RegistrationNo','OwnedOrHired','CreatedBy','CreatedOn','EditedBy','EditedOn');
}
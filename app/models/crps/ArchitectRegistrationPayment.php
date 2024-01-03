<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 7/4/2016
 * Time: 11:09 AM
 */
class ArchitectRegistrationPayment extends BaseModel
{
    protected $table = "crparchitectregistrationpayment";
    protected $fillable = array("Id","CrpArchitectFinalId","Amount","CreatedBy","EditedBy");
}
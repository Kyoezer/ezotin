<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 7/2/2016
 * Time: 7:09 PM
 */
class ConsultantRegistrationPayment extends BaseModel
{
    protected $table = 'crpconsultantregistrationpayment';
    protected $fillable = array('Id','CrpConsultantFinalId','CmnServiceCategoryId','AppliedService','VerifiedService','ApprovedService','ServiceXFee','Amount','CreatedBy','EditedBy');
}
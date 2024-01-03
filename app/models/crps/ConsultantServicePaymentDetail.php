<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 7/2/2016
 * Time: 7:09 PM
 */
class ConsultantServicePaymentDetail extends BaseModel
{
    protected $table = 'crpconsultantservicepaymentdetail';
    protected $fillable = array('Id','CrpConsultantServicePaymentId','CmnServiceCategoryId','AppliedService','VerifiedService','ApprovedService','ServiceXFee','Amount','CreatedBy','EditedBy');
}
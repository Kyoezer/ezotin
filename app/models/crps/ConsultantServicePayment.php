<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 7/2/2016
 * Time: 6:58 PM
 */
class ConsultantServicePayment extends BaseModel
{
    protected $table = 'crpconsultantservicepayment';
    protected $fillable = array('Id','CrpConsultantId','CmnServiceTypeId','NoOfDaysLate','NoOfDaysAfterGracePeriod','PenaltyPerDay','TotalAmount','PaymentAmount','WaiveOffLateFee','NewLateFeeAmount','CreatedBy','EditedBy');
}
<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 7/2/2016
 * Time: 6:58 PM
 */
class ArchitectServicePayment extends BaseModel
{
    protected $table = 'crparchitectservicepayment';
    protected $fillable = array('Id','CrpArchitectId','CmnServiceTypeId','NoOfDaysLate','NoOfDaysAfterGracePeriod','PenaltyPerDay','TotalAmount','PaymentAmount','WaiveOffLateFee','NewLateFeeAmount','CreatedBy','EditedBy');
}
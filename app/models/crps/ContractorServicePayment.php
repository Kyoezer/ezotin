<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 7/2/2016
 * Time: 6:58 PM
 */
class ContractorServicePayment extends BaseModel
{
    protected $table = 'crpcontractorservicepayment';
    protected $fillable = array('Id','CrpContractorId','CmnServiceTypeId','NoOfDaysLate','NoOfDaysAfterGracePeriod','PenaltyPerDay','TotalAmount','PaymentAmount','WaiveOffLateFee','NewLateFeeAmount','CreatedBy','EditedBy');
}
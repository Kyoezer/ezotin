<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 7/2/2016
 * Time: 7:09 PM
 */
class ContractorServicePaymentDetail extends BaseModel
{
    protected $table = 'crpcontractorservicepaymentdetail';
    protected $fillable = array('Id','CrpContractorServicePaymentId','CmnCategoryId','CmnExistingClassificationId','CmnAppliedClassificationId','CmnVerifiedClassificationId','CmnApprovedClassificationId','Amount','CreatedBy','EditedBy');
}
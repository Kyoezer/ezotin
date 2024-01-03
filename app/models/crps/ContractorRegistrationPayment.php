<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 7/2/2016
 * Time: 9:14 PM
 */
class ContractorRegistrationPayment extends BaseModel
{
    protected $table = 'crpcontractorregistrationpayment';
    protected $fillable = array('Id', 'CrpContractorFinalId', 'CmnCategoryId', 'CmnAppliedClassificationId', 'AppliedAmount', 'CmnVerifiedClassificationId', 'VerifiedAmount', 'CmnApprovedClassificationId', 'ApprovedAmount', 'CreatedBy', 'EditedBy');
}
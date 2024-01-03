<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 5/24/2016
 * Time: 4:05 PM
 */
class crpfeestructure extends BaseModel
{
    protected $table = 'crpservicefeestructure';
    protected $fillable = array(
        'Id','NewRegistrationFee','FirstRenewalFee','SecondRenewalFee','OwnershipChangeFee','LocationChangeFee','PenaltyLateFee','PenaltyLostFee','RegistrationValidity'
    );
}
<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 8/8/2016
 * Time: 7:54 PM
 */
class JournalTaskRemarksModel extends BaseModel
{
    protected $table = "webjournaltasklistremark";
    protected $fillable = array(
        "Application_No","Remark_By_JC","Remark_By_editorial","Remark_By_Reviewer","Remark_By_Chief","CreatedOn","CreatedBy"
    );
}
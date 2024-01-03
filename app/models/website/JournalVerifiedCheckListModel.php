<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 8/8/2016
 * Time: 7:54 PM
 */
class JournalVerifiedCheckListModel extends BaseModel
{
    protected $table = "webjournalverifiedchecklist";
    protected $fillable = array(
        "Id","Application_No","Group_Name","Checklist_Question","Checklist_Answer",
        "Subchecklist_Question","Subchecklist_Answer","CreatedOn","CreatedBy"
    );
}
<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 8/8/2016
 * Time: 7:54 PM
 */
class JournalManuscriptRejectedModel extends BaseModel
{
    protected $table = "webjournalrejected";
    protected $fillable = array(
        "File_Name","Name","CreatedBy","EditedBy"
    );
}
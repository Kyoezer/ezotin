<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 8/8/2016
 * Time: 7:54 PM
 */
class JournalManuscriptApprovedModel extends BaseModel
{
    protected $table = "webjournalmanuscript";
    protected $fillable = array(
        "Name","File","Name_of_Title","Abstract","CreatedBy","EditedBy"
    );
}
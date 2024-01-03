<?php

class JournalChecklistGroupModel extends BaseModel
{
    protected $table = "webjournalchecklistgroup";
    protected $fillable = array(
        "Title","CreatedBy","EditedBy"
    );
}
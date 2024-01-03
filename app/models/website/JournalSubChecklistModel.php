<?php

class JournalSubChecklistModel extends BaseModel
{
    protected $table = "webjournalsubchecklist";
    protected $fillable = array(
        "Name","Type","CreatedBy","EditedBy"
    );
}
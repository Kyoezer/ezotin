<?php
class WebAdvertisementsModel extends BaseModel{
	protected $fillable = array('Id', 'Title', 'Content', 'Image', 'Attachment','ShowInMarquee','DisplayOrder');
	protected $table="webadvertisements";
}
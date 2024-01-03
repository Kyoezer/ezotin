<?php
class WebPhotoGalleryAlbumModel extends BaseModel{
	protected $fillable = array('Id', 'AlbumName','AlbumDescription','AlbumImage');
	protected $table="webphotogalleryalbums";

	protected $rules = array(
		'AlbumName' => 'required',
	);
	protected $messages = array(
		'AlbumImage.required' => 'Image is required',
		'AlbumImage.image' => 'File should be an image',
		'AlbumName.required' => 'Album Name is required'
	);
}
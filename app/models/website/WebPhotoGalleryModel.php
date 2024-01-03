<?php
class WebPhotoGalleryModel extends BaseModel{
	protected $table="webphotogallery";
	protected $fillable = array('Id','ImageSource','ImageThumbSource','PhotoGalleryAlbumId','ImageName','ImageDescription','CreatedBy','EditedBy');
	protected $rules = array(
		'ImageName' => 'required',
		'PhotoGalleryAlbumId' => 'required',
	);

	protected $messages = array(
		'ImageName.required' => 'Image Name is requireed',
		'PhotoGalleryAlbumId.required' => 'Gallery is requireed',
		'GalleryImage.image' => 'File should be an image',
	);
}
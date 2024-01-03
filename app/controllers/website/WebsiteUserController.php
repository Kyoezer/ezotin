<?php
class WebsiteUserController extends BaseController{
	public function showLogin(){
		return View::make('website.webuserlogin');
	}

	public function doLogin(){
		return Redirect::to('web/managemenus');
	}
}
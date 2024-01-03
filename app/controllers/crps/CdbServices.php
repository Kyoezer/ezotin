<?php
class CdbServices extends CrpsController{
	public function index(){
		return View::make('crps.servicescontractor');
	}
	public function apply(){
		return View::make('crps.applyservicescontractor');
	}
	public function trackApplication(){
		return View::make('crps.trackapplication')
					->with('pageTitle','Track Your Application');
	}
}
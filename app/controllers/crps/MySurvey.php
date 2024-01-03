<?php
class MySurvey extends CrpsController{
	protected $layout = 'horizontalmenumaster';
	private $surveyId;

	
	public function myCertificate($surveyId){
		$surveyInfo=SurveyFinalModel::survey($surveyId)->get(array('crpsurveyfinal.Name','crpsurveyfinal.CIDNo','crpsurveyfinal.ARNo','crpsurveyfinal.InitialDate as RegistrationApprovedDate','crpsurveyfinal.RegistrationExpiryDate','T2.Name as Salutation'));
		$data['surveyInfo']=$surveyInfo;
		$pdf = App::make('dompdf');
		$pdf->loadView('printpages.surveycertificate',$data)->setPaper('a4')->setOrientation('potrait');
		return $pdf->stream();
	}
	
}
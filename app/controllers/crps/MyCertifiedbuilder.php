<?php
class MyCertifiedbuilder extends CrpsController{
	protected $layout = 'horizontalmenumaster'; 
	private $certifiedbuilderId;
	
	public function myCertificate($certifiedbuilderId){
		$nonBhutanese = false;
		$certifiedbuilderName=CertifiedbuilderHumanResourceFinalModel::where('CrpCertifiedBuilderFinalId',$certifiedbuilderId)->where('IsPartnerOrOwner',1)->where(DB::raw('coalesce(ShowInCertificate,0)'),1)->limit(1)->pluck('Name');
		$certifiedbuilderCIDNo=CertifiedbuilderHumanResourceFinalModel::where('CrpCertifiedBuilderFinalId',$certifiedbuilderId)->where('IsPartnerOrOwner',1)->where(DB::raw('coalesce(ShowInCertificate,0)'),1)->limit(1)->pluck('CIDNo');
		$info=CertifiedbuilderFinalModel::certifiedbuilder($certifiedbuilderId)->get(array('crpcertifiedbuilderfinal.NameOfFirm','T8.Id as CountryId','T8.Name as Country','crpcertifiedbuilderfinal.','crpcertifiedbuilderfinal.RegistrationApprovedDate','crpcertifiedbuilderfinal.RegistrationExpiryDate','crpcertifiedbuilderfinal.ApplicationDate','T2.NameEn as Dzongkhag'));
		$initialDate = DB::table('crpcertifiedbuilder')->where('Id',$certifiedbuilderId)->pluck('ApplicationDate');
		$certifiedbuilderCountryId = $info[0]->CountryId;
		if($certifiedbuilderCountryId != CONST_COUNTRY_BHUTAN){
			$nonBhutanese = true;
		}
		$data['certifiedbuilderName']=$certifiedbuilderName;
		$data['nonBhutanese'] = $nonBhutanese;
		$data['certifiedbuilderCIDNo']=$certifiedbuilderCIDNo;
		$data['InitialDate']=$initialDate;
	 
		$data['info']=$info;
		$pdf = App::make('dompdf');
		$pdf->loadView('printpages.certifiedbuildercertificate',$data)->setPaper('a4')->setOrientation('potrait');
		return $pdf->stream();
	}
}

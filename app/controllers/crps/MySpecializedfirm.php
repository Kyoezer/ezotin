<?php
class MySpecializedfirm extends CrpsController{
	protected $layout = 'horizontalmenumaster'; 
	private $specializedtradeId;
	
	public function myCertificate($specializedtradeId){
		$nonBhutanese = false;
		$specializedtradeName=SpecializedfirmHumanResourceFinalModel::where('CrpSpecializedtradeFinalId',$specializedtradeId)->where('IsPartnerOrOwner',1)->where(DB::raw('coalesce(ShowInCertificate,0)'),1)->limit(1)->pluck('Name');
		$specializedtradeCIDNo=SpecializedfirmHumanResourceFinalModel::where('CrpSpecializedtradeFinalId',$specializedtradeId)->where('IsPartnerOrOwner',1)->where(DB::raw('coalesce(ShowInCertificate,0)'),1)->limit(1)->pluck('CIDNo');
		$info=SpecializedfirmFinalModel::specializedtrade($specializedtradeId)->get(array('crpspecializedfirmfinal.NameOfFirm','T8.Id as CountryId','T8.Name as Country','crpspecializedfirmfinal.SPNo','crpspecializedfirmfinal.RegistrationApprovedDate','crpspecializedfirmfinal.RegistrationExpiryDate','crpspecializedfirmfinal.ApplicationDate','T2.NameEn as Dzongkhag'));
		$initialDate = DB::table('crpspecializedfirm')->where('Id',$specializedtradeId)->pluck('ApplicationDate');
		$specializedtradeCountryId = $info[0]->CountryId;
		if($specializedtradeCountryId != CONST_COUNTRY_BHUTAN){
			$nonBhutanese = true;
		}
		$data['specializedtradeName']=$specializedtradeName;
		$data['nonBhutanese'] = $nonBhutanese;
		$data['specializedtradeCIDNo']=$specializedtradeCIDNo;
	
		$data['InitialDate']=$initialDate;
		$specializedTradeWorkClassifications=DB::select("select distinct T1.Id as CategoryId,T1.Code,T1.Name,T2.CmnApprovedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedfirmworkclassificationfinal T2 on T1.Id=T2.CmnApprovedCategoryId and T2.CrpSpecializedTradeFinalId=? where T1.Code like '%SF%' order by T1.Code,T1.Name",array($specializedtradeId));
		$data['info']=$info;
		$data['specializedTradeWorkClassifications']=$specializedTradeWorkClassifications;
		$pdf = App::make('dompdf');
		$pdf->loadView('printpages.specializedfirmcertificate',$data)->setPaper('a4')->setOrientation('potrait');
		return $pdf->stream();
	}
}

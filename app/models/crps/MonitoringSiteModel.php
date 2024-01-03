<?php
class MonitoringSiteModel extends BaseModel{
	public $table="crpmonitoringsite";
	protected $fillable=array('Id','Year','Type','WorkId','CrpContractorFinalId','MonitoringDate','MonitoringStatus','OfficeSetUp','SignBoard','UseOfLocalMaterials','LocalMaterialsUsed','LocalMaterialsBroughtFrom','FabricationOfLocalMaterials','LocalMaterialsFabricated','OHSFacilitiesPresent','OHSDetail','TotalBhutanese','TotalNonBhutanese','BhutaneseEngineer','NonBhutaneseEngineer','CivilDegreeEngineer','ElectricalDegreeEngineer','CivilDiplomaEngineer','ElectricalDiplomaEngineer','Remarks','CreatedBy','EditedBy','CreatedOn','EditedOn');
}
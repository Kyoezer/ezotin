<?php
/*
Crafted with love and lots of Coffee
Name: Kinley Nidup
Web Name: Zero Cool
email: nidup.kinley@gmail.com
facebook link:https://www.facebook.com/kgyel
*/
Auth::attempting(function($credentials, $remember, $login){
    // Log the attempt or some other such activity
});
Blade::extend(function($value){
    return preg_replace('/(\s*)@(break|continue)(\s*)/', '$1<?php $2; ?>$3', $value);
});
function randomString(){
    $randomKey=substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"),0,5);
    return $randomKey;
}
function convertDateToClientFormat($value){
	if((bool)$value!=NULL || !empty($value)){
		$convertedDateToClient=date('d-m-Y',strtotime($value));
		return $convertedDateToClient;
	}
	return '-';
}
function lastUsedArchitectNo($country = NULL,$architectServiceSectorType=NULL){
    $query = "select MAX(TRIM(REPLACE(REPLACE(REPLACE(REPLACE(ARNo,'BA-',''),'NB-',''),'(P)',''),'(G)',''))) as ARNo from crparchitectfinal";
    $cdbNoQuery = DB::select($query);
    $arNo = $cdbNoQuery[0]->ARNo;
	return $arNo;
}
function lastUsedContractorCDBNo($country=NULL){
	if((bool)$country){
		if($country == "Bhutan"){
			$cdbNo = DB::table('crpcontractorfinal')->where('CDBNo',DB::raw('not like'),'NB%')->max('CDBNo');
		}else{
			$cdbNo = DB::table('crpcontractorfinal')->where('CDBNo',DB::raw('like'),'NB%')->max('CDBNo');
			$cdbNo = str_replace("NB",'',$cdbNo);
		}
	}else{
		$cdbNo = DB::table('crpcontractorfinal')->where('CDBNo',DB::raw('not like'),'NB%')->max('CDBNo');
	}
	return $cdbNo;
}
function lastUsedConsultantCDBNo(){
	$lastCDBNo = ConsultantFinalModel::max('CDBNo');
	if(!(bool)$lastCDBNo){
		$lastCDBNo = 1;
	}
	return $lastCDBNo;
}
function lastUsedEngineerNo(){
	$lastCDBNo = EngineerFinalModel::max('CDBNo');
	if((bool)$lastCDBNo){
		return $lastCDBNo;
	}else{
		return 1;
	}
}
function lastUsedSpecializedTradeNo(){
	return SpecializedTradeFinalModel::max('SPNo');
}
function showDateTimeDuration($value){
	$fromDate = new DateTime($value);
    $currentDate = new DateTime(date('Y-m-d'));
    $noOfDays=$fromDate->diff($currentDate)->days;
    if((int)$noOfDays!=0){
	    if((int)$noOfDays==1){
	    	$noOfDays.=" day ago";
	    }else{
	    	if($noOfDays>15){
	    		return false;
	    	}else{
	    		$noOfDays.=" days ago";
	    	}
		}
	    return $noOfDays;
	}
	return "Today";
}
function contractorFinalId(){
	$userId=Auth::user()->Id;
	return ContractorFinalModel::contractorIdAfterAuth($userId)->pluck('Id');
}
function contractorModelContractorId($contractorId){
	return ContractorModel::contractorHardList($contractorId)->pluck('CrpContractorId');
}
function consultantFinalId(){
	$userId=Auth::user()->Id;
	return ConsultantFinalModel::consultantIdAfterAuth($userId)->pluck('Id');
}
function consultantModelConsultantId($consultantId){
	return ConsultantModel::consultantHardList($consultantId)->pluck('CrpConsultantId');
}

function architectFinalId(){
	$userId=Auth::user()->Id;
	return ArchitectFinalModel::architectIdAfterAuth($userId)->pluck('Id');
}
function architectModelArchitectId($architectId){
	return ArchitectModel::architectHardList($architectId)->pluck('CrpArchitectId');
}
function engineerFinalId(){
	$userId=Auth::user()->Id;
	return EngineerFinalModel::engineerIdAfterAuth($userId)->pluck('Id');
}
function engineerModelEngineerId($engineerId){
	return EngineerModel::engineerHardList($engineerId)->pluck('CrpEngineerId');
}
function specializedTradeFinalId(){
	$userId=Auth::user()->Id;
	return SpecializedTradeFinalModel::specializedTradeIdAfterAuth($userId)->pluck('Id');
}
function specializedTradeModelSpecializedTradeId($specializedTradeId){
	return SpecializedTradeModel::specializedTradeHardList($specializedTradeId)->pluck('CrpSpecializedTradeId');
}
function registrationExpiryDateCalculator($validity){
	$registrationExpiryDate = date('Y-m-d', strtotime('+'.$validity.' years'));
	$registrationExpiryDate=date('d-m-Y',strtotime($registrationExpiryDate));
	return $registrationExpiryDate;
}
function registrationExpiryDateCalculatorRenewal($lastExpiryDate,$validity){
	$registrationExpiryDate = date('Y-m-d', strtotime('+'.$validity.' years',strtotime($lastExpiryDate)));
	$registrationExpiryDate=date('d-m-Y',strtotime($registrationExpiryDate));
	return $registrationExpiryDate;
}
/*Added by Sangay Wangdi */
function convertDateTimeToClientFormat($value){
    if((bool)$value!=NULL || !empty($value)){
        $convertedDateTimeToClient=date('d-m-Y H:i',strtotime($value));
        return $convertedDateTimeToClient;
    }
    return '-';
}
function getWorkId($year,$userProcuringAgencyId,$cinet=NULL){
    $startDate = "$year-01-01";
    $endDate = "$year-12-31";
    $append = "";
    $isCinet = false;
    if((bool)$cinet){
        if($cinet == 1){
            $isCinet = true;
            $append = " and TenderSource = 2";
        }
    }else{
		$append =" and TenderSource = 1";
	}
    $maxWorkId = DB::select("select max(CAST(WorkId as INT)) as WorkId from etltender where DateOfSaleOfTender between ? and ? and CmnProcuringAgencyId = ?$append",array($startDate,$endDate,$userProcuringAgencyId));
	if(!(bool)$maxWorkId[0]->WorkId){
		$newWorkId = "1";

    }else{
		if($isCinet){
			$subString = substr($maxWorkId[0]->WorkId,3,strlen($maxWorkId[0]->WorkId));
			$newWorkId = (int)$subString + 1;
		}else{
			$newWorkId = $maxWorkId[0]->WorkId + 1;
		}

    }
    return $isCinet?'ci-'.$newWorkId:$newWorkId;
}
function pagination($noOfPages,$paginationParameters,$currentPage,$route){
	//CHECK IF CURRENT PAGE integer
	if(!(int)$currentPage > 0){
		$currentPage = 1;
	}
	$htmlString = "";
	if($noOfPages>1):
		$htmlString.="<ul class='pagination'>";
		if((int)$currentPage > 1):
			$paginationParameters['page'] = (int)$currentPage - 1;
			$htmlString.="<li><a href='".route($route,$paginationParameters)."'> &laquo; </a></li>";
		endif;
		if($noOfPages <= 12):
			for($i = 1; $i<=$noOfPages; $i++):
				$paginationParameters['page'] = $i;
				$htmlString.="<li ";
				if($i == (int)$currentPage):
					$htmlString.="class='active'";
				endif;
				$htmlString.="><a href='".route($route,$paginationParameters)."'>".$i."</a></li>";
			endfor;
		else:
			for($pre = 1; $pre<4; $pre++):
				$paginationParameters['page'] = $pre;
				$htmlString.="<li ";
				if($pre == (int)$currentPage):
					$htmlString.="class='active'";
				endif;
				$htmlString.="><a href='".route($route,$paginationParameters)."'>".$pre."</a></li>";
			endfor;
			if((int)$currentPage>5):
				$htmlString.="<li><a href='#'>..</a></li>";
			endif;

			if((int)$currentPage > 2 && (int)$currentPage < ($noOfPages-1)):
				for($mid = (int)$currentPage-1; $mid<((int)$currentPage+3); $mid++):
					if($mid<(int)$noOfPages-2 && $mid>3){
						$paginationParameters['page'] = $mid;
						$htmlString.="<li ";
						if($mid == (int)$currentPage):
							$htmlString.="class='active'";
						endif;
						$htmlString.="><a href='".route($route,$paginationParameters)."'>".$mid."</a></li>";
					}

				endfor;
			endif;

			if((int)$currentPage < (int)$noOfPages-5):
				$htmlString.="<li><a href='#'>..</a></li>";
			endif;
			for($post = (int)$noOfPages-2; $post<=(int)$noOfPages; $post++):
				$paginationParameters['page'] = $post;
				$htmlString.="<li ";
				if($post == (int)$currentPage):
					$htmlString.="class='active'";
				endif;
				$htmlString.="><a href='".route($route,$paginationParameters)."'>".$post."</a></li>";
			endfor;

		endif;
		if((int)$currentPage < ($noOfPages)):
			$paginationParameters['page'] = (int)$currentPage + 1;
			$htmlString.="<li><a href='".route($route,$paginationParameters)."'> &raquo; </a></li>";
		endif;
		$htmlString.="</ul>";
	endif;
	$paginationParameters['page'] = 'All';
	$htmlString.="<br/><a id='all-pages' class='btn btn-sm green' href='".route($route,$paginationParameters)."'><i class='fa fa-lg fa-eye'></i>&nbsp;View All Records</a>";
	echo $htmlString;
}
function checkIfUUID($inputs){
	$re = '/^[0-9A-F]{8}-[0-9A-F]{4}-[0-9A-F]{4}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
	foreach($inputs as $input):
		if(count($input)>0){
			$match = preg_match($re, $input);
			if($match == 0){
				if(preg_match('/(\'(\'\'|[^\'])*\')|(;)|(\b(ALTER|CREATE|SELECT|DELETE|DROP|EXEC(UTE){0,1}|INSERT( +INTO){0,1}|MERGE|WHERE|SELECT|UPDATE|UNION( +ALL){0,1})\b)/i',$y)){
					//DB::table("sysinjectionlog")->insert(array('Id'=>UUID(),'SysUserId'=>Auth::user()->Id,'Time'=>date('Y-m-d G:i:s'),'IP'=>Request::getClientIp(true)));
				}
				return 0;
			}
		}
	endforeach;
	return 1;
}
/*End of Code by Sangay Wangdi */
<div class="col-lg-12 margin-top-20" style="overflow: scroll">
<a href="#" onclick="exportToExcel()" style="margin: 14px;" class="btn btn-success">Export</a>
<script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<script>
    function exportToExcel(){
        $("#contractorhumanresource").table2excel({
            filename: "Export_Monitoring_data.xls"
        });
    }
</script>

@if($reportType == "LOCATION" && $inspectionType == "OFFICE_ESTABLISHMENT" )
<div id="map" style="width:100%;height:600px;margin-bottom:20px"></div>
@endif
    @if($inspectionType == "OFFICE_ESTABLISHMENT")
        @if($reportType == "LOCATION"  )
        <table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
            <thead class="flip-content">
                <tr>
                    <th>Sl.No.</th>
                    <th>Name of Firm</th>
                    <th>CDB No</th>
                    <th>Address</th>
                    <th>TelephoneNo</th>
                    <th>MobileNo</th>
                    <th>Email</th>
                    <th>Locality name</th>
                    <th>Inspection date</th>
                </tr>
            </thead>
            <tbody>
            <?php $count = 1;?>
            @forelse($contractorsList as $data)
                <tr>
                    <td>{{$count++}}</td>
                    <td>{{$data->nameOfFirm}}</td>
                    <td>{{$data->cdbNo}}</td>
                    <td>{{$data->permAddress}}</td>
                    <td>{{$data->telNo}}</td>
                    <td>{{$data->mobNo}}</td>
                    <td>{{$data->email}}</td>
                    <td>{{$data->localityName}}</td>
                    <td>{{$data->createdOn}}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="14" class="font-red text-center">No data to display!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @elseif($reportType == "ACTION_TAKEN" )
        <table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
            <thead class="flip-content">
                <tr>
                    <th>Sl.No.</th>
                    <th>Name of Firm</th>
                    <th>CDB No</th>
                    <th>Address</th>
                    <th>Action Remarks</th>
                    <th>Action Taken</th>
                    <th>Action Date</th>
                </tr>
            </thead>
            <tbody>
            <?php $count = 1;?>
            @forelse($contractorsList as $data)
                <tr>
                    <td>{{$count++}}</td>
                    <td>{{$data->nameOfFirm}}</td>
                    <td>{{$data->cdbNo}}</td>
                    <td>{{$data->RegisteredAddress}}</td>
                    <td>{{$data->Remarks}}</td>
                    <td>{{$data->actionTaken}}</td>
                    <td>{{$data->actionDate}}</td>
                 </tr>
                @empty
                <tr>
                    <td colspan="14" class="font-red text-center">No data to display!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @elseif($reportType == "ACTION_TAKEN_SUMMARY" )
        <table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
            <thead class="flip-content">
                <tr>
                    <th>Sl.No.</th>
                    <th>Action Taken</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
            <?php $count = 1;?>
            @forelse($contractorsList as $data)
                <tr>
                    <td>{{$count++}}</td>
                    <td>{{$data->actionTaken}}</td>
                    <td>{{$data->rowCount}}</td>
                 </tr>
                @empty
                <tr>
                    <td colspan="14" class="font-red text-center">No data to display!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @elseif($reportType == "DETAIL_REPORT" )
        <table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
            <thead class="flip-content">
                <tr>
                    <th>Sl.No.</th>
                    <th>Name of Firm</th>
                    <th>CDB No</th>
                    <th>Address</th>
                    <th>TelephoneNo</th>
                    <th>MobileNo</th>
                    <th>Email</th>
                    <th>Is office establishment</th>
                    <th>Is office signboard</th>
                    <th>Is filing system</th>
                    <th>Locality name</th>
                     
                    <th>Inspection date</th>
                    <th>Representative name</th>
                    <th>Representative contact no</th>
                    <th>Representative cid</th>
                    <th>Is requiment fullfilled</th>
                    <th>Remarks</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody>
            <?php $count = 1;?>
            @forelse($contractorsList as $data)
                <tr>
                    <td>{{$count++}}</td>
                    <td>{{$data->nameOfFirm}}</td>
                    <td>{{$data->cdbNo}}</td>
                    <td>{{$data->permAddress}}</td>
                    <td>{{$data->telNo}}</td>
                    <td>{{$data->mobNo}}</td>
                    <td>{{$data->email}}</td>
                    <td>{{$data->isEstablished}}</td>
                    <td>{{$data->isSignboard}}</td>
                    <td>{{$data->isFileSystem}}</td>
                    <td>{{$data->localityName}}</td>
                    <td>{{$data->createdOn}}</td>
                    <td>{{$data->representativeName}}</td>
                    <td>{{$data->representativeContactNo}}</td>
                    <td>{{$data->representativeCidNo}}</td>
                    <td>{{$data->isRequirementFulfilled}}</td>
                    <td>{{$data->remarks}}</td>
                    <td><button class="btn btn-primary" onclick="viewDetail('Office',{{$data->inspectionId}})">View Details</button></td>
                 </tr>
                @empty
                <tr>
                    <td colspan="14" class="font-red text-center">No data to display!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
            @elseif($reportType == "SUMMARY" )

            <table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
            <thead class="flip-content">
                <tr>
                    <th></th>
                    <th>Yes</th>
                    <th>No</th>
                    <th>Not Applicable</th>
                </tr>
            </thead>
            <tbody>
            <?php $count = 1;?>
            @forelse($contractorsList as $data)
                <tr>
                    <td>Office Establishment</td>
                    <td>{{$data->yesOfficeEstablishment}}</td>
                    <td>{{$data->noOfficeEstablishment}}</td>
                    <td>{{$data->notOfficeEstablishment}}</td>
                </tr>
                <tr>
                    <td>Office Sign Board</td>
                    <td>{{$data->yesOfficeSignboard}}</td>
                    <td>{{$data->noOfficeSignboard}}</td>
                    <td>{{$data->notOfficeSignboard}}</td>
                </tr>
                <tr>
                    <td>Office Filing System</td>
                    <td>{{$data->yesfilingSystem}}</td>
                    <td>{{$data->nofilingSystem}}</td>
                    <td>{{$data->notfilingSystem}}</td>
                </tr>
                <tr>
                    <td>Is Requirement Fullfilled</td>
                    <td>{{$data->yesRequirementFullfilled}}</td>
                    <td>{{$data->noRequirementFullfilled}}</td>
                    <td>{{$data->notRequirementFullfilled}}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="14" class="font-red text-center">No data to display!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @endif
    @elseif($inspectionType == "PUBLIC_SITE")
        @if($reportType == "LOCATION")
        <table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
            <thead class="flip-content">
                <tr>
                    <th>Sl.No.</th>
                    <th>Name of Firm</th>
                    <th>CDB No</th>
                    <th>Work Name</th>
                    <th>Location</th>
                    <th>Procuring Agency</th>
                </tr>
            </thead>
            <tbody>
            <?php $count = 1;?>
            @forelse($contractorsList as $data)
                <tr>
                    <td>{{$count++}}</td>
                    <td>{{$data->nameOfFirm}}</td>
                    <td>{{$data->cdbNo}}</td>
                    <td>{{$data->nameOfWork}}</td>
                    <td>{{$data->dzongkhagName}}</td>
                    <td>{{$data->procuringAgency}}</td> 
                </tr>
                @empty
                <tr>
                    <td colspan="14" class="font-red text-center">No data to display!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @elseif($reportType == "DETAIL_REPORT" )
        <table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
            <thead class="flip-content">
                <tr>
                    <th>Sl.No.</th>
                    <th>Name of Firm</th>
                    <th>CDB No</th>
                    <th>Work Name</th>
                    <th>Procuring Agency</th>
                    <th>Dzongkhag</th>
                    <th>Start Date</th>
                    <th>Proposed Completion Date</th>
                    <th>Contract Price</th>
                    <th>Site Engineer Name</th>
                    <th>APS Maintained By Gov.</th>
                    <th>Office setup at site</th>
                    <th>Sign Board at Site</th>
                    <th>Store at Site</th>
                    <th>Habitable work accomodation at site</th>
                    <th>Proper sanitation faciliteies provided</th>
                    <th>Access to potable water</th>
                    <th>Work insurance</th>
                    <th>Labour insurance</th>
                    <th>Third Party insurance</th>
                    <th>Work Plan Available</th>
                    <th>BOQ available</th>
                    <th>Condition of contract available</th>
                    <th>Approved drawing available</th>
                    <th>Work Progress</th>
                    <th>Work Specification</th>
                    <th>Site order book available</th>
                    <th>Hindrance register available</th>
                    <th>MBS Available</th>
                    <th>Day work journal available</th>
                    <th>Quality assurance plan available</th>
                    <th>Quality control plan available</th>
                    <th>Mandatory test report available</th>
                    <th>Is local material used</th>
                    <th>Local material list</th>
                    <th>Sub contractor specialize trade</th>
                    <th>Safety Signages</th>
                    <th>Fire Extinguising</th>
                    <th>OHS in charge</th>
                    <th>First aid box available</th>
                    <th>Peripheral bouday construction</th>
                    <th>Safe and proper electrical connection</th>
                    <th>Total Bhutanese Labour</th>
                    <th>Total Intern</th>
                    <th>Total VTI</th>
                    <th>Total Foreign Labour</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody>
            <?php $count = 1;?>
            @forelse($contractorsList as $data)
                <tr>
                    <td>{{$count++}}</td>
                    <td>{{$data->nameOfFirm}}</td>
                    <td>{{$data->cdbNo}}</td>
                    <td>{{$data->nameOfWork}}</td>
                    <td>{{$data->procuringAgency}}</td>
                    <td>{{$data->dzongkhagName}}</td>
                    <td>{{$data->actualStartDate}}</td>
                    <td>{{$data->proposed_completion_date}}</td>
                    <td>{{$data->contract_price}}</td>
                    <td>{{$data->procuringSiteEngineerName}}</td>
                    <td>{{$data->hasAps}}</td>
                    <td>{{$data->hasOffice}}</td>
                    <td>{{$data->hasSignboard}}</td>
                    <td>{{$data->hasStore}}</td>
                    <td>{{$data->hasAccommodation}}</td>
                    <td>{{$data->hasSanitary}}</td>
                    <td>{{$data->hasPotableWater}}</td>
                    <td>{{$data->hasWorkInsurance}}</td>
                    <td>{{$data->hasLabourInsurance}}</td>
                    <td>{{$data->hasThirdPartyInsurance}}</td>
                    <td>{{$data->hasWorkPlan}}</td>
                    <td>{{$data->hasBoq}}</td>
                    <td>{{$data->hasContractConditions}}</td>
                    <td>{{$data->hasApprovedDrawing}}</td>
                    <td>{{$data->workStatus}}</td>
                    <td>{{$data->hasWorkSpecifications}}</td>
                    <td>{{$data->hasSiteOrderBook}}</td>
                    <td>{{$data->hasHindranceRegister}}</td>
                    <td>{{$data->hasMbs}}</td>
                    <td>{{$data->hasJournal}}</td>
                    <td>{{$data->hasQualityAssurancePlan}}</td>
                    <td>{{$data->hasQualityControlPlan}}</td>
                    <td>{{$data->hasTestReport}}</td>
                    <td>{{$data->hasLocalMaterials}}</td>
                    <td></td>
                    <td>{{$data->hasSpecializedTrade}}</td>
                    <td>{{$data->hasOhsIncharge}}</td>
                    <td>{{$data->hasSafetySignages}}</td>
                    <td>{{$data->hasFireExtinguishingEquipment}}</td>
                    <td>{{$data->hasFirstAidBox}}</td>
                    <td>{{$data->hasPeripheralBoundary}}</td>
                    <td>{{$data->hasProperElectricalInstallations}}</td>
                    <td>{{$data->nationalLabours}}</td>
                    <td>{{$data->internNo}}</td>
                    <td>{{$data->vtiGraduates}}</td>
                    <td>{{$data->foreignLabours}}</td>
                    <td><button class="btn btn-primary" onclick="viewDetail('Public ongoing site',{{$data->id}})">View Details</button></td>

                </tr>
                @empty
                <tr>
                    <td colspan="14" class="font-red text-center">No data to display!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @elseif($reportType == "SUMMARY" )
        <table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
        <thead class="flip-content">
            <tr>
                <th></th>
                <th>Yes</th>
                <th>No</th>
                <th>Not Applicable</th>
            </tr>
        </thead>
        <tbody>
        <?php $count = 1;?>
        @forelse($contractorsList as $data)
            <tr>
                <td>APS Maintained by gov Engineer</td>
                <td>{{$data->yesaps_maintained_by_gov_eng}}</td>
                <td>{{$data->noaps_maintained_by_gov_eng}}</td>
                <td>{{$data->notaps_maintained_by_gov_eng}}</td>
            </tr>
            <tr>
                <td>Office Setup At Site</td>
                <td>{{$data->yeshasOffice}}</td>
                <td>{{$data->nohasOffice}}</td>
                <td>{{$data->nothasOffice}}</td>
            </tr>
            <tr>
                <td>Sign Board_at_site</td>
                <td>{{$data->yeshasSignboard}}</td>
                <td>{{$data->nohasSignboard}}</td>
                <td>{{$data->nothasSignboard}}</td>
            </tr>
            <tr>
                <td>Store At Site</td>
                <td>{{$data->yeshasStore}}</td>
                <td>{{$data->nohasStore}}</td>
                <td>{{$data->nothasStore}}</td>
            </tr>
            <tr>
                <td>Habitable Work Accomodation at Site</td>
                <td>{{$data->yeshasAccommodation}}</td>
                <td>{{$data->nohasAccommodation}}</td>
                <td>{{$data->nothasAccommodation}}</td>
            </tr>
            <tr>
                <td>Proper Sanitation Facilities Provided</td>
                <td>{{$data->yeshasSanitary}}</td>
                <td>{{$data->nohasSanitary}}</td>
                <td>{{$data->nothasSanitary}}</td>
            </tr>
            <tr>
                <td>Access To Potable Water</td>
                <td>{{$data->yeshasPotableWater}}</td>
                <td>{{$data->nohasPotableWater}}</td>
                <td>{{$data->nothasPotableWater}}</td>
            </tr>
            <tr>
                <td>Work Insurance</td>
                <td>{{$data->yeshasWorkInsurance}}</td>
                <td>{{$data->nohasWorkInsurance}}</td>
                <td>{{$data->nothasPotableWater}}</td>
            </tr>
            <tr>
                <td>Labour Insurance</td>
                <td>{{$data->yeshasLabourInsurance}}</td>
                <td>{{$data->nohasLabourInsurance}}</td>
                <td>{{$data->nothasLabourInsurance}}</td>
            </tr>
           
            <tr>
                <td>Third party insurance</td>
                <td>{{$data->yeshasThirdPartyInsurance}}</td>
                <td>{{$data->nohasThirdPartyInsurance}}</td>
                <td>{{$data->nothasThirdPartyInsurance}}</td>
            </tr>
            <tr>
                <td> BOQ available</td>
                <td>{{$data->yeshasBoq}}</td>
                <td>{{$data->nohasBoq}}</td>
                <td>{{$data->nothasBoq}}</td>
            </tr>
           
            <tr>
                <td> Work plan available</td>
                <td>{{$data->yeshasWorkPlan}}</td>
                <td>{{$data->nohasWorkPlan}}</td>
                <td>{{$data->nothasWorkPlan}}</td>
            </tr>
           
           <tr>
               <td> Condition of contract available</td>
               <td>{{$data->yeshasContractConditions}}</td>
               <td>{{$data->nohasContractConditions}}</td>
               <td>{{$data->nothasContractConditions}}</td>
           </tr>
          
           
           <tr>
                <td> Approved drawing available</td>
                <td>{{$data->yeshasApprovedDrawing}}</td>
                <td>{{$data->nohasContractConditions}}</td>
                <td>{{$data->nothasContractConditions}}</td>
            </tr>
           <tr>
                <td> Work specification</td>
                <td>{{$data->yeshasWorkSpecifications}}</td>
                <td>{{$data->nohasWorkSpecifications}}</td>
                <td>{{$data->nothasWorkSpecifications}}</td>
            </tr>
           <tr>
                <td> Site order book available</td>
                <td>{{$data->yeshasSiteOrderBook}}</td>
                <td>{{$data->nohasSiteOrderBook}}</td>
                <td>{{$data->nothasSiteOrderBook}}</td>
            </tr>
           <tr>
                <td> Hindrance register available</td>
                <td>{{$data->yeshasHindranceRegister}}</td>
                <td>{{$data->nohasHindranceRegister}}</td>
                <td>{{$data->nothasHindranceRegister}}</td>
            </tr>
           <tr>
                <td> MBS available</td>
                <td>{{$data->yeshasMbs}}</td>
                <td>{{$data->nohasJournal}}</td>
                <td>{{$data->nothasJournal}}</td>
            </tr>
            <tr>
                <td> Quality Assurance plan available</td>
                <td>{{$data->yeshasQualityAssurancePlan}}</td>
                <td>{{$data->nohasQualityAssurancePlan}}</td>
                <td>{{$data->nothasQualityAssurancePlan}}</td>
            </tr>
            <tr>
                <td> Quality control plan available</td>
                <td>{{$data->yeshasQualityControlPlan}}</td>
                <td>{{$data->nohasQualityControlPlan}}</td>
                <td>{{$data->nothasQualityControlPlan}}</td>
            </tr>
            <tr>
                <td> Mandatory test report available</td>
                <td>{{$data->yeshasTestReport}}</td>
                <td>{{$data->nohasTestReport}}</td>
                <td>{{$data->nothasQualityControlPlan}}</td>
            </tr>
           <tr>
                <td> Is local material used</td>
                <td>{{$data->yeshasLocalMaterials}}</td>
                <td>{{$data->nohasLocalMaterials}}</td>
                <td>{{$data->nothasLocalMaterials}}</td>
            </tr>
           
            <tr>
                <td> Sub contractor specialize trade</td>
                <td>{{$data->yeshasSpecializedTrade}}</td>
                <td>{{$data->nohasSpecializedTrade}}</td>
                <td>{{$data->nothasSpecializedTrade}}</td>
            </tr>
            <tr>
                <td> OHS in charge</td>
                <td>{{$data->yeshasOhsIncharge}}</td>
                <td>{{$data->nohasOhsIncharge}}</td>
                <td>{{$data->nothasOhsIncharge}}</td>
            </tr>
            <tr>
                <td> Safety signages</td>
                <td>{{$data->yeshasSafetySignages}}</td>
                <td>{{$data->nohasSafetySignages}}</td>
                <td>{{$data->nothasSafetySignages}}</td>
            </tr>
            <tr>
                <td>Fire extinguising</td>
                <td>{{$data->yeshasFireExtinguishingEquipment}}</td>
                <td>{{$data->nohasFireExtinguishingEquipment}}</td>
                <td>{{$data->nothasFireExtinguishingEquipment}}</td>
            </tr>
            <tr>
                <td>First aid box</td>
                <td>{{$data->yeshasFirstAidBox}}</td>
                <td>{{$data->nohasFirstAidBox}}</td>
                <td>{{$data->nothasFirstAidBox}}</td>
            </tr>
            <tr>
                <td>Peripheral bounday construction</td>
                <td>{{$data->yeshasPeripheralBoundary}}</td>
                <td>{{$data->nohasPeripheralBoundary}}</td>
                <td>{{$data->nothasPeripheralBoundary}}</td>
            </tr>
            <tr>
                <td>Safe and proper electrical connection</td>
                <td>{{$data->yeshasProperElectricalInstallations}}</td>
                <td>{{$data->nohasProperElectricalInstallations}}</td>
                <td>{{$data->nothasProperElectricalInstallations}}</td>
            </tr>
           
            @empty
            <tr>
                <td colspan="14" class="font-red text-center">No data to display!</td>
            </tr>
            @endforelse
        </tbody>
        </table>
        @endif
    @elseif($inspectionType == "PRIVATE_SITE")
    @if($reportType == "LOCATION")
        <table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
            <thead class="flip-content">
                <tr>
                    <th>Sl.No.</th>
                    <th>Name of Firm</th>
                    <th>CDB No</th>
                    <th>Dzongkhag</th>
                    <th>Locality Name</th>
                    <th>Inspection Date</th>
                </tr>
            </thead>
            <tbody>
            <?php $count = 1;?>
            @forelse($contractorsList as $data)
                <tr>
                    <td>{{$count++}}</td>
                    <td>{{$data->nameOfFirm}}</td>
                    <td>{{$data->cdbNo}}</td>
                    <td>{{$data->dzongkhagName}}</td>
                    <td>{{$data->localityName}}</td> 
                    <td>{{$data->createdOn}}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="14" class="font-red text-center">No data to display!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @elseif($reportType == "DETAIL_REPORT" )
        <table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
            <thead class="flip-content">
                <tr>
                    <th>Sl.No.</th>
                    <th>Name of Firm</th>
                    <th>CDB No</th>
                    <th>Dzongkhag</th>
                    <th>Locality Name</th>
                    <th>Work Address</th>
                    <th>Email</th>
                    <th>Telephone No.</th>
                    <th>Mobile No.</th>
                    <th>Classification</th>
                    <th>Validity</th>
                    <th>Contract Extension</th>
                    <th>Contract Extension Start Date</th>
                    <th>Contract Extension End Date</th>
                    <th>Contract Value</th>
                    <th>Has Office</th>
                    <th>Office Remarks</th>
                    <th>Has Signboard</th>
                    <th>Signboard Remarks</th>
                    <th>Has Store</th>
                    <th>Store Remarks</th>
                    <th>Has Document</th>
                    <th>Document Remarks</th>
                    <th>Is Insuranced</th>
                    <th>Is Insuranced Remarks</th>
                    <th>Has Labour Insurance</th>
                    <th>Labour Insurance Remarks</th>
                    <th>Has Third Party Insurance</th>
                    <th>Third Party Insurance Remarks</th>
                    <th>Has Work Plan</th>
                    <th>Work Plan Remarks</th>
                    <th>Work Status</th>
                    <th>Work Status Remarks</th>
                    <th>Has Camp</th>
                    <th>Has Power Supply</th>
                    <th>Has Sanitary</th>
                    <th>Has Water Supply</th>
                    <th>Has OHS Incharge</th>
                    <th>Has OHS Incharge Remarks</th>
                    <th>Has Fire Extinguising Equipment</th>
                    <th>Has Fire Extinguising Equipment Remarks</th>
                    <th>Has First Aid Box</th>
                    <th>Has First Aid Box Remarks</th>
                    <th>National Labour</th>
                    <th>Foreign Labour</th>
                    <th>Intern No.</th>
                    <th>VTI Graduates</th>
                    <th>Has specialize Trade</th>
                    <th>Has specialize Trade Remarks</th>
                    <th>Representative Name</th>
                    <th>Representative CID No.</th>
                    <th>Representative Contact No.</th>
                    <th>Remarks</th>
                    <th>Inspection Date</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody>
            <?php $count = 1;?>
            @forelse($contractorsList as $data)
                <tr>
                    <td>{{$count++}}</td>
                    <td>{{$data->nameOfFirm}}</td>
                    <td>{{$data->cdbNo}}</td> 
                    <td>{{$data->dzongkhagName}}</td>
                    <td>{{$data->localityName}}</td>
                    <td>{{$data->permAddress}}</td>
                    <td>{{$data->email}}</td>
                    <td>{{$data->telNo}}</td>
                    <td>{{$data->mobNo}}</td>
                    <td>{{$data->classificationName}}</td>
                    <td>{{$data->validity}}</td>
                    <td>{{$data->contractExtension}}</td>
                    <td>{{$data->contractExtensionStartDate}}</td>
                    <td>{{$data->contractExtensionEndDate}}</td>
                    <td>{{$data->contractValue}}</td>
                    <td>{{$data->hasOffice}}</td>
                    <td>{{$data->hasOfficeRemarks}}</td>
                    <td>{{$data->hasSignboard}}</td>
                    <td>{{$data->hasSignboardRemarks}}</td>
                    <td>{{$data->hasStore}}</td>
                    <td>{{$data->hasStoreRemarks}}</td>
                    <td>{{$data->hasDocument}}</td>
                    <td>{{$data->hasDocumentRemarks}}</td>
                    <td>{{$data->isInsured}}</td>
                    <td>{{$data->isInsuredRemarks}}</td>
                    <td>{{$data->hasLabourInsurance}}</td>
                    <td>{{$data->hasLabourInsuranceRemarks}}</td>
                    <td>{{$data->hasThirdPartyInsurance}}</td>
                    <td>{{$data->hasThirdPartyInsuranceRemarks}}</td>
                    <td>{{$data->hasWorkPlan}}</td>
                    <td>{{$data->hasWorkPlanRemarks}}</td>
                    <td>{{$data->workStatus}}</td>
                    <td>{{$data->workStatusRemarks}}</td>
                    <td>{{$data->hasCamp}}</td>
                    <td>{{$data->hasPowerSupply}}</td>
                    <td>{{$data->hasSanitary}}</td>
                    <td>{{$data->hasWaterSupply}}</td>
                    <td>{{$data->hasOhsIncharge}}</td>
                    <td>{{$data->hasOhsInchargeRemarks}}</td>
                    <td>{{$data->hasFireExtinguishingEquipment}}</td>
                    <td>{{$data->hasFireExtinguishingEquipmentRemarks}}</td>
                    <td>{{$data->hasFirstAidBox}}</td>
                    <td>{{$data->hasFirstAidBoxRemarks}}</td>
                    <td>{{$data->nationalLabours}}</td>
                    <td>{{$data->foreignLabours}}</td>
                    <td>{{$data->internNo}}</td>
                    <td>{{$data->vtiGraduates}}</td>
                    <td>{{$data->hasSpecializeTrade}}</td>
                    <td>{{$data->hasSpecializeTradeRemarks}}</td>
                    <td>{{$data->representativeName}}</td>
                    <td>{{$data->representativeCidNo}}</td>
                    <td>{{$data->representativeContactNo}}</td>
                    <td>{{$data->remarks}}</td>
                    <td>{{$data->createdOn}}</td>
                    <td><button class="btn btn-primary" onclick="viewDetail('Private ongoing site',{{$data->inspectionId}})">View Detail</button></td>
                </tr>
                @empty
                <tr>
                    <td colspan="14" class="font-red text-center">No data to display!</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @elseif($reportType == "SUMMARY" )
                <table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
                <thead class="flip-content">
                    <tr>
                        <th></th>
                        <th>Yes</th>
                        <th>No</th>
                        <th>Not Applicable</th>
                    </tr>
                </thead>
                <tbody>
                <?php $count = 1;?>
                @forelse($contractorsList as $data)
                    <tr>
                        <td>Contract Extension</td>
                        <td>{{$data->yesContractorExtension}}</td>
                        <td>{{$data->noContractorExtension}}</td>
                        <td>{{$data->notContractorExtension}}</td>
                    </tr>
                    <tr>
                        <td>Has Office</td>
                        <td>{{$data->yeshasOffice}}</td>
                        <td>{{$data->nohasOffice}}</td>
                        <td>{{$data->nothasOffice}}</td>
                    </tr>
                    <tr>
                        <td>Has Signboard</td>
                        <td>{{$data->yeshasSignboard}}</td>
                        <td>{{$data->nohasSignboard}}</td>
                        <td>{{$data->nothasSignboard}}</td>
                    </tr>
                    <tr>
                        <td>Has Store</td>
                        <td>{{$data->yeshasStore}}</td>
                        <td>{{$data->nohasStore}}</td>
                        <td>{{$data->nothasStore}}</td>
                    </tr>
                    <tr>
                        <td>Has Document</td>
                        <td>{{$data->yeshasDocument}}</td>
                        <td>{{$data->nohasDocument}}</td>
                        <td>{{$data->nothasDocument}}</td>
                    </tr>
                    <tr>
                        <td>Has Labour Insurance</td>
                        <td>{{$data->yeshasLabourInsurance}}</td>
                        <td>{{$data->nohasLabourInsurance}}</td>
                        <td>{{$data->nothasLabourInsurance}}</td>
                    </tr>
                    <tr>
                        <td>Has Third Party Insurance</td>
                        <td>{{$data->yeshasThirdPartyInsurance}}</td>
                        <td>{{$data->nohasThirdPartyInsurance}}</td>
                        <td>{{$data->nothasThirdPartyInsurance}}</td>
                    </tr>
                    <tr>
                        <td>Has Work Plan</td>
                        <td>{{$data->yeshasWorkPlan}}</td>
                        <td>{{$data->nohasWorkPlan}}</td>
                        <td>{{$data->nothasWorkPlan}}</td>
                    </tr>
                    <tr>
                        <td>Has Camp</td>
                        <td>{{$data->yeshasCamp}}</td>
                        <td>{{$data->nohasCamp}}</td>
                        <td>{{$data->ntohasCamp}}</td>
                    </tr>
                    <tr>
                        <td>Has Power Supply</td>
                        <td>{{$data->yeshasPowerSupply}}</td>
                        <td>{{$data->nohasPowerSupply}}</td>
                        <td>{{$data->nothasPowerSupply}}</td>
                    </tr>
                    <tr>
                        <td>Has Sanitary</td>
                        <td>{{$data->yeshasSanitary}}</td>
                        <td>{{$data->nohasSanitary}}</td>
                        <td>{{$data->nothasSanitary}}</td>
                    </tr>
                    <tr>
                        <td>Has Water Supply</td>
                        <td>{{$data->yeshasWaterSupply}}</td>
                        <td>{{$data->nohasWaterSupply}}</td>
                        <td>{{$data->nothasWaterSupply}}</td>
                    </tr>
                    <tr>
                        <td>Has OHS Incharge</td>
                        <td>{{$data->yeshasOhsIncharge}}</td>
                        <td>{{$data->nohasOhsIncharge}}</td>
                        <td>{{$data->nothasOhsIncharge}}</td>
                    </tr>
                    <tr>
                        <td>Has Fire ExtinguishingEquipment</td>
                        <td>{{$data->yeshasFireExtinguishingEquipment}}</td>
                        <td>{{$data->nohasFireExtinguishingEquipment}}</td>
                        <td>{{$data->nothasFireExtinguishingEquipment}}</td>
                    </tr>
                    <tr>
                        <td>Has First AidBox</td>
                        <td>{{$data->yeshasFirstAidBox}}</td>
                        <td>{{$data->nohasFirstAidBox}}</td>
                        <td>{{$data->nothasFirstAidBox}}</td>
                    </tr>
                    <tr>
                        <td>Has Specialize Trade</td>
                        <td>{{$data->yeshasSpecializeTrade}}</td>
                        <td>{{$data->nohasSpecializeTrade}}</td>
                        <td>{{$data->nothasSpecializeTrade}}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="14" class="font-red text-center">No data to display!</td>
                    </tr>
                    @endforelse
                </tbody>
                </table>

        @endif

    @endif
</div>

<div id="detailReport" class="modal fade in" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3  class="modal-title font-red-intense"><span id="reprotTitle"></span> detail report</h3>
			</div>
			<div class="modal-body" id="exportThis">
			 <a href="#" onclick="printPDF()" style="margin: 14px;" class="btn btn-success">Export</a>
                <div id="detailReportRespond"></div>
			</div>
		</div>
	</div>
</div>

<script>


	function viewDetail(detailType,rowId)
	{

        $("#reprotTitle").html(detailType);
        var url = 'reportDetails';
        $.ajax({
            url: url,
            type: "get",
            data: {
                inspectionId   : rowId ,
                reportType : detailType
                },
            success: function(response){
                $("#detailReportRespond").html(response);
                viewOtherDetails(detailType+"_human_resources",rowId);
                viewOtherDetails(detailType+"_equipment",rowId);
                viewOtherDetails(detailType+"_image",rowId);
                viewOtherDetails(detailType+"_inspector",rowId);
                
            }
        });

        $("#detailReport").modal('show');
	}

    function viewOtherDetails(detailType,rowId)
	{ 
        var url = 'reportDetails';
        $.ajax({
            url: url,
            type: "get",
            data: {
                inspectionId   : rowId ,
                reportType : detailType
                },
            success: function(response){
                $("#detailReportRespond").append(response);

            }
        });

        $("#detailReport").modal('show');
	}

</script>

@if($reportType == "LOCATION" && $inspectionType == "OFFICE_ESTABLISHMENT")
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=apikey&callback=initMap" type="text/javascript"></script>


<script type="text/javascript"> 
    var locations = [
      @foreach($contractorsList as $row)  
      ['<div class="col-lg-12 text-black"><h4>{{$row->nameOfFirm}}</h4><h6>CDB No. : {{$row->cdbNo}}</h6><h6>Inspection Date : {{$row->createdOn}}</h6><h6>Locality Name : {{$row->localityName}}</h6></div>', {{$row->latitude}},{{$row->longitude}}, 4],
      @endforeach
    ];
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 8,
      center: new google.maps.LatLng(27.5142, 90.4336),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
    </script>
@endif
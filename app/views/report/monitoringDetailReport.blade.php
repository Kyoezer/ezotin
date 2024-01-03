<div class="margin-top-20" style="overflow: auto">



<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>

<script>
    function printPDF() {        
        var doc = new jsPDF();
        var specialElementHandlers = {
            '#editor': function (element, renderer) {
                return true;
            }
        };    

            doc.fromHTML($('#exportToPDF').html(), 15, 15, {
                'width': 170,
                    'elementHandlers': specialElementHandlers
            });
            doc.save('Detail-Report.pdf');
    }

</script>
<div id="exportToPDF">
    @if($reportType=='Office' || $reportType=='Private ongoing site'  || $reportType=='Public ongoing site')    
   
    <h4 style="font-weight: bold;">General Information</h4>
    @elseif($reportType=='Office_human_resources')
    <h4 style="font-weight: bold;">Human Resources</h4>
    @elseif($reportType=='Public ongoing site_human_resources')
    <h4 style="font-weight: bold;">Public ongoing site Human Resources</h4>
    @elseif($reportType=='Public ongoing site_equipment')
    <h4 style="font-weight: bold;">Public ongoing site Equipment</h4>
    @elseif($reportType=='Public ongoing site_inspector' || $reportType=='Office_inspector')
    <h4 style="font-weight: bold;">Site Inspector</h4>
    @endif
    <table class="table table- table-bordered" id="exportTable">
        @if($reportType=='Office')
        @foreach($detailReport as $data)
        <tr>
            <td>CDB No.</td>
            <td>{{$data->cdbNo}}</td>
        </tr>
        <tr>
            <td>Name of Firm</td>
            <td>{{$data->nameOfFirm}}</td>
        </tr>
        <tr>
            <td>License No.</td>
            <td>{{$data->licenseNo}}</td>
        </tr>
        <tr>
            <td>Dzongkhag Name.</td>
            <td>{{$data->dzongkhagName}}</td>
        </tr>        
        <tr>
            <td>Address</td>
            <td>{{$data->permAddress}}</td>
        </tr> 
        <tr>
            <td>Email</td>
            <td>{{$data->email}}</td>
        </tr>
        <tr>
            <td>Telephone No.</td>
            <td>{{$data->telNo}}</td>
        </tr>
        <tr>
            <td>Mobile No.</td>
            <td>{{$data->mobNo}}</td>
        </tr>
        <tr>
            <td>Classfication</td>
            <td>{{$data->classificationName}}</td>
        </tr>
        <tr>
            <td>Validity</td>
            <td>{{$data->validity}}</td>
        </tr>
        <tr>
            <td>Office establishment</td>
            <td>{{$data->isEstablished}}</td>
        </tr>
        <tr> 
            <td>Office signboard</td>
            <td>{{$data->isSignboard}}</td>
        </tr>
        <tr> 
            <td>Filing system</td>
            <td>{{$data->isFileSystem}}</td>
        </tr>
        <tr> 
            <td>OHS Handbook</td>
            <td>{{$data->hasHandbook}}</td>
        </tr>
        <tr> 
            <td>Safety Committee</td>
            <td>{{$data->hasSafety}}</td>
        </tr>
        <tr> 
            <td>Safety Officer</td>
            <td>{{$data->hasSafetyOfficer}}</td>
        </tr>
        <tr> 
            <td>Locality name</td>
            <td>{{$data->localityName}}</td>
        </tr>        
        <tr> 
            <td>Representative name</td>
            <td>{{$data->representativeName}}</td>
        </tr>
        <tr> 
            <td>Representative CID</td>
            <td>{{$data->representativeCidNo}}</td>
        </tr>
        <tr> 
            <td>Representative contact no</td>
            <td>{{$data->representativeContactNo}}</td>
        </tr>
        <tr> 
            <td>Remarks</td>
            <td>{{$data->remarks}}</td>
        </tr>
        <tr> 
            <td>Is requiment fullfilled</td>
            <td>{{$data->isRequirementFulfilled}}</td>
        </tr>       
        <tr> 
            <td>Inspection date</td>
            <td>{{$data->createdOn}}</td>
        </tr>
        @endforeach
        @elseif($reportType=='Office_inspector')
            <thead>
                <tr> 
                    <th>Inspector Name</th>    
                </tr>    
            </thead>
            @foreach($detailReport as $data)
            <tbody>
                <tr> 
                <td>{{$data->inspectorName}} </td>    
                </tr>
            </tbody>
            @endforeach
    @elseif($reportType=='Public ongoing site')
    @foreach($detailReport as $data)  
        <tr>
            <td>CDB No.</td>
            <td>{{$data->cdbNo}}</td>
        </tr>
        <tr>
            <td>Name of Firm</td>
            <td>{{$data->nameOfFirm}}</td>
        </tr>      
        <tr>
            <td>Work Name</td>
            <td>{{$data->nameOfWork}}</td>
        </tr>
        <tr>
            <td>License No</td>
            <td>{{$data->licenseNo}}</td>
        </tr>
        <tr>
            <td>Dzongkhag</td>
            <td>{{$data->dzongkhagName}}</td>
        </tr>
        <tr>
            <td>Address</td>
            <td>{{$data->permAddress}}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>{{$data->email}}</td>
        </tr>
        <tr>
            <td>Mobile No</td>
            <td>{{$data->mobNo}}</td>
        </tr>
        <tr>
            <td>Classification</td>
            <td>{{$data->classificationName}}</td>
        </tr>
        <tr>
            <td>Validity</td>
            <td>{{$data->validity}}</td>
        </tr>
        <tr>
            <td>Procuring Agency</td>
            <td>{{$data->procuringAgency}}</td>
        </tr>
        <tr>
            <td>Start Date</td>
            <td>{{$data->actualStartDate}}</td>
        </tr>
        <tr>
            <td>Proposed Completion Date</td>
            <td>{{$data->actualEndDate}}</td>
        </tr>
        <tr>
            <td>Contract Price</td>
            <td>{{$data->awardedAmount}}</td>
        </tr> 
        <tr>
            <td>Client Site Engineer Name</td>
            <td>{{$data->procuringSiteEngineerName}}</td>
        </tr>
        <tr> 
            <td>Location</td>
            <td>{{$data->latitude}},{{$data->longitude}},{{$data->localityName}}</td>
        </tr>  
        <tr> 
            <td>Representative name</td>
            <td>{{$data->representativeName}}</td>
        </tr>    
        <tr> 
            <td>Representative CID</td>
            <td>{{$data->representativeCidNo}}</td>
        </tr>
        <tr> 
            <td>Representative contact no</td>
            <td>{{$data->representativeContactNo}}</td>
        </tr>
        <tr>
            <td>Government Site Engineer Name</td>
            <td>{{$data->siteEngineerName}}</td>
        </tr>    
        <tr>
            <td>Remarks</td>
            <td>{{$data->remarks}}</td>
        </tr>     
        </table>

        <h4 style="font-weight: bold;">Check List</h4>
        <table class="table table- table-bordered">
        <tr>
            <th>Check List</th>
            <th>Status</th>
            <th>Remarks</th>
        </tr>
        <tr>
            <td>APS maintained by Government Engineer</td>
            <td>{{$data->hasAps}}</td>
            <td>{{$data->hasApsRemarks}}</td>
        </tr>
        <tr>
            <td>Office Setup at Site</td>
            <td>{{$data->hasOffice}}</td>
            <td>{{$data->hasOfficeRemarks}}</td>
        </tr>
        <tr>
            <td>Office Signboard at Site</td>
            <td>{{$data->hasSignboard}}</td>
            <td>{{$data->hasSignboardRemarks}}</td>
        </tr>
        <tr>
            <td>Store at Site</td>
            <td>{{$data->hasStore}}</td>
            <td>{{$data->hasStoreRemarks}}</td>
        </tr>
        <tr>
            <td>Hibatable work accomodation at site</td>
            <td>{{$data->hasAccommodation}}</td>
            <td>{{$data->hasAccommodationRemarks}}</td>
        </tr>
        <tr>
            <td>Proper sanitation facilities provided</td>
            <td>{{$data->hasSanitary}}</td>
            <td>{{$data->hasSanitaryRemarks}}</td>
        </tr>
        <tr>
            <td>Access to potable water</td>
            <td>{{$data->hasPotableWater}}</td>
            <td>{{$data->hasPotableWaterRemarks}}</td>
        </tr>
        <tr>
            <td>Work insurance</td>
            <td>{{$data->hasWorkInsurance}}</td>
            <td>{{$data->hasWorkInsuranceRemarks}}</td>
        </tr>
        <tr>
            <td>Labour insurance</td>
            <td>{{$data->hasLabourInsurance}}</td>
            <td>{{$data->hasLabourInsuranceRemarks}}</td>
        </tr>
        <tr>
            <td>Third party insurance</td>
            <td>{{$data->hasThirdPartyInsurance}}</td>
            <td>{{$data->hasThirdPartyInsuranceRemarks}}</td>
        </tr>
        <tr>
            <td>Work plan available</td>
            <td>{{$data->hasWorkPlan}}</td>
            <td>{{$data->hasWorkPlanRemarks}}</td>
        </tr>
        <tr>
            <td>Work Status</td>
            <td>{{$data->workStatus}}</td>
            <td>{{$data->workStatusRemarks}}</td>
        </tr>
        <tr>
            <td>BOQ available</td>
            <td>{{$data->hasBoq}}</td>
            <td>{{$data->hasBoqRemarks}}</td>
        </tr>
        <tr>
            <td>Condidtion of contract available</td>
            <td>{{$data->hasContractConditions}}</td>
            <td>{{$data->hasContractConditionsRemarks}}</td>
        </tr>
        <tr>
            <td>Approved drawing available</td>
            <td>{{$data->hasApprovedDrawing}}</td>
            <td>{{$data->hasApprovedDrawingRemarks}}</td>
        </tr>
        <!-- <tr>
            <td>Work Progress</td>
            <td>$data->work_progress</td>
        </tr> -->
        <tr>
            <td>Work Specification</td>
            <td>{{$data->hasWorkSpecifications}}</td>
            <td>{{$data->hasWorkSpecificationsRemarks}}</td>
        </tr>
        <tr>
            <td>Site order book available</td>
            <td>{{$data->hasSiteOrderBook}}</td>
            <td>{{$data->hasSiteOrderBookRemarks}}</td>
        </tr>
        <tr>
            <td>Hindrance register available</td>
            <td>{{$data->hasHindranceRegister}}</td>
            <td>{{$data->hasHindranceRegisterRemarks}}</td>
        </tr>
        <tr>
            <td>MBS available</td>
            <td>{{$data->hasMbs}}</td>
            <td>{{$data->hasMbsRemarks}}</td>
        </tr>
        <tr>
            <td>CMS Maintained </td>
            <td>{{$data->hasCms}}</td>
            <td>{{$data->hasCmsRemarks}}</td>
        </tr>
        <tr>
            <td>Day work journal available</td>
            <td>{{$data->hasJournal}}</td>
            <td>{{$data->hasJournalRemarks}}</td>
        </tr>
        <tr>
            <td>Quality assurance plan available</td>
            <td>{{$data->hasQualityAssurancePlan}}</td>
            <td>{{$data->hasQualityAssurancePlanRemarks}}</td>
        </tr>
        <tr>
            <td>Quality control plan available</td>
            <td>{{$data->hasQualityControlPlan}}</td>
            <td>{{$data->hasQualityControlPlanRemarks}}</td>
        </tr>
        <tr>
            <td>Mandatory test report available</td>
            <td>{{$data->hasTestReport}}</td>
            <td>{{$data->hasTestReportRemarks}}</td>
        </tr>        
        <tr>
            <td>Local material list</td>
            <td>{{$data->hasLocalMaterials}}</td>
            <td>{{$data->hasLocalMaterialsRemarks}}</td>
        </tr>        
        <tr>
            <td>Sub contractor specialize trade</td>
            <td>{{$data->hasSpecializedTrade}}</td>
            <td>{{$data->hasSpecializedTradeRemarks}}</td>
        </tr>       
        <tr>
            <td>OHS in charge</td>
            <td>{{$data->hasOhsIncharge}}</td>
            <td>{{$data->hasOhsInchargeRemarks}}</td>
        </tr>   
        <tr>
            <td>Safety signages</td>
            <td>{{$data->hasSafetySignages}}</td>
            <td>{{$data->hasSafetySignagesRemarks}}</td>
        </tr>
        <tr>
            <td>Fire extinguising</td>
            <td>{{$data->hasFireExtinguishingEquipment}}</td>
            <td>{{$data->hasFireExtinguishingEquipmentRemarks}}</td>
        </tr>
        <tr>
            <td>First aid box</td>
            <td>{{$data->hasFirstAidBox}}</td>
            <td>{{$data->hasFirstAidBoxRemarks}}</td>
        </tr>
        <tr>
            <td>Peripheral bouday construction</td>
            <td>{{$data->hasPeripheralBoundary}}</td>
            <td>{{$data->hasPeripheralBoundaryRemarks}}</td>
        </tr>
        <tr>
            <td>Safe and proper electrical connection</td>
            <td>{{$data->hasProperElectricalInstallations}}</td>
            <td>{{$data->hasProperElectricalInstallationsRemarks}}</td>
        </tr>
        <tr>
            <td>CCTV Installed</td>
            <td>{{$data->hasCctv}}</td>
            <td>{{$data->hasCctvRemarks}}</td>
        </tr>
        </table>

        <h4 style="font-weight: bold;">PPE Check List</h4>
        <table class="table table- table-bordered">
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Quantity</th>
                <th>Remarks</th>
            </tr>
            @foreach($ppeCheckList as $ppeCheck)
            <tr>
                <td>{{$ppeCheck->name}}</td>
                <td>{{$ppeCheck->status}}</td>
                <td>{{$ppeCheck->quantity}}</td>
                <td>{{$ppeCheck->remarks}}</td>
            </tr>
            @endforeach
        </table>

        <h4 style="font-weight: bold;">Labour Information</h4>
        <table class="table table- table-bordered">
        <tr>
            <td>Total National Labour</td>
            <td>{{$data->nationalLabours}}</td>
        </tr>
        <tr>
            <td>Total Foreign Labour</td>
            <td>{{$data->foreignLabours}}</td>
        </tr>

        <tr>
            <td>Total Intern</td>
            <td>{{$data->internNo}}</td>
        </tr>
        <tr>
            <td>Total TVET Graduates</td>
            <td>{{$data->vtiGraduates}}</td>
        </tr>       
        <tr>
            <td>Inspection date</td>
            <td>{{$data->createdOn}}</td>
        </tr>
	@endforeach
        @elseif($reportType=='Public ongoing site_human_resources')
        <thead>
            <tr>  
                <th>CID No.</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        @foreach($detailReport as $data)
        <tbody>
            <tr> 
            <td>{{$data->cid}}</td>
            <td>{{$data->name}}</td>
            <td>{{$data->designation}}</td>
            <td>{{$data->status}}</td>
            <td>{{$data->remarks}}</td>
            
            </tr>
        </tbody>           
        @endforeach

        @elseif($reportType=='Public ongoing site_equipment')       
        <thead>
            <tr> 
                <th>Registration No.</th>
                <th>Equipment Type</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        @foreach($detailReport as $data)
        <tbody>
            <tr> 
            <td>{{$data->vehicle_no}}</td>
            <td>{{$data->name}}</td>
            <td>{{$data->quantity}}</td>
            <td>{{$data->status}}</td>
            <td>{{$data->remarks}}</td>
            </tr>
        </tbody>        
        @endforeach	
        @elseif($reportType=='Public ongoing site_inspector')
        <table class="table table- table-bordered">
            <thead>
                <tr> 
                    <th>Inspector Name</th>    
                </tr>    
            </thead>
            @foreach($detailReport as $data)
            <tbody>
                <tr> 
                <td>{{$data->inspectorName}} </td>    
                </tr>
            </tbody>
        </table>
            @endforeach
        @elseif($reportType=='Private ongoing site')
            @foreach($detailReport as $data)
            <table class="table table- table-bordered">
            <tbody>
                <tr> 
                    <td>CDB No.</td>
                    <td>{{$data->cdbNo}}</td>
                </tr>
                <tr> 
                    <td>Name of Firm</td>
                    <td>{{$data->nameOfFirm}}</td>
                </tr>
                <tr> 
                    <td>License No.</td>
                    <td>{{$data->licenseNo}}</td>
                </tr>
                <tr> 
                    <td>Dzongkhag Name</td>
                    <td>{{$data->dzongkhagName}}</td>
                </tr>
                <tr> 
                    <td>Work Address</td>
                    <td>{{$data->workAddress}}</td>
                </tr>
                <tr> 
                    <td>Email</td>
                    <td>{{$data->email}}</td>
                </tr>
                <tr> 
                    <td>Telephone</td>
                    <td>{{$data->telNo}}</td>
                </tr>
                <tr> 
                    <td>Mobile No.</td>
                    <td>{{$data->mobNo}}</td>
                </tr>
                <tr> 
                    <td>Classfication</td>
                    <td>{{$data->classificationName}}</td>
                </tr>
                <tr> 
                    <td>Validity</td>
                    <td>{{$data->validity}}</td>
                </tr>
                <tr> 
                    <td>Contract Extension</td>
                    <td>{{$data->contractExtension}}</td>
                </tr>
                <tr> 
                    <td>Contract Extension Start Date</td>
                    <td>{{$data->contractExtensionStartDate}}</td>
                </tr>
                <tr> 
                    <td>Contract Extension End Date</td>
                    <td>{{$data->contractExtensionEndDate}}</td>
                </tr>
                <tr> 
                    <td>Contract Value</td>
                    <td>{{$data->contractValue}}</td>
                </tr>
                <tr> 
                    <td>Has Office</td>
                    <td>{{$data->hasOffice}}</td>
                </tr>
                <tr> 
                    <td>Has Office Remarks</td>
                    <td>{{$data->hasOfficeRemarks}}</td>
                </tr>
                <tr> 
                    <td>Has Signboard</td>
                    <td>{{$data->hasSignboard}}</td>
                </tr>
                <tr> 
                    <td>Has Signboard Remarks</td>
                    <td>{{$data->hasSignboardRemarks}}</td>
                </tr>
                <tr> 
                    <td>Has Store</td>
                    <td>{{$data->hasStore}}</td>
                </tr>
                <tr> 
                    <td>Has Store Remarks</td>
                    <td>{{$data->hasStoreRemarks}}</td>
                </tr>
                <tr> 
                    <td>Has Document</td>
                    <td>{{$data->hasDocument}}</td>
                </tr>
                <tr> 
                    <td>Has Document Remarks</td>
                    <td>{{$data->hasDocumentRemarks}}</td>
                </tr>
                <tr> 
                    <td>Is Insured</td>
                    <td>{{$data->isInsured}}</td>
                </tr>
                <tr> 
                    <td>Is Insured Remarks</td>
                    <td>{{$data->isInsuredRemarks}}</td>
                </tr>
                <tr> 
                    <td>Has Labour Insurance</td>
                    <td>{{$data->hasLabourInsurance}}</td>
                </tr>
                <tr> 
                    <td>Has Labour Insurance Remarks</td>
                    <td>{{$data->hasLabourInsuranceRemarks}}</td>
                </tr>
                <tr> 
                    <td>Has Third Party Insurance</td>
                    <td>{{$data->hasThirdPartyInsurance}}</td>
                </tr>
                <tr> 
                    <td>Has Third Party Insurance Remarks</td>
                    <td>{{$data->hasThirdPartyInsuranceRemarks}}</td>
                </tr>
                <tr> 
                    <td>Has Work plan</td>
                    <td>{{$data->hasWorkPlan}}</td>
                </tr>
                <tr> 
                    <td>Has Work plan Remarks</td>
                    <td>{{$data->hasWorkPlanRemarks}}</td>
                </tr>
                <tr> 
                    <td>Work Status</td>
                    <td>{{$data->workStatus}}</td>
                </tr>
                <tr> 
                    <td>Work Status Remarks</td>
                    <td>{{$data->workStatusRemarks}}</td>
                </tr>
                <tr> 
                    <td>Has Camp</td>
                    <td>{{$data->hasCamp}}</td>
                </tr>
                <tr> 
                    <td>Has Power Supply</td>
                    <td>{{$data->hasPowerSupply}}</td>
                </tr>
                <tr> 
                    <td>Has Sanitary</td>
                    <td>{{$data->hasSanitary}}</td>
                </tr>
                <tr> 
                    <td>Has Water Supply</td>
                    <td>{{$data->hasWaterSupply}}</td>
                </tr>
                <tr> 
                    <td>Has OHS Incharge</td>
                    <td>{{$data->hasOhsIncharge}}</td>
                </tr>
                <tr> 
                    <td>Has OHS Incharge Remarks</td>
                    <td>{{$data->hasOhsInchargeRemarks}}</td>
                </tr>
                <tr> 
                    <td>Has Fire Extinguishing Equipment</td>
                    <td>{{$data->hasFireExtinguishingEquipment}}</td>
                </tr>
                <tr> 
                    <td>Has Fire Extinguishing Equipment Remarks</td>
                    <td>{{$data->hasFireExtinguishingEquipmentRemarks}}</td>
                </tr>
                <tr> 
                    <td>Has First Aid Box</td>
                    <td>{{$data->hasFirstAidBox}}</td>
                </tr>
                <tr> 
                    <td>Has First Aid Box Remarks</td>
                    <td>{{$data->hasFirstAidBoxRemarks}}</td>
                </tr>
                <tr> 
                    <td>Total National Labour</td>
                    <td>{{$data->nationalLabours}}</td>
                </tr>
                <tr> 
                    <td>Total Foreign Labour</td>
                    <td>{{$data->foreignLabours}}</td>
                </tr>
                <tr> 
                    <td>Total Intern</td>
                    <td>{{$data->internNo}}</td>
                </tr>
                <tr> 
                    <td>Total VTI Graduate</td>
                    <td>{{$data->vtiGraduates}}</td>
                </tr>
                <tr> 
                    <td>Has Specialize Trade</td>
                    <td>{{$data->hasSpecializeTrade}}</td>
                </tr>
                <tr> 
                    <td>Has Specialize Trade Remarks</td>
                    <td>{{$data->hasSpecializeTradeRemarks}}</td>
                </tr>
                <tr> 
                    <td>Location</td>
                    <td>{{$data->latitude}},{{$data->longitude}},{{$data->localityName}}</td>
                </tr>
                <tr> 
                    <td>Representative Name</td>
                    <td>{{$data->representativeName}}</td>
                </tr>
                <tr> 
                    <td>Representative CID No.</td>
                    <td>{{$data->representativeCidNo}}</td>
                </tr>
                <tr> 
                    <td>Representative Contact No.</td>
                    <td>{{$data->representativeContactNo}}</td>
                </tr>
                <tr> 
                    <td>Remarks</td>
                    <td>{{$data->remarks}}</td>
                </tr>
                <tr> 
                    <td>Inspection Date</td>
                    <td>{{$data->inspection_date}}</td>
                </tr>
                
            </tbody>   
        </table>
  @endforeach 
        <h4 style="font-weight: bold;">PPE Check List</h4>
        <table class="table table- table-bordered">
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Quantity</th>
                <th>Remarks</th>
            </tr>
            @foreach($ppeCheckList as $ppeCheck)
            <tr>
                <td>{{$ppeCheck->name}}</td>
                <td>{{$ppeCheck->status}}</td>
                <td>{{$ppeCheck->quantity}}</td>
                <td>{{$ppeCheck->remarks}}</td>
            </tr>
            @endforeach              
           
        @elseif($reportType=='Office_human_resources')
        <thead>
            <tr> 
                <th>CID No.</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        @foreach($detailReport as $data)
        <tbody>
            <tr> 
            <td>{{$data->cidNo}}</td>
            <td>{{$data->name}}</td>
            <td>{{$data->designation}}</td>
            <td>{{$data->status}}</td>
            <td>{{$data->remarks}}</td>
            </tr>
        </tbody>
        @endforeach        
        @elseif($reportType=='Office_image' || $reportType=='Private ongoing site_image'  || $reportType=='Public ongoing site_image')
        <h4 style="font-weight: bold;">Images</h4>    
        <thead>
                <tr> 
                    <th>Sl#</th>
                    <th>Description</th>
                    <th>Image</th>
                </tr>
            </thead>
            <?php $rowCount = 1;?>
            @foreach($detailReport as $data)
            <tbody>
                <tr> 
                    <td>{{$rowCount++}}</td>
                    <td>{{$data->document_title}}</td>
                    <td style="text-align: center;"><img src="{{asset('uploads/monitoring/'.$data->document_title)}}" style="width: 410px;"/></td>
                </tr>
            </tbody>
            @endforeach       
        @endif
    </table>
</div>
</div>
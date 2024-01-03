@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
<?php 
    $route="contractorrpt.totalparticipant";
?>
	<div class="portlet-body flip-scroll">
        <?php
            $url = 'contractorrpt/totalparticipant';
            $route="contractorrpt.totalparticipant";
        ?>
		<div class="form-body">
			<div class="row">
                <div class="col-lg-12">
                    <div >
                        <div class="col-lg-12">
                            <div class="form-group col-lg-6">
                                <label class="control-label col-lg-3">Report Type <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                <select class="form-control select2me"  name="reportType" id="reportType">
                                        <option value="">Select Report Type</option>
                                        @if($inspectionType=='OFFICE_ESTABLISHMENT')
                                            <option value="LOCATION">Office Location</option>
                                        @elseif($inspectionType=='PUBLIC_SITE')
                                            <option value="LOCATION">Public Site Location</option>
                                        @elseif($inspectionType=='PRIVATE_SITE')
                                            <option value="LOCATION">Private Site Location</option>
                                        @endif

                                        <option value="DETAIL_REPORT">Detail Report</option>
                                        <option value="SUMMARY">Summary Report</option>
                                        @if($inspectionType=='OFFICE_ESTABLISHMENT')
                                        <option value="ACTION_TAKEN">Action Take Report</option>
                                        <option value="ACTION_TAKEN_SUMMARY">Action Take Summary</option>
                                        @endif
                                    </select>
                                    <div class="col-lg-12 margin-top-10 text-danger hidden errorReportType">Select Report Type</div>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="control-label col-lg-3">CDB No.</label>
                                <div class="col-lg-9">
                                    <input type="text" name="cdbNo" id="cdbNo" placeholder="CDB No." class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group col-lg-6">
                                <label class="control-label col-lg-3">From Date</label>
                                <div class="col-lg-9">
                                    <input type="text" name="fromDate" id="fromDate"  placeholder="dd-mm-yyyy" class="form-control datepicker" />
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="control-label col-lg-3">To  Date</label>
                                <div class="col-lg-9">
                                    <input type="text" name="toDate" id="toDate"  placeholder="dd-mm-yyyy" class="form-control datepicker" />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group col-lg-6">
                                <label class="control-label col-lg-3">Dzongkhag</label>
                                <div class="col-lg-9">
                                <select class="form-control select2me"  name="dzongkhag" id="dzongkhag">
                                        <option value="">Select Dzongkhag</option>
                                        @foreach($dzongkhagList as $dzongkhag)
                                                <option   value="{{$dzongkhag->Id}}">{{$dzongkhag->NameEn}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="control-label col-lg-3">Classification</label>
                                <div class="col-lg-9">
                                <select class="form-control select2me"  name="classification" id="classification">
                                        <option value="">Select Classification</option>
                                        @foreach($classificationList as $class)
                                                <option   value="{{$class->Id}}">{{$class->Name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 text-center">
                        <label class="control-label">&nbsp;</label>
                        <div class="btn-set">
                            <button type="button" class="btn btn-primary" onclick="getReport()">Search</button>
                        </div>
                    </div>
                    <div id="respondResult"></div>
                </div>
			</div>
		</div>
	</div>
</div>
  
  <script>
        function getReport()
        {
            var reportType = $("#reportType").val();
            var cdbNo = $("#cdbNo").val();
            var fromDate  = $("#fromDate").val();
            var toDate  = $("#toDate").val();
            var dzongkhag  = $("#dzongkhag").val();
            var classification = $("#classification").val();
            var inspectionType = '{{$inspectionType}}';
            if(reportType=="")
            {
                $(".errorReportType").removeClass("hidden");
            }
            else
            {
                $(".errorReportType").addClass("hidden"); 
                var url = 'generateOfficeReport';
                $.ajax({
                    url: url,
                    type: "get",
                    data: {
                        
                            reportType   : reportType,
                            cdbNo   : cdbNo,
                            fromDate: fromDate,
                            toDate  : toDate,
                            dzongkhag   : dzongkhag,
                            classification : classification,
                            inspectionType : inspectionType
                        },
                    success: function(response){
                        $("#respondResult").html(response);

                    }
                });
            }
        }
  </script>
@stop

 
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
                <form id="report_from">
                    <div >
                        <div class="col-lg-12">
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
                                <label class="control-label col-lg-3">Action Type</label>
                                <div class="col-lg-9">
                                <select class="form-control select2me"  name="actionType" id="actionType">
                                        <option value="">All</option>
                                        <option value="1">Downgrad</option>
                                        <option value="2">Suspend</option>
                                        <option value="3">Warning</option>
                                        <option value="4">Monitoring Record</option>
                                        <option value="5">Reinstate</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                    <div class="col-lg-12 text-center">
                        <label class="control-label">&nbsp;</label>
                        <div class="btn-set">
                            <button type="button" class="btn btn-primary" onclick="getReport()">Search</button>
                            <button type="button" class="btn btn-primary" onclick="resetForm()">Reset</button>

                        </div>
                    </div>
                    <div id="respondResult"></div>
                </div>
			</div>
		</div>
	</div>
</div>
  
  <script>
        function resetForm() {
            document.getElementById("report_from").reset();
            $('.select2me').val('');
            $('.select2me option[value=""]').prop('selected', 'selected').change();
        }
        function getReport()
        {
            var cdbNo = $("#cdbNo").val();
            var fromDate  = $("#fromDate").val();
            var toDate  = $("#toDate").val();
            var dzongkhag  = $("#dzongkhag").val();
            var actionType = $("#actionType").val();
            var inspectionType = '{{$inspectionType}}';
            $(".errorReportType").addClass("hidden"); 
            if(fromDate!="" || toDate!="")
            {
                var url = 'actionTakenReport';
                $.ajax({
                    url: url,
                    type: "get",
                    data: {
                        
                            cdbNo   : cdbNo,
                            fromDate: fromDate,
                            toDate  : toDate,
                            dzongkhag   : dzongkhag,
                            actionType : actionType,
                            inspectionType : inspectionType
                        },
                    success: function(response)
                    {
                    $("#respondResult").html(response);
                     }
              });
            }
        }
  </script>
@stop

 
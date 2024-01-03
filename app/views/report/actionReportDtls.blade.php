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
    <table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
        <thead class="flip-content">
            <tr>
                <th>Sl.No.</th>
                <th>Name of Firm</th>
                <th>CDB No</th>
                <th>Action Date</th>
                <th>Action Type</th>
                <th>From Category</th>
                <th>From Classification</th>
                <th>To Category</th>
                <th>To Classification</th>
                <th>Dzongkhag</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
        <?php $count = 1;?>
        @forelse($actionTaken as $data)
            <tr>
                <td>{{$count++}}</td>
                <td>{{$data->NameOfFirm}}</td>
                <td>{{$data->CDBNo}}</td>
                <td>{{$data->MonitoringDate}}</td>
                <td>{{$data->actionTaken}}</td>
                <td>{{$data->to_category}}</td>
                <td>{{$data->to_classification}}</td>
                <td>{{$data->from_category}}</td>
                <td>{{$data->from_classificaiton}}</td>
                <td>{{$data->Remarks}}</td>
                <td>{{$data->dzongkhagName}}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="font-red text-center">No data to display!</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
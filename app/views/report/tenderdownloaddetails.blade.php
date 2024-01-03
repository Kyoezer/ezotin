@if(count($reportData1)>0)
<div class="col-md-6">
<table class="table table-condensed table-bordered">
    <thead>
        <tr>
            <th>Email</th>
            <th>Phone No</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reportData1 as $data1)
            <tr>
                <td>{{$data1->Email}}</td>
                <td>{{$data1->PhoneNo}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
@endif
@if(count($reportData2)>0)
<div class="col-md-6">
    <table class="table table-condensed table-bordered">
        <thead>
        <tr>
            <th>Email</th>
            <th>Phone No</th>
        </tr>
        </thead>
        <tbody>
        @foreach($reportData2 as $data2)
            <tr>
                <td>{{$data2->Email}}</td>
                <td>{{$data2->PhoneNo}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endif
<div class="clearfix"></div>

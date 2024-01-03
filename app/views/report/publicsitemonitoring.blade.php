@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
<?php 
    $route="contractorrpt.totalparticipant";
?>
   
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
			<i class="fa fa-cogs"></i>List of Surveyor  &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route($route,$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route($route,$parameters)}}" class="btn btn-sm purple" target=" blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != 'print')
            <?php 
                $url = 'contractorrpt/totalparticipant'; 
                $route="contractorrpt.totalparticipant";
            ?>
        {{Form::open(array('url'=>$url,'method'=>'get'))}}
		<div class="form-body">
			<div class="row">
                <!-- <div class="col-lg-12">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Work Id</label>
                            <input type="text" name="workId" value="{{Input::get('workId')}}" class="form-control" />
                        </div>
                    </div>
                    
                    
                    @if(!Input::has('export'))
                        <div class="col-md-2">
                            <label class="control-label">&nbsp;</label>
                            <div class="btn-set">
                                <button type="submit" class="btn blue-hoki btn-sm">Search</button>
                                <a href="{{Request::url()}}" class="btn grey-cascade btn-sm">Clear</a>
                            </div>
                        </div>
                    @endif
                </div> -->
			</div>
		</div>
        <div>
            <div id="map" style="width: 100%; height: 500px;"></div>
        </div>
        {{Form::close()}}
        @else
            @foreach(Input::all() as $key=>$value)
                @if($key != 'export')
                    <b>{{$key}}: {{$value}}</b><br>
                @endif
            @endforeach
            <br/>
        @endif
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
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Inspection date</th>
                    <th>Representative name</th>
                    <th>Representative contact no</th>
                    <th>Representative cid</th>
                    <th>Is requiment fullfilled</th>
                    <th>Remarks</th>
				</tr>
			</thead>
			<tbody>
            @forelse($officeList as $contractor)
				<tr>
                    <td>{{$start++}}</td>
                    <td>{{$contractor->NameOfFirm}}</td>
                    <td>{{$contractor->CDBNo}}</td>
                    <td>{{$contractor->Address}}</td>
                    <td>{{$contractor->TelephoneNo}}</td>
                    <td>{{$contractor->MobileNo}}</td>
                    <td>{{$contractor->Email}}</td>
                    <td>{{$contractor->office_establishment}}</td>
                    <td>{{$contractor->office_signboard}}</td>
                    <td>{{$contractor->filing_system}}</td>
                    <td>{{$contractor->locality_name}}</td>
                    <td>{{$contractor->latitude}}</td>
                    <td>{{$contractor->longtitude}}</td>
                    <td>{{$contractor->inspection_date}}</td>
                    <td>{{$contractor->representative_name}}</td>
                    <td>{{$contractor->representative_contact_no}}</td>
                    <td>{{$contractor->representative_cid}}</td>
                    <td>{{$contractor->is_requiment_fullfilled}}</td>
                    <td>{{$contractor->remarks}}</td>

				</tr>
                @empty
                <tr>
                    <td colspan="14" class="font-red text-center">No data to display!</td>
                </tr>
                @endforelse
			</tbody>
		</table>
        <?php pagination($noOfPages,Input::all(),Input::get('page'),$route); ?>
	</div>
</div>


 
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA2OR9NCts43D7CLtAxovY1zonifMtMVis&callback=initMap" type="text/javascript"></script>
<script type="text/javascript">
    
    var locations = [
      @foreach($officeList as $row)  
      ['<div class="col-lg-12 text-black"><h4>{{$row->NameOfFirm}}</h4><h6>CDB No. : {{$row->CDBNo}}</h6><h6>Inspection Date : {{$row->inspection_date}}</h6><h6>Locality Name : {{$row->locality_name}}</h6></div>', {{$row->latitude}},{{$row->longtitude}}, 4],
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
@stop

 
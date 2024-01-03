 @extends('master')

@section('content')

    <div class="row">
        <div class="col-md-12">
            @if (Session::get('successeditorial'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{ Session::get('successeditorial') }}</strong>
                        </div>
                    @endif
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!-- Striped table -->
                    {{ Form::open(['url' => 'web/journalforwardtojcbyeditorial/']) }}
                    <div class="table-responsive card pmd-card">
                        <!-- Table -->
                        <table class="table pmd-table table-striped">
                            <thead class="thead-dark">
                                <h3><b>Editorial Board Member </b><b id="Editorial">( Editorial Team )</b></h3>
                                <tr>

                                </tr>
                            </thead>
                            <tbody class="fond">

                                @foreach ($content as $item)
                                    <tr>
                                        <th class="width">Application No:</th>
                                        <td>{{ $item->Application_No }}</td>
                                    </tr>
                                    <tr>
                                        <th>Name of the Author:</th>
                                        <!-- < userName = Session::get('userName');?>
                                        <td></td> -->
                                        @foreach ($authorname as $items)
                                        <td>{{ $items->Name }}</td>
                                        @endforeach

                                    </tr>
                                    <tr>
                                        <th>Title:</th>
                                        <td class="title">{{ $item->Name_of_Title }}</td>
                                    </tr>
                                    <tr>
                                        <th>File:</th>
                                        <td>
                                            <a href="{{ asset($item->File) }}" target="_blank">
                                            <b>Download File</b>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Remarks by Journal Coordinator:</th>
                                        <td>{{ $item->Remark_By_JC }}</td>
                                    </tr>
                                    <tr>
                                        <th>Submitted Date:</th>
                                        <td>{{date('d/m/Y', strtotime($item->CreatedOn))}}</td>
                                    </tr>
                                    <tr>
                                        <th>Forward To Reviewer 1: <p id="reviewer1_name"><b>( Academician )</b></p></th>
                                        <td>
                                            <select  name="reviewerId" required  class="bg-info bold" id="select_reviewer" style="padding: 1mm">
                                                <option value="">Select Reviewer Verifier 1 </option>
                                                @foreach ($reviewerlist as $reviewer)
                                                <option value="{{ $reviewer->Id }}">{{ $reviewer->FullName }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="Application_No" value="{{$item->Application_No}}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Forward To Reviewer 2:<p id="reviewer1_name"><b>( Field Expert )</b></p></th>
                                        <td>
                                            <select  name="reviewer2Id" required  class="bg-info bold" id="select_reviewer2" style="padding: 1mm">
                                                <option value="">Select Reviewer Verifier 2</option>
                                                @foreach ($reviewerlist as $reviewer)
                                                <option value="{{ $reviewer->Id }}">{{ $reviewer->FullName }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="Application_No" value="{{$item->Application_No}}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Give your remarks:</th>
                                        <td>
                                            <textarea name="editorialremark" id="editorialremarks"></textarea>
                                            <input type="hidden" class="" name="Application_No" value="{{$item->Application_No}}">
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            {{-- <a href="{{ url('/web/journalforwardtoreviewer/' . $item->Application_No) }}">
                                                <button class="btn btn-primary" type="button">Forward</button>
                                            </a> --}}
                                            <button type="submit" onclick="validateField()" class="btn btn-primary">Forward</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ Form::close() }}
                </div>
                
            </div>
        </div>
    </div>
    <style>
        .fond{
            font-size: 14px;
        }
        .width{
            width: 40%;
        }
        textarea{
            width: 40%;
        }
	#reviewer1_name{
            color: red;
        }
        #Editorial{
            color: green;
        }
    </style>
	<script>
    function validateField(e) {
        var status = true;
        $("Form").submit(function(event) {
            debugger;
            if ($('#select_reviewer').val() != '') {
                if ($('#select_reviewer2').val() != '') {
                    if ($('#editorialremarks').val() != '') {

                    } else {
                    status = false;
                    alert('Please give your Remark.');
                    event.preventDefault();
                    }
                } else {
                status = false;
                alert('Please select reviewer ( Field Expert ).');
                event.preventDefault();
                }
            } else {
            status = false;
            alert('Please select reviewer ( Academician ).');
            event.preventDefault();
            }
        });
    }
</script>

    
@stop

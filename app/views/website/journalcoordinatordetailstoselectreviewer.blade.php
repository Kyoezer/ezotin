@extends('master')

@section('content')

    <div class="row">
        <div class="col-md-12">
            @if (Session::get('messagesuccess'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{ Session::get('messagesuccess') }}</strong>
                        </div>
                    @endif

            <div class="portlet light bordered"> 
                <div class="portlet-body form">  
                    <!-- Striped table -->

                    {{ Form::open(['url' => 'web/journalforwardtoreviewer/', 'files'=>true]) }}
                    <div class="table-responsive card pmd-card">
                        <!-- Table -->
                        <table class="table pmd-table table-striped">
                            
                            <thead class="thead-dark">
                                <h3><b>Journal Coordinator </b><b id="Editor">( Managing Editor )</b></h3>

                            </thead>
                            <tbody class="fond">

                                @foreach ($content as $item)
                                    <tr>
                                        <th class="width">Application No:</th>
                                        <td>{{ $item->Application_No }}</td>
                                    </tr>
                                    <tr>
                                        <th>Name of the Author:</th>
                                        @foreach ($authorname as $items)
                                        <td>{{ $items->Name }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th>Title:</th>
                                        <td>{{ $item->Name_of_Title }}</td>
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
                                        <th>Submitted Date:</th>
                                        <td>{{date('d/m/Y', strtotime($item->CreatedOn))}}</td>
                                    </tr>
					<tr>
                                <th>Email:</th>
                                @foreach ($authorname as $items)
                                <td>{{ $items->Email }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <th>Contact Number:</th>
                                @foreach ($authorname as $items)
                                <td>{{ $items->Contact }}</td>
                                @endforeach
                            </tr>
                                    <tr>
                                        <th>
                                            Remark by Editorial:
                                        </th>
                                        <td>
                                            {{ $item->Remark_By_Editorial }}
                                        </td>
                                    </tr>
                                    <?php if($item->Task_Status_Id == 14){?>
                                        <tr>
                                        <th>Reviewer Assigned by Editorial:</th>
                                        @foreach ($username1 as $reviewers)
                                        <td class="bold">{{ $reviewers->FullName }} and
                                        @endforeach
                                        @foreach ($username2 as $reviewers)
                                        {{ $reviewers->FullName }}</td>
                                        @endforeach
                                    </tr> 
                                    <tr>
                                        <th>Forward To Reviewer 1:<p id="reviewer1_name"><b>( Academician )</b></p></th>
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
                                    <?php }?>
                                    <tr>
                                        <th>Upload file:</th>
                                        <td><div class="form-group">
                                            <input class="center-block" type="file" name="file_forwarded_to_reviewer"> <br>
                                            @if ($errors->has('file'))
                                                <p class="error1">{{ $errors->first('file') }}</p>
                                            @endif
                                            
                                        </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Give your remarks:</th><br>
                                        <td>
                                            <textarea name="jcremarktoreviewer"></textarea>
                                            <input type="hidden" name="Application_No" value="{{$item->Application_No}}">
                                        </td>
                                    </tr>
                                    <br><br><br>
                                    <tr>
                                         <td>
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
        .center-block{
                background: #78D5CD;
                padding: 15px;
                margin-top: 20px;
                font-family: Arial;
                margin-left: 0%;
            }
	#reviewer1_name{
            color: red;
        }
	.error1{
            color: red;
        }
	#Editor{
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
                    if ($('#jcremarkstoreviewer').val() != '') {

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
